<?php
namespace NID\Cards\Giants;
use NID\Cards;

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

  public function updateScores(&$scores, $player)
  {
    if ($this->getActivationStatus() == GIANT_CAPTURED) {
      $card = Cards::getInLocation($this->location, $this->state - 1)->first();
      $scores[MYTHOLOGY_SCORE] += 3 * $card->getBV();
    }
  }
}
