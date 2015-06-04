<?php

/**
 * This file is part of the ModelNormalizer package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\ModelNormalizer\Exception;

use FivePercent\Component\Exception\UnexpectedTrait;

/**
 * Fail denormalization
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class DenormalizationFailedException extends \Exception
{
    use UnexpectedTrait;
}
