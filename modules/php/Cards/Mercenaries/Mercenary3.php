<?php
namespace NID\Cards\Mercenaries;

class Mercenary3 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_3;
    $this->age = 1;
    $this->grades = [
      HUNTER => 0,
      MINER => 1,
    ];
  }
}
