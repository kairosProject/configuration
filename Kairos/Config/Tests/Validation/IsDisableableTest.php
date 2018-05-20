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
use Kairos\Config\Validation\IsDisableable;
use Kairos\Config\Exception\RemovedElement;

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
class IsDisableableTest extends TestCase
{
    /**
     * Test validate
     *
     * Validate the Kairos\Config\Validation\IsDisableable::validate method
     *
     * @return void
     */
    public function testValidate()
    {
        $instance = new IsDisableable();
        $this->assertSame('content', $instance->validate('content'));

        $this->expectException(RemovedElement::class);
        $instance->validate(false);
    }
}
