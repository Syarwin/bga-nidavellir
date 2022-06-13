<?php
namespace NID\Cards\Valkyries;
use NID\Cards;

class Brynhildr extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = BRYNHILDR;
    $this->name = 'Brynhildr';
    $this->tooltip[] = clienttranslate(
      'Each time you win a bid and that you can choose first during the resolution of a Tavern, move down one notch the Strenght token on this Valkyrie.'
    );
    $this->forces = [0, 3, 6, 10, 16];
  }
}
