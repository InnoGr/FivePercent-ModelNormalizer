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
 * All metadata factories should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface MetadataFactoryInterface
{
    /**
     * Is supports class
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class);

    /**
     * Load metadata for class
     *
     * @param string $class
     *
     * @return ObjectMetadata
     *
     * @throws \FivePercent\Component\ModelNormalizer\Normalizer\Annotated\Exception\NormalizeAnnotationNotFoundException
     */
    public function loadMetadata($class);
}
