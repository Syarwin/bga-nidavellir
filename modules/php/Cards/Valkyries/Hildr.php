<?php
namespace NID\Cards\Valkyries;
use NID\Cards;

class Hildr extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = HILDR;
    $this->name = 'Hildr';
    $this->tooltip[] = clienttranslate(
      'During Troop Evaluation, for each Distinction you are awarded, move down one notch the Strength token on this Valkyrie.'
    );
    $this->forces = [0, 8, 16, 0];
  }
}
