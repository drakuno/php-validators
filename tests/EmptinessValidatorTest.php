<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\EmptinessValidator;

class EmptinessValidatorTest extends TestCase
{
  public function testValidatesEmptiness():void
  {
    $validator = new EmptinessValidator(true);
    $this->assertEmpty($validator(0));
    $this->assertEmpty($validator(""));
    $this->assertEmpty($validator(null));
    $this->assertEmpty($validator([]));
    $this->assertNotEmpty($validator("meow"));
  }

  public function testCanBeNegated():void
  {
    $validator = new EmptinessValidator(false);
    $this->assertNotEmpty($validator(0));
    $this->assertNotEmpty($validator(""));
    $this->assertNotEmpty($validator(null));
    $this->assertNotEmpty($validator([]));
    $this->assertEmpty($validator("meow"));
    $this->assertEmpty($validator([1,2,3]));
  }
}