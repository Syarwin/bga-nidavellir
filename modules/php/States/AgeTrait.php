<?php
namespace NID\States;
use NID\Coins;
use NID\Cards;
use NID\Game\Players;
use NID\Game\Globals;
use NID\Game\Notifications;

trait AgeTrait
{
  public function stStartOfAge()
  {
    Globals::startNewAge();
    $this->gamestate->nextState('turn');
  }

  public function stEndOfAge()
  {
    // TODO : relou hero qui se passe juste avant la distinction
//    $this->saveCurrentStateAsSource();
    if(Globals::getAge() == 1){
      Globals::setCurrentDistinction(0);
      $this->gamestate->nextState('distinctions');
    }
    else {
      $this->gamestate->nextState('scores');
    }
  }



  /***********************
  ***** Distinctions *****
  ***********************/
  public function stNextDistinction()
  {
    // Proceed next distinction
    $this->saveCurrentStateAsSource();
    $distinction = Cards::getDistinctionCard(Globals::nextDistinction());
    if(is_null($distinction)){
      $this->gamestate->nextState('done');
      return;
    }


    // Compute majority and check whether its exclusive
    $maxRank = $this->computeMajority($distinction->getDistinctionClass());

    if(count($maxRank) > 1){
      Notifications::distinctionTie($distinction, $maxRank);
      Cards::discard([$distinction->getId()]);
      $this->gamestate->nextState('next');
    }
    else {
      $player = Players::get($maxRank[0]);
      $player->recruit($distinction);

      Cards::refresh($distinction); // Update location
      Notifications::distinction($player, $distinction);

      $distinction->applyEffect($player);
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
}
?>
