<?php
namespace NID\Cards\Heroes;
use NID\Cards;

class Khrad extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->thingvellir = true;
    $this->id = KHRAD;
    $this->name = 'Khrad';
    $this->subname = clienttranslate("The Beggar");
    $this->tooltip = [
      clienttranslate("Add 4 points to your Final Bravery Value."),
      clienttranslate("When you recruit him, immediately add +10 to your lowest value coin.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      4
    ];
  }


  public function stateAfterRecruit($player){
    return 'khradTransform';
  }
}
