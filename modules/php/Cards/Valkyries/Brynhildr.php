<?php
namespace NID\Cards\Valkyries;
use NID\Cards;

class Brynhildr extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = BRYNHILDR;
    $this->name = 'Brynhildr';
    $this->tooltip = [
      clienttranslate(
        'Chaque fois que vous remportez la mise et que vous avez l’opportunité de choisir en premier·ère lors d’une résolution de taverne, descendez d’une encoche le jeton Force sur cette Valkyrie.'
      ),
    ];
    $this->forces = [0, 3, 6, 10, 16];
  }
}
