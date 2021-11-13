<?php

namespace Drakuno\Validators;

/**
 * Validator interface
 * 
 * Validators must be callable and return an array of errors.
 * An empty array returned indicates no errors.
 * 
 * Validators may very well be simple functions, or configured
 * instances.
 */
interface ValidatorInterface
{
  /**
   * Performs validation of a value
   * 
   * Returns an array of errors
   */
  public function __invoke($value):array;
}