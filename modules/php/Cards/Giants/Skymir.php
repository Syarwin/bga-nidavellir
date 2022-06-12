<?php
namespace NID\Cards\Giants;

class Skymir extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = SKYMIR;
    $this->name = 'Skymir';
    $this->tooltip = [
      clienttranslate(
        'Capturez la prochaine carte que vous recruterez pour piocher 5 cartes Mythologie du paquet posé à côté du Trésor Royal et gardez-en 2. Remettez les 3 cartes restantes sous le paquet de cartes Mythologie à côté du Trésor Royal.'
      ),
    ];
    $this->grade = [null];
    $this->giantClass = HUNTER;
  }
}
