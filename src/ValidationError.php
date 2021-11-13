<?php

namespace Drakuno\Validators;

class ValidationError
{
  public $code;
  public $msg;
  public $data;

  public function __construct(string $code,string $msg=null,$data=null)
  {
    $this->code = $code;
    $this->msg  = $msg;
    $this->data = $data;
  }

  static public function asArrayMap(ValidationError $error):array
  {
    $arr = array_filter(get_object_vars($error));
    if (!is_null($error->data))
      $arr['data'] = static::dataNestedMap($error->data);
    return $arr;
  }

  static public function dataNestedMap($data)
  {
    if (is_null($data))
      return $data;
    elseif (is_a($data,self::class))
      return static::asArrayMap($data);
    elseif (is_array($data))
      return array_map([static::class,"dataNestedMap"],$data);
    else
      return $data;
  }
}