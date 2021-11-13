<?php

namespace Drakuno\Validators;

class TypeValidator extends AbstractConditionValidator
{
  use CustomErrorsMakerTrait;

  public function __construct(string $type,?callable $errors_maker=null)
  {
    $this->type = $type;
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  public function test($value):bool
  {
    switch ($this->type) {
      case "int":
      case "integer"  : return is_int($value);

      case "float"    : return is_float($value);

      case "str":
      case "string"   : return is_string($value);

      case "bool":
      case "boolean"  : return is_bool($value);

      case "array"    : return is_array($value);

      case "null"     : return is_null($value);

      case "callable" : return is_callable($value);

      default         : return is_a($value,$this->type);
    }
  }

  public function defaultErrorsMake($value):array
  {
    return [
      new ValidationError(
        "type-error",
        "Value must be of type '{$this->type}'",
        $value
      )
    ];
  }
}