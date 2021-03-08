<?php
namespace NID\Cards\Heroes;

class Lokdur extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = LOKDUR;
    $this->name = 'Lokdur';
    $this->subname = clienttranslate("Greedy Heart");
    $this->tooltip = [
      clienttranslate("Has 1 rank."),
      clienttranslate("Add 3 Bravery Points to the sum of the Miners.")
    ];
    $this->heroClass = MINER;
    $this->grade = [
      3
    ];
  }
}
