<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators as V;

class ChainedValidatorTest extends TestCase
{
  public function testOnlyAcceptsCallables():void
  {
    new V\ChainedValidator([function($value){ return array(); }]);
    $this->expectException(TypeError::class);
    new V\ChainedValidator(["meow"]);
  }

  public function testShortCircuitsWhenIndicated():void
  {
    $validator = new V\ChainedValidator(
      [
        function($value){ return array("error"); },
        function($value){ return array("anothererror"); },
      ],
      true
    );
    $errors = $validator("meow");
    $this->assertEquals(1,count($errors));
  }

  public function testRunsAllValidatorsWhenIndicated():void
  {
    $validator = new V\ChainedValidator(
      [
        function($value){ return array("error"); },
        function($value){ return array("anothererror","aa"); },
      ],
      false
    );
    $errors = $validator("meow");
    $this->assertEquals(3,count($errors));
  }
}