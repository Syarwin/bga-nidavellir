<?php
namespace NID\Cards\Giants;

class Gymir extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = GYMIR;
    $this->name = 'Gymir';
    $this->tooltip = [
      clienttranslate(
        'Capturez la prochaine carte que vous recruterez pour gagner le triple de sa Valeur de Bravoure lors du décompte final.'
      ),
    ];
    $this->grade = [null];
    $this->giantClass = EXPLORER;
  }
}
