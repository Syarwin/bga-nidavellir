<?php
namespace NID\Cards;

class Kraal extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = KRAAL;
    $this->name = 'Kraal';
    $this->heroClass = WARRIOR;
    $this->grade = [
      [7],
      [0],
    ];
  }
}
