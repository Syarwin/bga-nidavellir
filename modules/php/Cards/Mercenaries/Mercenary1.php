<?php
namespace NID\Cards\Mercenaries;

class Mercenary1 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_1;
    $this->age = 1;
    $this->grades = [
      WARRIOR => 6,
      BLACKSMITH => 0,
    ];
  }
}
