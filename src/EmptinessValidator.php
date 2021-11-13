<?php

namespace Drakuno\Validators;

class EmptinessValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  private $empty_passes;

  public function __construct(
    bool $empty_passes=false,
    ?callable $errors_maker=null
  )
  {
    $this->empty_passes = $empty_passes;
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  public function test($value):bool
  {
    return (!$this->empty_passes)^empty($value);
  }

  public function defaultErrorsMake($value):array
  {
    return [
      new ValidationError(
        $this->empty_passes?"value-not-empty":"value-empty",
        sprintf("Value %s be empty",
          $this->empty_passes?"must":"must not"
        ),
        $value
      )
    ];
  }
}