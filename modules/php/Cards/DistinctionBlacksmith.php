<?php
namespace NID\Cards;

class DistinctionBlacksmith extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = BLACKSMITH;
  }
}
