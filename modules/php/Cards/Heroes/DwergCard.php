<?php
namespace NID\Cards\Heroes;

/*
 * DwergCard: same behavior for all dwerg brothers
 */

abstract class DwergCard extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->name = 'Dwerg';
    $this->subname = clienttranslate("The 5 Brothers");
    $this->tooltip = [
      clienttranslate("Add X points to your final Bravery Value."),
      clienttranslate("X depends on the number of recruited brothers."),
      clienttranslate("X = 13 for 1 brother, X = 40 for 2 brothers, X = 81 for 3 brothers, X = 108 for 4 brothers, X = 135 for 5 brothers.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      null,
    ];
  }

  public function updateBraveryValues(&$values, $player){
    $dwergCount = $player->getCards()->reduce(function($carry, $card){
      return $carry + ($card instanceof DwergCard? 1 : 0);
    }, 0);

    // It's going to be multiplied by the number of Dwerg since each one adds the same amount
    $dwergBV = [0, 13, 20, 27, 27, 27];

    $values[NEUTRAL] += $dwergBV[$dwergCount];
  }
}
