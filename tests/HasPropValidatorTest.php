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

  public function testNegated():void
  {
    $validator = new HasPropValidator("sound",false);
    $value     = (object)[
      'sound'=>"meow",
      'family'=>"felidae",
    ];
    $this->assertEmpty($validator($value));

    $validator = new HasPropValidator("sound",true);
    $this->assertNotEmpty($validator($value));
  }
}
