<?php
namespace NID\Cards\Animals;

class Durathor extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = DURATHOR;
    $this->name = 'Durathor';
    $this->tooltip = [
      clienttranslate('Has 1 Hunter rank.'),
      clienttranslate('Reduce the discard effect of Dagda by one card.'),
    ];
    $this->animalClass = HUNTER;
    $this->grade = [null];
  }
}
