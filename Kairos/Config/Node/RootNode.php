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

use Kairos\Config\Validation\PostValidationInterface;
use Kairos\Config\Configuration;
use Kairos\Config\Exception\MissingElementException;
use Kairos\Config\Exception\NestedException;

/**
 * Root node
 *
 * This class is used to process a configuration root
 *
 * @category Node
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class RootNode extends ArrayNode
{
    private $postValidations = [];

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
        $element = parent::process($element);

        foreach ($this->postValidations as $validation) {
            $element = $validation->validate($element);
        }

        return $element;
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
        $this->postValidations[] = $validation;
        return $this;
    }
}
