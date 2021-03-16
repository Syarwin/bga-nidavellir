<?php
namespace NID\Cards\Mercenaries;

class Mercenary8 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_8;
    $this->age = 2;
    $this->grades = [
      BLACKSMITH => 0,
      EXPLORER => 8,
    ];
  }
}
