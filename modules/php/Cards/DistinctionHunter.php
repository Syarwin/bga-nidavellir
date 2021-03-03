<?php
namespace NID\Cards;
use NID\Coins;
use NID\Game\Notifications;

class DistinctionHunter extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = HUNTER;
  }

  public function applyEffect($player){
    // Get the old coin
    $coins = $player->getCoins()->filter(function($coin){ return $coin['value'] == 0; });
    $oldCoin = $coins[0];

    $newCoin = Coins::hunterDistinction($oldCoin);
    Notifications::distinctionCoin($player, $oldCoin, $newCoin);
  }
}
