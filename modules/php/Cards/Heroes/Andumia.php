<?php
namespace NID\Cards\Heroes;

class Andumia extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->expansion = true;
    $this->id = ANDUMIA;
    $this->name = 'Andumia';
    $this->subname = clienttranslate("The Necromancer");
    $this->tooltip = [
      clienttranslate("Add 12 points to your Final Bravery Value."),
      clienttranslate("When you recruit her, immediately look at all the cards in the discard pile and keep one.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      12
    ];
  }
}
