<?php
namespace NID\Cards;

class Tarah extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = TARAH;
    $this->name = 'Tarah';
    $this->heroClass = WARRIOR;
    $this->grade = [
      14,
    ];
  }
}
