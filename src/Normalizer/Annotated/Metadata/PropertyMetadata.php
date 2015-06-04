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
 * Property normalize metadata
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class PropertyMetadata
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var bool
     */
    private $shouldNormalize;

    /**
     * @var string
     */
    private $expressionValue;

    /**
     * @var string
     */
    private $denormalizationClass;

    /**
     * Construct
     *
     * @param string $fieldName
     * @param array  $groups
     * @param bool   $shouldNormalize
     * @param string $expressionValue
     * @param string $denormalizationClass
     */
    public function __construct(
        $fieldName,
        array $groups,
        $shouldNormalize,
        $expressionValue,
        $denormalizationClass = null
    ) {
        $this->fieldName = $fieldName;
        $this->groups = $groups;
        $this->shouldNormalize = $shouldNormalize;
        $this->expressionValue = $expressionValue;
        $this->denormalizationClass = $denormalizationClass;
    }

    /**
     * Get field name
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Get groups
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Is should normalize
     *
     * @return bool
     */
    public function isShouldNormalize()
    {
        return $this->shouldNormalize;
    }

    /**
     * Get expression value
     *
     * @return string
     */
    public function getExpressionValue()
    {
        return $this->expressionValue;
    }

    /**
     * Get denormalizer class
     *
     * @return string
     */
    public function getDenormalizationClass()
    {
        return $this->denormalizationClass;
    }
}
