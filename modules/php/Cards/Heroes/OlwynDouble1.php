<?php
namespace NID\Cards\Heroes;

class OlwynDouble1 extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = OLWYN_DOUBLE1;
    $this->name = clienttranslate('Olwyn\'s double');
    $this->subname = clienttranslate("The Illusionist");
    $this->tooltip = [];
    $this->heroClass = NEUTRAL;
    $this->grade = [null];
  }

  public function isDiscardable() { return true; }

  public function updateRanks(&$ranks){
    $ranks[$this->zone]++;
  }
}
