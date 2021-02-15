<?php
namespace NID\States;

use Nidavellir;
use NID\Coins;
use NID\Game\Players;
use NID\Game\Globals;
use NID\Game\Notifications;


trait BidsTrait
{
  ///////////////////////////////
  //////////// Bids  ////////////
  ///////////////////////////////
  public function stPlayersBids()
  {
    $this->gamestate->setAllPlayersMultiactive();
    // TODO : handle Uline by setting player inactive
  }


	public function argPlayerBids()
  {
    $data = [ '_private' => [] ];
    foreach(Players::getAll() as $pId => $player){
      $data['_private'][$pId] = $player->getCoinIds();
    }
    return $data;
	}


  public function actPlayerBid($coinId, $tavern)
  {
    $this->checkAction("bid");

    // Check if coins belongs to player
    $coin = Coins::get($coinId);
    $player = Players::getCurrent();
    if($coin['pId'] != $player->getId())
      throw new \BgaUserException(_("This coin is not yours!"));

    // Move coin in corresponding position and notify (useful for replay)
    $player->bid($coinId, $tavern);
    Notifications::bid($player, $coin, $tavern);

  }

  public function actConfirmBids()
  {
    $this->checkAction("bid");

    // Check number of bids
    $player = Players::getCurrent();
    if(count($player->getBids()) != 3){
      throw new \BgaUserException(_("You still have coins to bids!"));
    }

    // set of current index to -1 for resolution.
    Globals::setCurrentPlayerIndex(-1);

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
    $currentTavern = Globals::getTavern();
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
    $currentTavern = Globals::getTavern();
    $coins = Coins::reveal($currentTavern);
    Notifications::revealBids($coins, $currentTavern);

    $pId = Players::getUlineOwner();
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
    //$currentTavern = Globals::getTavern();
    //
    //// Sort players by bids
    //$players = Players::getAll();
    //$bids = [];
    //foreach ($players as $player) {
    //  $bids[$player->getBid($currentTavern)][] = $player;
    //}
    //ksort($bids, SORT_NUMERIC);
    //
    //// Then by gems
    //$order = [];
    //foreach($bids as $bid => $bidders){
    //  //Log::addTie($bidders);
    //  usort($bidders, function($p1, $p2){ return $p1->getGem() - $p2->getGem(); });
    //  foreach($bidders as $player){
    //    array_push($order, $player->getId());
    //  }
    //}
    //
    ////Log::addPlayerOrder($order);
    //$this->setGameStateValue('currentPlayerIndex', -1);
    $this->gamestate->nextState("resolved");
  }


  /*
   * stNextPlayer: make the next player in current turn order active
   */
  public function stNextPlayer()
  {
    //$order = Log::getPlayerOrder();
    $order = self::getBidOrder();
    $index = Globals::incCurrentPlayerIndex(1);

    if($index >= count($order)){
      // If all players already played this turn, go on to reveal next bids (if any left)
      $this->gamestate->nextState("done");
    } else {
      // Otherwise, make player active and go to recruitDwarf state
      $this->gamestate->changeActivePlayer($order[$index]);
      Notifications::recruitStart(Players::getActive(), $index + 1);
      $this->gamestate->nextState("recruit");
    }
  }

  // Provides order of resolution of the bid
  public function getBidOrder()
  {
    $currentTavern = Globals::getTavern();
    // Sort players by bids
    $players = Players::getAll();
    $bids = [];
    foreach ($players as $player) {
      $bids[$player->getBid($currentTavern)][] = $player;
    }
    krsort($bids, SORT_NUMERIC);

    // Then by gems
    $order = [];
    foreach($bids as $bid => $bidders){
      //Log::addTie($bidders);
      usort($bidders, function($p1, $p2){ return $p1->getGem() - $p2->getGem(); });
      foreach($bidders as $player){
        array_push($order, $player->getId());
      }
    }

    return $order;
  }

  // Send ties info
  public function getTies() {
    // TODO: return array of Gems/player to switch
  }

}
?>
