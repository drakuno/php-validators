<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\PropertyValidator;

class PropertyValidatorTest extends TestCase
{
  public function testProvidesKeyToChildValidator():void
  {
    $child_validator
      = function($value){ $this->assertEquals($value,"meow");return []; };

    $validator = new PropertyValidator("sound",$child_validator);

    $value  = (object)['sound'=>"meow",'family'=>"felidae"];
    $errors = $validator($value);
  }

  public function testFailsWhenChildValidationFails():void
  {
    $child_validator = function($value){ return ["error"]; }; // always fail

    $validator = new PropertyValidator("somekey",$child_validator);

    $this->assertNotEmpty($validator((object)['somekey'=>"rawr"]));
  }

  public function testProvidesDefaultWhenKeyMissing():void
  {
    $child_validator
      = function($value){ $this->assertEquals($value,"meow");return []; };

    $validator = new PropertyValidator(
      "sound",
      $child_validator,
      "meow"
    );

    $value  = (object)['family'=>"felidae"];
    $errors = $validator($value);
  }

  public function testAllowsToPreventDefault():void
  {
    $child_validator
      = function($value){ $this->assertEquals($value,"meow");return []; };

    $validator = new PropertyValidator(
      "sound",
      $child_validator,
      "meow",
      false
    );

    $value  = (object)['family'=>"felidae"];
    $this->expectException(Exception::class);
    $errors = $validator($value);
  }
}