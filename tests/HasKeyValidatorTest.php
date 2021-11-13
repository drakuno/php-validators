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
}