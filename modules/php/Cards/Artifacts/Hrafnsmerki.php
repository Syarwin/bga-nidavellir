<?php
namespace NID\Cards\Artifacts;

class Hrafnsmerki extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = HRAFNSMERKI;
    $this->age = 2;
    $this->name = 'Hrafnsmerki';
    $this->tooltip = [
      clienttranslate("At the end of Age 2, when counting points, add to your Final Bravery Value: 5 points per Mercenary card in your possession."),
    ];
    $this->grade = [ null ];
  }

  public function updateScores(&$scores, $player){
    $scores[ARTIFACT_SCORE] += 5 * $player->countMercenaries();
  }
}
