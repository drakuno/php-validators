<?php

namespace Drakuno\Validators;

use ArrayIterator;
use TypeError;

/**
 * Validator composed of multiple alternative validators
 * 
 * As opposed to ChainedValidator, SwitchValidator reports
 * validity if at least one of its child validators does too.
 */
class SwitchValidator implements ValidatorInterface
{
  use CustomErrorsMakerTrait;

  private $validators;

  /**
   * @param $validators The array of validators to run
   */
  public function __construct(array $validators,?callable $errors_maker=null)
  {
    if (count($validators)!=count(array_filter($validators,"is_callable")))
      throw new TypeError("Array elements must be callable");
    $this->validators = $validators;
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  public function __invoke($value):array
  {
    $keys_errors = array();
    $iter        = new ArrayIterator($this->validators);
    while ($iter->valid()) {
      $key       = $iter->key();
      $validator = $iter->current();
      if (empty($item_errors=$validator($value)))
        return array();
      $keys_errors[$key] = $item_errors;
      $iter->next();
    }
    return $this->errorsMake($value,$keys_errors);
  }

  public function defaultErrorsMake($value,array $keys_errors):array
  {
    return [new ValidationError(
      "no-alternative-satisfied",
      "At least one alternative must be satisfied",
      $keys_errors
    )];
  }
}