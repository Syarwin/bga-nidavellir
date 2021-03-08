<?php
namespace NID\Cards\Heroes;

class Holda extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->expansion = true;
    $this->id = HOLDA;
    $this->name = 'Holda';
    $this->subname = clienttranslate("The Ministrel");
    $this->tooltip = [
      clienttranslate("Add 12 points to your Final Bravery Value."),
      clienttranslate("When you recruit her, immediately choose a Mercenary or Artifact card available at the Camp.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      12
    ];
  }
}
