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
 * This trait is used to manage the require state of configuration
 *
 * @category Configuration
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait RequirementTrait
{
    /**
     * Is required
     *
     * Indicate if the element is required or not
     *
     * @var bool
     */
    private $isRequired = false;

    /**
     * Is requerid
     *
     * Inform if the element is required or not
     *
     * @return boolean
     */
    public function isRequired() : bool
    {
        return $this->isRequired;
    }

    /**
     * Set required
     *
     * Set the require state of the element
     *
     * @param boolean $isRequired the require state of the element
     *
     * @return $this
     */
    public function setRequired(bool $isRequired) : Configuration
    {
        $this->isRequired = $isRequired;

        return $this;
    }
}
