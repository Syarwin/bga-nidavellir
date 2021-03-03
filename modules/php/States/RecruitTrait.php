<?php
namespace NID\States;

use Nidavellir;
use NID\Coins;
use NID\Cards;
use NID\Game\Players;
use NID\Game\Globals;
use NID\Game\Notifications;

trait RecruitTrait
{
  /**********************
  ******** DWARF ********
  **********************/

  public function argRecruitDwarf()
  {
    $taverns = [
      GOBLIN_TAVERN => clienttranslate('Laughing Goblin Tavern'),
      DRAGON_TAVERN => clienttranslate('Dancing Dragon Tavern'),
      HORSE_TAVERN => clienttranslate('Shining Horse Tavern')
    ];

    $tavern = Globals::getTavern();
    return [
      'i18n' => ['tavern_name'],
      'cards' => Cards::getInTavern($tavern)->getIds(),
      'tavern' => $tavern,
      'tavern_name' => $taverns[$tavern],
    ];
  }


  public function actRecruit($cardId)
  {
    $this->checkAction("recruit");

    // Check if cards is in tavern
    $cards = $this->gamestate->state()['args']['cards'];
    if(!in_array($cardId, $cards))
      throw new \BgaUserException(_("You cannot recruit this dwarf/hero"));

    // Move card in corresponding position and notify (useful for replay)
    $card = Cards::get($cardId);
    $player = Players::getCurrent();
    $player->recruit($card);

    Cards::refresh($card); // Update location
    Notifications::recruit($player, $card);
    $card->applyEffect($player);
    Players::updateScores();

    if($card->getClass() == ROYAL_OFFER){
      Globals::setTransformValue($card->getValue());
      $this->gamestate->nextState("transform");
    } else {
      $this->nextStateAfterRecruit($card, $player);
    }
  }



  public function nextStateAfterRecruit($card, $player){
    $nextState = $card->stateAfterRecruit();
    if($nextState != null)
      $this->gamestate->nextState($nextState);
    else {
      if($player->canRecruitHero())
        $this->gamestate->nextState('hero');
      else
        $this->nextStateFromSource('recruitDone');
    }
  }


  /**********************
  ******** HERO ********
  **********************/

  public function argRecruitHero()
  {
    $player = Players::getActive();
    $heroes = Cards::getRecruitableHeroes($player);
    return [
      'cards' => array_map(function($hero){ return $hero->getId(); }, $heroes),
      'test' => $player->getDiscardableStacks(),
    ];
  }


  public function argDiscardCard()
  {
    $player = Players::getActive();
    $recruit = $player->getLastAction("recruit");
    $card = Cards::get($recruit['card']['id']);
    return [
      'n' => $card->getDiscardRequirement(),
      'cards' => array_map(function($card){ return $card->getId(); }, $player->getDiscardableCards()),
    ];
  }


  public function actDiscardCards($cardIds)
  {
    $this->checkAction("discard");

    $discardableCards = $this->argDiscardCard()['cards'];
    foreach($cardIds as $cId){
      if(!in_array($cId, $discardableCards))
        throw new \BgaUserException(_("You cannot discard this card"));
    }

    $player = Players::getActive();
    $cards = Cards::get($cardIds);
    Cards::discard($cardIds);
    Notifications::discardCards($player, $cards);
    Players::updateScores();
    $nextState = $player->canRecruitHero()? 'hero' : 'trade';
    $this->gamestate->nextState($nextState);
  }
}
