<?php

namespace Drakuno\Validators;

class HasKeyValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  public function __construct(
    protected string $key,
    protected bool $negated=false,
    ?callable $errors_maker=null
  )
  {
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  // AbstractConditionValidator {

  public function test($value):bool
  {
    return ($this->negated)^isset($value[$this->key]);
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
            $this->key,
            $this->negated
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
        $this->negated
          ?"key-missing"
          :"key-present",
        "Key `{$this->key}` must be ".($this->negated?"provided":"omitted"),
        $this->key
      )
    ];
  }
}
