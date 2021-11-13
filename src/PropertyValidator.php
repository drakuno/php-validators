<?php

namespace Drakuno\Validators;

class PropertyValidator extends ChildValidator
{
  private $allow_default;
  private $default;
  private $prop;

  public function __construct(
    string $prop,
    callable $validator,
    $default=null,
    $allow_default=true,
    ?callable $errors_maker=null
  )
  {
    $this->allow_default = $allow_default;
    $this->default       = $default;
    $this->prop          = $prop;

    $accessor = ChildValidator::propertyAccessorMake($prop,$default,$allow_default);
    parent::__construct($accessor,$validator,$errors_maker);
  }

  public function defaultErrorsMake($value,$child,array $child_errors):array
  {
    return [
      new ValidationError(
        "invalid-property",
        "Property `{$this->prop}` has validation errors",
        [
          'property'=>$this->prop,
          'value'=>$child,
          'errors'=>$child_errors
        ]
      )
    ];
  }
}