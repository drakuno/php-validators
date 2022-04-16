<?php

namespace Drakuno\Validators;

class KeyValidator extends ChildValidator
{
  protected $allow_default;
  protected $default;
  protected $key;

  public function __construct(
    string $key,
    callable $validator,
    $default=null,
    $allow_default=true,
    ?callable $errors_maker=null
  )
  {
    $this->allow_default = $allow_default;
    $this->default       = $default;
    $this->key           = $key;

    $accessor = ChildValidator::keyAccessorMake($key,$default,$allow_default);
    parent::__construct($accessor,$validator,$errors_maker);
  }

  public function defaultErrorsMake($value,$key_value,array $child_errors):array
  {
    return [
      new ValidationError(
        "invalid-key",
        "Key `{$this->key}` has validation errors",
        [
          'key'=>$this->key,
          'value'=>$key_value,
          'errors'=>$child_errors
        ]
      )
    ];
  }
}

