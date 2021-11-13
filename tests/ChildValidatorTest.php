<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\{ChildValidator,ValidationError};

class ChildValidatorTest extends TestCase
{
  public function testProvidesChildUsingAccessor():void
  {
    $child_accessor
      = function($value){ return $value['sound']; };
    $child_validator
      = function($value){ $this->assertEquals($value,"meow");return []; };

    $validator = new ChildValidator($child_accessor,$child_validator);

    $value  = ['sound'=>"meow",'family'=>"felidae"];
    $errors = $validator($value);
  }

  public function testFailsWhenChildValidationFails():void
  {
    $child_accessor  = "is_int"; // mock accessor
    $child_validator = function($value){ return ["error"]; }; // always fail

    $validator = new ChildValidator($child_accessor,$child_validator);

    $this->assertTrue(boolval($validator("rawr")));
  }

  public function testDefaultErrorsMaker():void
  {
    $child_accessor  = "is_int"; // mock accessor
    $child_validator = function($value){ return ["error"]; }; // always fail

    $validator = new ChildValidator($child_accessor,$child_validator);

    $this->assertEquals($validator("rawr"),[
      new ValidationError(
        "invalid-child",
        "A child has validation errors",
        [
          'child'=>false,
          'errors'=>["error"]
        ]
      )
    ]);
  }

  public function testErrorsMakerMaker():void
  {
    $errors_maker = ChildValidator::errorsMakerMake("id","key");
    $errors = $errors_maker(null,"meow",["child-error"]);
    $this->assertEquals($errors,[
      new ValidationError(
        "invalid-key",
        'Key `id` has validation errors',
        [
          'key'=>"id",
          'errors'=>["child-error"]
        ]
      )
    ]);
  }
}