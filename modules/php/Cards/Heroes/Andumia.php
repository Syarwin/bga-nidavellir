<?php
namespace NID\Cards\Heroes;
use NID\Cards;

class Andumia extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->thingvellir = true;
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

  public function canBeRecruited($player){
    return Cards::countInLocation('discard') > 0;
  }

  public function stateAfterRecruit($player){
    return 'andumia';
  }
}
