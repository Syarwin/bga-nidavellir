<?php
namespace NID\Cards\Ases;

class Frigg extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = FRIGG;
    $this->name = 'Frigg';
    $this->tooltip = [
      clienttranslate(
        'Au moment de choisir une carte Nain/Naine ou Offrande Royale dans la taverne en cours de résolution, placez 1 des cartes de cette taverne sous la pioche pour piocher 3 cartes de l’Âge en cours et en garder 1. Remettez les 2 autres cartes sous la pioche dans l’ordre de votre choix. Vous aurez alors connaissance de 3 cartes de la dernière taverne de cet Âge.'
      ),
    ];
    $this->grade = [12];
  }
}
