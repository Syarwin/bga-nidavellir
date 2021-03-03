<?php
namespace NID\Cards;

class Thrud extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = THRUD;
    $this->name = 'Thrud';
    $this->heroClass = NEUTRAL;
    $this->advanced = true;
    $this->grade = [
      13
    ];
  }

  public function updateRanks(&$ranks){
    $ranks[$this->zone] += $this->getRanks();
  }
}
