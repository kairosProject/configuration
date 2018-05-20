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
 * This trait is used to manage the default value of configuration
 *
 * @category Configuration
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait DefaultValueTrait
{
    /**
     * Has default
     *
     * Define if the current element has a default value
     *
     * @var string
     */
    private $hasDefault = false;

    /**
     * Dafault value
     *
     * The default value of the element
     *
     * @var mixed
     */
    private $defaultValue = null;

    /**
     * Has default value
     *
     * Return the drfault value existance state
     *
     * @return bool
     */
    public function hasDefaultValue() : bool
    {
        return $this->hasDefault;
    }

    /**
     * Get default value
     *
     * Return the default value of the current element
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set default value
     *
     * Set the current element default value
     *
     * @param mixed $defaultValue The default value
     *
     * @return $this
     */
    public function setDefaultValue($defaultValue) : Configuration
    {
        $this->hasDefault = true;
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Remove default value
     *
     * Remove the current element default value
     *
     * @return $this
     */
    public function removeDefaultValue() : Configuration
    {
        $this->hasDefault = false;
        $this->defaultValue = null;

        return $this;
    }
}
