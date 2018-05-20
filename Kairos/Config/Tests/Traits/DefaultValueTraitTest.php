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

use PHPUnit\Framework\TestCase;
use Kairos\Config\Configuration;

/**
 * DefaultValueTrait test
 *
 * This test is used to validate the methods of DefaultValueTrait
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait DefaultValueTraitTest
{
    /**
     * Test hasDefaultValue
     *
     * Validate the Kairos\Config\Configuration::hasDefaultValue method
     *
     * @return void
     */
    public function testHasDefaultValue()
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'hasDefault');
        $reflex->setAccessible(true);

        $this->getTestCase()->assertFalse($instance->hasDefaultValue());
        $reflex->setValue($instance, true);
        $this->getTestCase()->assertTrue($instance->hasDefaultValue());
    }

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
     * Test getDefaultValue
     *
     * Validate the Kairos\Config\Configuration::getDefaultValue method
     *
     * @param mixed $value The value to set into instance
     *
     * @return       void
     * @dataProvider valueProvider
     */
    public function testGetDefaultValue($value)
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'defaultValue');
        $reflex->setAccessible(true);

        $this->getTestCase()->assertNull($instance->getDefaultValue());
        $reflex->setValue($instance, $value);
        $this->getTestCase()->assertSame($value, $instance->getDefaultValue());
    }

    /**
     * Test setDefaultValue
     *
     * Validate the Kairos\Config\Configuration::setDefaultValue method
     *
     * @param mixed $value The value to set into instance
     *
     * @return       void
     * @dataProvider valueProvider
     */
    public function testSetDefaultValue($value)
    {
        $instance = new Configuration();

        $hasReflex = new \ReflectionProperty(Configuration::class, 'hasDefault');
        $hasReflex->setAccessible(true);
        $defaultReflex = new \ReflectionProperty(Configuration::class, 'defaultValue');
        $defaultReflex->setAccessible(true);

        $this->getTestCase()->assertFalse($hasReflex->getValue($instance));
        $this->getTestCase()->assertSame($instance, $instance->setDefaultValue($value));
        $this->getTestCase()->assertTrue($hasReflex->getValue($instance));
        $this->getTestCase()->assertSame($value, $defaultReflex->getValue($instance));

        return $instance;
    }

    /**
     * Test removeDefaultValue
     *
     * Validate the Kairos\Config\Configuration::removeDefaultValue method
     *
     * @param mixed $value The value to set into instance
     *
     * @return       void
     * @depends      testSetDefaultValue
     * @dataProvider valueProvider
     */
    public function testRemoveDefaultValue($value)
    {
        $instance = new Configuration();
        $instance->setDefaultValue($value);

        $hasReflex = new \ReflectionProperty(Configuration::class, 'hasDefault');
        $hasReflex->setAccessible(true);
        $defaultReflex = new \ReflectionProperty(Configuration::class, 'defaultValue');
        $defaultReflex->setAccessible(true);

        $instance->removeDefaultValue();

        $this->getTestCase()->assertFalse($hasReflex->getValue($instance));
        $this->getTestCase()->assertNull($defaultReflex->getValue($instance));
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
