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
    $this->tooltip = [
      clienttranslate(
        'Chaque fois que vous recrutez une carte Héros/Héroïne, peu importe la façon, descendez d’une encoche le jeton Force sur cette Valkyrie.'
      ),
    ];
    $this->forces = [0, 0, 8, 16];
  }
}
