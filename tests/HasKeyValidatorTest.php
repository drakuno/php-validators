<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\HasKeyValidator;

class HasKeyValidatorTest extends TestCase
{
  public function testValidatesKeyExistence():void
  {
    $validator = new HasKeyValidator("sound");
    $this->assertEmpty($validator([
      'sound'=>"meow",
      'family'=>"felidae",
    ]));
    $this->assertNotEmpty($validator([]));
  }

  public function testPresentIsValidParameter():void
  {
    $validator = new HasKeyValidator("sound",true);
    $value     = [
      'sound'=>"meow",
      'family'=>"felidae",
    ];
    $this->assertEmpty($validator($value));

    $validator = new HasKeyValidator("sound",false);
    $this->assertNotEmpty($validator($value));
  }
}