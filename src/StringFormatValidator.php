<?php

namespace Drakuno\Validators;

use Exception;

class StringFormatValidator extends GenericConditionValidator
{
  use CustomErrorsMakerTrait;

  public function __construct(
    string $pattern,
    ?callable $errors_maker=null
  )
  {
    $this->pattern = $pattern;
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  public function test($value):bool
  {
    $result = preg_match($this->pattern,$value);
    if ($result===false)
      throw new Exception("preg_match('{$pattern}','{$value}') failed");
    else
      return boolval($result);
  }

  public function defaultErrorsMake($value):array
  {
    return [
      new ValidationError(
        "invalid-format",
        "The value must respect the required format",
        $value
      ),
    ];
  }
}