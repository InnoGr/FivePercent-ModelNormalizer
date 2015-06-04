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
 * Indicate object for available normalize
 *
 * @Annotation()
 * @Target("CLASS")
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Object
{
    /**
     * Normalize all properties in object.
     * Attention: all property values must be a scalar type
     *
     * @var bool
     */
    public $allProperties = false;
}
