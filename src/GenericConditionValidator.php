<?php

namespace Drakuno\Validators;

class GenericConditionValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  private $custom_test;

  public function __construct(
    callable $custom_test,
    ?callable $errors_maker=null
  )
  {
    $this->setTest($custom_test);
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  public function getTest():callable{ return $this->custom_test; }
  public function setTest(callable $test)
  {
    $this->custom_test = $test;
  }

  public function test($value):bool
  {
    return ($this->custom_test)($value);
  }

  public function defaultErrorsMake($value):array
  {
    return [
      new ValidationError(
        "value-failed-condition",
        "A condition was not met by the value",
        $value
      )
    ];
  }
}