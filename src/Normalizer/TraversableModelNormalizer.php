<?php

/**
 * This file is part of the ModelNormalizer package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\ModelNormalizer\Normalizer;

use FivePercent\Component\ModelNormalizer\Context;
use FivePercent\Component\ModelNormalizer\ContextInterface;
use FivePercent\Component\ModelNormalizer\Exception\DenormalizationFailedException;
use FivePercent\Component\ModelNormalizer\Exception\UnsupportedClassException;
use FivePercent\Component\ModelNormalizer\ModelNormalizerManagerAwareInterface;
use FivePercent\Component\ModelNormalizer\ModelNormalizerInterface;
use FivePercent\Component\ModelNormalizer\ModelNormalizerManagerInterface;
use FivePercent\Component\Reflection\Reflection;

/**
 * Normalize Collection instance
 *
 * @author Dmitry Krasun <krasun.net@gmail.com>
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class TraversableModelNormalizer implements ModelNormalizerInterface, ModelNormalizerManagerAwareInterface
{
    /**
     * @var ModelNormalizerManagerInterface
     */
    private $normalizerManager;

    /**
     * {@inheritdoc}
     */
    public function setModelNormalizerManager(ModelNormalizerManagerInterface $normalizerManager)
    {
        $this->normalizerManager = $normalizerManager;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, ContextInterface $context)
    {
        if (!$object instanceof \Traversable) {
            throw UnsupportedClassException::create($object);
        }

        $normalized = [];

        foreach ($object as $key => $child) {
            $normalized[$key] = $this->normalizerManager->normalize($child);
        }

        return $normalized;
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, ContextInterface $context)
    {
        if (!is_array($data)) {
            throw DenormalizationFailedException::unexpected($data, 'array');
        }

        $collectionClass = $context->getAttribute('collection_class');
        $class = $context->getAttribute('class');

        if (!$class) {
            throw new DenormalizationFailedException('Undefined denormalizer class.');
        }

        try {
            $this->normalizerManager->getNormalizerForClass($class);
        } catch (UnsupportedClassException $e) {
            throw new DenormalizationFailedException(sprintf(
                'Not found normalizer for denormalize collection. Collection class "%s". Denormalizer class "%s".',
                $collectionClass,
                $class
            ), 0, $e);
        }

        if ($collectionClass) {
            $reflection = Reflection::loadClassReflection($collectionClass);
            $collection = $reflection->newInstanceWithoutConstructor();
        } else {
            $collection = [];
        }

        if (is_object($collection) && !$collection instanceof \ArrayAccess) {
            throw new DenormalizationFailedException(sprintf(
                'The collection instance for denormalize data should implement "ArrayAccess", but "%s" given.',
                get_class($collection)
            ));
        }

        foreach ($data as $key => $childData) {
            $denormalized = $this->normalizerManager->denormalize($class, $childData);

            $collection[$key] = $denormalized;
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return is_a($class, 'Traversable', true);
    }
}
