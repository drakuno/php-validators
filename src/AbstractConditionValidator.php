<?php

namespace Drakuno\Validators;

/**
 * Abstract class for simple conditional validators
 */
abstract class AbstractConditionValidator implements ValidatorInterface
{
  public function __invoke($value):array
  {
    return $this->test($value)?array():$this->errorsMake($value);
  }

  /**
   * Creates the errors thrown by a particular value
   */
  abstract public function errorsMake($value):array;

  /**
   * Tests a value
   */
  abstract public function test($value):bool;
}
