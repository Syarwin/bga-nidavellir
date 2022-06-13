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
      clienttranslate('Has 1 Warrior rank.'),
      clienttranslate(
        'Adds 5 points to your Warrior Bravery Values + 2 points per rank present in the Warrior column, including his own.'
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
