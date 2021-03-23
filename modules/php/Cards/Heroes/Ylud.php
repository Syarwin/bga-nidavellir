<?php
namespace NID\Cards\Heroes;

class Ylud extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = YLUD;
    $this->name = 'Ylud';
    $this->subname = clienttranslate("The Unpredictable");
    $this->tooltip = [
      clienttranslate("Place it in your Command Zone."),
      clienttranslate("Just before the resolution of the last Tavern of an age, place/move Ylud to a column of your choice, and consider Ylud as a dwarf of this class."),
      clienttranslate("She takes the value corresponding to the column where she is at the end of Age 2 and her rank couns in the Warrior majority if placed in this column."),
      clienttranslate("Placing Ylud in your army may trigger a Hero card recruitment."),
    ];
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
