<?php
namespace NID\Cards\Artifacts;

class Fafnir extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = FAFNIR;
    $this->age = 1;
    $this->name = 'Fafnir Baleygr';
    $this->tooltip = [
      clienttranslate("After taking possession of it and throughout the game, you can go to the Camp on your turn instead of taking a card from the tavern being resolved if the Elvaland that won the bid did not go."),
    ];
    $this->grade = [ null ];
  }
}
