<?php
namespace NID\Cards\Mercenaries;

class Mercenary7 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_7;
    $this->age = 2;
    $this->grades = [
      WARRIOR => 6,
      MINER => 1,
    ];
  }
}
