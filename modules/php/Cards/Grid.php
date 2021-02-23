<?php
namespace NID\Cards;
use \NID\Game\Globals;

class Grid extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = GRID;
    $this->name = 'Grid';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [7]
    ];
  }

  public function stateAfterRecruit(){
    Globals::setTransformValue(7);
    return 'transform';
  }
}
