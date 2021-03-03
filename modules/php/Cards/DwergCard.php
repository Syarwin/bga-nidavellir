<?php
namespace NID\Cards;

/*
 * DwergCard: same behavior for all dwerg brothers
 */

abstract class DwergCard extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->name = 'Dwerg';
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
