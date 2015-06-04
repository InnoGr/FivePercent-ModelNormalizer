<?php

/**
 * This file is part of the ModelNormalizer package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\ModelNormalizer\Normalizer\Annotated;

use FivePercent\Component\Exception\UnexpectedTypeException;
use FivePercent\Component\ModelNormalizer\Exception\DenormalizationFailedException;
use FivePercent\Component\Reflection\Reflection;
use FivePercent\Component\ModelNormalizer\ContextInterface;
use FivePercent\Component\ModelNormalizer\Exception\NormalizationFailedException;
use FivePercent\Component\ModelNormalizer\ModelNormalizerInterface;
use FivePercent\Component\ModelNormalizer\ModelNormalizerManagerAwareInterface;
use FivePercent\Component\ModelNormalizer\ModelNormalizerManagerInterface;
use FivePercent\Component\ModelNormalizer\Normalizer\Annotated\Metadata\MetadataFactoryInterface;
use FivePercent\Component\ModelNormalizer\Normalizer\Annotated\Metadata\PropertyMetadata;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Normalize annotated objects
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class AnnotatedModelNormalizer implements ModelNormalizerInterface, ModelNormalizerManagerAwareInterface
{
    /**
     * @var ModelNormalizerManagerInterface
     */
    private $normalizerManager;

    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    /**
     * Construct
     *
     * @param MetadataFactoryInterface $metadataFactory
     * @param ExpressionLanguage       $expressionLanguage
     */
    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        ExpressionLanguage $expressionLanguage = null
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * {@inheritDoc}
     */
    public function setModelNormalizerManager(ModelNormalizerManagerInterface $normalizerManager)
    {
        $this->normalizerManager = $normalizerManager;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize($object, ContextInterface $context)
    {
        $metadata = $this->metadataFactory->loadMetadata($object);

        // Get properties for normalization
        if ($context->getGroups()) {
            $normalizeProperties = $metadata->getPropertiesForGroups($context->getGroups());
        } else {
            $normalizeProperties = $metadata->getProperties();
        }

        $objectReflection = Reflection::loadClassReflection($object);
        $normalized = [];

        foreach ($normalizeProperties as $normalizePropertyName => $propertyMetadata) {
            $objectPropertyReflection = $objectReflection->getProperty($normalizePropertyName);

            if (!$objectPropertyReflection->isPublic()) {
                $objectPropertyReflection->setAccessible(true);
            }

            $objectPropertyValue = $objectPropertyReflection->getValue($object);

            $normalizedValue = $this->normalizeValue(
                $object,
                $objectPropertyValue,
                $propertyMetadata,
                $objectPropertyReflection
            );

            $normalized[$propertyMetadata->getFieldName()] = $normalizedValue;
        }

        return $normalized;
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, ContextInterface $context)
    {
        $class = $context->getAttribute('_class');

        if (!$class) {
            throw new DenormalizationFailedException('Undefined class for denormalization');
        }

        $metadata = $this->metadataFactory->loadMetadata($class);

        if ($context->getGroups()) {
            $denormalizeProperties = $metadata->getPropertiesForGroups($context->getGroups());
        } else {
            $denormalizeProperties = $metadata->getProperties();
        }

        $classReflection = Reflection::loadClassReflection($class);

        if ($object = $context->getAttribute('_object')) {
            if (!is_object($object)) {
                throw UnexpectedTypeException::create($object, 'object');
            }

            if (get_class($object) != $classReflection && !is_a($object, $class)) {
                throw UnexpectedTypeException::create($object, $class);
            }
        } else {
            $object = $classReflection->newInstanceWithoutConstructor();
        }

        foreach ($denormalizeProperties as $denormalizePropertyName => $propertyMetadata) {
            $fieldName = $propertyMetadata->getFieldName();

            if (!isset($data[$fieldName])) {
                continue;
            }

            $objectPropertyReflection = $classReflection->getProperty($denormalizePropertyName);

            if (!$objectPropertyReflection->isPublic()) {
                $objectPropertyReflection->setAccessible(true);
            }

            $denormalizedValue = $this->denormalizeValue(
                $data[$fieldName],
                $propertyMetadata,
                $objectPropertyReflection
            );

            $objectPropertyReflection->setValue($object, $denormalizedValue);
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $this->metadataFactory->supportsClass($class);
    }

    /**
     * Normalized value
     *
     * @param object              $object
     * @param mixed               $value
     * @param PropertyMetadata    $metadata
     * @param \ReflectionProperty $property
     *
     * @return mixed
     *
     * @throws NormalizationFailedException
     */
    protected function normalizeValue($object, $value, PropertyMetadata $metadata, \ReflectionProperty $property)
    {
        if (!$value) {
            return $value;
        }

        if ($metadata->isShouldNormalize()) {
            if (!is_object($value)) {
                throw new NormalizationFailedException(sprintf(
                    'Can not normalize property "%s" in class "%s". The value must be a object, but "%s" given.',
                    $property->getName(),
                    $property->getDeclaringClass()->getName(),
                    gettype($value)
                ));
            }

            return $this->normalizerManager->normalize($value);
        }

        if ($metadata->getExpressionValue()) {
            if (!$this->expressionLanguage) {
                throw new \LogicException(
                    'Can not evaluate expression language. Please inject ExpressionLanguage to normalizer.'
                );
            }

            $attributes = [
                'object' => $object,
                'value' => $value
            ];

            $value = $this->expressionLanguage->evaluate($metadata->getExpressionValue(), $attributes);
        }

        return $value;
    }

    /**
     * Denormalize value
     *
     * @param mixed               $value
     * @param PropertyMetadata    $metadata
     * @param \ReflectionProperty $property
     *
     * @return mixed
     *
     * @throws DenormalizationFailedException
     */
    protected function denormalizeValue($value, PropertyMetadata $metadata, \ReflectionProperty $property)
    {
        if (null === $value) {
            return $value;
        }

        if ($metadata->isShouldNormalize() || $metadata->getDenormalizationClass()) {
            if (!$metadata->getDenormalizationClass()) {
                throw new DenormalizationFailedException(sprintf(
                    'Can not denormalize property "%s" in class "%s". Undefined denormalization class.',
                    $property->getName(),
                    $property->getDeclaringClass()->getName()
                ));
            }

            return $this->normalizerManager->denormalize(
                $metadata->getDenormalizationClass(),
                $value
            );
        }

        return $value;
    }
}
