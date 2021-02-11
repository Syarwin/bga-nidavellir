<?php
namespace NID\States;
use NID\Cards;
use NID\Game\Globals;
use NID\Game\Notifications;

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
}
?>
