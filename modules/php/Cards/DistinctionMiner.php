<?php
namespace NID\Cards;
use NID\Game\Globals;

class DistinctionMiner extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = MINER;
  }


  public function stateAfterRecruit(){
    Globals::setTransformValue(5);
    return 'transform';
  }

}
