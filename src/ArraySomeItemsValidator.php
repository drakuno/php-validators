<?php

namespace Drakuno\Validators;

class ArraySomeItemsValidator implements ValidatorInterface
{
  use CustomErrorsMakerTrait;

  private $item_validator;
  private $provide_items_as_tuples;

  public function __construct(
    callable $item_validator,
    bool $provide_items_as_tuples=false,
    int $minimum=1,
    ?int $maximum=null,
    ?callable $item_errors_maker=null,
    ?callable $errors_maker=null
  )
  {
    $this->item_validator          = $item_validator;
    $this->provide_items_as_tuples = $provide_items_as_tuples;
    $this->minimum                 = max(0,$minimum);
    $this->maximum                 = max(-1,$maximum);

    $this->item_errors_maker = $item_errors_maker?:[$this,"defaultItemErrorsMake"];
    $this->setErrorsMaker($errors_maker?:[$this,"defaultErrorsMake"]);
  }

  public function __invoke($value):array
  {
    $items_errors = array();
    foreach ($value as $key=>$item) {
      if ($this->provide_items_as_tuples)
        $item = [$key,$item];

      $item_errors = ($this->item_validator)($item);

      if (!empty($item_errors))
        $items_errors = array_merge(
          $items_errors,
          ($this->item_errors_maker)($key,$item,$item_errors,$value)
        );
    }

    $valid_items_count = count($value)-count($items_errors);
    if ($this->minimum>$valid_items_count)
      return $this->errorsMake($value,"insufficient-valid-items",$items_errors);
    elseif ($this->maximum>-1&&$this->maximum<$valid_items_count)
      return $this->errorsMake($value,"excessive-matched-items",$items_errors);
    else
      return [];
  }

  public function defaultErrorsMake(
    $value,
    string $error_code,
    array $items_errors
  ):array
  {
    if ($error_code=="insufficient-valid-items")
      return [new ValidationError(
        "insufficient-valid-items",
        "At least {$this->minimum} items must pass validation",
        $items_errors
      )];
    elseif ($error_code=="excessive-matched-items")
      return [new ValidationError(
        "excessive-matched-items",
        "At most {$this->maximum} items can pass validation",
        $items_errors
      )];
  }

  public function defaultItemErrorsMake(
    $key,
    $item,
    array $item_errors,
    $value
  ):array
  {
    return [
      new ValidationError(
        "invalid-item",
        "The item at key `{$key}` has validation errors",
        [
          'key'=>$key,
          'value'=>$item,
          'errors'=>$item_errors
        ]
      )
    ];
  }
}