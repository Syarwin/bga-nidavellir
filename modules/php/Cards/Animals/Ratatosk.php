<?php
namespace NID\Cards\Animals;

class Ratatosk extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = RATATOSK;
    $this->name = 'Ratatosk';
    $this->tooltip = [
      clienttranslate('Has 1 rank.'),
      clienttranslate('Ajoute 2 points à votre Valeur de Bravoure'),
      clienttranslate(
        'Lors du décompte final, chaque paire de grade de valeur 0 ajoute 1 point à la Valeur de Bravoure des avant la multiplication par le nombre de grades.'
      ),
    ];
    $this->animalClass = MINER;
    $this->grade = [2];
  }
}
