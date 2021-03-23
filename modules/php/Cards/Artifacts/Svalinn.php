<?php
namespace NID\Cards\Artifacts;

class Svalinn extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = SVALINN;
    $this->age = 1;
    $this->name = 'Svalinn';
    $this->tooltip = [
      clienttranslate("At the end of Age 2, when counting points, add to your Final Bravey Value: 5 points per Hero card in your possession."),
    ];
    $this->grade = [ null ];
  }


  public function updateScores(&$scores, $player){
    $scores[ARTIFACT_SCORE] += 5 * $player->countHeroes();
  }
}
