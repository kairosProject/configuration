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

use Kairos\Config\Exception\InvalidValueException;

/**
 * Allowed values
 *
 * This validation is used to validate the values of a node
 *
 * @category Validation
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class AllowedValues implements ValidationInterface
{
    /**
     * Values
     *
     * The values allowed
     *
     * @var array
     */
    private $values;

    /**
     * Construct
     *
     * The default AllowedValues constructor
     *
     * @param array $values The allowed values
     *
     * @return void
     */
    public function __construct(array $values)
    {
        $this->values = $values;
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
        if (!in_array($element, $this->values, !is_object($element))) {
            throw new InvalidValueException(
                sprintf(
                    'The value "%s" is not allowed. The allowed values are [%s]',
                    $this->convertToString($element),
                    implode(', ', array_map([$this, 'convertToString'], $this->values))
                )
            );
        }

        return $element;
    }

    /**
     * Convert to string
     *
     * Convert the given value to string
     *
     * @param mixed $value The value to convert
     *
     * @return string
     */
    private function convertToString($value) : string
    {
        if (is_scalar($value)) {
            return (string)$value;
        }
        if (is_object($value)) {
            return get_class($value);
        }

        return gettype($value);
    }
}
