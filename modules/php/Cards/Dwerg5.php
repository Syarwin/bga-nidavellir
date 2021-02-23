<?php
namespace NID\Cards;

class Dwerg5 extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DWERG5;
    $this->name = 'Dwerg';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [null],
    ];
  }
}
