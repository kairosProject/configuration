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
 * ChildTrait test
 *
 * This test is used to validate the methods of ChildTrait
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait ChildTraitTest
{
    /**
     * Test getChildrens
     *
     * Validate the Kairos\Config\Configuration::getChildrens method
     *
     * @return void
     */
    public function testGetChildrens()
    {
        $instance = new Configuration();

        $this->getTestCase()->assertTrue(is_array($instance->getAllowedTypes()));
        $this->getTestCase()->assertEmpty($instance->getChildrens());

        $reflex = new \ReflectionProperty(Configuration::class, 'childrens');
        $reflex->setAccessible(true);

        $childs = [
            $this->getTestCase()
                ->getMockBuilder(Configuration::class)
                ->getMock(),
            $this->getTestCase()
                ->getMockBuilder(Configuration::class)
                ->getMock()
            ];

        $reflex->setValue($instance, $childs);

        $this->getTestCase()->assertEquals($childs, $instance->getChildrens());
    }

    /**
     * Test getChild
     *
     * Validate the Kairos\Config\Configuration::getChild method
     *
     * @return void
     */
    public function testGetChild()
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'childrens');
        $reflex->setAccessible(true);

        $childs = [
            'key1' => $this->getTestCase()
                ->getMockBuilder(Configuration::class)
                ->getMock(),
            'key2' => $this->getTestCase()
                ->getMockBuilder(Configuration::class)
                ->getMock()
        ];
        $reflex->setValue($instance, $childs);

        $this->getTestCase()->assertSame($childs['key1'], $instance->getChild('key1'));
        $this->getTestCase()->assertSame($childs['key2'], $instance->getChild('key2'));
        $this->getTestCase()->assertNull($instance->getChild('key3'));
    }

    /**
     * Test setChildrens
     *
     * Validate the Kairos\Config\Configuration::setChildrens method
     *
     * @return void
     */
    public function testSetChildrens()
    {
        $instance = new Configuration();

        $child = $this->getTestCase()
            ->getMockBuilder(Configuration::class)
            ->setMethods(['setParent'])
            ->getMock();

        $child->expects(
            $this->getTestCase()
                ->exactly(3)
        )->method('setParent')
            ->withConsecutive(
                $this->getTestCase()
                    ->identicalTo($instance),
                $this->getTestCase()
                    ->isNull(),
                $this->getTestCase()
                    ->identicalTo($instance)
            );

        $allowedReflex = new \ReflectionProperty(Configuration::class, 'allowedTypes');
        $allowedReflex->setAccessible(true);
        $allowedReflex->setValue($instance, ['array']);

        $childrens = ['key' => $child];
        $instance->setChildrens($childrens);

        $reflex = new \ReflectionProperty(Configuration::class, 'childrens');
        $reflex->setAccessible(true);
        $this->getTestCase()->assertSame($childrens, $reflex->getValue($instance));
        $this->getTestCase()->assertSame($instance, $instance->setChildrens($childrens));
    }

    /**
     * Test addChild
     *
     * Validate the Kairos\Config\Configuration::addChild method
     *
     * @return void
     */
    public function testAddChild()
    {
        $instance = new Configuration();

        $child = $this->getTestCase()
            ->getMockBuilder(Configuration::class)
            ->setMethods(['setParent'])
            ->getMock();

        $child->expects(
            $this->getTestCase()
                ->once()
        )->method('setParent')
            ->with(
                $this->getTestCase()
                    ->identicalTo($instance)
            );

            $allowedReflex = new \ReflectionProperty(Configuration::class, 'allowedTypes');
            $allowedReflex->setAccessible(true);
            $allowedReflex->setValue($instance, ['array']);

            $this->getTestCase()->assertSame($instance, $instance->addChild('key', $child));

            $reflex = new \ReflectionProperty(Configuration::class, 'childrens');
            $reflex->setAccessible(true);
            $this->getTestCase()->assertSame(['key' => $child], $reflex->getValue($instance));
    }

    /**
     * Test addChild error
     *
     * Validate the Kairos\Config\Configuration::addChild method in case of error
     *
     * @return void
     */
    public function testAddChildError()
    {
        $instance = new Configuration();

        $child = $this->getTestCase()
            ->getMockBuilder(Configuration::class)
            ->getMock();

        $this->getTestCase()->expectException(\UnexpectedValueException::class);
        $this->getTestCase()->expectExceptionMessage('Type "array" must be allowed to add children');

        $instance->addChild('key', $child);
    }

    /**
     * Test addChild error
     *
     * Validate the Kairos\Config\Configuration::addChild method in case of error
     *
     * @return void
     */
    public function testRemoveChild()
    {
        $instance = new Configuration();

        $child = $this->getTestCase()
            ->getMockBuilder(Configuration::class)
            ->setMethods(['setParent'])
            ->getMock();

        $child->expects($this->getTestCase()->once())
            ->method('setParent')
            ->with($this->getTestCase()->isNull());

        $reflex = new \ReflectionProperty(Configuration::class, 'childrens');
        $reflex->setAccessible(true);
        $reflex->setValue($instance, ['key' => $child]);

        $this->getTestCase()->assertSame($instance, $instance->removeChild('key'));
        $this->getTestCase()->assertEmpty($reflex->getValue($instance));
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
