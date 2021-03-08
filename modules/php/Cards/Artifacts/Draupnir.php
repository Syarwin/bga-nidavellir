<?php
namespace NID\Cards\Artifacts;

class Draupnir extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DRAUPNIR;
    $this->age = 1;
    $this->name = 'Draupnir';
    $this->tooltip = [
      clienttranslate("At the end of Age 2, when counting points, add to your Final Bravery Value : 6 points per coin of value 15 or more owned."),
    ];
    $this->grade = [ null ];
  }
}
