<?php
namespace NID\States;

use Nidavellir;
use NID\Game\Players;

trait BidsTrait
{
  ///////////////////////////////
  //////////// Bids  ////////////
  ///////////////////////////////
  public function stPlayersBids()
  {
    $this->gamestate->setAllPlayersMultiactive();
    // TODO : handle Uline by setting player inactive

    $this->setGameStateValue('currentTavern', GOBLIN_TAVERN);
  }


	public function argPlayerBids()
  {
    $data = [ '_private' => [] ];
    foreach(Players::getAll() as $pId => $player){
      $data['_private'][$pId] = $player->getCoinIds();
    }
    return $data;
	}


  public function actConfirmBids($bids)
  {
    $this->checkAction("bid");

    // Check number of bids
    if(count($bids) != 3){
      throw new \BgaUserException(_("You still have coins to bids!"));
    }

    // Check if coins belongs to player
    $player = Players::getCurrent();
    $coins = $player->getCoinIds();
    foreach($bids as $coinId){
      if(!in_array($coinId, $coins))
        throw new \BgaUserException(_("This coin is not yours!"));
    }

    // Move coin in corresponding position
    foreach($bids as $tavern => $coinId){
      $player->bid($tavern, $coinId);
    }

    $this->gamestate->setPlayerNonMultiactive($player->getId(), 'done');
  }


  public function actChangeBids()
  {
    $this->gamestate->setPlayersMultiactive([self::getCurrentPlayerId()], '');
  }



  /////////////////////////////
  /////// Bids are over  //////
  /////////////////////////////
  public function stNextResolution()
  {
    // Are we done with the three tavers ?
    $currentTavern = $this->getGameStateValue('currentTavern');
    $nextState = in_array($currentTavern, [GOBLIN_TAVERN, DRAGON_TAVERN, HORSE_TAVERN])? "reveal" : "finished";
    $this->gamestate->nextState($nextState);
  }


  //////////////////////////////////
  /////// Reveal and resolve  //////
  //////////////////////////////////
  /*
   * stRevealBids : notifiy player to reveal bids of current tavern and either
   *   - go to bids resolution if Uline is not owned (all bids are revealed)
   *   - let Uline owner places its bid first before resolution
   */
  public function stRevealBids()
  {
    die("Coucou");

    NotificationManager::revealBids();
    $pId = PlayerManager::getUlineOwner();
    if(is_null($pId)){
      $this->gamestate->nextState("revealed");
    } else {
      $this->gamestate->changeActivePlayer($pId);
      $this->gamestate->nextState("uline");
    }
  }


  /*
   * argUlineBid: Uline owner can choose whatever coin left in his hand for current bid
   */
  public function argUlineBid()
  {
    return []; // TODO
  }

  // TODO : action for uline bid


  /*
   * stResolveBids: compute the order of players according to bids and gem
   *                  and store ties to make the gems exchanges later on
   */
  public function stResolveBids()
  {
    $currentTavern = $this->getGameStateValue('currentTavern');

    // Sort players by bids
    $players = PlayerManager::getAll();
    $bids = [];
    foreach ($players as $player) {
      $bids[$player->getBid($currentTavern)][] = $player;
    }
    ksort($bids, SORT_NUMERIC);

    // Then by gems
    $order = [];
    foreach($bids as $bid => $bidders){
      Log::addTie($bidders);
      usort($bidders, function($p1, $p2){ return $p1->getGem() - $p2->getGem(); });
      foreach($bidders as $player){
        array_push($order, $player->getId());
      }
    }

    Log::addPlayerOrder($order);
    $this->setGameStateValue('currentPlayerIndex', -1);
    $this->gamestate->nextState("resolved");
  }


  /*
   * stNextPlayer: make the next player in current turn order active
   */
  public function stNextPlayer()
  {
    $order = Log::getPlayerOrder();
    $index = $this->gamestate->incGameStateValue('currentPlayerIndex', 1);

    if($index >= count($order)){
      // If all players already played this turn, go on to reveal next bids (if any left)
      $this->gamestate->nextState("done");
    } else {
      // Otherwise, make player active and go to recruitDwarf state
      $this->gamestate->changeActivePlayer($order[$index]);
      $this->gamestate->nextState("recruit");
    }
  }

}
?>
