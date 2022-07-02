<?php
namespace NID\Cards\Ases;

class Loki extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = LOKI;
    $this->name = 'Loki';
    $this->tooltip[] = clienttranslate(
      'At the end of the Entrance of the Dwarves and before the biddings, you can place the Power Token of Loki on 1 card of your choice to reserve that card.'
    );
    $this->tooltip[] = clienttranslate('No one but you can recruit this card.');
    $this->tooltip[] = clienttranslate(
      'In the end, if you recruited another card in the Tavern than the chosen card, discard the Power token of Loki at the end of your turn.'
    );
    $this->grade = [8];
  }

  public function updateScores(&$scores, $player)
  {
    $scores[MYTHOLOGY_SCORE] += 8;
  }
}
