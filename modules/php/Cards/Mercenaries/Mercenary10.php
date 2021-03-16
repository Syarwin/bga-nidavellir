<?php
namespace NID\Cards\Mercenaries;

class Mercenary10 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_10;
    $this->age = 2;
    $this->grades = [
      EXPLORER => 8,
      MINER => 1,
    ];
  }
}
