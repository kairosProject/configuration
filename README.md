# configuration

A processor for configuration structure

## Key concept

The key concept of the configuration is to append elements inside another, following composite structure pattern.

Each configuration element will specify a list of allowed type inside.

```php
$config = new Configuration();
$config->setAllowedTypes(['string']);

echo $config->process('There is a string'); // will return the acceptable input 'There is a string'

$config->process(12); // will throw an exception
```

As composite nested elements, it's possible to add childs to an array node.

```php
$config = new Configuration();
$config->addAllowedType('array');

$config->addChild('balance', new Configuration());
$config->getChild('balance')->addAllowedType('int');

$config->process(['balance' => 12]);
```

### Validation

It's possible to add a list of validation for each elements. This validation can execute more than one action :

 * Change the element value
 * Remove the element from parent

By this way, the validation MUST return the element value.

The validation can be a callback or a n instance of object implementing the `Kairos\Config\Validation\ValidationInterface`.

```PHP
$config = new Configuration();

$config->addValidation(function($element){
	return is_array($element) ? $element : [$element];
});
```

With object :

```PHP
class EmbedAsArray implements ValidationInterface
{
    /**
     * Validate
     *
     * Validate the element
     *
     * @param mixed $element The element to validate
     *
     * @return mixed
     */
    public function validate($element)
    {
    	return is_array($element) ? $element : [$element];
    }
}
```

### Require element and default

The element can be set as required element to ensure the presence in the result. By default, the element can be skipped and will not be present in the result.

```PHP
$config = new Configuration();
$config->setRequired(true);
```

It's also possible to define a default value.

```PHP
$config = new Configuration();
$config->setDefaultValue('default_value');
$config->setDefaultValue(null);
```

### Post process validation

To interact with the complete result of the process, the element can define a post validation. This validation must be an object implementing the PostValidationInterface.

```PHP
class ConflictWithAlt implements PostValidationInterface
{
	private $originalNode;

    /**
     * Validate
     *
     * Validate the element
     *
     * @param mixed $element The element to validate
     *
     * @return mixed
     */
    public function validate($element)
    {
    	if (isset($element['alt'])) {
    		throw new LogicException('Something as a conflict with "alt" element');
    	}
    	return $element;
    }
    
    /**
     * Set original node
     *
     * Set up the original validation node
     *
     * @param Configuration $node The original node
     *
     * @return $this
     */
    public function setOriginalNode(Configuration $node) : PostValidationInterface
    {
    	$this->originalNode = $node;
    	return $this;
    }
}
```

```PHP
$config = new Configuration();
$config->addPostValidation(new ConflictWithAlt());
```

### Predefined elements

#### Nodes

Some predefined nodes exists. These nodes are `Configuration` elements, with predefined allowed types, or decoration logic.

##### RootNode

The first node of a tree, store the PostValidations and execute them. Extends `ArrayNode`.

##### ArrayNode

Allow array type, can be a prototype.

##### ScalarNode

Allow integer, string, float, double and boolean value.

##### IntegerNode

Allow integer value.

##### StringNode

Allow string value.

#### Validation

Some predefined validations exists. These validations are `ValidationInterface` implementations.

##### AllowedValues

Validate that the given value is allowed.

```PHP
$config = new StringNode();
$config->addValidation(new AllowedValue('mysql', 'oracle'));

$config->process('mysql'); // Work
$config->process('oracle'); // Work
$config->process('mongodb'); // Fail
```

##### IsActivable

Transform a boolean 'true' value to something. Note 'bool' type must be allowed

```PHP
$config = new ArrayNode();
$config->addAllowedType('bool');
$config->addValidation(new IsActivable(['debug' => true, 'env' => 'test']));

$config->process(['debug' => true, 'env' => 'dev']); // Will return the input
$config->process(true); // Will return the IsActivable constructor argument
```

##### IsDisableable

Transform a boolean 'false' value by removing the current key.

```PHP
$root = new RootNode();

$config = new ArrayNode();
$config->addAllowedType('bool');
$config->addValidation(new IsDisableable());

$root->addChild('manager', $config);

$root->process(['manager' => ['a', 'b', 'c']]); // Will return the input
$root->process(['manager' => false]); // Will return an empty array
```

##### IsType

Validate the given element as allowed type. Usefull with concordance of IsActivable or IsDisableable

```PHP
$config = new ArrayNode();
$config->addAllowedType('bool');
$config->addValidation(new IsActivable(['debug' => true, 'env' => 'test']));
$config->addValidation(new IsType(['array']));

$config->process(false); // Will fail due to type validation
```

### Prototype

An array node can also be a prototype. The difference is that a prototype will take all first level elements and apply the childs to each parts.

```PHP
$config = new ArrayNode();
$config->addChild('class', new StringNode());
$config->addChild('args', new ArrayNode());

$config->getChild('class')->setRequired(true);

$config->process([
	'user' => [
		'class' => 'MyClass'
	],
	'dispatcher' => [
		'class' => 'EventDispatcher',
		'args' => [1, 2, 3]
	]
]);

```

