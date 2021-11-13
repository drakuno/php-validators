<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\{AbstractConditionValidator,ValidationError};

class AbstractConditionValidatorTest extends TestCase
{
  public function testFailsOnClassTest():void
  {
    $validator = new class() extends AbstractConditionValidator
    {
      public function test($value):bool{ return $value==="meow"; }
      public function errorsMake($value):array
      {
        return ["not-meow"];
      }
    };

    $this->assertEmpty($validator("meow"));
    $this->assertNotEmpty($validator("asdfasdf"));
  }

  public function testReturnsClassDefinedErrors():void
  {
    $validator = new class() extends AbstractConditionValidator
    {
      public function test($value):bool{ return $value==="meow"; }
      public function errorsMake($value):array
      {
        return ["not-meow"];
      }
    };

    $this->assertEquals($validator("asdf"),["not-meow"]);
  }
}