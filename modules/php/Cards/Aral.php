<?php
namespace NID\Cards;

class Aral extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = ARAL;
    $this->name = 'Aral';
    $this->heroClass = HUNTER;
    $this->grade = [
      null,
      null,
    ];
  }
}
