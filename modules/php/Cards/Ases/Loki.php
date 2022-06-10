<?php
namespace NID\Cards\Ases;

class Loki extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = LOKI;
    $this->name = 'Loki';
    $this->tooltip = [
      clienttranslate(
        'À la fin de l’entrée des Nains et des Naines et avant le choix des mises, vous pouvez placer le jeton Pouvoir de Loki sur 1 carte d’une taverne de votre choix pour la réserver. Ainsi, personne ne pourra prendre cette carte, à part vous. Si finalement, vous décidez de recruter une autre carte pré­sente dans la même taverne que celle que vous avez marqué, défaussez le jeton Pouvoir de Loki à la fin de votre tour.'
      ),
    ];
    $this->grade = [8];
  }
}
