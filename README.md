# Validators

Is this the WIP validation library you need?

Let's see...

- [x] Validators that are simple to understand and instantiate
- [x] ***Easy to implement*** custom validators 
- [x] Custom and built-in validators with **fully** customizable errors
- [ ] "Validate my entire project in one line of code"... I mean, you can try

## A philosophy

This library may not solve *all of your problems*, but its philosophy just might!

### What is *a validator*?

A callable that takes a single value as input and returns an array of *errors*.

Custom validators are as easy as:
```php
function validate($value):array{ return []; }

// OR

$validator = function($value):array{ return []; }
```
(*More on this ahead!*)

**But why an array? What if I just want to return one error?**

Then wrap it in an array, geez! I mean, please n.n

In my experience, individual errors do cover the majority of cases, **BUT** sooner or later the moment arrives when one must inform of several errors at once.

### What is *an error*?

Any object or value that represents *failure* u_u

Down below you will find the library's included error class, but the most important part is that it is completely replaceable.

## The library

If you just look inside the `src/` dir, you will find...

### `ValidatorInterface`

Yup. Go look at that

### `ValidationError`

My favorite structure for error-reporting. Its three attributes mean the following:
- `code`: A short string error identifier
- `msg`: A description of the error
- `data`: Any other value that could be helpful in debugging the error.

### `CustomErrorsMakerTrait`

Go look at that too. Allows instances of (validator) classes to specify a custom "errors-maker" function (through `setErrorsMaker()`).

An "errors-maker" function should receive the validated value and possibly other parameters to produce the array of errors returned by the validator. The trait binds these functions to the validator instance.

This trait is used in all concrete and applicable validation classes, so that you may completely bend the errors returned to your will. Take each class's `defaultErrorsMake()` as an example of the signature needed (some classes provide more than the validated value).

### `AbstractConditionValidator`

Aids in the creation of simple condition validators.

The method `test()` must be defined in child classes. The boolean returned determines if the value passed validation.

The method `errorsMake()` must also be defined. Most classes in the library define `defaultErrorsMake()` instead because they make use of `CustomErrorsMakerTrait`, which in turn defines the former method.

### `GenericConditionValidator`

An implementation of `AbstractConditionValidator` that allows you to specify a test function for each *instance*.

Example Code
```php
use Drakuno\Validators\GenericConditionValidator;

$validator = new GenericConditionValidator("is_int");
$validator("hi im int!"); // fails and returns an error
```

### `ChainedValidator`

Validator composed of validators, evaluated in sequence, with all of their errors merged into a single array.

Constructor parameter `short_circuit` specifies if the validator should stop evaluations as soon as one of its children returns errors.

### `SwitchedValidator`

Validator composed of validators which succeeds when at least one of its children also succeeds.

This is the *OR* to `ChainedValidator`'s *AND*.

### `ArrayItemsValidator`

Validates items of an array, using a child validator.

Constructor parameter `provide_items_as_tuples` specifies if the child validator should receive a `[item_key,item_value]` tuple. Otherwise, item values are passed directly.

By default, a single error is returned for each failing item, but this behavior can be modified with constructor parameter `callable errors_maker`.

### `KeyValidator` and `PropertyValidator`

Validate the key or property of a value, respectively, using a child validator.

Defaults can be controlled through constructor parameters.

Examples
```php
use Drakuno\Validators\{KeyValidator,TypeValidator};

$validator = new KeyValidator("id",new TypeValidator("int"));
$value     = ['id'=>1,'title'=>"aaa"];

$validator($value); // [] passes!

// provide a default
$validator = new KeyValidator(
  "optional",
  new TypeValidator("array"),
  [] //default
);
$validator(['cat'=>"meow"]); // [] passes!
$validator(['optional'=>[]]); // [] passes!
$validator(['optional'=>"meow"]); // fails! not an array

// specify that defaults are NOT ALLOWED
$validator = new KeyValidator(
  "optional",
  new TypeValidator("array"),
  null,
  false
);
$validator(['cat'=>"meow"]); // raises an actual exception
```

### `ChildValidator`

Validate a child (or mapped version) of the value, determined by an accessor, using a child validator.

An accessor is a callable that takes a single input (the validated value) and returns the value to be validated.

`KeyValidator` and `PropertyValidator` are actually subclasses of `ChildValidator`, providing key and property accessors, respectively.

### Other validators

The few remaining validators should be self-explanatory, but I will mention them eventually as well. I will also eventually develop a proper documentation of the API for each of these classes. I will also eventually die, so, whatever comes first.

## Examples

Look at `tests/ExamplesTest.php`.

---

Rawr