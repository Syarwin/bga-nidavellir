<?php
namespace NID\Cards\Ases;

class Odin extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = ODIN;
    $this->name = 'Odin';
    $this->tooltip = [
      clienttranslate(
        'À la fin de votre tour vous pouvez remettre une de vos cartes Héros/Héroïne Neutre dans la réserve disponible et recruter une carte Héros/Héroïne Neutre disponible en remplacement. Appliquez l’éventuel effet de cette carte.'
      ),
    ];
    $this->grade = [0];
  }
}
