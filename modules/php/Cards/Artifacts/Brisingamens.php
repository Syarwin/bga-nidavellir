<?php
namespace NID\Cards\Artifacts;

class Brisingamens extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = BRISINGAMENS;
    $this->age = 2;
    $this->name = 'Brisingamens';
    $this->tooltip = [
      clienttranslate("Immediately look at all the cards in the discard pile and choose two."),
      clienttranslate("At the end of Age 2, before counting points, discard a Dwarf card of your choice from your army. This card can be taken anywhere, in any column, but it cannot be a Hero card."),
    ];
    $this->grade = [ null ];
  }
}
