<?php
namespace NID\Cards\Animals;

/*
 * AnimalCard: all utility functions concerning an animal
 */

class AnimalCard extends \NID\Cards\AbstractCard
{
  protected $animalClass = null;
  public function __construct($row)
  {
    parent::__construct($row);
    $this->class = ANIMAL;
  }

  public function isDiscardable()
  {
    return false;
  }

  public function getUiData()
  {
    $data = parent::getUiData();
    $data['animalClass'] = $this->animalClass;
    return $data;
  }

  public function getRecruitementZone()
  {
    return $this->animalClass;
  }

  public function hasRankOfClass($class){
    return $this->animalClass == $class;
  }


  public function updateRanks(&$ranks, $uselessExceptThrud)
  {
    $ranks[$this->animalClass] += $this->getRanks();
  }

  public function updateBraveryValues(&$values, $player)
  {
    $values[$this->animalClass] += $this->getBV();
  }

  public function getNotifString()
  {
    return [
      'log' => clienttranslate('a mythical animal (${animal_name})'),
      'args' => [
        'animal_name' => $this->name,
      ],
    ];
  }
}
