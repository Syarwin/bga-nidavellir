<?php
namespace NID\Cards\Giants;

class Hrungnir extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = HRUNGNIR;
    $this->name = 'Hrungnir';
    $this->tooltip = [
      clienttranslate(
        'Capturez la prochaine carte que vous recruterez pour transformer immédiatement chacune de vos pièces à +2 . Cet effet ne s’applique pas sur la pièce d’échange (0 ou 3 spéciale). Réalisez les transformations dans l’ordre de votre plateau en commen- çant par la pièce posée sur l’emplacement du Gobelin Rieur jusqu’aux pièces de votre bourse, d’abord la gauche, puis la droite. Toutes les règles de base des transformations de pièces s’appliquent normalement.'
      ),
    ];
    $this->grade = [null];
    $this->giantClass = MINER;
  }
}
