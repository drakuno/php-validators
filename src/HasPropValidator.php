<?php

namespace Drakuno\Validators;

class HasPropValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  public function __construct(
    protected string $property,
    protected bool $negated=false,
    ?callable $errors_maker=null
  )
  {
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  // AbstractConditionValidator {

  public function test($value):bool
  {
    return ($this->negated)^isset($value->{$this->property});
  }

  // }

  static public function errorsMakerMake(string $keyword):callable
  {
    return function($value) use ($keyword)
    {
      return [
        new ValidationError(
          $this->negated
            ?"{$keyword}-missing"
            :"{$keyword}-present",
          sprintf("%s `%s` must be %s",
            ucfirst($keyword),
            $this->property,
            $this->negated
              ?"provided"
              :"omitted"
          ),
          $this->property
        )
      ];
    };
  }

  public function defaultErrorsMake($value):array
  {
    return [
      new ValidationError(
        $this->negated
          ?"property-missing"
          :"property-present",
        "Property `{$this->property}` must be ".($this->negated?"defined":"omitted"),
        $this->property
      )
    ];
  }
}
