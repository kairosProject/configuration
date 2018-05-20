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
 * Parent traits
 *
 * This trait is used to manage the parent of configuration
 *
 * @category Configuration
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait ParentTrait
{
    /**
     * Parent
     *
     * The current element parent
     *
     * @var Configuration
     */
    private $parent = null;

    /**
     * Get path
     *
     * Return the current element path
     *
     * @return string
     */
    public function getPath() : string
    {
        if ($this->parent) {
            return sprintf(
                '%s[%s]',
                $this->parent->getPath(),
                array_search($this, $this->parent->getChildrens(), true)
            );
        }

        return '';
    }

    /**
     * Get root
     *
     * Return the tree root node
     *
     * @return Configuration
     */
    public function getRoot() : Configuration
    {
        if ($this->parent) {
            return $this->parent->getRoot();
        }

        return $this;
    }

    /**
     * Set parent
     *
     * Set the current element parent
     *
     * @param Configuration $parent The current element parent
     *
     * @throws \LogicException If already a child of another element
     * @return $this
     */
    protected function setParent(?Configuration $parent) : Configuration
    {
        if ($this->parent !== null) {
            throw new \LogicException('Configuration is already defined as child');
        }

        $this->parent = $parent;

        return $this;
    }
}
