<?php

use PHPUnit\Framework\TestCase;

use Drakuno\Validators as V;
use Drakuno\Validators\ValidationError;

class ExamplesTest extends TestCase
{
  public function testComplexStructureValidation():void
  {
    $email_pattern = "/^[A-z0-9\.-_]+@[A-z0-9]+(\.[A-z0-9]+)+$/";
    $phone_pattern = "/^\+?\d{10,13}$/";
    $date_pattern  = "/^\d{4,4}-\d{2,2}-\d{2,2}$/";

    $phone_errors_maker = function($value):array
    {
      return [
        new ValidationError(
          "invalid-phone",
          "The value must be a valid phone number",
          $value
        )
      ];
    };

    $non_empty_string_validator = new V\ChainedValidator([
      new V\TypeValidator("string"),
      new V\EmptinessValidator(false),
    ]);
    $date_string_validator = new V\ChainedValidator([
      new V\TypeValidator("string"),
      new V\StringFormatValidator($date_pattern),
    ]);
    $room_pax_validator = function($value):array
    {
      $errors = array();
      if (!is_array($value))
        $errors[] = "type-error";
      else {
        if (!array_key_exists("adults",$value))
          $errors[] = "no-adults-specified";
        if (!array_key_exists("children",$value))
          $errors[] = "no-children-specified";
      }
      return $errors;
    };

    $validator = new V\ChainedValidator([
      new V\TypeValidator("array"),
      new V\ChainedValidator([
        new V\HasKeyValidator("holder"),
        new V\HasKeyValidator("check_in"),
        new V\HasKeyValidator("check_out"),
        new V\HasKeyValidator("rooms_paxes"),
      ],false),
      new V\ChainedValidator([
        new V\KeyValidator("holder",new V\ChainedValidator([
          new V\TypeValidator("array"),
          new V\ChainedValidator([
            new V\HasKeyValidator("name"),
            new V\HasKeyValidator("surname"),
            new V\HasKeyValidator("email"),
            new V\HasKeyValidator("phone"),
          ],false),
          new V\KeyValidator("name",$non_empty_string_validator),
          new V\KeyValidator("surname",$non_empty_string_validator),
          new V\KeyValidator("email",new V\ChainedValidator([
            new V\TypeValidator("string"),
            new V\StringFormatValidator($email_pattern)
          ])),
          new V\KeyValidator("phone",new V\ChainedValidator([
            new V\TypeValidator("string"),
            new V\StringFormatValidator($phone_pattern,$phone_errors_maker)
          ]))
        ])),
        new V\KeyValidator("check_in",$date_string_validator),
        new V\KeyValidator("check_out",$date_string_validator),
        new V\KeyValidator("rooms_paxes",new V\ChainedValidator([
          new V\TypeValidator("array"),
          new V\ArrayItemsValidator($room_pax_validator)
        ]))
      ],false),
    ]);

    $value = [
      'holder'=>[
        'name'=>"Princess",
        'surname'=>"Carolyn",
        'email'=>"pc@vim.com",
        'phone'=>"12",
      ],
      'check_in'=>"2021-11-09",
      'check_out'=>"2021-11-12",
      'rooms_paxes'=>[
        true,
        ['adults'=>1]
      ]
    ];

    $errors = $validator($value);

    // map to simple array tree
    $data_actual   = ValidationError::dataNestedMap($errors);
    // exportable and comparable to json
    $data_expected = json_decode(
      file_get_contents(__DIR__."/example1.json"),
      true
    );

    $this->assertEquals($data_expected,$data_actual);
  }
}
