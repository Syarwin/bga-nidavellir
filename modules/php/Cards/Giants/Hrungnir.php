<?php
namespace NID\Cards\Giants;

class Hrungnir extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = HRUNGNIR;
    $this->name = 'Hrungnir';
    $this->tooltip[] = clienttranslate('Capture the next Miner card you recruit to transform immediately each of your coins with +2.');
    $this->tooltip[] = clienttranslate('This effect does not applyy to the trading coins (0 or Special 3).');
    $this->tooltip[] = clienttranslate('Apply the transformations in the order of your board, starting with the coin for the Laughing Goblin to the coins in your pouch.');
    $this->tooltip[] = clienttranslate('All the basic rules for coin transformation are to be applied as usual.');
    $this->grade = [null];
    $this->giantClass = MINER;
  }
}
