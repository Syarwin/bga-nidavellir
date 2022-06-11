<?php
namespace NID\Cards\Animals;

class Nidhogg extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = NIDHOGG;
    $this->name = 'Nidhogg';
    $this->tooltip = [
      clienttranslate('Has 1 rank.'),
      clienttranslate(
        'Ajoute 5 points à votre Valeur de Bravoure + 2 points par grade présent dans la colonne , y compris le sien.'
      ),
    ];
    $this->animalClass = WARRIOR;
    $this->grade = [5];
  }

  public function updateBraveryValues(&$values, $player)
  {
    $values[WARRIOR] += $this->getBV() + $player->getRanks()[WARRIOR] * 2;
  }
}
