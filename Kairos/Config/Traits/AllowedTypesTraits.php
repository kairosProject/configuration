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
 * This trait is used to manage the allowed types of configuration
 *
 * @category Configuration
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait AllowedTypesTraits
{
    /**
     * Allowed types
     *
     * The allowed types sets
     *
     * @var array
     */
    private $allowedTypes = [];

    /**
     * Get allowed types
     *
     * Return the allowed types for the element
     *
     * @return array
     */
    public function getAllowedTypes() : array
    {
        return $this->allowedTypes;
    }

    /**
     * Set allowed types
     *
     * Set the allowed types for the element
     *
     * @param array $allowedTypes the element allowed types
     *
     * @throws \UnexpectedValueException
     * @return $this
     */
    public function setAllowedTypes(array $allowedTypes) : Configuration
    {
        if (!in_array('array', $allowedTypes) && !empty($this->childrens)) {
            throw new \UnexpectedValueException('Type "array" must be allowed if element have children');
        }

        $this->allowedTypes = $allowedTypes;

        return $this;
    }

    /**
     * Add allowed type
     *
     * Add a new allowed type for the element
     *
     * @param string $allowedType The new allowed type
     *
     * @return $this
     */
    public function addAllowedType(string $allowedType) : Configuration
    {
        $this->allowedTypes[] = $allowedType;

        return $this;
    }

    /**
     * Remove alloawed type
     *
     * Remove an allowed type
     *
     * @param string $allowedType The allowed type to remove
     *
     * @return $this
     */
    public function removeAllowedType(string $allowedType) : Configuration
    {
        $this->allowedTypes = array_diff($this->allowedTypes, [$allowedType]);

        return $this;
    }
}
