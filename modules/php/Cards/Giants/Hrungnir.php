<?php
namespace NID\Cards\Giants;
use NID\Cards;

class Hrungnir extends GiantCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = HRUNGNIR;
    $this->name = 'Hrungnir';
    $this->tooltip[] = clienttranslate(
      'Capture the next Miner card you recruit to transform immediately each of your coins with +2.'
    );
    $this->tooltip[] = clienttranslate('This effect does not apply to the trading coins (0 or Special 3).');
    $this->tooltip[] = clienttranslate(
      'Apply the transformations in the order of your board, starting with the coin for the Laughing Goblin to the coins in your pouch.'
    );
    $this->tooltip[] = clienttranslate('All the basic rules for coin transformation are to be applied as usual.');
    $this->grade = [null];
    $this->giantClass = MINER;
  }

  public function applyCaptureEffect($player)
  {
    foreach ([GOBLIN_TAVERN, DRAGON_TAVERN, HORSE_TAVERN] as $tavern) {
      $coin = $player->getBid($tavern, true);
      if ($coin !== null && $coin['value'] != 0 && ($coin['value'] != 3 || $coin['type'] != COIN_DISTINCTION)) {
        $player->tradeCoin($coin, 2);
      }
    }

    $coins = $player->getUnbidCoins();
    // Handle Uline transform when > 2 coins in her hand
    $ulineOwnerId = Cards::getUlineOwner();
    if (count($coins) > 2 && $player->getId() == $ulineOwnerId) {
      return 'hrungnir';
    } else {
      foreach ($coins as $coin) {
        if ($coin !== null && $coin['value'] != 0 && ($coin['value'] != 3 || $coin['type'] != COIN_DISTINCTION)) {
          $player->tradeCoin($coin, 2);
        }
      }
    }
  }
}
