<?php

/**
 * This file is part of the ModelNormalizer package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\ModelNormalizer\Normalizer\Annotated\Metadata;

use Doctrine\Common\Annotations\Reader;
use FivePercent\Component\Reflection\Reflection;
use FivePercent\Component\ModelNormalizer\Annotation\Object as ObjectAnnotation;
use FivePercent\Component\ModelNormalizer\Annotation\Property as PropertyAnnotation;
use FivePercent\Component\ModelNormalizer\Normalizer\Annotated\Exception\NormalizeAnnotationNotFoundException;

/**
 * Base metadata factory
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class MetadataFactory implements MetadataFactoryInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Construct
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        try {
            $this->loadMetadata($class);

            return true;
        } catch (NormalizeAnnotationNotFoundException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function loadMetadata($class)
    {
        // Try get @Object annotation
        $objectAnnotation = null;
        $classAnnotations = Reflection::loadClassAnnotations($this->reader, $class);

        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof ObjectAnnotation) {
                if ($objectAnnotation) {
                    throw new \RuntimeException(sprintf(
                        'Many @Normalize\Object annotations in class "%s".',
                        $class
                    ));
                }

                $objectAnnotation = $classAnnotation;
            }
        }

        // Try get @Property annotation from properties
        $properties = [];
        $classProperties = Reflection::getClassProperties($class, true);

        if ($objectAnnotation && $objectAnnotation->allProperties) {
            foreach ($classProperties as $classProperty) {
                /** @var PropertyAnnotation $propertyAnnotation */
                $propertyAnnotation = $this->reader->getPropertyAnnotation(
                    $classProperty,
                    'FivePercent\Component\ModelNormalizer\Annotation\Property'
                );

                if ($propertyAnnotation) {
                    $properties[$classProperty->getName()] = new PropertyMetadata(
                        $propertyAnnotation->fieldName ?: $classProperty->getName(),
                        $propertyAnnotation->groups,
                        $propertyAnnotation->shouldNormalize,
                        $propertyAnnotation->expressionValue,
                        $propertyAnnotation->denormalizerClass
                    ) ;
                } else {
                    $properties[$classProperty->getName()] = new PropertyMetadata(
                        $classProperty->getName(),
                        [],
                        false,
                        null
                    );
                }
            }
        } else {
            foreach ($classProperties as $classProperty) {
                $propertyAnnotations = $this->reader->getPropertyAnnotations($classProperty);

                foreach ($propertyAnnotations as $propertyAnnotation) {
                    if ($propertyAnnotation instanceof PropertyAnnotation) {
                        $properties[$classProperty->getName()] = new PropertyMetadata(
                            $propertyAnnotation->fieldName ?: $classProperty->getName(),
                            $propertyAnnotation->groups,
                            $propertyAnnotation->shouldNormalize,
                            $propertyAnnotation->expressionValue,
                            $propertyAnnotation->denormalizerClass
                        );
                    }
                }
            }
        }

        if (!count($properties) && !$objectAnnotation) {
            throw new NormalizeAnnotationNotFoundException(sprintf(
                'Not found normalize annotations in class "%s".',
                $class
            ));
        }

        return new ObjectMetadata($properties);
    }
}
