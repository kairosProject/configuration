<?php
declare(strict_types=1);
/**
 * This file is part of the chronos project.
 *
 * As each files provides by the CSCFA, this file is licensed
 * under the MIT license.
 *
 * PHP version 7.2
 *
 * @category Validation
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace Kairos\Config\Validation;

use Kairos\Config\Configuration;

/**
 * Post validation interface
 *
 * This interface describe the base methods of a PostValidation class
 *
 * @category Validation
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
interface PostValidationInterface extends ValidationInterface
{
    /**
     * Set original node
     *
     * Set up the original validation node
     *
     * @param Configuration $node The original node
     *
     * @return $this
     */
    public function setOriginalNode(Configuration $node) : PostValidationInterface;
}
