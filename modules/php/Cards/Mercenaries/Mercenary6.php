<?php
namespace NID\Cards\Mercenaries;

class Mercenary6 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_6;
    $this->age = 2;
    $this->grades = [
      HUNTER => 0,
      BLACKSMITH => 0,
    ];
  }
}
