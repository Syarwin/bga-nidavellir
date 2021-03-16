<?php
namespace NID\Cards\Mercenaries;

class Mercenary9 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_9;
    $this->age = 2;
    $this->grades = [
      WARRIOR => 6,
      HUNTER => 0,
    ];
  }
}
