<?php
namespace NID\Cards;

class Dwerg2 extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DWERG2;
    $this->name = 'Dwerg';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [null],
    ];
  }
}
