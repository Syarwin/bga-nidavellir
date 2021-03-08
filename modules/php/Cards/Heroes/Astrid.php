<?php
namespace NID\Cards\Heroes;

class Astrid extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = ASTRID;
    $this->name = 'Astrid';
    $this->subname = clienttranslate("La fortunée");
    $this->tooltip = [
      clienttranslate("Add X points to your final Bravery Value."),
      clienttranslate("X is the value of the largest coin you own.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      null
    ];
  }

  public function updateBraveryValues(&$values, $player){
    $values[NEUTRAL] += $player->getMaxCoin();
  }
}
