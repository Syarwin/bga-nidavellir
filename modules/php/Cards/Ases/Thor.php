<?php
namespace NID\Cards\Ases;

class Thor extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = THOR;
    $this->name = 'Thor';
    $this->tooltip[] = clienttranslate('When a discard effect should trigger, you can cancel it.');
    $this->tooltip[] = clienttranslate(
      'In the base game, this effect works on the discard effect of Bonfur, on 1 of the discard effect of Dagda.'
    );
    $this->tooltip[] = clienttranslate('In thingvellir, it works on the Brisingamen and on Hofud');
    $this->grade = [8];
  }

  public function updateScores(&$scores, $player)
  {
    $scores[MYTHOLOGY_SCORE] += 8;
  }
}
