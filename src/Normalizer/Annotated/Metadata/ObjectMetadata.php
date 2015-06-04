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

/**
 * Object normalize metadata
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ObjectMetadata
{
    /**
     * @var array|PropertyMetadata[]
     */
    private $properties;

    /**
     * Construct
     *
     * @param array|PropertyMetadata[] $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * Get properties
     *
     * @return array|PropertyMetadata[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Get properties for groups
     *
     * @param array $groups
     *
     * @return array|PropertyMetadata[]
     */
    public function getPropertiesForGroups(array $groups)
    {
        $properties = [];

        foreach ($groups as $group) {
            foreach ($this->properties as $key => $property) {
                if (in_array($group, $property->getGroups())) {
                    $properties[$key] = $property;
                }
            }
        }

        return $properties;
    }
}
