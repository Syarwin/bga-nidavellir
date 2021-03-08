<?php
namespace NID\Cards;

class Tarah extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = TARAH;
    $this->name = 'Tarah';
    $this->subname = clienttranslate("Lethal Strike");
    $this->tooltip = [
      clienttranslate("Has 1 ranks."),
      clienttranslate("Add 14 points to your Warrior Bravery Value.")
    ];
    $this->heroClass = WARRIOR;
    $this->grade = [
      14,
    ];
  }
}
