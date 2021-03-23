<?php
namespace NID\Cards\Distinctions;
use NID\Game\Globals;
use NID\Cards;


class DistinctionWarrior extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = WARRIOR;
    $this->name = clienttranslate("The King's Hand");
    $this->tooltip = [
      clienttranslate("Add +5 immediately to one of your coins."),
    ];
  }


  public function stateAfterRecruit($player){
    $value = 5;
    if(Cards::getJarikaOwner() == $player->getId())
      $value += 2;

    Globals::setTransformValue($value);
    return 'transform';
  }
}
