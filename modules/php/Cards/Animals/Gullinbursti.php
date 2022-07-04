<?php
namespace NID\Cards\Animals;

class Gullinbursti extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = GULLINBURSTI;
    $this->name = 'Gullinbursti';
    $this->tooltip = [];
    $this->animalClass = NEUTRAL;
    $this->grade = [null];
  }

  public function isDiscardable()
  {
    return true;
  }

  public function updateRanks(&$ranks, $uselessExceptThrud)
  {
    $ranks[$this->zone]++;
  }

  public function updateBraveryValues(&$values, $player)
  {
  }
}
