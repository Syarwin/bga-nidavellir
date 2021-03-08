<?php
namespace NID\Cards\Heroes;

class Idunn extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = IDUNN;
    $this->name = 'Idunn';
    $this->subname = clienttranslate("The Furtive");
    $this->tooltip = [
      clienttranslate("Has 1 rank."),
      clienttranslate("Add 7 points to your Explorer Bravery Value plus 2 points per present rank in this column, including her own.")
    ];
    $this->heroClass = EXPLORER;
    $this->grade = [
      7
    ];
  }

  public function updateBraveryValues(&$values, $player){
    $values[EXPLORER] += $this->getBV() + $player->getRanks()[EXPLORER]*2;
  }
}
