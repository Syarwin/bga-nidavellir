<?php
namespace NID\States;

use Nidavellir;
use NID\Coins;
use NID\Cards;
use NID\Game\Players;
use NID\Game\Globals;
use NID\Game\Notifications;
use NID\Game\Stats;
use NID\Game\Stack;

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
    $player = Players::getActive();
    if($player->canVisitCamp()){
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
    if($card->getLocation() == 'camp')
      Globals::visitCamp();

    Cards::refresh($card); // Update location
    Notifications::recruit($player, $card);
    $card->applyEffect($player);
    Players::updateScores();
    if($card->getClass() == HERO){
      Stats::recruitHero($player);
    }

    if($card->getClass() == ROYAL_OFFER){
      $value = $card->getValue();
      if(Cards::getJarikaOwner() == $player->getId())
        $value += 2;
      Globals::setTransformValue($value);
      $this->gamestate->nextState("transform");
    } else {
      $this->nextStateAfterRecruit($card, $player);
    }
  }



  // $bypassCardState useful for Thrud
  public function nextStateAfterRecruit($card, $player, $bypassCardState = false){
    // Card require a new state ?
    $nextState = $bypassCardState? null : $card->stateAfterRecruit($player);

    // Thrud has been moved away in command zone ?
    if($nextState == null){
      $thrud = Cards::get(THRUD);
      if($thrud->getPId() != null && $thrud->getZone() == NEUTRAL){
//        $this->gamestate->changeActivePlayer($thrud->getPId());
        $nextState = 'placeThrud';
      }
    }

    // Can recruit a hero ?
    if($nextState == null && $player->canRecruitHero())
      $nextState = 'hero';

    Stack::nextState('recruitDone', $nextState);
  }


  public function argRecruitCamp()
  {
    return [
      'cards' => Cards::getInCamp()->getIds(),
    ];
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
    $newState = $player->canRecruitHero()? 'hero' : null;
    Stack::nextState('discardDone', $newState);
  }



  /**********************
  ******** THRUD ********
  **********************/
  public function actChooseColumn($column)
  {
    $this->checkAction('pickColumn');

    // Move card
    $player = Players::getActive();
    $cardAssoc = [
      'chooseThrudColumn' => THRUD,
      'chooseYludColumn' => YLUD,
      'placeOlwynDouble' => OLWYN_DOUBLE1,
    ];
    $card = Cards::get($cardAssoc[$this->gamestate->state()['name']]);
    if($card->getId() == OLWYN_DOUBLE1 && $card->getLocation() != "pending"){
      $card = Cards::get(OLWYN_DOUBLE2);
    }


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



  /**********************
  ****** ANDUMIA ********
  **********************/
  public function argPickDiscardAndumia()
  {
    $cards = Cards::getInLocation('discard');
    return [
      'cards' => $cards->getIds(), // Useful for auto check in recruit method
      'cardsObj' => $cards->ui(),
    ];
  }


  /********************
  ******* OLWYN *******
  ********************/
  public function stPlaceOlwynDouble()
  {
    $double1 = Cards::get(OLWYN_DOUBLE1);
    $double2 = Cards::get(OLWYN_DOUBLE2);
    if($double1->getLocation() != "pending" && $double2->getLocation() != "pending")
      $this->gamestate->nextState('finished');
  }
}
