<?php
namespace NID\Cards;

class DistinctionExplorer extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = EXPLORER;
  }

}
