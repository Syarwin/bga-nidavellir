<?php
namespace NID\Cards\Heroes;

class Jarika extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->thingvellir = true;
    $this->id = JARIKA;
    $this->name = 'Jarika';
    $this->subname = clienttranslate("The Rogue");
    $this->tooltip = [
      clienttranslate("Add 8 points to your Final Bravery Value."),
      clienttranslate("During a coin transformation or a coin trade, increase the value of the desired sum by +2.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      8
    ];
  }
}
