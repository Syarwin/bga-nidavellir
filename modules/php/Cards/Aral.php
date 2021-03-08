<?php
namespace NID\Cards;

class Aral extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = ARAL;
    $this->name = 'Aral';
    $this->subname = clienttranslate("Eagle Claws");
    $this->tooltip = [ clienttranslate("Has 2 ranks.") ];
    $this->heroClass = HUNTER;
    $this->grade = [
      null,
      null,
    ];
  }
}
