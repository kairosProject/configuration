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
 * @category Node
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace Kairos\Config\Node;

use Kairos\Config\Configuration;
use Kairos\Config\Exception\MissingElementException;
use Kairos\Config\Exception\NestedException;

/**
 * Array node
 *
 * This class is used to process a configuration array
 *
 * @category Node
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ArrayNode extends Configuration
{
    /**
     * Is prototype
     *
     * Indicate if the current array node is a prototype
     *
     * @var bool
     */
    private $isPrototype = false;

    /**
     * Set prototype
     *
     * Set the prototype state of the node
     *
     * @param bool $isPrototype The prototype state of the node
     *
     * @return $this
     */
    public function setPrototype(bool $isPrototype) : ArrayNode
    {
        $this->isPrototype = $isPrototype;

        return $this;
    }

    /**
     * Is prototype
     *
     * Return the prototype state of the current element
     *
     * @return bool
     */
    public function isPrototype() : bool
    {
        return $this->isPrototype;
    }

    /**
     * Construct
     *
     * The default ArrayNode constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->addAllowedType('array');
    }

    /**
     * Process
     *
     * Process the given configuration
     *
     * @param mixed $element The configuration element to process
     *
     * @throws MissingElementException In case of missing required child
     * @throws NestedException In case of child process exception
     *
     * @return mixed
     */
    public function process($element)
    {
        if ($this->isPrototype) {
            foreach ($element as $elementKey => $elementValue) {
                $element[$elementKey] = parent::process($elementValue);
            }

            return $element;
        }

        return parent::process($element);
    }
}
