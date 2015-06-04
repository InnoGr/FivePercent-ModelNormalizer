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
 * All model normalizers context should be implemented of this interface
 *
 * @author Dmitry Krasun <krasun.net@gmail.com>
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ContextInterface
{
    /**
     * Set groups
     *
     * @param array $groups
     *
     * @return Context
     */
    public function setGroups(array $groups);

    /**
     * Get groups
     *
     * @return array
     */
    public function getGroups();

    /**
     * Has group
     *
     * @param string $group
     */
    public function hasGroup($group);

    /**
     * Set attributes
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes);

    /**
     * Set attribute
     *
     * @param string $attribute
     * @param mixed  $value
     */
    public function setAttribute($attribute, $value);

    /**
     * Get attribute
     *
     * @param string $attribute
     * @param mixed  $default
     *
     * @return string
     */
    public function getAttribute($attribute, $default = null);
}
