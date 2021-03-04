<?php
namespace NID\Cards;

class Bonfur extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = BONFUR;
    $this->name = 'Bonfur';
    $this->heroClass = BLACKSMITH;
    $this->grade = [
      null,
      null,
      null,
    ];
  }

  public function canBeRecruited($player){
    $stacks = $player->getDiscardableStacks();
    return count($stacks) >= 1 + (in_array(BLACKSMITH, $stacks)? 1 : 0);
  }

  public function stateAfterRecruit(){
    return 'discard';
  }

  public function getDiscardRequirement(){
    return 1;
  }
}
