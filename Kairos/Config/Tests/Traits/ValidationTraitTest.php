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
use Kairos\Config\Validation\ValidationInterface;
use Kairos\Config\Validation\PostValidationInterface;
use Kairos\Config\Node\RootNode;

/**
 * ValidationTrait test
 *
 * This test is used to validate the methods of ValidationTrait
 *
 * @category Test
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait ValidationTraitTest
{
    /**
     * Test getValidations
     *
     * Validate the Kairos\Config\Configuration::getValidations method
     *
     * @return void
     */
    public function testGetValidations()
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'validations');
        $reflex->setAccessible(true);

        $validations = [$this->getTestCase()->getMockBuilder(ValidationInterface::class)];
        $reflex->setValue($instance, $validations);

        $this->getTestCase()->assertSame($validations, $instance->getValidations());
    }

    /**
     * Test addValidation
     *
     * Validate the Kairos\Config\Configuration::addValidation method
     *
     * @return void
     */
    public function testAddValidations()
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'validations');
        $reflex->setAccessible(true);

        $this->getTestCase()->assertTrue(is_array($reflex->getValue($instance)));
        $this->getTestCase()->assertEmpty($reflex->getValue($instance));

        $validation = $this->getTestCase()->getMockBuilder(ValidationInterface::class)->getMock();
        $this->getTestCase()->assertSame($instance, $instance->addValidation($validation));
        $this->getTestCase()->assertContains($validation, $reflex->getValue($instance));

        $validation = $this->getTestCase()->getMockBuilder(ValidationInterface::class)->getMock();
        $this->getTestCase()->assertSame($instance, $instance->addValidation($validation));
        $this->getTestCase()->assertContains($validation, $reflex->getValue($instance));
    }

    /**
     * Test setValidations
     *
     * Validate the Kairos\Config\Configuration::setValidations method
     *
     * @return void
     */
    public function testSetValidations()
    {
        $instance = new Configuration();

        $reflex = new \ReflectionProperty(Configuration::class, 'validations');
        $reflex->setAccessible(true);

        $validations = [
            $this->getTestCase()->getMockBuilder(ValidationInterface::class)->getMock(),
            $this->getTestCase()->getMockBuilder(ValidationInterface::class)->getMock()
        ];

        $this->getTestCase()->assertSame($instance, $instance->setValidations($validations));

        $this->getTestCase()->assertSame($validations, $reflex->getValue($instance));
    }

    /**
     * Test addPostValidation
     *
     * Validate the Kairos\Config\Configuration::addPostValidation method
     *
     * @return void
     */
    public function testAddPostValidation()
    {
        $instance = new Configuration();

        $validation = $this->getTestCase()->getMockBuilder(PostValidationInterface::class)
            ->setMethods(['setOriginalNode', 'validate'])
            ->getMock();
        $validation->expects($this->getTestCase()->once())
            ->method('setOriginalNode')
            ->with($this->getTestCase()->identicalTo($instance));
        $root = $this->getTestCase()->getMockBuilder(RootNode::class)
            ->setMethods(['addPostValidation'])
            ->getMock();
        $root->expects($this->getTestCase()->once())
            ->method('addPostValidation')
            ->with($this->getTestCase()->identicalTo($validation));

        $reflex = new \ReflectionProperty(Configuration::class, 'parent');
        $reflex->setAccessible(true);
        $reflex->setValue($instance, $root);

        $this->getTestCase()->assertSame($instance, $instance->addPostValidation($validation));
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
