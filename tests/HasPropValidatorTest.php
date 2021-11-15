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

  public function testPresentIsValidParameter():void
  {
    $validator = new HasPropValidator("sound",true);
    $value     = (object)[
      'sound'=>"meow",
      'family'=>"felidae",
    ];
    $this->assertEmpty($validator($value));

    $validator = new HasPropValidator("sound",false);
    $this->assertNotEmpty($validator($value));
  }
}