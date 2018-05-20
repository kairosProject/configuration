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
use Kairos\Config\Validation\PostValidationInterface;

/**
 * Allowed types traits
 *
 * This trait is used to manage the validations of configuration
 *
 * @category Configuration
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait ValidationTrait
{
    /**
     * Validations
     *
     * The element validations
     *
     * @var array
     */
    private $validations = [];

    /**
     * Get validations
     *
     * Return the element validations
     *
     * @return array
     */
    public function getValidations() : array
    {
        return $this->validations;
    }

    /**
     * Set validations
     *
     * Set the element validations
     *
     * @param array $validations The element validations
     *
     * @return $this
     */
    public function setValidations(array $validations) : Configuration
    {
        $this->validations = [];

        foreach ($validations as $validation) {
            $this->addValidation($validation);
        }

        return $this;
    }

    /**
     * Add validation
     *
     * Add an element validation
     *
     * @param callable|Configuration $validation the new element validation
     *
     * @return $this
     */
    public function addValidation($validation) : Configuration
    {
        $this->validations[] = $validation;

        return $this;
    }

    /**
     * Add post validation
     *
     * Insert a validation to be runned after all process
     *
     * @param PostValidationInterface $validation The validation to add
     *
     * @return $this
     */
    public function addPostValidation(PostValidationInterface $validation) : Configuration
    {
        $validation->setOriginalNode($this);
        $this->getRoot()->addPostValidation($validation);

        return $this;
    }
}
