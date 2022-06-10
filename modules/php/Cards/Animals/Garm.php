<?php
namespace NID\Cards\Animals;

class Garm extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = GARM;
    $this->name = 'Garm';
    $this->tooltip = [
      clienttranslate('Has 2 ranks.'),
      clienttranslate(
        'Réduit l’effet de défausse de Dagda d’une carteAjoute 9 et 0 points à votre Valeur de Bravoure + 1 point par grade présent dans la colonne, y compris les siens.'
      ),
      clienttranslate(
        'Si vous remportez la Distinction : piochez parmi 6 cartes du paquet de l’Âge 2 (au lieu de 3) et gardez-en 1.'
      ),
    ];
    $this->animalClass = EXPLORER;
    $this->grade = [9, 0];
  }
}
