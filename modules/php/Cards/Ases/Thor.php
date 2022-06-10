<?php
namespace NID\Cards\Ases;

class Thor extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = THOR;
    $this->name = 'Thor';
    $this->tooltip = [
      clienttranslate(
        'Au moment où un effet de défausse s’active, vous pouvez le bloquer. Cet effet fonctionne dans le jeu de base sur l’effet de défausse de Bonfur, sur 1 des effets de défausse de Dagda. Dans Thingvellir, cet effet fonctionne sur le Brisingamen et sur Hofud.'
      ),
    ];
    $this->grade = [8];
  }
}
