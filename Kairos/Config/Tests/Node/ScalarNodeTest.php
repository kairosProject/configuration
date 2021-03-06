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
use Kairos\Config\Node\ScalarNode;
use Kairos\Config\Configuration;

/**
 * ScalarNode test
 *
 * This test is used to validate the methods of ScalarNode
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ScalarNodeTest extends TestCase
{
    /**
     * Test construct
     *
     * Validate the Kairos\Config\Node\ScalarNode::__construct method
     *
     * @return void
     */
    public function testConstruct()
    {
        $instance = new ScalarNode();

        $reflex = new \ReflectionProperty(Configuration::class, 'allowedTypes');
        $reflex->setAccessible(true);

        $this->assertEquals(['int', 'float', 'double', 'string', 'bool'], $reflex->getValue($instance));
    }
}
