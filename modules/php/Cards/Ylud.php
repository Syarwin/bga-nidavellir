<?php
namespace NID\Cards;

class Ylud extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = YLUD;
    $this->name = 'Ylud';
    $this->heroClass = NEUTRAL;
    $this->advanced = true;
    $this->grade = [
      null
    ];
  }

  public function updateRanks(&$ranks){
    $ranks[$this->zone] += $this->getRanks();
  }

  public function updateBraveryValues(&$values, $player){
    $bv = [0, 0, 0, 11, 1, 7];
    $values[$this->zone] += $bv[$this->zone];
  }
}
