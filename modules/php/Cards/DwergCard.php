<?php
namespace NID\Cards;

/*
 * DwergCard: same behavior for all dwerg brothers
 */

abstract class DwergCard extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->name = 'Dwerg';
    $this->heroClass = NEUTRAL;
    $this->grade = [
      null,
    ];
  }
}
