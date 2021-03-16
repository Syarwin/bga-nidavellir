<?php
namespace NID\Cards\Mercenaries;

class Mercenary11 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_11;
    $this->age = 2;
    $this->grades = [
      WARRIOR => 9,
      EXPLORER => 11,
    ];
  }
}
