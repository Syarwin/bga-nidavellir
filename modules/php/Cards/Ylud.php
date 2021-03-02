<?php
namespace NID\Cards;

class Ylud extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = YLUD;
    $this->name = 'Ylud';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      null
    ];
  }
}
