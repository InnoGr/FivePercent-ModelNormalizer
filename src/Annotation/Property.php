<?php

/**
 * This file is part of the ModelNormalizer package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\ModelNormalizer\Annotation;

/**
 * Indicate object property for available transform
 *
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Property
{
    /** @var string */
    public $fieldName;
    /** @var array */
    public $groups = [];
    /** @var bool */
    public $shouldNormalize = false;
    /** @var string */
    public $expressionValue = '';
    /** @var string Used only for denormalization function */
    public $denormalizerClass;
}
