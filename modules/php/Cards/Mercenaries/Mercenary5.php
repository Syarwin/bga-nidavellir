<?php
namespace NID\Cards\Mercenaries;

class Mercenary5 extends MercenaryCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MERCENARY_5;
    $this->age = 1;
    $this->grades = [
      WARRIOR => 9,
      EXPLORER => 11,
    ];
  }
}
