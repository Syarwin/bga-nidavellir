<?php
namespace NID\Cards\Ases;

class Freya extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = FREYA;
    $this->name = 'Freya';
    $this->tooltip[] = clienttranslate(
      'At the end of the Entrance of the Dwarves and before the biddings, you may swap one card in a tavern with a card in another tavern.'
    );
    $this->tooltip[] = clienttranslate('Loki has priority on the ability of Freya');
    $this->grade = [15];
  }
}
