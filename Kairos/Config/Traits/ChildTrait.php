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
 * @category Configuration
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace Kairos\Config\Traits;

use Kairos\Config\Configuration;

/**
 * Allowed types traits
 *
 * This trait is used to manage the childs of configuration
 *
 * @category Configuration
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait ChildTrait
{
    /**
     * Childrens
     *
     * The element childs
     *
     * @var array
     */
    private $childrens = [];

    /**
     * Get childrens
     *
     * Return the element childs
     *
     * @return array
     */
    public function getChildrens() : array
    {
        return $this->childrens;
    }

    /**
     * Get child
     *
     * Return a specific child by key, or null if not registered
     *
     * @param string $key the child key
     *
     * @return $this|null
     */
    public function getChild(string $key) : ?Configuration
    {
        if (!isset($this->childrens[$key])) {
            return null;
        }

        return $this->childrens[$key];
    }

    /**
     * Set childrens
     *
     * Set the element childs
     *
     * @param array $childrens the element child set
     *
     * @return $this
     */
    public function setChildrens(array $childrens) : Configuration
    {
        foreach ($this->childrens as $child) {
            $child->setParent(null);
        }

        foreach ($childrens as $key => $child) {
            $this->addChild($key, $child);
        }

        return $this;
    }

    /**
     * Add child
     *
     * Add an element child
     *
     * @param string $key   The child key
     * @param array  $child the element child to add
     *
     * @return $this
     */
    public function addChild(string $key, Configuration $child) : Configuration
    {
        if (!in_array('array', $this->allowedTypes)) {
            throw new \UnexpectedValueException('Type "array" must be allowed to add children');
        }

        $child->setParent($this);
        $this->childrens[$key] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * Remove a child from the child set
     *
     * @param string $key The child key
     *
     * @return $this
     */
    public function removeChild(string $key) : Configuration
    {
        if (isset($this->childrens[$key])) {
            $this->getChild($key)->setParent(null);
            unset($this->childrens[$key]);
        }

        return $this;
    }
}
