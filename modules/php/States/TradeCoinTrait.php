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
    $player = Players::getActive();
    if($player->shouldTrade()){
      $coins = $player->getUnbidCoins();

      // Handle Uline transform when > 2 coins in her hand
      $ulineOwnerId = Players::getUlineOwnerId();
      if(count($coins) > 2 && $player->getId() == $ulineOwnerId){
        $this->gamestate->changeActivePlayer($ulineOwnerId);
        $this->gamestate->nextState("uline");
        return;
      }

      $this->tradeCoin($player, $coins[0], $coins[1]);
    }

    $this->gamestate->nextState('next');
  }

  public function tradeCoin($player, $coin1, $coin2)
  {
    $coinMin = $coin1['value'] <= $coin2['value']? $coin1 : $coin2;
    $coinMax = $coin1['value'] <= $coin2['value']? $coin2 : $coin1;
    $newCoin = Coins::trade($coinMax, $coinMin['value'] + $coinMax['value']);

    Notifications::tradeCoin($player, $coinMin, $coinMax, $newCoin);
    Players::updateScores();
  }


  public function argUlineTradeCoin()
  {
    return $this->argUlineBid(); // Actually the same arg as Uline bid !
  }


  public function actUlineTrade($coinIds)
  {
    $coins = Coins::get($coinIds)->toArray();
    $this->tradeCoin(Players::getActive(), $coins[0], $coins[1]);
    $this->gamestate->nextState('next');
  }



  /**************************
  ***** Transform coins *****
  **************************/
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
    Players::updateScores();

    if($player->canRecruitHero())
      $this->gamestate->nextState('hero');
    else
      $this->nextStateFromSource('transformDone');
  }
}
?>
