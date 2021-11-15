<?php

namespace Drakuno\Validators;

class HasKeyValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  public function __construct(
    private string $key,
    private bool $present_is_valid=true,
    ?callable $errors_maker=null
  )
  {
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  // AbstractConditionValidator {

  public function test($value):bool
  {
    return (!$this->present_is_valid)^isset($value[$this->key]);
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
            $this->key,
            $this->present_is_valid
              ?"provided"
              :"omitted"
          ),
          $this->key
        )
      ];
    };
  }

  public function defaultErrorsMake($value):array
  {
    return [
      new ValidationError(
        $this->present_is_valid
          ?"key-missing"
          :"key-present",
        "Key `{$this->key}` must be ".($this->present_is_valid?"provided":"omitted"),
        $this->key
      )
    ];
  }
}