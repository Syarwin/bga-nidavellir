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

trait BidsTrait
{
  ///////////////////////////////
  //////////// Bids  ////////////
  ///////////////////////////////
  public function stPlayersBids()
  {
    $ids = Players::getAll()->getIds();
    $ulineOwner = Cards::getUlineOwner();
    $ids = array_diff($ids, [$ulineOwner]);
    $this->gamestate->setPlayersMultiactive($ids, '', true);

    foreach ($ids as $pId) {
      self::giveExtraTime($pId);
    }
  }

  public function argPlayerBids()
  {
    $data = ['_private' => []];
    $ulineOwnerId = Cards::getUlineOwner();
    foreach (Players::getAll() as $pId => $player) {
      if ($pId != $ulineOwnerId) {
        $data['_private'][$pId] = $player->getCoinIds();
      }
    }
    return $data;
  }

  public function actPlayerBid($coinId, $tavern)
  {
    $this->checkAction('bid');

    // Check if coins belongs to player
    $coin = Coins::get($coinId);
    $player = Players::getCurrent();
    if ($coin['pId'] != $player->getId()) {
      throw new \BgaUserException(_('This coin is not yours!'));
    }

    // Move coin in corresponding position and notify (useful for replay)
    $player->bid($coinId, $tavern);
    Notifications::bid($player, $coin, $tavern);
  }

  public function actAutobid()
  {
    $player = Players::getCurrent();
    $coins = $player->getCoinIds();
    for ($i = 0; $i < 3; $i++) {
      $player->bid($coins[$i], $i);
      $coin = Coins::get($coins[$i]);
      Notifications::bid($player, $coin, $i);
    }

    $this->actConfirmBids();
  }

  public function actConfirmBids()
  {
    $this->checkAction('bid');

    // Check number of bids
    $player = Players::getCurrent();
    if (count($player->getBids()) != 3) {
      throw new \BgaUserException(_('You still have coins to bids!'));
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
    $currentTavern = Globals::incTavern();
    $nextState = in_array($currentTavern, [GOBLIN_TAVERN, DRAGON_TAVERN, HORSE_TAVERN]) ? 'reveal' : 'finished';
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

    $pId = Cards::getUlineOwner();
    if (is_null($pId)) {
      $this->gamestate->nextState('revealed');
    } else {
      $this->gamestate->changeActivePlayer($pId);
      $this->gamestate->nextState('uline');
    }
  }

  /*
   * argUlineBid: Uline owner can choose whatever coin left in his hand for current bid
   */
  public function argUlineBid()
  {
    $player = Players::getActive();
    return [
      'coins' => array_map(function ($coin) {
        return $coin['id'];
      }, $player->getUnbidCoins()),
    ];
  }

  public function actUlineBid($coinId)
  {
    $this->checkAction('bid');

    $coins = $this->argUlineBid()['coins'];
    if (!in_array($coinId, $coins)) {
      throw new \BgaUserException(_('You cannot bid this coin!'));
    }

    $tavern = Globals::getTavern();
    $coin = Coins::get($coinId);
    $player = Players::getActive();

    // Bid and reveal
    Coins::putOnTavern($coin, $tavern);
    Notifications::revealUlineBid($coin, $tavern);

    $this->gamestate->nextState('revealed');
  }

  /*
   * stResolveBids: compute the order of players according to bids and gem
   *                  and store ties to make the gems exchanges later on
   */
  public function stResolveBids()
  {
    // Compute and store order and ties
    $currentTavern = Globals::getTavern();
    // Sort players by bids
    $players = Players::getAll();
    $bids = [];
    foreach ($players as $player) {
      if ($player->getBid($currentTavern) !== null) {
        $bids[$player->getBid($currentTavern)][] = $player;
      }
    }
    krsort($bids, SORT_NUMERIC);

    // Then by gems
    $order = [];
    $ties = [];
    foreach ($bids as $bid => &$bidders) {
      //Log::addTie($bidders);
      usort($bidders, function ($p1, $p2) {
        return $p2->getGem() - $p1->getGem();
      });
      foreach ($bidders as $player) {
        array_push($order, $player->getId());
      }

      // TIES
      if (count($bidders) == 1) {
        continue;
      } // No tie

      $trades = [];
      $fbidders = array_values(
        array_filter($bidders, function ($p) {
          return $p->getGem() != 6;
        })
      );
      for ($i = 0; $i < count($fbidders) / 2; $i++) {
        $p1 = $fbidders[$i];
        $p2 = $fbidders[count($fbidders) - 1 - $i];
        $trades[] = [$p1->getId(), $p1->getGem(), $p2->getId(), $p2->getGem()];
      }
      $ties[$bid] = [
        'bidders' => array_map(function ($player) {
          return $player->getName();
        }, $fbidders),
        'trades' => $trades,
      ];
      Stats::addTie($bidders);
    }

    Log::storeOrder($order, $ties);
    Notifications::newOrder($order);

    // reset of current index for resolution.
    Globals::resetCurrentPlayerIndex();

    $this->gamestate->nextState('resolved');
  }

  /*
   * stNextPlayer: make the next player in current turn order active
   */
  public function stNextPlayer()
  {
    $index = Globals::getCurrentPlayerIndex();
    $tavern = Globals::getTavern();
    // THINGVELLIR : special rule for 2 players
    if ($index == 0 && Players::count() == 2 && Cards::getInTavern($tavern)->count() == 3) {
      $this->gamestate->nextState('discardTavern');
      return;
    }

    $order = Log::getTurnOrder();
    $index = Globals::incCurrentPlayerIndex();

    if ($index >= count($order)) {
      // If all players already played this turn, trade gems
      $ties = Log::getTies();
      foreach ($ties as $bid => $info) {
        if (!empty($info['trades'])) {
          Players::tradeGems($info['trades']);
          Notifications::tradeGems($bid, $info['bidders'], $info['trades']);
        }
      }

      // Clear tavern in 2p mode
      $tavern = Globals::getTavern();
      if (Cards::getInTavern($tavern)->count() != 0) {
        Cards::clearTavern($tavern);
        Notifications::clearTavern($tavern);
      }

      // And go on to reveal next bids (if any left)
      $this->gamestate->nextState('done');
    } else {
      // Otherwise, make player active and go to recruitDwarf state
      $this->gamestate->changeActivePlayer($order[$index]);
      self::giveExtraTime($order[$index]);
      Notifications::recruitStart(Players::getActive(), $index + 1);
      if ($index == 0) {
        Cards::increaseForce(BRYNHILDR, Players::getActive()); // Valkyrie
      }
      $this->gamestate->nextState('recruit');
    }
  }

  /*
   * THINGVELLIR 2 player special rule for camp
   */

  public function argDiscardTavernCard()
  {
    $taverns = [
      GOBLIN_TAVERN => clienttranslate('Laughing Goblin Tavern'),
      DRAGON_TAVERN => clienttranslate('Dancing Dragon Tavern'),
      HORSE_TAVERN => clienttranslate('Shining Horse Tavern'),
    ];

    $tavern = Globals::getTavern();
    return [
      'i18n' => ['tavern_name'],
      'cards' => Cards::getInTavern($tavern)->getIds(),
      'tavern' => $tavern,
      'tavern_name' => $taverns[$tavern],
    ];
  }

  public function actDiscardTavernCard($cardId)
  {
    $player = Players::getCurrent();
    $card = Cards::get($cardId);
    Cards::discard($cardId);
    Notifications::discardCards($player, $card);
    $this->gamestate->nextState('next');
  }
}
?>
