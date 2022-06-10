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
      clienttranslate('Has 1 rank.'),
      clienttranslate('Réduit l’effet de défausse de Dagda d’une carte'),
    ];
    $this->animalClass = HUNTER;
    $this->grade = [null];
  }
}
