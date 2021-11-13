<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\KeyValidator;

class KeyValidatorTest extends TestCase
{
  public function testProvidesKeyToChildValidator():void
  {
    $child_validator
      = function($value){ $this->assertEquals($value,"meow");return []; };

    $validator = new KeyValidator("sound",$child_validator);

    $value  = ['sound'=>"meow",'family'=>"felidae"];
    $errors = $validator($value);
  }

  public function testFailsWhenChildValidationFails():void
  {
    $child_validator = function($value){ return ["error"]; }; // always fail

    $validator = new KeyValidator("somekey",$child_validator);

    $this->assertNotEmpty($validator(['somekey'=>"rawr"]));
  }

  public function testProvidesDefaultWhenKeyMissing():void
  {
    $child_validator
      = function($value){ $this->assertEquals($value,"meow");return []; };

    $validator = new KeyValidator(
      "sound",
      $child_validator,
      "meow"
    );

    $value  = ['family'=>"felidae"];
    $errors = $validator($value);
  }

  public function testAllowsToPreventDefault():void
  {
    $child_validator
      = function($value){ $this->assertEquals($value,"meow");return []; };

    $validator = new KeyValidator(
      "sound",
      $child_validator,
      "meow",
      false
    );

    $value  = ['family'=>"felidae"];
    $this->expectException(Exception::class);
    $errors = $validator($value);
  }
}