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

    $this->saveCurrentStateAsSource();
    $this->gamestate->nextState('start');
  }

  public function stEndOfTurn()
  {
    foreach(Players::getAll() as $player){
      $player->clearBids();
    }

    Cards::clearTaverns(); // Useful in 2p mode only
    Notifications::clearTurn();


    $cardsLeft = Cards::countInLocation(['age', Globals::getAge()]);
    $this->gamestate->nextState($cardsLeft == 0? 'nextAge' : 'nextTurn');
  }
}
?>
