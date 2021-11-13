<?php

namespace Drakuno\Validators;

class ArrayItemsValidator implements ValidatorInterface
{
  use CustomErrorsMakerTrait;

  private $item_validator;
  private $provide_items_as_tuples;

  public function __construct(
    callable $item_validator,
    bool $provide_items_as_tuples=false,
    ?callable $item_errors_maker=null,
    ?callable $errors_maker=null
  )
  {
    $this->item_validator          = $item_validator;
    $this->provide_items_as_tuples = $provide_items_as_tuples;

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

    return $this->errorsMake($value,$items_errors);
  }

  public function defaultErrorsMake(
    $value,
    array $items_errors
  ):array
  {
    return $items_errors;
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