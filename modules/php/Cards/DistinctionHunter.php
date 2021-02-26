<?php
namespace NID\Cards;

class DistinctionHunter extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = HUNTER;
  }
}
