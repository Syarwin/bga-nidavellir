<?php
namespace NID\Cards;

class Dwerg3 extends DwergCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DWERG3;
  }
}
