<?php
namespace NID\Cards\Animals;

class Ratatosk extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = RATATOSK;
    $this->name = 'Ratatosk';
    $this->tooltip = [
      clienttranslate('Has 1 Miner rank.'),
      clienttranslate('Adds 2 points to your Miner Bravery Value.'),
      clienttranslate(
        'When determining the Bravery Value for each class, each pair of 0 point rank adds 1 point to the Miner Bravery Value before the multiplication by the amount of ranks.'
      ),
    ];
    $this->animalClass = MINER;
    $this->grade = [2];
  }
}
