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

/**
 * Validation interface
 *
 * This interface is used to describe the validation class interface
 *
 * @category Validation
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
interface ValidationInterface
{
    /**
     * Validate
     *
     * Validate the element
     *
     * @param mixed $element The element to validate
     *
     * @return mixed
     */
    public function validate($element);
}
