<?php
namespace NID\Cards\Ases;

class Frigg extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = FRIGG;
    $this->name = 'Frigg';
    $this->tooltip[] = clienttranslate(
      'When you choose a Dwarf card or a Royal Offering in the Tavern being resolved, place 1 of the cards below the deck to draw 3 cards from the current Age deck and keep 1.'
    );
    $this->tooltip[] = clienttranslate('Put the remaining cards under the deck in the order of your choice');
    $this->tooltip[] = clienttranslate('You will then know 3 cards of the last Tavern of this Age.');
    $this->grade = [12];
  }
}
