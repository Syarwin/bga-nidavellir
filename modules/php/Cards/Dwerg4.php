<?php
namespace NID\Cards;

class Dwerg4 extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DWERG4;
    $this->name = 'Dwerg';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [null],
    ];
  }
}
