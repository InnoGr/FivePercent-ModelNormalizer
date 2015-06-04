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
 * Allows to inject model normalizer to service.
 *
 * @author Dmitry Krasun <krasun.net@gmail.com>
 */
interface ModelNormalizerManagerAwareInterface
{
    /**
     * Set model normalizer
     *
     * @param ModelNormalizerManagerInterface $normalizerManager
     *
     * @return mixed
     */
    public function setModelNormalizerManager(ModelNormalizerManagerInterface $normalizerManager);
}
