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

use FivePercent\Component\ModelNormalizer\ContextInterface;
use FivePercent\Component\ModelNormalizer\Exception\DenormalizationFailedException;
use FivePercent\Component\ModelNormalizer\Exception\UnsupportedClassException;
use FivePercent\Component\ModelNormalizer\ModelNormalizerManagerAwareInterface;
use FivePercent\Component\ModelNormalizer\ModelNormalizerInterface;
use FivePercent\Component\ModelNormalizer\ModelNormalizerManagerInterface;

/**
 * Normalize models, if models implement NormalizableInterface.
 *
 * @author Dmitry Krasun <krasun.net@gmail.com>
 */
class NormalizableModelNormalizer implements ModelNormalizerInterface, ModelNormalizerManagerAwareInterface
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
        if (!$this->supportsClass($object)) {
            throw new UnsupportedClassException(sprintf('The object "%s" is not supported.', get_class($object)));
        }

        return $object->normalize($this->normalizerManager, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, ContextInterface $context)
    {
        throw new DenormalizationFailedException('Denormalize not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return is_a($class, 'FivePercent\Component\ModelNormalizer\NormalizableInterface', true);
    }
}
