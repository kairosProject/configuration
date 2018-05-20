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
use Kairos\Config\Validation\IsType;
use Kairos\Config\Exception\InvalidValueException;

/**
 * IsType test
 *
 * This test is used to validate the methods of IsType
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class IsTypeTest extends TestCase
{
    /**
     * Test construct
     *
     * Validate the Kairos\Config\Validation\IsType::__construct method
     *
     * @return void
     */
    public function testConstruct()
    {
        $instance = new IsType('string');

        $reflex = new \ReflectionProperty(IsType::class, 'type');
        $reflex->setAccessible(true);

        $this->assertEquals('string', $reflex->getValue($instance));

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('function is_test does not exist');
        $instance = new IsType('test');
    }

    /**
     * Test validate
     *
     * Validate the Kairos\Config\Validation\IsType::validate method
     *
     * @return void
     */
    public function testValidate()
    {
        $instance = new IsType('string');
        $this->assertSame('content', $instance->validate('content'));
    }

    /**
     * Test validate error
     *
     * Validate the Kairos\Config\Validation\IsType::validate method in case of exception
     *
     * @return void
     */
    public function testValidateError()
    {
        $instance = new IsType('string');

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessageRegExp('#Unalowed type "[a-zA-Z]+". Allowed is "[a-zA-Z]+"#');

        $instance->validate(12);
    }
}
