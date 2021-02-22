<?php
namespace NID\Cards;

class Aegur extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = AEGUR;
    $this->name = 'Aëgur';
    $this->heroClass = BLACKSMITH;
    $this->grade = [
      [null],
      [null],
    ];
  }
}
