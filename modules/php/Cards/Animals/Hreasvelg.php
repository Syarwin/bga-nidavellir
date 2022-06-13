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
      clienttranslate('Has 1 Blacksmith rank.'),
      clienttranslate('Take the special card Gullinbursti and place it in the column of your choice.'),
    ];
    $this->animalClass = BLACKSMITH;
    $this->grade = [null];
  }
}
