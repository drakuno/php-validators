<?php

namespace Drakuno\Validators;

class HasPropValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  public function __construct(
    private string $prop,
    private bool $present_is_valid=true,
    ?callable $errors_maker=null
  )
  {
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  // AbstractConditionValidator {

  public function test($value):bool
  {
    return (!$this->present_is_valid)^isset($value->{$this->prop});
  }

  // }

  static public function errorsMakerMake(string $keyword):callable
  {
    return function($value) use ($keyword)
    {
      return [
        new ValidationError(
          $this->present_is_valid
            ?"{$keyword}-missing"
            :"{$keyword}-present",
          sprintf("%s `%s` must be %s",
            ucfirst($keyword),
            $this->prop,
            $this->present_is_valid
              ?"provided"
              :"omitted"
          ),
          $this->prop
        )
      ];
    };
  }

  public function defaultErrorsMake($value):array
  {
    return [
      new ValidationError(
        $this->present_is_valid
          ?"property-missing"
          :"property-present",
        "Property `{$this->prop}` must be ".($this->present_is_valid?"defined":"omitted"),
        $this->prop
      )
    ];
  }
}