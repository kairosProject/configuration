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
namespace Kairos\Config\Tests;

use PHPUnit\Framework\TestCase;
use Kairos\Config\Configuration;
use Kairos\Config\Node\RootNode;
use Kairos\Config\Node\StringNode;
use Kairos\Config\Node\ArrayNode;
use Kairos\Config\Validation\IsDisableable;
use Kairos\Config\Validation\IsActivable;
use Kairos\Config\Validation\IsType;
use Kairos\Config\Validation\AllowedValues;
use Kairos\Config\Tests\Traits as TestTrait;
use Kairos\Config\Exception\NestedException;
use Kairos\Config\Node\ScalarNode;
use Kairos\Config\Exception\MissingElementException;

/**
 * Configuration test
 *
 * This test is used to validate the methods of Configuration
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ConfigurationTest extends TestCase
{
    use TestTrait\AllowedTypesTraitTest,
        TestTrait\ChildTraitTest,
        TestTrait\DefaultValueTraitTest,
        TestTrait\ParentTraitTest,
        TestTrait\RequirementTraitTest,
        TestTrait\ValidationTraitTest;

    /**
     * Test definition
     *
     * Validate the Kairos\Config\Configurationmethods in case of undeclared element
     *
     * @return void
     */
    public function testDefinition()
    {
        $simpleArray = new RootNode();
        $this->assertSame(
            ['element' => true, 'test' => 'testValue'],
            $simpleArray->process(['element' => true, 'test' => 'testValue'])
        );

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('key "element" does not exist');

        $instance = new RootNode();
        $instance->addChild('test', new StringNode());

        $instance->process(['element' => true, 'test' => 'testValue']);
    }

    /**
     * Test process nested error
     *
     * Validate the Kairos\Config\Configurationmethods
     *
     * @return void
     */
    public function testProcessNestedError()
    {
        $root = new RootNode();
        $child = new StringNode();
        $root->addChild('test', $child);

        $this->expectException(NestedException::class);

        $root->process(['test' => 12]);
    }

    /**
     * Test unrequired
     *
     * Validate the Kairos\Config\Configurationmethods
     *
     * @return void
     */
    public function testUnrequired()
    {
        $root = new RootNode();
        $child = new StringNode();
        $root->addChild('test', $child);

        $this->assertSame([], $root->process([]));
    }

    /**
     * Test required
     *
     * Validate the Kairos\Config\Configurationmethods
     *
     * @return void
     */
    public function testRequired()
    {
        $root = new RootNode();
        $child = new StringNode();
        $child->setRequired(true);
        $root->addChild('test', $child);

        $this->expectException(MissingElementException::class);
        $this->expectExceptionMessage('Element "test" is required');

        $this->assertSame([], $root->process([]));
    }

    /**
     * Test default
     *
     * Validate the Kairos\Config\Configurationmethods
     *
     * @return void
     */
    public function testDefault()
    {
        $method = new \ReflectionMethod(sprintf('%s::%s', Configuration::class, 'setDefaultValue'));
        $this->assertTrue($method->isPublic());

        $root = new RootNode();
        $child = new StringNode();
        $child->setDefaultValue('testValue');
        $root->addChild('test', $child);

        $this->assertSame(['test' => 'testValue'], $root->process([]));
    }

    /**
     * Test removed
     *
     * Validate the Kairos\Config\Configurationmethods
     *
     * @return void
     */
    public function testRemoved()
    {
        $root = new RootNode();
        $child1 = new Configuration();
        $child1->addAllowedType('bool')
            ->addValidation(new IsDisableable());
        $child2 = new Configuration();
        $child2->addAllowedType('bool')
            ->addValidation(new IsDisableable());

        $root->addChild('test1', $child1);
        $root->addChild('test2', $child2);

        $this->assertSame([], $root->process(['test1'=>false, 'test2'=>false]));
    }

    /**
     * Test validations
     *
     * Validate the Kairos\Config\Configurationmethods
     *
     * @return void
     */
    public function testValidations()
    {
        $root = new RootNode();
        $multiplicationChild = new Configuration();
        $multiplicationChild->addAllowedType('int')
            ->addValidation(
                function ($element) {
                    return ($element * 2);
                }
            );
        $stringChild = new Configuration();
        $stringChild->addAllowedType('string')
            ->addValidation(new AllowedValues(['test']));
        $unvalidatedChild = new ScalarNode();
        $unvalidatedChild->addValidation(new \stdClass());

        $root->addChild('multiple', $multiplicationChild)
            ->addChild('string', $stringChild)
            ->addChild('unvalidated', $unvalidatedChild);

        $this->assertEquals(
            ['multiple' => 4, 'string' => 'test', 'unvalidated' => false],
            $root->process(['multiple' => 2, 'string' => 'test', 'unvalidated' => false])
        );
    }

    /**
     * Test configuration
     *
     * Validate the Kairos\Config\Configurationmethods
     *
     * @return void
     */
    public function testConfiguration()
    {
        foreach (['getPath', 'getRoot', 'addChild', 'getChild'] as $method) {
            $methodRelfex = new \ReflectionMethod(sprintf('%s::%s', Configuration::class, $method));
            $this->assertTrue($methodRelfex->isPublic());
        }

        $base = [
            'process' => [
                'name' => 'nom',
                'ext' => false
            ]
        ];

        $configuration = new RootNode();
        $configuration->addChild('process', new ArrayNode());
        $configuration->getChild('process')->addChild('name', new StringNode());
        $configuration->getChild('process')->addChild('class', new StringNode());

        $default = new ArrayNode();
        $default->setDefaultValue(['first' => 'element']);

        $default->addChild('first', new StringNode());
        $default->getChild('first')->addValidation(new AllowedValues(['element', 'elements']));
        $configuration->getChild('process')->addChild('default', $default);

        $ext = new Configuration();
        $ext->setAllowedTypes(['array', 'bool'])
            ->addValidation(new IsDisableable())
            ->addValidation(new IsActivable([]))
            ->addValidation(new IsType('array'));

        $configuration->getChild('process')->addChild('ext', $ext);

        $this->assertSame($configuration, $ext->getRoot());
        $this->assertSame($configuration, $default->getRoot());

        $this->assertEquals('[process][ext]', $ext->getPath());
        $this->assertEquals('[process][default]', $default->getPath());

        $expected = [
            'process' => [
                'name' => 'nom',
                'default' => [
                    'first' => 'element'
                ]
            ]
        ];

        $this->assertTrue((new \ReflectionMethod(sprintf('%s::%s', Configuration::class, 'process')))->isPublic());
        $this->assertEquals($expected, $configuration->process($base));
    }

    /**
     * Get TestCase
     *
     * Return the current test case instance
     *
     * @return TestCase
     */
    protected function getTestCase(): TestCase
    {
        return $this;
    }
}
