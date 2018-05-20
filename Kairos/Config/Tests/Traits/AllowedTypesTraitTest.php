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
 * AllowedTypesTrait test
 *
 * This test is used to validate the methods of AllowedTypesTrait
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait AllowedTypesTraitTest
{
    /**
     * Test getAllowedTypes
     *
     * Validate the Kairos\Config\Configuration::getAllowedTypes method
     *
     * @return void
     */
    public function testGetAllowedTypes()
    {
        $instance = new Configuration();

        $this->getTestCase()->assertTrue(is_array($instance->getAllowedTypes()));
        $this->getTestCase()->assertEmpty($instance->getAllowedTypes());

        $reflex = new \ReflectionProperty(Configuration::class, 'allowedTypes');
        $reflex->setAccessible(true);

        $allowedTypes = ['stringType', 'boolType'];
        $reflex->setValue($instance, $allowedTypes);

        $this->getTestCase()->assertEquals($allowedTypes, $instance->getAllowedTypes());
    }

    /**
     * Allowed types provider
     *
     * Return a set of allowed type to be validated
     *
     * @return array
     */
    public function allowedTypesProvider()
    {
        return [
            [['stringType']],
            [['ArrayType']],
            [['ArrayType', 'stringType']],
            [['ArrayType', 'stringType', 'boolType']],
            [['array', 'object', 'string']]
        ];
    }

    /**
     * Test setAllowedTypes
     *
     * Validate the Kairos\Config\Configuration::setAllowedTypes method
     *
     * @param array $types The allowed types list
     *
     * @return       void
     * @dataProvider allowedTypesProvider
     */
    public function testSetAllowedTypes(array $types)
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'allowedTypes');
        $reflex->setAccessible(true);

        $this->getTestCase()->assertEmpty($reflex->getValue($instance));

        $this->getTestCase()
            ->assertSame(
                $instance,
                $instance->setAllowedTypes($types)
            );
        $this->getTestCase()->assertEquals($types, $reflex->getValue($instance));
    }

    /**
     * Test setAllowedTypes error
     *
     * Validate the Kairos\Config\Configuration::setAllowedTypes method in case of exception
     *
     * @return void
     */
    public function testSetAllowedTypesError()
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'childrens');
        $reflex->setAccessible(true);
        $reflex->setValue($instance, [$this->getTestCase()->getMockBuilder(Configuration::class)->getMock()]);

        $this->getTestCase()->expectException(\UnexpectedValueException::class);
        $this->getTestCase()->expectExceptionMessage('Type "array" must be allowed if element have children');

        $this->getTestCase()
            ->assertSame(
                $instance,
                $instance->setAllowedTypes(['stringType'])
            );
    }

    /**
     * Test addAllowedType
     *
     * Validate the Kairos\Config\Configuration::addAllowedType method
     *
     * @return void
     */
    public function testAddAllowedType()
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'allowedTypes');
        $reflex->setAccessible(true);

        $allowedTypes = ['stringType', 'boolType'];
        $reflex->setValue($instance, $allowedTypes);

        $this->getTestCase()->assertEquals($allowedTypes, $instance->getAllowedTypes());

        $this->getTestCase()
            ->assertSame(
                $instance,
                $instance->addAllowedType('arrayType')
            );

        array_push($allowedTypes, 'arrayType');
        $this->getTestCase()->assertEquals($allowedTypes, $instance->getAllowedTypes());

        return $instance;
    }

    /**
     * Test removeAllowedType
     *
     * Validate the Kairos\Config\Configuration::removeAllowedType method
     *
     * @param Configuration $configuration The original configuration
     *
     * @return  void
     * @depends testAddAllowedType
     */
    public function testRemoveAllowedType(Configuration $configuration)
    {
        $reflex = new \ReflectionProperty(Configuration::class, 'allowedTypes');
        $reflex->setAccessible(true);

        $allowedTypes = $reflex->getValue($configuration);

        foreach ($allowedTypes as $type) {
            $this->getTestCase()
                ->assertSame(
                    $configuration,
                    $configuration->removeAllowedType($type)
                );
            $this->getTestCase()->assertNotContains($type, $reflex->getValue($configuration));
        }

        $this->getTestCase()->assertEmpty($reflex->getValue($configuration));
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
