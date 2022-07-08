<?php
namespace NID\Cards\Artifacts;

class Vegvisir extends ArtifactCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = VEGVISIR;
    $this->age = 1;
    $this->name = 'Vegvisir';
    $this->tooltip = [
      clienttranslate(
        'Immediately place this Artifact in the Explorer column of your army. Its pose can trigger the recruitment of a Hero card if it completes a rank line.'
      ),
      clienttranslate('The artifact counts as an Explorer rank and adds 13 points to your Explorer Bravery Rating.'),
    ];
    $this->grade = [13];
  }

  public function getRecruitementZone()
  {
    return EXPLORER;
  }

  public function hasRankOfClass($class)
  {
    return EXPLORER == $class;
  }

  public function updateRanks(&$ranks, $uselessExceptThrud)
  {
    $ranks[EXPLORER]++;
  }

  public function updateBraveryValues(&$values, $player)
  {
    $values[EXPLORER] += 13;
  }
}
