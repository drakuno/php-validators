<?php

namespace Drakuno\Validators;

class HasPropValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  private $prop;

  public function __construct(
    string $prop,
    ?callable $errors_maker=null
  )
  {
    $this->prop = $prop;
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  // AbstractConditionValidator {

  public function test($value):bool
  {
    return isset($value->{$this->prop});
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
            $this->prop
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
        "prop-missing",
        "Property `{$this->prop}` must be defined",
        $this->prop
      )
    ];
  }
}