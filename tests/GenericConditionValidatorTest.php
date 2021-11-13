<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\{GenericConditionValidator,ValidationError};

class GenericConditionValidatorTest extends TestCase
{
  public function testUsesCustomTest():void
  {
    $test      = "is_int";
    $validator = new GenericConditionValidator($test);

    $this->assertNotEmpty($validator("meow"));
    $this->assertEmpty($validator(1));
  }
}