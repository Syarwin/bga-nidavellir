<?php
namespace NID\Cards;

class Skaa extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = SKAA;
    $this->name = 'Skaa';
    $this->subname = clienttranslate("The Unfathomable");
    $this->tooltip = [
      clienttranslate("Add 17 points to your final Bravery Value.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      17
    ];
  }
}
