<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\ArraySomeItemsValidator;

class ArraySomeItemsValidatorTest extends TestCase
{
  public function testParameterMinimum()
  {
    $validator = new ArraySomeItemsValidator(function($value){ return $value=="meow"?[]:["error"]; },false,1);
    $this->assertEmpty($validator(["bork","rawr","meow"]));
    $this->assertNotEmpty($validator(["bork","rawr","wow"]));

    $validator = new ArraySomeItemsValidator(function($value){ return $value=="meow"?[]:["error"]; },false,2);
    $this->assertEmpty($validator(["bork","rawr","meow","meow"]));
    $this->assertNotEmpty($validator(["bork","rawr","meow"]));
  }

  public function testParameterMaximum()
  {
    $validator = new ArraySomeItemsValidator(function($value){ return $value=="meow"?[]:["error"]; },false,1,1);
    $this->assertEmpty($validator(["bork","rawr","meow"]));
    $this->assertNotEmpty($validator(["bork","meow","meow"]));

    $validator = new ArraySomeItemsValidator(function($value){ return $value=="meow"?[]:["error"]; },false,0,0);
    $this->assertNotEmpty($validator(["bork","rawr","meow"]));
    $this->assertEmpty($validator(["bork","rawr","grawr"]));
  }
}
