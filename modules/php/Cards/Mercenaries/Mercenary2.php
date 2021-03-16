<?php
namespace NID\Cards\Mercenaries;

class Mercenary2 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_2;
    $this->age = 1;
    $this->grades = [
      HUNTER => 0,
      EXPLORER => 8,
    ];
  }
}
