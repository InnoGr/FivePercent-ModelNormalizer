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
 * Responds for object normalization (transformation to array).
 *
 * @author Dmitry Krasun <krasun.net@gmail.com>
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ModelNormalizerInterface
{
    /**
     * Converts any object to array if supports.
     *
     * @param object           $object
     * @param ContextInterface $context
     *
     * @return array|string
     *
     * @throws \FivePercent\Component\ModelNormalizer\Exception\NormalizationFailedException
     */
    public function normalize($object, ContextInterface $context);

    /**
     * Denormalize array data to object
     *
     * @param array|string     $data
     * @param ContextInterface $context
     *
     * @return object
     *
     * @throws \FivePercent\Component\ModelNormalizer\Exception\DenormalizationFailedException
     */
    public function denormalize($data, ContextInterface $context);

    /**
     * If supports normalization of specified object returns true, otherwise false.
     *
     * @param object $class
     *
     * @return bool
     */
    public function supportsClass($class);
}
