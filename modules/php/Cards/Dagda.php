<?php
namespace NID\Cards;

class Dagda extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DAGDA;
    $this->name = 'Dagda';
    $this->heroClass = HUNTER;
    $this->grade = [
      [null],
      [null],
      [null],
    ];
  }

  // TODO : destruct and check recruit
}
