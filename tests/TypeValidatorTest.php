<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\TypeValidator;

class TypeValidatorTest extends TestCase
{
  public function testValidatesIntegers():void
  {
    $validator = new TypeValidator("int");
    $this->assertEmpty($validator(1));
    $this->assertNotEmpty($validator("1"));
    $this->assertNotEmpty($validator("meow"));
    $this->assertNotEmpty($validator(1.5));
    $this->assertNotEmpty($validator([1,2,3]));
    $this->assertNotEmpty($validator(true));
    $this->assertNotEmpty($validator(null));
    $this->assertNotEmpty($validator(new stdClass));
  }

  public function testValidatesFloats():void
  {
    $validator = new TypeValidator("float");
    $this->assertNotEmpty($validator(1));
    $this->assertNotEmpty($validator("1"));
    $this->assertNotEmpty($validator("meow"));
    $this->assertEmpty($validator(1.5));
    $this->assertNotEmpty($validator([1.5,2.5,3.5]));
    $this->assertNotEmpty($validator(true));
    $this->assertNotEmpty($validator(null));
    $this->assertNotEmpty($validator(new stdClass));
  }

  public function testValidatesStrings():void
  {
    $validator = new TypeValidator("str");
    $this->assertNotEmpty($validator(1));
    $this->assertEmpty($validator("1"));
    $this->assertEmpty($validator("1.5"));
    $this->assertEmpty($validator("meow"));
    $this->assertNotEmpty($validator(1.5));
    $this->assertNotEmpty($validator(["meow","bork","bop"]));
    $this->assertNotEmpty($validator(true));
    $this->assertNotEmpty($validator(null));
    $this->assertNotEmpty($validator(new stdClass));
  }

  public function testValidatesArrays():void
  {
    $validator = new TypeValidator("array");
    $this->assertNotEmpty($validator(1));
    $this->assertNotEmpty($validator("1"));
    $this->assertNotEmpty($validator("1.5"));
    $this->assertNotEmpty($validator("meow"));
    $this->assertNotEmpty($validator(1.5));
    $this->assertEmpty($validator(["meow","bork","bop"]));
    $this->assertNotEmpty($validator(true));
    $this->assertNotEmpty($validator(null));
    $this->assertNotEmpty($validator(new stdClass));
  }

  public function testValidatesBooleans():void
  {
    $validator = new TypeValidator("bool");
    $this->assertNotEmpty($validator(1));
    $this->assertNotEmpty($validator("1"));
    $this->assertNotEmpty($validator("meow"));
    $this->assertNotEmpty($validator(1.5));
    $this->assertNotEmpty($validator(["meow","bork","bop"]));
    $this->assertEmpty($validator(true));
    $this->assertEmpty($validator(false));
    $this->assertNotEmpty($validator(null));
    $this->assertNotEmpty($validator(new stdClass));
  }

  public function testValidatesNulls():void
  {
    $validator = new TypeValidator("null");
    $this->assertNotEmpty($validator(1));
    $this->assertNotEmpty($validator("1"));
    $this->assertNotEmpty($validator("meow"));
    $this->assertNotEmpty($validator(1.5));
    $this->assertNotEmpty($validator(["meow","bork","bop"]));
    $this->assertNotEmpty($validator(true));
    $this->assertNotEmpty($validator(false));
    $this->assertEmpty($validator(null));
    $this->assertNotEmpty($validator(new stdClass));
  }

  public function testValidatesCallables():void
  {
    $validator = new TypeValidator("callable");
    $this->assertNotEmpty($validator(1));
    $this->assertNotEmpty($validator("1"));
    $this->assertNotEmpty($validator("meow"));
    $this->assertNotEmpty($validator(1.5));
    $this->assertNotEmpty($validator(["meow","bork","bop"]));
    $this->assertNotEmpty($validator(true));
    $this->assertNotEmpty($validator(false));
    $this->assertNotEmpty($validator(null));
    $this->assertNotEmpty($validator(new stdClass));
    $this->assertEmpty($validator(function(){}));
    $this->assertEmpty($validator($validator));
  }

  public function testValidatesClasses():void
  {
    $validator = new TypeValidator(DateTime::class);
    $this->assertNotEmpty($validator(1));
    $this->assertNotEmpty($validator("1"));
    $this->assertNotEmpty($validator("meow"));
    $this->assertNotEmpty($validator(1.5));
    $this->assertNotEmpty($validator(["meow","bork","bop"]));
    $this->assertNotEmpty($validator(true));
    $this->assertNotEmpty($validator(false));
    $this->assertNotEmpty($validator(null));
    $this->assertNotEmpty($validator(new stdClass));
    $this->assertNotEmpty($validator(function(){}));
    $this->assertNotEmpty($validator($validator));
    $this->assertEmpty($validator(new DateTime));
  }
}