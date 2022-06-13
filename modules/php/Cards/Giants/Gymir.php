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
    $this->tooltip[] = clienttranslate('Capture the next Explorer card you recruit to earn 3 times its Bravery Value when counting the final Bravery Value.');
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
