<?php
namespace NID\Cards\Artifacts;

class Megingjord extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MEGINGJORD;
    $this->age = 1;
    $this->name = 'Megingjord';
    $this->tooltip = [
      clienttranslate("During the rest of the game, you can no longer recruit a Hero card by making rank lines. So making rank lines has no effect for you."),
      clienttranslate("At the end of Age 2, when counting points, add 28 points to your Final Bravey Value."),
    ];
    $this->grade = [ 28 ];
  }

  public function updateScores(&$scores, $player){
    $scores[ARTIFACT_SCORE] += 28;
  }
}
