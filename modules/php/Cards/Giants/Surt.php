<?php
namespace NID\Cards\Giants;

class Surt extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = SURT;
    $this->name = 'Surt';
    $this->grade = [null];
    $this->giantClass = WARRIOR;
    $this->tooltip[] = clienttranslate('Capture the next Warrior card you recruit.');
    $this->tooltip[] = clienttranslate('This will allow you, at the end of the game, to add to your final Bravery Value the value of your highest coin.');
  }

  public function updateScores(&$scores, $player)
  {
    if ($this->getActivationStatus() == GIANT_CAPTURED) {
      $scores[MYTHOLOGY_SCORE] += $player->getMaxCoin();
    }
  }
}
