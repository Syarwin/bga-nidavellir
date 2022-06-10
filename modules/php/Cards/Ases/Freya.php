<?php
namespace NID\Cards\Ases;

class Freya extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = FREYA;
    $this->name = 'Freya';
    $this->tooltip = [
      clienttranslate(
        'À la fin de l’Entrée des Nains et des naines, avant le choix des mises, vous pouvez échanger une carte d’une taverne contre une carte d’une autre taverne. Loki a la priorité sur le pouvoir de Freya.'
      ),
    ];
    $this->grade = [15];
  }
}
