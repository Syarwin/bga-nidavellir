<?php
namespace NID\Cards\Giants;
use NID\Cards;

class Skymir extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = SKYMIR;
    $this->name = 'Skymir';
    $this->grade = [null];
    $this->giantClass = HUNTER;
    $this->tooltip[] = clienttranslate(
      'Capture the next Hunter card you recruit to draw 5 Mythology cards from the Mythology deck and keep 2.'
    );
    $this->tooltip[] = clienttranslate(
      'Put the 3 remaining cards under the Mythology card deck next to the Royal Treasure.'
    );
  }

  public function applyCaptureEffect($player)
  {
    Cards::pickForLocation(5, 'mythology', 'skymir');
    return 'skymir';
  }
}
