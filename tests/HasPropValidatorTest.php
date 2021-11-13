<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\HasPropValidator;

class HasPropValidatorTest extends TestCase
{
  public function testValidatesPropExistence():void
  {
    $validator = new HasPropValidator("sound");
    $this->assertEmpty($validator((object)[
      'sound'=>"meow",
      'family'=>"felidae",
    ]));
    $this->assertNotEmpty($validator(new stdClass));
  }
}