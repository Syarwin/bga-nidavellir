<?php
namespace NID\Cards;

class Zoral extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = ZORAL;
    $this->name = 'Zoral';
    $this->heroClass = MINER;
    $this->grade = [
      1,
      0,
      0,
    ];
  }
}
