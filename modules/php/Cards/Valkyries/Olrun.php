<?php
namespace NID\Cards\Valkyries;
use NID\Cards;

class Olrun extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = OLRUN;
    $this->name = 'Ölrun';
    $this->tooltip = [
      clienttranslate(
        'Dès que vous placez Ölrun dans votre zone de Commandement, placez 1 jeton de Classe dessus. Chaque fois que vous allez recruter une carte possédant au moins un grade de la couleur de la classe choisie dans votre armée : descendez d’une encoche le jeton Force sur cette Valkyrie.'
      ),
    ];
    $this->forces = [0, 3, 6, 10, 16];
  }
}
