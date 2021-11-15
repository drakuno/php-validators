<?php

namespace Drakuno\Validators;

class IntRangeValidator implements ValidatorInterface
{
  use CustomErrorsMakerTrait;

  public function __construct(
    private ?int $min=null,
    private ?int $max=null,
    ?callable $errors_maker=null
  )
  {
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  public function __invoke($value):array
  {
    if (!is_int($value))
      return $this->errorsMake($value,"type-error");
    if (!is_null($this->min)&&$value<$this->min)
      return $this->errorsMake($value,"below-minimum");
    elseif (!is_null($this->max)&&$value>$this->max)
      return $this->errorsMake($value,"above-maximum");
    else
      return array();
  }

  public function defaultErrorsMake($value,string $error_code):array
  {
    $both_limits_defined = !is_null($this->min)&&!is_null($this->max);
    $both_limits_message = $both_limits_defined?"The value must be between {$this->min} and {$this->max}":null;

    if ($error_code=="wrong-type")
      return [new ValidationError(
        "type-error",
        "Value must be an integer",
        $value
      )];
    elseif ($error_code=="below-minimum")
      return [new ValidationError(
        "value-below-minimum",
        $both_limits_defined
          ?$both_limits_message
          :"The value must be greater than {$this->min}",
        $value
      )];
    else
      return [new ValidationError(
        "value-above-maximum",
        $both_limits_defined
          ?$both_limits_message
          :"The value must be lesser than {$this->max}",
        $value
      )];
  }
}