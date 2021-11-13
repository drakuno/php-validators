<?php

namespace Drakuno\Validators;

use Closure;

trait CustomErrorsMakerTrait
{
  private $errors_maker;

  public function getErrorsMaker():callable { return $this->errors_maker; }
  public function setErrorsMaker(callable $errors_maker)
  {
    $this->errors_maker =
      Closure::fromCallable($errors_maker)->bindTo($this,$this);
  }

  public function errorsMake(...$args):array
  {
    return call_user_func_array($this->errors_maker,$args);
  }
}