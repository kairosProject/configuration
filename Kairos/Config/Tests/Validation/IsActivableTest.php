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
use Kairos\Config\Validation\IsActivable;

/**
 * IsActivable test
 *
 * This test is used to validate the methods of IsActivable
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class IsActivableTest extends TestCase
{
    /**
     * Test construct
     *
     * Validate the Kairos\Config\Validation\IsActivable::__construct method
     *
     * @return void
     */
    public function testConstruct()
    {
        $instance = new IsActivable('content');

        $reflex = new \ReflectionProperty(IsActivable::class, 'value');
        $reflex->setAccessible(true);

        $this->assertEquals('content', $reflex->getValue($instance));
    }

    /**
     * Test validate
     *
     * Validate the Kairos\Config\Validation\IsActivable::validate method
     *
     * @return void
     */
    public function testValidate()
    {
        $instance = new IsActivable('content');
        $this->assertSame('content', $instance->validate(true));

        $this->assertSame('other', $instance->validate('other'));
    }
}
