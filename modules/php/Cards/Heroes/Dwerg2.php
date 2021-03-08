<?php
namespace NID\Cards\Heroes;

class Dwerg2 extends DwergCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DWERG2;
  }
}
