<?php
namespace NID\Cards;

class Dwerg1 extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DWERG1;
    $this->name = 'Dwerg';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [null],
    ];
  }
}
