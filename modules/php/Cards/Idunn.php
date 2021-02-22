<?php
namespace NID\Cards;

class Idunn extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = IDUNN;
    $this->name = 'Idunn';
    $this->heroClass = EXPLORER;
    $this->grade = [
      [7]
    ];
  }

  // TODO : score blue more points per card
}
