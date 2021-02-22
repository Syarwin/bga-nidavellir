<?php
namespace NID\States;
use NID\Game\Globals;
use NID\Game\Players;
use NID\Game\Notifications;
use NID\Coins;

trait TradeCoinTrait
{
  public function stTradeCoin()
  {
    // TODO : Uline

    $player = Players::getActive();
    if($player->shouldTrade()){
      $coins = $player->getUnbidCoins();

      $coinMin = $coins[0]['value'] <= $coins[1]['value']? $coins[0] : $coins[1];
      $coinMax = $coins[0]['value'] <= $coins[1]['value']? $coins[1] : $coins[0];
      $newCoin = Coins::trade($coinMax, $coinMin['value'] + $coinMax['value']);

      Notifications::tradeCoin($player, $coinMin, $coinMax, $newCoin);
    }

    $this->gamestate->nextState('next');
  }
}
?>
