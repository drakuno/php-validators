<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\IntRangeValidator;

class IntRangeValidatorTest extends TestCase
{
  public function testFailsOnWrongType():void
  {
    $validator = new IntRangeValidator;
    $this->assertEmpty($validator(1));
    $this->assertNotEmpty($validator("meow"));
  }

  public function testMinParameter():void
  {
    $validator = new IntRangeValidator(min:5);
    $this->assertEmpty($validator(5));
    $this->assertEmpty($validator(6));
    $this->assertNotEmpty($validator(4));
    $this->assertNotEmpty($validator(-100));
  }

  public function testMaxParameter():void
  {
    $validator = new IntRangeValidator(max:5);
    $this->assertEmpty($validator(5));
    $this->assertEmpty($validator(4));
    $this->assertNotEmpty($validator(100));
  }
}