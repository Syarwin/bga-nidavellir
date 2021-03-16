<?php
namespace NID\Cards\Mercenaries;

class Mercenary0 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_0;
    $this->age = 1;
    $this->grades = [
      WARRIOR => 6,
      EXPLORER => 8,
    ];
  }
}
