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

use FivePercent\Component\Exception\UnexpectedTypeException;
use FivePercent\Component\ModelNormalizer\Exception\UnsupportedClassException;

/**
 * Base model normalizer manager
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ModelNormalizerManager implements ModelNormalizerManagerInterface
{
    /**
     * @var array|ModelNormalizerInterface[]
     */
    private $normalizers = [];

    /**
     * @var bool
     */
    private $sorted = false;

    /**
     * Add normalizer
     *
     * @param ModelNormalizerInterface $normalizer
     * @param int                      $priority
     *
     * @return ModelNormalizerManager
     */
    public function addNormalizer(ModelNormalizerInterface $normalizer, $priority = 0)
    {
        $this->sorted = false;

        $hash = spl_object_hash($normalizer);

        $this->normalizers[$hash] = [
            'normalizer' => $normalizer,
            'priority' => $priority
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports($class)
    {
        try {
            $this->getNormalizerForClass($class);

            return true;
        } catch (UnsupportedClassException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getNormalizerForClass($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $this->sortNormalizers();

        foreach ($this->normalizers as $entry) {
            /** @var ModelNormalizerInterface $normalizer */
            $normalizer = $entry['normalizer'];

            if ($normalizer->supportsClass($class)) {
                return $normalizer;
            }
        }

        throw UnsupportedClassException::create($class);
    }

    /**
     * {@inheritDoc}
     */
    public function normalize($object, ContextInterface $context = null)
    {
        if (!is_object($object)) {
            throw UnexpectedTypeException::create($object, 'object');
        }

        if (!$context) {
            $context = new Context();
        }

        $normalizer = $this->getNormalizerForClass(get_class($object));

        if ($normalizer instanceof ModelNormalizerManagerAwareInterface) {
            $normalizer->setModelNormalizerManager($this);
        }

        return $normalizer->normalize($object, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($class, $data, ContextInterface $context = null)
    {
        if (!$context) {
            $context = new Context();
        }

        // Save class to context
        $context->setAttribute('_class', $class);

        $normalizer = $this->getNormalizerForClass($class);

        if ($normalizer instanceof ModelNormalizerManagerAwareInterface) {
            $normalizer->setModelNormalizerManager($this);
        }

        return $normalizer->denormalize($data, $context);
    }

    /**
     * Sort normalizer
     */
    private function sortNormalizers()
    {
        if ($this->sorted) {
            return;
        }

        $this->sorted = true;

        uasort($this->normalizers, function ($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }

            return $a['priority'] > $b['priority'] ? -1 : 1;
        });
    }
}
