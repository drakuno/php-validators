<?php

namespace Drakuno\Validators;

use ArrayIterator;
use TypeError;

/**
 * Validator composed of multiple validators evaluated in sequence
 */
class ChainedValidator implements ValidatorInterface
{
  private $short_circuit;

  /**
   * @param $validators The array of validators to run
   * @param $short_circuit Stop on first failed validator
   */
  public function __construct(
    array $validators,
    bool $short_circuit=true
  )
  {
    if (count($validators)!=count(array_filter($validators,"is_callable")))
      throw new TypeError("Array elements must be callable");
    $this->validators    = $validators;
    $this->short_circuit = $short_circuit;
  }

  public function __invoke($value):array
  {
    $errors = array();
    $iter   = new ArrayIterator($this->validators);
    while (
      (!$this->short_circuit||count($errors)==0)
      &&$iter->valid()
      &&($validator=$iter->current())
    ) {
      $errors = array_merge($errors,$validator($value));
      $iter->next();
    }
    return $errors;
  }
}