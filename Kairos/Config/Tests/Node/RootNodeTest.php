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
use Kairos\Config\Node\RootNode;
use Kairos\Config\Validation\PostValidationInterface;
use Kairos\Config\Configuration;

/**
 * RootNode test
 *
 * This test is used to validate the methods of RootNode
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class RootNodeTest extends TestCase
{
    /**
     * Test addPostValidation
     *
     * Validate the Kairos\Config\Node\RootNode::addPostValidation method
     *
     * @return void
     */
    public function testAddPostValidation()
    {
        $instance = new RootNode();

        $reflex = new \ReflectionProperty(RootNode::class, 'postValidations');
        $reflex->setAccessible(true);

        $validation = $this->createMock(PostValidationInterface::class);

        $this->assertTrue(is_array($reflex->getValue($instance)));
        $this->assertEmpty($reflex->getValue($instance));
        $this->assertSame($instance, $instance->addPostValidation($validation));
        $this->assertContains($validation, $reflex->getValue($instance));
    }

    /**
     * Test process
     *
     * Validate the Kairos\Config\Node\RootNode::process method
     *
     * @return void
     */
    public function testProcess()
    {
        $result = [$this->createMock(\stdClass::class)];

        $instance = new RootNode();
        $validation = $this->createMock(PostValidationInterface::class);
        $validation->expects($this->once())
            ->method('validate')
            ->with($this->equalTo([]))
            ->willReturn($result);

        $reflex = new \ReflectionProperty(RootNode::class, 'postValidations');
        $reflex->setAccessible(true);
        $reflex->setValue($instance, [$validation]);

        $this->assertTrue((new \ReflectionMethod(sprintf('%s::%s', Configuration::class, 'process')))->isPublic());
        $this->assertSame($result, $instance->process([]));
    }
}
