<?php
namespace NID\Cards\Valkyries;
use NID\Cards;

class Sigrdrifa extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = SIGRDRIFA;
    $this->name = 'Sigrdrifa';
    $this->tooltip[] = clienttranslate(
      'Each time you recruit a Hero card, regardless of how, move down one notch the Strength token on this Valkyrie.'
    );
    $this->tooltip[] = clienttranslate('Note: the ability of Odin does not trigger Sigrdrifa');
    $this->forces = [0, 0, 8, 16];
  }
}
