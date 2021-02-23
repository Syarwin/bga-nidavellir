<?php
namespace NID\Cards;

class Dwerg3 extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DWERG3;
    $this->name = 'Dwerg';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [null],
    ];
  }
}
