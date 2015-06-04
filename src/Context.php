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
 * Base context
 *
 * @author Dmitry Krasun <krasun.net@gmail.com>
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Context implements ContextInterface
{
    /**
     * @var array
     */
    private $groups = [];

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * {@inheritDoc}
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * {@inheritDoc}
     */
    public function hasGroup($group)
    {
        return in_array($group, $this->groups, true);
    }

    /**
     * {@inheritDoc}
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $this->setAttribute($attribute, $value);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($attribute, $default = null)
    {
        return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : $default;
    }
}
