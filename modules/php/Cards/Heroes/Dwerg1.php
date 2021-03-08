<?php
namespace NID\Cards\Heroes;

class Dwerg1 extends DwergCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = DWERG1;
  }
}
