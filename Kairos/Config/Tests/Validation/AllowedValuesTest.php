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
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace Kairos\Config\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Kairos\Config\Validation\AllowedValues;
use Kairos\Config\Exception\InvalidValueException;

/**
 * AllowedValues test
 *
 * This test is used to validate the methods of AllowedValues
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class AllowedValuesTest extends TestCase
{
    /**
     * Value provider
     *
     * Return a set of value to validate the Kairos\Config\Configuration::getDefaultValue method
     *
     * @return array
     */
    public function valueProvider()
    {
        return [
            [true],
            [false],
            [1],
            [12],
            [130],
            [1.4],
            [12.32],
            ['string'],
            ['valueAsString'],
            [['simpleValue']],
            [['array' => 'value']],
            [new \stdClass()],
            [null]
        ];
    }

    /**
     * Test construct
     *
     * Validate the Kairos\Config\Validation\AllowedValues::__construct method
     *
     * @param array $value The value used to validate the method
     *
     * @return       void
     * @dataProvider valueProvider
     */
    public function testConstruct($value)
    {
        $values = [$value];
        $instance = new AllowedValues($values);

        $reflex = new \ReflectionProperty(AllowedValues::class, 'values');
        $reflex->setAccessible(true);

        $this->assertSame($values, $reflex->getValue($instance));
    }

    /**
     * Test validate
     *
     * Validate the Kairos\Config\Validation\AllowedValues::validate method
     *
     * @param array $value The value used to validate the method
     *
     * @return       void
     * @dataProvider valueProvider
     */
    public function testValidate($value)
    {
        $instance = new AllowedValues([is_object($value) ? clone $value : $value]);
        $this->assertSame($value, $instance->validate($value));
    }

    /**
     * Test validate error
     *
     * Validate the Kairos\Config\Validation\AllowedValues::validate method in case of exception
     *
     * @param array $value The value used to validate the method
     *
     * @return       void
     * @dataProvider valueProvider
     */
    public function testValidateError($value)
    {
        $instance = new AllowedValues(['Not allowed']);

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessageRegExp('#The value "[^"]*" is not allowed. The allowed values are \[.*\]#');
        $this->assertSame($value, $instance->validate($value));
    }
}
