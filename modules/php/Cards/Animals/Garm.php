<?php
namespace NID\Cards\Animals;

class Garm extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = GARM;
    $this->name = 'Garm';
    $this->tooltip = [
      clienttranslate('Has 2 Explorer ranks.'),
      clienttranslate(
        'Adds 9 and 0 points to your Explorer Bravey Value + 1 point per rank present in the Explorer column, including his own.'
      ),
      clienttranslate(
        'If you win the Explorer Distinction: draw 6 cards from the Age 2 deck (instead of 3) and keep 1.'
      ),
    ];
    $this->animalClass = EXPLORER;
    $this->grade = [9, 0];
  }

  public function updateBraveryValues(&$values, $player)
  {
    $values[EXPLORER] += $this->getBV() + $player->getRanks()[EXPLORER] * 1;
  }
}
