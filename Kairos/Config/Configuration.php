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
namespace Kairos\Config;

use Kairos\Config\Exception\InvalidTypeException;
use Kairos\Config\Exception\MissingElementException;
use Kairos\Config\Exception\NestedException;
use Kairos\Config\Validation\ValidationInterface;
use Kairos\Config\Exception\UnrequiredException;
use Kairos\Config\Exception\RemovedElement;
use Kairos\Config\Traits\AllowedTypesTraits;
use Kairos\Config\Traits\ChildTrait;
use Kairos\Config\Traits\ValidationTrait;
use Kairos\Config\Traits\RequirementTrait;
use Kairos\Config\Traits\DefaultValueTrait;
use Kairos\Config\Traits\ParentTrait;

/**
 * Configuration
 *
 * This class is used to process a configuration array
 *
 * @category Configuration
 * @package  Configuration
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class Configuration
{
    use AllowedTypesTraits,
        ChildTrait,
        ValidationTrait,
        RequirementTrait,
        DefaultValueTrait,
        ParentTrait;

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
        $this->validateType($element);
        $element = $this->validate($element);

        if (is_array($element) && !empty($this->childrens)) {
            $this->validateKeys($element);
        }

        foreach ($this->childrens as $childKey => $childValue) {
            $childElement = $this->getElementValue($element, $childKey, $childValue);

            try {
                $element[$childKey] = $childValue->process($childElement);
            } catch (UnrequiredException $exception) {
                continue;
            } catch (RemovedElement $exception) {
                unset($element[$childKey]);
                continue;
            } catch (\Exception $exception) {
                throw new NestedException(
                    sprintf(
                        'Error during process for key "%s"',
                        $childKey
                    ),
                    $exception->getCode(),
                    $exception
                );
            }
        }

        return $element;
    }

    /**
     * Validate key
     *
     * Validate the elements keys to be allowed
     *
     * @param array $elements The element to valudate
     *
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    private function validateKeys(array $elements) : void
    {
        foreach (array_keys($elements) as $key) {
            if ($this->getChild($key) === null) {
                throw new \UnexpectedValueException(sprintf('key "%s" does not exist', $key));
            }
        }

        return;
    }

    /**
     * Get element value
     *
     * Return the element value, accordingly with the default value. Throw an exception if no value can be resolved
     *
     * @param array         $element       The base resolving element
     * @param string        $childKey      The element key
     * @param Configuration $configuration The child configuration
     *
     * @throws MissingElementException In case of missing required child
     * @return mixed
     */
    private function getElementValue(array $element, string $childKey, Configuration $configuration)
    {
        if (!isset($element[$childKey]) && $configuration->hasDefaultValue()) {
            return $configuration->getDefaultValue();
        }

        if (!isset($element[$childKey]) && $configuration->isRequired()) {
            throw new MissingElementException(
                sprintf('Element "%s" is required', $childKey)
            );
        }

        return ($element[$childKey] ?? null);
    }

    /**
     * Validate
     *
     * Validate the given element
     *
     * @param mixed $element The element to validate
     *
     * @return mixed
     */
    private function validate($element)
    {
        foreach ($this->validations as $validation) {
            $element = $this->processCallable($element, $validation);
        }

        return $element;
    }

    /**
     * Process callable
     *
     * Validate the given element with callable validation, or fallback to processValidation
     *
     * @param mixed $element    The element to validate
     * @param mixed $validation The validation to execute
     *
     * @return mixed
     */
    private function processCallable($element, $validation)
    {
        if (is_callable($validation)) {
            return call_user_func($validation, $element);
        }

        return $this->processValidation($element, $validation);
    }

    /**
     * Process validation
     *
     * Validate the given element with validation instance
     *
     * @param mixed $element    The element to validate
     * @param mixed $validation The validation to execute
     *
     * @return mixed
     */
    private function processValidation($element, $validation)
    {
        if ($validation instanceof ValidationInterface) {
            return $validation->validate($element);
        }

        return $element;
    }

    /**
     * Validate type
     *
     * Validate the element type
     *
     * @param mixed $element The element to validate
     *
     * @throws InvalidTypeException If element does not rely to an allowed type
     *
     * @return void
     */
    private function validateType($element) : void
    {
        if ($element === null && !$this->isRequired()) {
            throw new UnrequiredException();
        }

        foreach ($this->allowedTypes as $allowedType) {
            $function = sprintf('is_%s', $allowedType);
            if (function_exists($function) && call_user_func($function, $element)) {
                return;
            }
        }

        throw new InvalidTypeException(
            sprintf(
                'Unalowed type "%s". Allowed are [%s]',
                is_object($element) ? get_class($element) : gettype($element),
                implode(', ', $this->allowedTypes)
            )
        );
    }
}
