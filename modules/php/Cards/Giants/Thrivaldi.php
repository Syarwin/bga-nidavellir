<?php
namespace NID\Cards\Giants;

class Thrivaldi extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = THRIVALDI;
    $this->name = 'Thrivaldi';
    $this->tooltip = [
      clienttranslate(
        'Capturez la prochaine carte que vous recruterez pour recruter immédiatement une carte Héros/Héroïne. Cette carte ne compte pas dans votre nombre de cartes Héros/Héroïne possédées dépendantes de vos lignes (cf encarts Recruter une carte Héros/Héroïne et Attention ! p. 9 de la règle du jeu de base).'
      ),
    ];
    $this->grade = [null];
    $this->giantClass = BLACKSMITH;
  }
}
