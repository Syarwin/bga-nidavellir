<?php
namespace NID\Cards\Giants;

class Thrivaldi extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = THRIVALDI;
    $this->name = 'Thrivaldi';
    $this->tooltip[] = clienttranslate('Capture the next Blacksmith card you recruit to immediately recruit a Hero card.');
    $this->tooltip[] = clienttranslate('This card does not count in the number of Hero cards you own according to the number of completed lines you have.');
    $this->grade = [null];
    $this->giantClass = BLACKSMITH;
  }

  public function applyCaptureEffect($player)
  {
    return 'hero';
  }
}
