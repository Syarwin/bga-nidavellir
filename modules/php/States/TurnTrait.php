<?php

namespace NID\States;

use NID\Cards;
use NID\Game\Globals;
use NID\Game\Notifications;
use NID\Game\Players;
use NID\Game\Stack;

trait TurnTrait
{
  public function stStartOfTurn()
  {
    Globals::startNewTurn();

    // Draw cards and notify them
    $cards = Cards::startNewTurn();
    Notifications::startNewTurn($cards);

    $nextState = 'start';
    $this->checkAseActivation($nextState, FREYA, 'freya');
    $this->checkAseActivation($nextState, LOKI, 'loki');
    $this->gamestate->nextState($nextState);
  }

  public function stEndOfTurn()
  {
    foreach (Players::getAll() as $player) {
      $player->clearBids();
    }

    Cards::clearTaverns(); // TODO : useless now
    Notifications::clearTurn();

    $cardsLeft = Cards::countInLocation(['age', Globals::getAge()]);
    $this->gamestate->nextState($cardsLeft == 0 ? 'nextAge' : 'nextTurn');
  }

  public function stResolveStack()
  {
    Stack::resolve();
  }

  /********************
   ******* IDAVOLL *******
   ********************/

  public function argLoki()
  {
    $cards = Cards::getInTavern('%');

    return [
      'cards' => $cards->getIds(),
    ];
  }

  public function actSkipLokiPower()
  {
    $this->checkAction('actSkipLokiPower');
    $this->gamestate->nextState('');
  }

  public function actUseLokiPower($cardId)
  {
    $this->checkAction('actUseLokiPower');
    $player = Players::getActive();

    // Notify Loki
    $loki = Cards::get(LOKI);
    $loki->usePower();

    Globals::setLoki($cardId);
    Notifications::reserveCard($player, Cards::get($cardId));
    $this->gamestate->nextState('');
  }

  public function stPreFreya()
  {
    $nextState = 'start';
    $this->checkAseActivation($nextState, FREYA, 'freya');
    $this->gamestate->nextState($nextState);
  }

  public function argFreya()
  {
    $cards = Cards::getInTavern('%')->getIds();
    return [
      'cards' => $cards,
    ];
  }

  public function actSkipFreyaPower()
  {
    $this->checkAction('actSkipFreyaPower');
    $this->gamestate->nextState('start');
  }

  public function actUseFreyaPower($card1Id, $card2Id)
  {
    $this->checkAction('actUseFreyaPower');
    $player = Players::getActive();

    // Notify Freya
    $freya = Cards::get(FREYA);
    $freya->usePower();

    $card1 = Cards::get($card1Id);
    $card2 = Cards::get($card2Id);
    Cards::move($card1Id, $card2->getLocation());
    Cards::move($card2Id, $card1->getLocation());
    Notifications::exchangeCard($player, $card1, $card2);

    $this->gamestate->nextState('start');
  }
}
