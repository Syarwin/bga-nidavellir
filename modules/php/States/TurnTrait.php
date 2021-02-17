<?php
namespace NID\States;
use NID\Cards;
use NID\Game\Globals;
use NID\Game\Notifications;
use NID\Game\Players;

trait TurnTrait
{
  public function stStartOfTurn()
  {
    Globals::startNewTurn();

    // Draw cards and notify them
    $cards = Cards::startNewTurn();
    Notifications::startNewTurn($cards);

    $this->gamestate->nextState('');
  }

  public function stEndOfTurn()
  {
    foreach(Players::getAll() as $player){
      $player->clearBids();
    }
//    Notifications::clearTurn();

    // TODO
    $this->gamestate->nextState('nextTurn');
  }
}
?>
