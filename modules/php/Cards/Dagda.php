<?php
namespace NID\Cards;

class Dagda extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DAGDA;
    $this->name = 'Dagda';
    $this->heroClass = HUNTER;
    $this->grade = [
      null,
      null,
      null,
    ];
  }

  public function canBeRecruited($player){
    $stacks = $player->getDiscardableStacks();
    return count($stacks) >= 2 + (in_array(HUNTER, $stacks)? 1 : 0);
  }

  public function stateAfterRecruit(){
    return 'discard';
  }

  public function getDiscardRequirement(){
    return 2;
  }
}
