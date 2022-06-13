<?php
namespace NID\Cards\Valkyries;
use NID\Cards;

class Hildr extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = HILDR;
    $this->name = 'Hildr';
    $this->tooltip = [
      clienttranslate(
        'Lors de l’Évaluation des troupes, pour chaque Distinction que vous remportez, descendez d’une encoche le jeton Force sur cette Valkyrie.'
      ),
    ];
    $this->forces = [0, 8, 16, 0];
  }
}
