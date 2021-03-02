<?php
namespace NID\Cards;

class Lokdur extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = LOKDUR;
    $this->name = 'Lokdur';
    $this->heroClass = MINER;
    $this->grade = [
      3
    ];
  }
}
