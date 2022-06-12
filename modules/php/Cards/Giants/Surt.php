<?php
namespace NID\Cards\Giants;

class Surt extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = SURT;
    $this->name = 'Surt';
    $this->tooltip = [
      clienttranslate(
        'Capturez la prochaine carte que vous recruterez. Cela vous donne le droit, en fin de partie, d’ajouter à votre Valeur finale de Bravoure la valeur de votre plus forte pièce.'
      ),
    ];
    $this->grade = [null];
    $this->giantClass = WARRIOR;
  }
}
