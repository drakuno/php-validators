<?php

namespace Drakuno\Validators;

use Exception;

class ChildValidator implements ValidatorInterface
{
  use CustomErrorsMakerTrait;

  private $accessor;
  private $validator;

  public function __construct(
    callable $accessor,
    callable $validator,
    ?callable $errors_maker=null
  )
  {
    $this->accessor  = $accessor;
    $this->validator = $validator;

    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  public function __invoke($value):array
  {
    $child        = ($this->accessor)($value);
    $child_errors = ($this->validator)($child);
    return count($child_errors)
             ?$this->errorsMake($value,$child,$child_errors)
             :array();
  }

  static public function errorsMakerMake(
    string $child_identifier,
    string $keyword="child"
  ):callable
  {
    return function($value,$child,array $child_errors) use ($child_identifier,$keyword)
    {
      return [
        new ValidationError(
          "invalid-{$keyword}",
          sprintf('%s `%s` has validation errors',
            ucfirst($keyword),
            $child_identifier
          ),
          [
            $keyword=>$child_identifier,
            'errors'=>$child_errors,
          ]
        )
      ];
    };
  }

  static public function keyAccessorMake(
    string $key,
    $default=null,
    bool $allow_default=true
  ):callable
  {
    return function($value) use ($key,$default,$allow_default)
    {
      if (array_key_exists($key,$value))
        $child_value = $value[$key];
      else if ($allow_default)
        $child_value = $default;
      else
        throw new Exception("Undefined array key '{$key}'");
      return $child_value;
    };
  }

  static public function propertyAccessorMake(
    string $prop,
    $default=null,
    bool $allow_default=true
  ):callable
  {
    return function($value) use ($prop,$default,$allow_default)
    {
      if (property_exists($value,$prop))
        $child_value = $value->$prop;
      else if ($allow_default)
        $child_value = $default;
      else
        throw new Exception(sprintf('Undefined property: %s::$%s',
          get_class($value),
          $prop
        ));
      return $child_value;
    };
  }

  public function defaultErrorsMake($value,$child,array $child_errors):array
  {
    return [
      new ValidationError(
        "invalid-child",
        "A child has validation errors",
        [
          'child'=>$child,
          'errors'=>$child_errors
        ]
      )
    ];
  }
}