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
 * Is type
 *
 * This validation is used to validate an element type
 *
 * @category Validation
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class IsType implements ValidationInterface
{
    /**
     * Type
     *
     * The type to use as validation
     *
     * @var string
     */
    private $type;

    /**
     * Construct
     *
     * The default IsType constructor
     *
     * @param mixed $type The type to validate
     *
     * @return void
     */
    public function __construct(string $type)
    {
        if (!function_exists(sprintf('is_%s', $type))) {
            throw new \UnexpectedValueException(sprintf('function is_%s does not exist', $type));
        }

        $this->type = $type;
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
        $function = sprintf('is_%s', $this->type);
        if (call_user_func($function, $element)) {
            return $element;
        }

        throw new InvalidValueException(
            sprintf(
                'Unalowed type "%s". Allowed is "%s"',
                is_object($element) ? get_class($element) : gettype($element),
                $this->type
            )
        );
    }
}
