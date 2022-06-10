<?php
namespace NID\States;

use Nidavellir;
use NID\Coins;
use NID\Cards;
use NID\Game\Players;
use NID\Game\Globals;
use NID\Game\Notifications;
use NID\Game\Log;
use NID\Game\Stats;



trait EnlistMercenaryTrait
{
  /*
   * stStartMercenaryEnlistment: compute the order of players according to number of mercenaries and gem
   */
  public function stStartMercenaryEnlistment()
  {
    if(!Globals::isThingvellir()){
      $this->gamestate->nextState('skip');
      return;
    }

    // Sort players by mercenaries
    $players = Players::getAll();
    $amounts = [];
    foreach ($players as $player) {
      $amounts[$player->countUnplacedMercenaries()][] = $player;
    }
    krsort($amounts, SORT_NUMERIC);

    // Then by gems
    $order = [];
    foreach($amounts as $bid => &$bidders){
      usort($bidders, function($p1, $p2){ return $p2->getGem() - $p1->getGem(); });
      foreach($bidders as $player){
        array_push($order, $player->getId());
      }
    }

    // Add first player back at the end in case he wants to be last
    $order[] = $order[0];
    Log::storeEnlistOrder($order);
    Notifications::newEnlistOrder($order);

    // reset of current index for resolution.
    Globals::resetCurrentPlayerIndex();

    $this->gamestate->nextState("resolved");
  }


  /*
   * stNextPlayer: make the next player in current turn order active
   */
  public function stNextPlayerEnlist()
  {
    $index = Globals::incCurrentPlayerIndex();
    $order = Log::getEnlistOrder();
    $pId = $order[$index] ?? null;

    if($index == 0){
      $player = Players::get($pId);
      if($player->countUnplacedMercenaries() > 0){
        $this->gamestate->changeActivePlayer($pId);
        self::giveExtraTime($pId);
        $this->gamestate->nextState("chooseOrder");
      } else {
        $this->gamestate->nextState("end");
      }
      return;
    }


    if($index >= count($order)){
      $this->gamestate->nextState("end");
    } else {
      $player = Players::get($order[$index]);
      if($player->countUnplacedMercenaries() > 0){
        $this->gamestate->changeActivePlayer($pId);
        self::giveExtraTime($pId);
        $this->gamestate->nextState("enlist");
      }
      else
        $this->stNextPlayerEnlist();
    }
  }


  public function actChooseOrder($position)
  {
    $this->gamestate->nextState($position == 0? 'first' : 'last');
  }


  public function stEnlistMercenary()
  {
    if(empty($this->argEnlistMercenary()['cards'])){
      $this->gamestate->nextState('next');
    }
  }

  public function argEnlistMercenary()
  {
    $player = Players::getActive();
    return [
      'cards' => array_map(function($mercenary){ return $mercenary->getId(); }, $player->getUnplacedMercenaries()),
    ];
  }


  public function actEnlistMercenary($cardId, $column)
  {
    $this->checkAction("enlist");

    $cards = $this->argEnlistMercenary()['cards'];
    if(!in_array($cardId, $cards))
      throw new \BgaUserException(_("You cannot enlist this mercenary"));

    $player = Players::getCurrent();
    $card = Cards::get($cardId);
    // TODO : add a check for the column,
    Cards::changeColumn($card, $player, $column, true);
    Players::updateScores();
    $this->nextStateAfterRecruit($card, $player, true);
  }
}
?>
