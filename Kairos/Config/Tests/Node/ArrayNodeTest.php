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
namespace Kairos\Config\Tests\Node;

use PHPUnit\Framework\TestCase;
use Kairos\Config\Node\ArrayNode;
use Kairos\Config\Configuration;
use Kairos\Config\Node\StringNode;
use Kairos\Config\Exception\NestedException;

/**
 * ArrayNode test
 *
 * This test is used to validate the methods of ArrayNode
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ArrayNodeTest extends TestCase
{
    /**
     * Test construct
     *
     * Validate the Kairos\Config\Node\ArrayNode::__construct method
     *
     * @return void
     */
    public function testConstruct()
    {
        $instance = new ArrayNode();

        $reflex = new \ReflectionProperty(Configuration::class, 'allowedTypes');
        $reflex->setAccessible(true);

        $this->assertEquals(['array'], $reflex->getValue($instance));
    }

    /**
     * Test setPrototype
     *
     * Validate the Kairos\Config\Node\ArrayNode::setPrototype method
     *
     * @return void
     */
    public function testSetPrototype()
    {
        $instance = new ArrayNode();

        $reflex = new \ReflectionProperty(ArrayNode::class, 'isPrototype');
        $reflex->setAccessible(true);

        $this->assertFalse($reflex->getValue($instance));
        $this->assertSame($instance, $instance->setPrototype(true));
        $this->assertTrue($reflex->getValue($instance));
    }

    /**
     * Test isPrototype
     *
     * Validate the Kairos\Config\Node\ArrayNode::isPrototype method
     *
     * @return void
     */
    public function testIsPrototype()
    {
        $instance = new ArrayNode();

        $reflex = new \ReflectionProperty(ArrayNode::class, 'isPrototype');
        $reflex->setAccessible(true);

        $this->assertFalse($instance->isPrototype());
        $reflex->setValue($instance, true);
        $this->assertTrue($instance->isPrototype());
        $reflex->setValue($instance, false);
        $this->assertFalse($instance->isPrototype());
    }

    /**
     * Test process
     *
     * Validate the Kairos\Config\Node\ArrayNode::process method
     *
     * @return void
     */
    public function testProcess()
    {
        $method = new \ReflectionMethod(sprintf('%s::%s', Configuration::class, 'setRequired'));
        $this->assertTrue($method->isPublic());

        $instance = new ArrayNode();
        $instance->setPrototype(true);
        $instance->addChild('inside', new StringNode());
        $instance->getChild('inside')->setRequired(true);

        $this->assertEquals(
            ['test1'=>['inside' => 'A'], 'test2'=>['inside' => 'B'], 'test3'=>['inside' => 'C']],
            $instance->process(['test1'=>['inside' => 'A'], 'test2'=>['inside' => 'B'], 'test3'=>['inside' => 'C']])
        );
    }

    /**
     * Test process error
     *
     * Validate the Kairos\Config\Node\ArrayNode::process method in case of exception
     *
     * @return void
     */
    public function testProcessError()
    {
        $instance = new ArrayNode();
        $instance->setPrototype(true);
        $instance->addChild('inside', new StringNode());
        $instance->getChild('inside')->setRequired(true);

        $this->expectException(NestedException::class);
        $instance->process(['test1'=>['inside' => 1]]);
    }
}
