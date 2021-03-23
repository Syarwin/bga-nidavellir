<?php
namespace NID\Cards\Heroes;

class Dagda extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DAGDA;
    $this->name = 'Dagda';
    $this->heroClass = HUNTER;
    $this->subname = clienttranslate("The Explosive");
    $this->tooltip = [
      clienttranslate("Has 3 ranks."),
      clienttranslate("Immediately place Dagda in your Hunter column, and Immediately discard the last Dwarf card of your choosing from two others columns of your army. The two columns must be different.")
    ];
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

  public function stateAfterRecruit($player){
    return 'discard';
  }

  public function getDiscardRequirement(){
    return 2;
  }
}
