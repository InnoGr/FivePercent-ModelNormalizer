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
use FivePercent\Component\ModelNormalizer\ModelNormalizerInterface;
use FivePercent\Component\Money\Money;
use FivePercent\Component\ModelNormalizer\Exception\NormalizationFailedException;
use FivePercent\Component\ModelNormalizer\Exception\DenormalizationFailedException;

/**
 * Money normalizer
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class MoneyNormalizer implements ModelNormalizerInterface
{
    /**
     * @var int
     */
    private $precision;

    /**
     * @var string
     */
    private $createMethod = 'create';

    /**
     * Construct
     *
     * @param int $precision
     */
    public function __construct($precision = 2)
    {
        $this->precision = $precision;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize($object, ContextInterface $context)
    {
        if (!$object instanceof Money) {
            throw NormalizationFailedException::unexpected($object, 'FivePercent\Component\Money\Money');
        }

        return $object->toDouble($this->precision);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, ContextInterface $context)
    {
        if (!is_numeric($data)) {
            throw DenormalizationFailedException::unexpected($data, 'numeric');
        }

        return call_user_func(['FivePercent\Component\Money\Money', $this->createMethod], $data);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return is_a($class, 'FivePercent\Component\Money\Money', true);
    }
}
