<?php
namespace NID\Cards\Heroes;

class Kraal extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = KRAAL;
    $this->name = 'Kraal';
    $this->subname = clienttranslate("The Venal");
    $this->tooltip = [
      clienttranslate("Has 2 ranks."),
      clienttranslate("Add 7 and 0 points to your Warrior Bravery Value.")
    ];
    $this->heroClass = WARRIOR;
    $this->grade = [
      7,
      0,
    ];
  }
}
