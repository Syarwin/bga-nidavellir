<?php
namespace NID\Cards;

/*
 * DwarfCard: all utility functions concerning a card : dwarf/improvements or hero
 */

class DwarfCard extends AbstractCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->ranks = 1; // TODO : handle ROYAL_OFFER ?
    $this->bv = 0; // TODO
  }
}
