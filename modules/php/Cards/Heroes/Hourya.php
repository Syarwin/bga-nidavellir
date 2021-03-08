<?php
namespace NID\Cards\Heroes;

class Hourya extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = HOURYA;
    $this->name = 'Hourya';
    $this->subname = clienttranslate("The Elusive");
    $this->tooltip = [
      clienttranslate("Has 1 rank."),
      clienttranslate("Add 20 points to your Explorer Bravery Value."),
      clienttranslate("You need to have 5 ranks in the Explorer column of your army in order to recruit her."),
    ];
    $this->heroClass = EXPLORER;
    $this->grade = [
      20
    ];
  }

  public function canBeRecruited($player){
    return $player->getRanks()[EXPLORER] >= 5;
  }
}
