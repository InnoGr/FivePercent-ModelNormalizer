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
 * All model normalizers managers should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ModelNormalizerManagerInterface
{
    /**
     * Is class supports
     *
     * @param string|object $class
     *
     * @return bool
     */
    public function supports($class);

    /**
     * Get normalizer by class
     *
     * @param string|object $class
     *
     * @return ModelNormalizerInterface
     */
    public function getNormalizerForClass($class);

    /**
     * Normalize object
     *
     * @param object                $object
     * @param ContextInterface|null $context
     *
     * @return array
     */
    public function normalize($object, ContextInterface $context = null);

    /**
     * Denormalize
     *
     * @param string                $class
     * @param array|string          $data
     * @param ContextInterface|null $context
     *
     * @return object
     */
    public function denormalize($class, $data, ContextInterface $context = null);
}
