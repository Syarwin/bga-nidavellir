<?php
namespace NID\Cards;

class Hourya extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = HOURYA;
    $this->name = 'Hourya';
    $this->heroClass = EXPLORER;
    $this->grade = [
      [20]
    ];
  }

  public function canBeRecruited($player){
    return $player->getRanks()[EXPLORER] >= 5;
  }
}
