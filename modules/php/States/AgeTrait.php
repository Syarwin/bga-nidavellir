<?php
namespace NID\States;
use NID\Coins;
use NID\Cards;
use NID\Game\Players;
use NID\Game\Globals;
use NID\Game\Notifications;
use NID\Game\Stack;

trait AgeTrait
{
  public function stStartOfAge()
  {
    Globals::startNewAge();

    if(Globals::getAge() == 2){
      Cards::clearCamp();
      Notifications::clearCamp();
    }

    $this->gamestate->nextState('turn');
  }

  public function stPreEndOfAge()
  {
    $pId = Cards::getYludOwner();
    $newState = null;
    if($pId != null){
      $this->gamestate->changeActivePlayer($pId);
      $newState = 'placeYlud';
    }

    Stack::nextState('end', $newState);
  }

  public function stEndOfAge()
  {
    if(Globals::getAge() == 1){
      Globals::setCurrentDistinction(0);
      $this->gamestate->nextState('distinctions');
    }
    else {
      $this->gamestate->nextState('scores');
    }
  }



  public function stPreEndOfGame()
  {
    $pId = Cards::getThrudOwner();
    if($pId != null){
      $card = Cards::get(THRUD);
      Cards::changeColumn($card, Players::get($pId), NEUTRAL, true);
      Players::updateScores();
    }

    $this->gamestate->nextState('');
  }


  /***********************
  ***** Distinctions *****
  ***********************/
  public function stNextDistinction()
  {
    // Proceed next distinction
    $distinction = Cards::getDistinctionCard(Globals::nextDistinction());
    if(is_null($distinction)){
      Cards::shuffle(['age', 2]); // After explorer distinction and before age 2, shuffle deck
      $this->gamestate->nextState('done');
      return;
    }


    // Compute majority and check whether its exclusive
    $maxRank = $this->computeMajority($distinction->getDistinctionClass());

    if(count($maxRank) > 1){
      Notifications::distinctionTie($distinction, $maxRank);
      $distinction->applyTieEffect();
      Cards::discard([$distinction->getId()], true);
      $this->gamestate->nextState('next');
    }
    else {
      $player = Players::get($maxRank[0]);
      $player->recruit($distinction);

      Cards::refresh($distinction); // Update location
      Notifications::distinction($player, $distinction);

      $distinction->applyEffect($player);
      $this->gamestate->changeActivePlayer($player->getId());
      $this->nextStateAfterRecruit($distinction, $player);
    }
  }


  public function computeMajority($class)
  {
    $ranks = [];
    foreach(Players::getAll() as $player){
      $ranks[$player->getId()] = $player->getRanks()[$class];
    }

    return array_keys($ranks, max($ranks));
  }


  public function argDistinctionExplorer()
  {
    $cards = Cards::getTopOf(['age', 2], 3);
    return [
      'cards' => $cards->getIds(),
      'cardsObj' => $cards->ui(),
    ];
  }
}
?>
