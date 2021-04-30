<?php
namespace NID\Cards\Artifacts;

class Gjallarhorn extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = GJALLARHORN;
    $this->age = 2;
    $this->name = 'Gjallarhorn';
    $this->tooltip = [
      clienttranslate("Immediately recruit a Hero card regardless of your rank line number. To recruit your next Hero card, you will need to validate the golden rule: to have a number of rank lines greater than your number of Hero cards owned.")
    ];
    $this->grade = [ null ];
  }

  public function stateAfterRecruit($player){
    return 'hero';
  }
}
