<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\SwitchValidator;

class SwitchValidatorTest extends TestCase
{
  public function testSucceedsWhenChildSucceeds()
  {
    $validator = new SwitchValidator([
      function($value){ return ["error"]; },
      function($value){ return []; }
    ]);

    $this->assertEmpty($validator("miau"));
  }

  public function testFailsWhenAllChildrenFail()
  {
    $validator = new SwitchValidator([
      function ($value){ return ["error"]; },
      function ($value){ return ["error-tu"]; }
    ]);

    $this->assertNotEmpty($validator("miaumiau"));
  }
}