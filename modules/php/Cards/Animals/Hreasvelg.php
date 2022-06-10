<?php
namespace NID\Cards\Animals;

class Hreasvelg extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = HREASVELG;
    $this->name = 'Hreasvelg';
    $this->tooltip = [
      clienttranslate('Has 1 rank.'),
      clienttranslate('Prenez la carte spéciale Gullinbursti et placez-la dans la colonne de votre choix.'),
    ];
    $this->animalClass = BLACKSMITH;
    $this->grade = [null];
  }
}
