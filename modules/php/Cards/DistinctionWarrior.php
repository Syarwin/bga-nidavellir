<?php
namespace NID\Cards;
use NID\Game\Globals;


class DistinctionWarrior extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = WARRIOR;
  }


  public function stateAfterRecruit(){
    Globals::setTransformValue(5);
    return 'transform';
  }
}
