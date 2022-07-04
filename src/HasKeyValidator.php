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
    return function() use ($keyword)
    {
      return [
        new ValidationError(
          $this->negated
            ?"{$keyword}-present"
            :"{$keyword}-missing",
          sprintf("%s `%s` must be %s",
            ucfirst($keyword),
            $this->key,
            $this->negated
              ?"omitted"
              :"provided"
          ),
          $this->key
        )
      ];
    };
  }

  public function defaultErrorsMake():array
  {
    return [
      new ValidationError(
        $this->negated
          ?"key-present"
          :"key-missing",
        "Key `{$this->key}` must be ".($this->negated?"omitted":"provided"),
        $this->key
      )
    ];
  }
}

