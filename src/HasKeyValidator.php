<?php

namespace Drakuno\Validators;

class HasKeyValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  private $key;

  public function __construct(
    string $key,
    ?callable $errors_maker=null
  )
  {
    $this->key = $key;
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  // AbstractConditionValidator {

  public function test($value):bool
  {
    return isset($value[$this->key]);
  }

  // }

  static public function errorsMakerMake(string $keyword):callable
  {
    return function($value) use ($keyword)
    {
      return [
        new ValidationError(
          "{$keyword}-missing",
          sprintf("%s `%s` must be provided",
            ucfirst($keyword),
            $this->key
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
        "key-missing",
        "Key `{$this->key}` must be provided",
        $this->key
      )
    ];
  }
}