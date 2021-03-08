<?php
namespace NID\Cards\Heroes;

class Zoral extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = ZORAL;
    $this->name = 'Zoral';
    $this->subname = clienttranslate("The Venal");
    $this->tooltip = [
      clienttranslate("Has 3 ranks."),
      clienttranslate("Add 1, 0 and 0 Bravery Points to the sum of Miners.")
    ];
    $this->heroClass = MINER;
    $this->grade = [
      1,
      0,
      0,
    ];
  }
}
