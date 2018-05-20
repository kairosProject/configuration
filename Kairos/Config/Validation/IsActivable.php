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
 * Is activable
 *
 * This validation is used to set a defaul value to an element on true
 *
 * @category Validation
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class IsActivable implements ValidationInterface
{
    /**
     * Value
     *
     * The value to use in case of activation
     *
     * @var mixed
     */
    private $value;

    /**
     * Construct
     *
     * The default IsActivable constructor
     *
     * @param mixed $value The value to use in case of actived
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Validate
     *
     * Validate the element
     *
     * @param mixed $element The element to validate
     *
     * @return mixed
     */
    public function validate($element)
    {
        if ($element === true) {
            return $this->value;
        }

        return $element;
    }
}
