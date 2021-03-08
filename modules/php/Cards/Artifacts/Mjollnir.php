<?php
namespace NID\Cards\Artifacts;

class Mjollnir extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = MJOLLNIR;
    $this->age = 2;
    $this->name = 'Mjöllnir';
    $this->tooltip = [
      clienttranslate("At the end of Age 2, when counting points, add to your Final Bravery Value: 2 points per rank in the class of your choice."),
    ];
    $this->grade = [ null ];
  }
}
