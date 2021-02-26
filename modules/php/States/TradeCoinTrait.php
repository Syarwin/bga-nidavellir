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



  public function argTransformCoin()
  {
    $player = Players::getActive();

    $coins = $player->getCoins();
    $upgradableCoins = [];
    foreach($coins as $coin){
      if($coin['value'] != 0 && ($coin['value'] != 3 || $coin['type'] != COIN_DISTINCTION))
        $upgradableCoins[] = $coin['id'];
    }

    return [
      'value' => Globals::getTransformValue(),
      'coins' => $upgradableCoins,
    ];
  }

  public function actTransformCoin($coinId)
  {
    $this->checkAction("transform");

    // Check if coins belongs to player
    $coin = Coins::get($coinId);
    $player = Players::getCurrent();
    if($coin['pId'] != $player->getId())
      throw new \BgaUserException(_("This coin is not yours!"));

    $newCoin = Coins::trade($coin, $coin['value'] + Globals::getTransformValue(), true);
    Notifications::transformCoin($player, $coin, $newCoin);


    if($player->canRecruitHero())
      $this->gamestate->nextState('hero');
    else
      $this->nextStateFromSource('transformDone');
  }
}
?>
