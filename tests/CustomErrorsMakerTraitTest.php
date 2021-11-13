<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators as V;

class CustomErrorsMakerTraitTest extends TestCase
{
  public function testUsesProvidedErrorsMaker()
  {
    $errors_maker = function($value)
    {
      return array("my_custom_error!");
    };
    $mock_validator = new class($errors_maker)
    {
      use V\CustomErrorsMakerTrait;
      public function __construct(callable $errors_maker)
      {
        $this->setErrorsMaker($errors_maker);
      }
    };

    $this->assertEquals($mock_validator->errorsMake("bop"),["my_custom_error!"]);
  }

  public function testErrorsMakerIsBoundToInstance()
  {
    $errors_maker = function($value)
    {
      return [$this->secret];
    };
    $mock_validator = new class($errors_maker)
    {
      use V\CustomErrorsMakerTrait;
      private $secret="bop";
      public function __construct(callable $errors_maker)
      {
        $this->setErrorsMaker($errors_maker);
      }
    };

    $this->assertEquals($mock_validator->errorsMake("asdfas"),["bop"]);
  }
}
