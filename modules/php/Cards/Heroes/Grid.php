<?php
namespace NID\Cards\Heroes;
use \NID\Game\Globals;
use \NID\Cards;

class Grid extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = GRID;
    $this->name = 'Grid';
    $this->subname = clienttranslate("The Mercantile");
    $this->tooltip = [
      clienttranslate("Add 7 points to your final Bravery Value."),
      clienttranslate("When you recruit her, immediately add +7 to one of your coins.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      7
    ];
  }

  public function stateAfterRecruit($player){
    $value = 7;
    if(Cards::getJarikaOwner() == $player->getId())
      $value += 2;

    Globals::setTransformValue($value);
    return 'transform';
  }
}
