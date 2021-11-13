<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators\{ArrayItemsValidator,ValidationError};

class ArrayItemsValidatorTest extends TestCase
{
  public function testValidatesItems():void
  {
    $item_validator = function($item):array{ return is_int($item)?[]:["not-an-int"]; };
    $validator = new ArrayItemsValidator($item_validator);

    $this->assertNotEmpty($validator([1,2,"meow",3]));
    $this->assertEmpty($validator([5,1]));
  }

  public function testCanProvideItemsAsTuples():void
  {
    $item_validator = function($tuple):array
    {
      $this->assertIsArray($tuple);
      return [];
    };
    $validator = new ArrayItemsValidator($item_validator,true);
    $validator([1]);
  }
}