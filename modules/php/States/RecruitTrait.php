<?php
namespace NID\States;

use Nidavellir;
use NID\Coins;
use NID\Cards;
use NID\Game\Players;
use NID\Game\Globals;
use NID\Game\Notifications;
use NID\Game\Stats;

trait RecruitTrait
{
  /**********************
  ******** DWARF ********
  **********************/
  public function stRecruitDwarf()
  {
    $args = $this->argRecruitDwarf();
    $player = Players::getActive();
    if(count($args['cards']) == 1 && $player->wantAutopick()){
      $this->actRecruit($args['cards'][0], false);
    }
  }


  public function argRecruitDwarf()
  {
    $taverns = [
      GOBLIN_TAVERN => clienttranslate('Laughing Goblin Tavern'),
      DRAGON_TAVERN => clienttranslate('Dancing Dragon Tavern'),
      HORSE_TAVERN => clienttranslate('Shining Horse Tavern')
    ];

    $tavern = Globals::getTavern();
    $data = [
      'i18n' => ['tavern_name', 'camp_title'],
      'cards' => Cards::getInTavern($tavern)->getIds(),
      'tavern' => $tavern,
      'tavern_name' => $taverns[$tavern],
      'camp' => false,
      'camp_title' => '',
    ];

    // THINGVELLIR
    if(Globals::getCurrentPlayerIndex() == 0){ // TODO : artefact machin truc
      $data['cards'] = array_merge($data['cards'], Cards::getInCamp()->getIds() );
      $data['camp_title'] = clienttranslate(" / an artifact or a missionary at the Camp");
      $data['camp'] = true;
    }

    return $data;
  }


  public function actRecruit($cardId, $checkActivity = true)
  {
    if($checkActivity)
      $this->checkAction("recruit");
    else
      $this->gamestate->checkPossibleAction("recruit");

    // Check if cards is in tavern
    $cards = $this->gamestate->state()['args']['cards'];
    if(!in_array($cardId, $cards))
      throw new \BgaUserException(_("You cannot recruit this dwarf/hero"));

    // Move card in corresponding position and notify (useful for replay)
    $card = Cards::get($cardId);
    $player = Players::getActive();
    $player->recruit($card);

    Cards::refresh($card); // Update location
    Notifications::recruit($player, $card);
    $card->applyEffect($player);
    Players::updateScores();
    if($card->getClass() == HERO){
      Stats::recruitHero($player);
    }

    if($card->getClass() == ROYAL_OFFER){
      Globals::setTransformValue($card->getValue());
      $this->gamestate->nextState("transform");
    } else {
      $this->nextStateAfterRecruit($card, $player);
    }
  }



  // $bypassCardState useful for Thrud
  public function nextStateAfterRecruit($card, $player, $bypassCardState = false){
    $nextState = $bypassCardState? null : $card->stateAfterRecruit();
    if($nextState != null)
      $this->gamestate->nextState($nextState);
    else {
      $thrud = Cards::get(THRUD);
      if($thrud->getPId() != null && $thrud->getZone() == NEUTRAL)
        $this->gamestate->nextState('placeThrud');
      else if($player->canRecruitHero())
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



  /**********************
  ******** THRUD ********
  **********************/
  public function actChooseColumn($column)
  {
    $this->checkAction('pickColumn');

    // Move card
    $player = Players::getActive();
    $card = $this->gamestate->state()['name'] == 'chooseThrudColumn'?
      Cards::get(THRUD) : Cards::get(YLUD);

    Cards::changeColumn($card, $player, $column);
    Players::updateScores();
    $this->nextStateAfterRecruit($card, $player, true);
  }


  /***********************
  ******* AUTOPICK *******
  ***********************/
  public function actSetAutopick($mode)
  {
    $player = Players::getCurrent();
    $player->setAutoPick($mode);
  }
}
