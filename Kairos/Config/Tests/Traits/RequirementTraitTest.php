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
namespace Kairos\Config\Tests\Traits;

use Kairos\Config\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * RequirementTrait test
 *
 * This test is used to validate the methods of RequirementTrait
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait RequirementTraitTest
{
    /**
     * Test isRequired
     *
     * Validate the Kairos\Config\Configuration::isRequired method
     *
     * @return void
     */
    public function testIsRequired()
    {
        $method = new \ReflectionMethod(sprintf('%s::%s', Configuration::class, 'isRequired'));
        $this->assertTrue($method->isPublic());

        $instance = new Configuration();

        $this->getTestCase()->assertFalse($instance->isRequired());

        $reflex = new \ReflectionProperty(Configuration::class, 'isRequired');
        $reflex->setAccessible(true);

        $reflex->setValue($instance, true);

        $this->getTestCase()->assertTrue($instance->isRequired());
    }

    /**
     * Test setRequired
     *
     * Validate the Kairos\Config\Configuration::setRequired method
     *
     * @return void
     */
    public function testSetRequired()
    {
        $method = new \ReflectionMethod(sprintf('%s::%s', Configuration::class, 'setRequired'));
        $this->assertTrue($method->isPublic());

        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'isRequired');
        $reflex->setAccessible(true);

        $this->getTestCase()->assertFalse($reflex->getValue($instance));
        $this->getTestCase()->assertSame($instance, $instance->setRequired(true));
        $this->getTestCase()->assertTrue($reflex->getValue($instance));
        $this->getTestCase()->assertSame($instance, $instance->setRequired(false));
        $this->getTestCase()->assertFalse($reflex->getValue($instance));
    }

    /**
     * Get TestCase
     *
     * Return the current test case instance
     *
     * @return TestCase
     */
    protected abstract function getTestCase() : TestCase;
}
