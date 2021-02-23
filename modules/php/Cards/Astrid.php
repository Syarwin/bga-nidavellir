<?php
namespace NID\Cards;

class Astrid extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = ASTRID;
    $this->name = 'Astrid';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [null]
    ];
  }
}
