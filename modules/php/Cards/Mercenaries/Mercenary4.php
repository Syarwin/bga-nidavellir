<?php
namespace NID\Cards\Mercenaries;

class Mercenary4 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_4;
    $this->age = 1;
    $this->grades = [
      BLACKSMITH => 0,
      MINER => 1,
    ];
  }
}
