<?php

namespace Drakuno\Validators;

class PropertyValidator extends ChildValidator
{
  protected $allow_default;
  protected $default;
  protected $property;

  public function __construct(
    string $property,
    callable $validator,
    $default=null,
    $allow_default=true,
    ?callable $errors_maker=null
  )
  {
    $this->allow_default = $allow_default;
    $this->default       = $default;
    $this->property      = $property;

    $accessor = ChildValidator::propertyAccessorMake($property,$default,$allow_default);
    parent::__construct($accessor,$validator,$errors_maker);
  }

  public function defaultErrorsMake($value,$property_value,array $child_errors):array
  {
    return [
      new ValidationError(
        "invalid-property",
        "Property `{$this->property}` has validation errors",
        [
          'property'=>$this->property,
          'value'=>$property_value,
          'errors'=>$child_errors
        ]
      )
    ];
  }
}

