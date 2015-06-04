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
use FivePercent\Component\ModelNormalizer\Exception\NormalizationFailedException;
use FivePercent\Component\ModelNormalizer\ModelNormalizerInterface;

/**
 * Normalize date time object
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class DateTimeNormalizer implements ModelNormalizerInterface
{
    /**
     * @var string
     */
    private $dateTimeFormat;

    /**
     * Construct
     *
     * @param string $dateTimeFormat
     */
    public function __construct($dateTimeFormat = \DateTime::ISO8601)
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize($object, ContextInterface $context)
    {
        if (!$object instanceof \DateTime) {
            throw NormalizationFailedException::unexpected($object, 'DateTime');
        }

        return $object->format($this->dateTimeFormat);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, ContextInterface $context)
    {
        if (!is_string($data)) {
            throw DenormalizationFailedException::unexpected($data, 'string');
        }

        try {
            $datetime = \DateTime::createFromFormat($this->dateTimeFormat, $data);
        } catch (\Exception $e) {
            throw new DenormalizationFailedException('Could not denormalize date time.', 0, $e);
        }

        return $datetime;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return is_a($class, 'DateTime', true);
    }
}
