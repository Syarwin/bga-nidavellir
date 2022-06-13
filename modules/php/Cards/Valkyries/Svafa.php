<?php
namespace NID\Cards\Valkyries;
use NID\Cards;

class Svafa extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = SVAFA;
    $this->name = 'Svafa';
    $this->tooltip = [
      clienttranslate(
        'Chaque fois que vous faites une plus-value (cf Glossaire, p.12) lors d’un échange de pièces ou lors d’une transformation de pièces, descendez d’une encoche par point de plus-value le jeton Force sur cette Valkyrie.'
      ),
    ];
    $this->forces = [0, 4, 8, 16];
  }
}
