<?php
namespace NID\Cards;

class Uline extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = ULINE;
    $this->name = 'Uline';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      [9]
    ];
  }

  public function stateAfterRecruit(){
    return 'ulineRecruited';
  }
}
