<?php
namespace NID\Cards;
use \NID\Game\Globals;

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

  public function stateAfterRecruit(){
    Globals::setTransformValue(7);
    return 'transform';
  }
}
