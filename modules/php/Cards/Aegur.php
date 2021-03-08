<?php
namespace NID\Cards;

class Aegur extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = AEGUR;
    $this->name = 'Aëgur';
    $this->subname = clienttranslate("Fist of steel");
    $this->tooltip = [ clienttranslate("Has 2 ranks.") ];
    $this->heroClass = BLACKSMITH;
    $this->grade = [
      null,
      null,
    ];
  }
}
