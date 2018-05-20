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
 * This test is used to validate the methods of ParentTrait
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait ParentTraitTest
{
    /**
     * Test setParent
     *
     * Validate the Kairos\Config\Configuration::setParent method
     *
     * @return void
     */
    public function testSetParent()
    {
        $instance = new Configuration();

        $parentReflex = new \ReflectionProperty(Configuration::class, 'parent');
        $parentReflex->setAccessible(true);

        $methodReflex = new \ReflectionMethod(sprintf('%s::%s', Configuration::class, 'setParent'));
        $this->getTestCase()->assertTrue($methodReflex->isProtected());
        $methodReflex->setAccessible(true);

        $parent = $this->getTestCase()->getMockBuilder(Configuration::class)->getMock();
        $this->getTestCase()->assertSame($instance, $methodReflex->invoke($instance, $parent));
        $this->getTestCase()->assertSame($parent, $parentReflex->getValue($instance));
    }

    /**
     * Test setParent error
     *
     * Validate the Kairos\Config\Configuration::setParent method in case of exception
     *
     * @return void
     */
    public function testSetParentError()
    {
        $instance = new Configuration();

        $parentReflex = new \ReflectionProperty(Configuration::class, 'parent');
        $parentReflex->setAccessible(true);

        $methodReflex = new \ReflectionMethod(sprintf('%s::%s', Configuration::class, 'setParent'));
        $this->getTestCase()->assertTrue($methodReflex->isProtected());
        $methodReflex->setAccessible(true);

        $parent = $this->getTestCase()->getMockBuilder(Configuration::class)->getMock();
        $parentReflex->setValue($instance, $parent);

        $this->getTestCase()->expectException(\LogicException::class);
        $this->getTestCase()->expectExceptionMessage('Configuration is already defined as child');
        $methodReflex->invoke($instance, $this->getTestCase()->getMockBuilder(Configuration::class)->getMock());
    }

    /**
     * Test getRoot
     *
     * Validate the Kairos\Config\Configuration::getRoot method
     *
     * @return void
     */
    public function testGetRoot()
    {
        $instance = new Configuration();

        $this->getTestCase()->assertSame($instance, $instance->getRoot());

        $parentReflex = new \ReflectionProperty(Configuration::class, 'parent');
        $parentReflex->setAccessible(true);

        $parent = $this->getTestCase()->getMockBuilder(Configuration::class)
            ->setMethods(['getRoot'])
            ->getMock();
        $parent->expects($this->getTestCase()->once())
            ->method('getRoot')
            ->willReturn($parent);
        $parentReflex->setValue($instance, $parent);

        $this->getTestCase()->assertSame($parent, $instance->getRoot());
    }

    /**
     * Test getPath
     *
     * Validate the Kairos\Config\Configuration::getPath method
     *
     * @return void
     */
    public function testGetPath()
    {
        $instance = new Configuration();
        $secondInstance = new Configuration();

        $this->getTestCase()->assertEquals('', $instance->getPath());

        $parentReflex = new \ReflectionProperty(Configuration::class, 'parent');
        $parentReflex->setAccessible(true);

        $parent = $this->getTestCase()->getMockBuilder(Configuration::class)
            ->setMethods(['getChildrens', 'getPath'])
            ->getMock();
        $parent->expects($this->getTestCase()->once())
            ->method('getChildrens')
            ->willReturn(['test' => $secondInstance, 'path' => $instance]);
        $parent->expects($this->getTestCase()->once())
            ->method('getPath')
            ->willReturn('');
        $parentReflex->setValue($instance, $parent);
        $parentReflex->setValue($secondInstance, $parent);

        $this->getTestCase()->assertEquals('[path]', $instance->getPath());
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
