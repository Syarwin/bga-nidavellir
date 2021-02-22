<?php
namespace NID\Cards;

class Skaa extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = SKAA;
    $this->name = 'Skaa';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [17]
    ];
  }
}
