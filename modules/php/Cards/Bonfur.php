<?php
namespace NID\Cards;

class Bonfur extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = BONFUR;
    $this->name = 'Bonfur';
    $this->heroClass = BLACKSMITH;
    $this->grade = [
      [null],
      [null],
      [null],
    ];
  }

  // TODO : destruct + check recruit
}
