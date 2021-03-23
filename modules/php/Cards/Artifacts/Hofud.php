<?php
namespace NID\Cards\Artifacts;

class Hofud extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = HOFUD;
    $this->age = 2;
    $this->name = 'Höfud';
    $this->tooltip = [
      clienttranslate("Immediately, each other Elvaland chooses and discards a Warrior card from their army. The discarded card can be any card in the Warrior column except a Hero card."),
    ];
    $this->grade = [ null ];
  }

  public function stateAfterRecruit($player){
    return 'hofud';
  }
}
