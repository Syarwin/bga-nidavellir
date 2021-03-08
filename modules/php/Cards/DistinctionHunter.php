<?php
namespace NID\Cards;
use NID\Coins;
use NID\Game\Notifications;

class DistinctionHunter extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = HUNTER;
    $this->name = clienttranslate("Hunting Master");
    $this->tooltip = [
      clienttranslate("Trade your coin of value 0 immediately with the special coin of value 3."),
      clienttranslate("This special coins keeps its trading properties and cannot be transformed."),
    ];
  }

  public function applyEffect($player){
    // Get the old coin
    $coins = $player->getCoins()->filter(function($coin){ return $coin['value'] == 0; });
    $oldCoin = $coins[0];

    $newCoin = Coins::hunterDistinction($oldCoin);
    Notifications::distinctionCoin($player, $oldCoin, $newCoin);
  }
}
