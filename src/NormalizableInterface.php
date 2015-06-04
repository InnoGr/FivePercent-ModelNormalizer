<?php

/**
 * This file is part of the ModelNormalizer package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\ModelNormalizer;

/**
 * Provides simple interface for ability to use normalization directly in object.
 *
 * @author Dmitry Krasun <krasun.net@gmail.com>
 */
interface NormalizableInterface
{
    /**
     * Normalize object
     *
     * @param ModelNormalizerManagerInterface $normalizerManager
     * @param ContextInterface                $context
     *
     * @return array
     */
    public function normalize(ModelNormalizerManagerInterface $normalizerManager, ContextInterface $context);
}
