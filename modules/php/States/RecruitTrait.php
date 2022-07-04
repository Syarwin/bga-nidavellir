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
    $player = Players::getActive();
    if ($player->wantAutopick() && !$player->canVisitCamp()) {
      $cardId = $this->getAutopickCard();
      if ($cardId != null) {
        $this->actRecruit($cardId, false, false);
      }
    }
  }

  public function getAutopickCard()
  {
    $tavern = Globals::getTavern();
    $cardsPerClass = [];
    $player = Players::getActive();
    $canCapture = false;
    foreach (Cards::getInTavern($tavern) as $card) {
      $cardsPerClass[$card->getClass()][] = $card;
      if (!$canCapture && $player->canCapture($card)) {
        $canCapture = true;
      }
    }

    // More than one color ? No autopick
    if (count($cardsPerClass) > 1 || $canCapture) {
      return null;
    }
    $class = array_keys($cardsPerClass)[0];
    if (in_array($class, [ASE, VALKYRIE, GIANT, ANIMAL])) {
      return null;
    }

    $cards = reset($cardsPerClass);
    usort($cards, function ($a, $b) {
      return $b->getPriority() - $a->getPriority();
    });
    $card = reset($cards);
    return $card->getId();
  }

  public function argRecruitDwarf()
  {
    $taverns = [
      GOBLIN_TAVERN => clienttranslate('Laughing Goblin Tavern'),
      DRAGON_TAVERN => clienttranslate('Dancing Dragon Tavern'),
      HORSE_TAVERN => clienttranslate('Shining Horse Tavern'),
    ];

    $tavern = Globals::getTavern();
    $cards = Cards::getInTavern($tavern);
    if (Globals::getLokiCardId() !== 0 && Players::getActiveId() != Cards::getOwner(LOKI)) {
      $cards = $cards->cfilter(function ($card) {
        return $card->getId() != Globals::getLokiCardId();
      });
    }

    $data = [
      'i18n' => ['tavern_name', 'camp_title'],
      'cards' => $cards->getIds(),
      'tavern' => $tavern,
      'tavern_name' => $taverns[$tavern],
      'camp' => false,
      'camp_title' => '',
    ];

    // THINGVELLIR
    $player = Players::getActive();
    if ($player->canVisitCamp()) {
      $data['cards'] = array_merge($data['cards'], Cards::getInCamp());
      $data['camp_title'] = clienttranslate(' / an artifact or a missionary at the Camp');
      $data['camp'] = true;
    }

    // IDAVOLL
    $capture = [];
    foreach ($cards as $card) {
      if ($player->canCapture($card)) {
        $capture[] = $card->getId();
      }
    }
    $data['capture'] = $capture;

    return $data;
  }

  public function actRecruit($cardId, $capture = false, $checkActivity = true)
  {
    if ($checkActivity) {
      $this->checkAction('recruit');
    } else {
      $this->gamestate->checkPossibleAction('recruit');
    }

    // Check if cards is in tavern
    $args = $this->gamestate->state()['args'];
    $cards = $args['cards'];
    if (!in_array($cardId, $cards)) {
      throw new \BgaUserException(_('You cannot recruit this dwarf/hero'));
    }

    // IDAVOLL => Check capture
    if ($capture) {
      if (!in_array($cardId, $args['capture'])) {
        throw new \BgaUserException(_('You cannot capture this dwarf'));
      }
      $this->actCapture($cardId);
      return;
    }

    // Move card in corresponding position and notify (useful for replay)
    $card = Cards::get($cardId);
    $player = Players::getActive();
    $player->recruit($card);
    if ($card->getLocation() == 'camp' && $this->gamestate->state()['name'] == 'recruitDwarf') {
      Globals::visitCamp();
    }
    if ($card->getClass() == HERO) {
      Cards::increaseForce(SIGRDRIFA, $player); // Valkyrie
    }

    Cards::refresh($card); // Update location
    Notifications::recruit($player, $card);
    // IDAVOLL => disable Giant's power if no capture
    if (!$capture && isset($args['capture']) && in_array($cardId, $args['capture'])) {
      $player->denyCapture($card);
    }
    $card->applyEffect($player);
    Players::updateScores();
    if ($card->getClass() == HERO) {
      Stats::recruitHero($player);
    }

    $this->nextStateAfterRecruit($card, $player);
  }

  // $bypassCardState useful for Thrud
  public function nextStateAfterRecruit($card, $player, $bypassCardState = false)
  {
    // Card require a new state ?
    $nextState = $bypassCardState ? null : $card->stateAfterRecruit($player);

    // Royal Offer
    if ($card != null && $card->getClass() == ROYAL_OFFER) {
      $value = $card->getValue();
      if (Cards::getJarikaOwner() == $player->getId()) {
        $value += 2;
      }
      Globals::setTransformValue($value);
      $nextState = 'transform';
    }

    // Thrud has been moved away in command zone ?
    if ($nextState == null) {
      $thrud = Cards::get(THRUD);
      if (
        $thrud->getPId() != null &&
        $thrud->getZone() == NEUTRAL &&
        $this->gamestate->state_id() != ST_BRISINGAMENS_DISCARD
      ) {
        //        $this->gamestate->changeActivePlayer($thrud->getPId());
        $nextState = 'placeThrud';
      }
    }

    // Olwyn doubles need to be placed somewhere ?
    if ($nextState == null) {
      $double1 = Cards::get(OLWYN_DOUBLE1);
      $double2 = Cards::get(OLWYN_DOUBLE2);
      if (
        ($double1->getPId() != null && $double1->getZone() == NEUTRAL) ||
        ($double2->getPId() != null && $double2->getZone() == NEUTRAL)
      ) {
        $nextState = 'olwyn';
      }
    }

    // Gullinbursti need to be placed somewhere ?
    if ($nextState == null) {
      $animal = Cards::get(GULLINBURSTI);
      if ($animal->getPId() != null && $animal->getZone() == NEUTRAL) {
        $nextState = 'placeGullinbursti';
      }
    }

    // Can recruit a hero ?
    if ($nextState == null && $player->canRecruitHero()) {
      $nextState = 'hero';
    }

    Stack::nextState('recruitDone', $nextState);
  }

  public function argRecruitCamp()
  {
    return [
      'cards' => Cards::getInCamp(),
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
      'cards' => array_map(function ($hero) {
        return $hero->getId();
      }, $heroes),
      'test' => $player->getDiscardableStacks(),
    ];
  }

  public function argDiscardCard()
  {
    $player = Players::getActive();
    $recruit = $player->getLastAction('recruit');
    $card = Cards::get($recruit['card']['id']);
    return [
      'n' => $card->getDiscardRequirement($player) - (Globals::getThorCardId() == $card->getId() ? 1 : 0),
      'thor' => $player->canUseAse(THOR),
      'cards' => array_map(function ($card) {
        return $card->getId();
      }, $player->getDiscardableCards()),
    ];
  }

  public function actDiscardCards($cardIds)
  {
    $this->checkAction('discard');

    $discardableCards = $this->gamestate->state()['args']['cards'];
    foreach ($cardIds as $cId) {
      if (!in_array($cId, $discardableCards)) {
        throw new \BgaUserException(_('You cannot discard this card'));
      }
    }

    $player = Players::getActive();
    $cards = Cards::get($cardIds);
    Cards::discard($cardIds);
    Notifications::discardCards($player, $cards);
    Players::updateScores();
    $this->nextStateAfterRecruit(null, $player, true);
  }

  public function actUseThorPower()
  {
    $this->checkAction('actUseThorPower');
    $args = $this->gamestate->state()['args'];
    if (!$args['thor']) {
      throw new \BgaUserException(_('You cannot use Thor Power'));
    }

    $player = Players::getActive();
    $recruit = $player->getLastAction('recruit');
    $card = Cards::get($recruit['card']['id']);
    $thor = Cards::get(THOR);
    $thor->usePower();
    Globals::setThor($card->getId());

    if ($card->getDiscardRequirement($player) == 1) {
      $this->nextStateAfterRecruit(null, $player, true);
    } else {
      $this->gamestate->nextState('discard');
    }
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
      'placeGullinbursti' => GULLINBURSTI,
    ];
    $card = Cards::get($cardAssoc[$this->gamestate->state()['name']]);
    if ($card->getId() == OLWYN_DOUBLE1 && $card->getLocation() != 'pending' && $card->getZone() != NEUTRAL) {
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
    ];
  }

  /***************************
   ****** BRISINGAMENS ********
   ***************************/
  public function stPreBrisingamens()
  {
    $n = Globals::incBrisingamens();
    $this->gamestate->nextState($n <= 2 ? 'recruit' : 'done');
  }

  /********************
   ******* OLWYN *******
   ********************/
  public function argPlaceOlwynDouble()
  {
    $columns = [BLACKSMITH, HUNTER, MINER, WARRIOR, EXPLORER];
    $double1 = Cards::get(OLWYN_DOUBLE1);
    $player = Players::getActive();
    if ($double1->getLocation() != 'pending' && $double1->getPId() == $player->getId()) {
      $columns = array_values(array_diff($columns, [$double1->getZone()]));
    }
    $double2 = Cards::get(OLWYN_DOUBLE2); // Very edgy case that happens only when discarding olwyn double...
    if ($double2->getLocation() != 'pending' && $double2->getPId() == $player->getId()) {
      $columns = array_values(array_diff($columns, [$double2->getZone()]));
    }
    return [
      'columns' => $columns,
    ];
  }

  public function stPlaceOlwynDouble()
  {
    $double1 = Cards::get(OLWYN_DOUBLE1);
    $double2 = Cards::get(OLWYN_DOUBLE2);
    if (
      $double1->getLocation() != 'pending' &&
      $double2->getLocation() != 'pending' &&
      $double1->getZone() != NEUTRAL &&
      $double2->getZone() != NEUTRAL
    ) {
      $this->gamestate->nextState('finished');
    }
  }

  /********************
   ******* HOFUD *******
   ********************/
  public function stPreHofud()
  {
    $pIds = [];
    foreach ($this->argDiscardHofud()['_private'] as $pId => $arg) {
      if (!empty($arg['cards'])) {
        $pIds[] = $pId;
      }
    }
    $this->gamestate->setPlayersMultiactive($pIds, '', true);
    $this->gamestate->nextState('');
  }

  public function argDiscardHofud()
  {
    $ownerId = Cards::getHofudOwner();
    $data = ['_private' => []];
    foreach (Players::getAll() as $pId => $player) {
      if ($pId == $ownerId) {
        continue;
      }

      $cards = $player->getCards()->filter(function ($card) {
        return $card->isDiscardable() && $card->getZone() == WARRIOR;
      });
      $data['_private'][$pId]['cards'] = array_map(function ($card) {
        return $card->getId();
      }, $cards);
    }

    return $data;
  }

  public function actDiscardHofud($cardId)
  {
    $card = Cards::get($cardId);
    $player = Players::getCurrent();
    if ($card->getPId() != $player->getId() || $card->getZone() != WARRIOR || !$card->isDiscardable()) {
      throw new \BgaUserException(_('You cannot discard this card'));
    }

    Cards::discard([$cardId]);
    $warriors = $player->getCards()->filter(function ($card) {
      return $card->getZone() == WARRIOR;
    });
    Notifications::discardHofud($player, $card, $warriors);
    Players::updateScores();
    $this->gamestate->setPlayerNonMultiactive($player->getId(), 'done');
  }

  /********************
   ******* KHRAD *******
   ********************/
  public function argKhradTransform()
  {
    $player = Players::getActive();
    $min = 30;
    $minCoins = null;
    foreach ($player->getCoins() as $coin) {
      if ($coin['value'] < $min && $coin['value'] != 0 && ($coin['value'] != 3 || $coin['type'] != COIN_DISTINCTION)) {
        $min = $coin['value'];
        $minCoins = [$coin['id']];
      } elseif ($coin['value'] == $min) {
        $minCoins[] = $coin['id'];
      }
    }

    return [
      'coins' => $minCoins,
    ];
  }

  public function stKhradTransform()
  {
    $player = Players::getActive();
    // Add +10
    $up = 10;
    if (Cards::getJarikaOwner() == $player->getId()) {
      $up += 2;
    }
    Globals::setTransformValue($up);

    $coins = $this->argKhradTransform()['coins'];
    if (count($coins) == 1) {
      $this->actTransformCoin($coins[0]);
    }
  }

  /********************
   ******* IDAVOLL *******
   ********************/

  public function actCapture($cardId)
  {
    $player = Players::getActive();
    $card = Cards::get($cardId);
    $giant = $player->getCapturingGiant($card);
    $giant->capture($card);
    $giant->applyEffect($player);
    Players::updateScores();
    $this->gamestate->nextState('recruitDone');
  }

  public function argOdin()
  {
    $player = Players::getActive();
    $card = Cards::get(ODIN);
    $heroes = Cards::getRecruitableHeroes($player);
    $heroes = array_values(
      array_filter($heroes, function ($hero) {
        return $hero->getRecruitementZone() == NEUTRAL;
      })
    );

    return [
      'cards' => $card->getNeutralHeroes(),
      'heroes' => array_map(function ($hero) {
        return $hero->getId();
      }, $heroes),
    ];
  }

  public function actSkipOdinPower()
  {
    $this->checkAction('actSkipOdinPower');
    $this->gamestate->nextState('done');
  }

  public function actUseOdinPower($cardId, $heroId)
  {
    $this->checkAction('actUseOdinPower');
    $player = Players::getActive();

    // Notify Odin
    $odin = Cards::get(ODIN);
    $odin->usePower();

    // Put back card
    Cards::move($cardId, 'hall');
    $card = Cards::get($cardId);
    Notifications::returnCard($player, $card);

    // Move card in corresponding position and notify
    $hero = Cards::get($heroId);
    $player->recruit($hero);
    Cards::refresh($hero);
    Notifications::recruit($player, $hero);
    $hero->applyEffect($player);
    Players::updateScores();
    $this->nextStateAfterRecruit($hero, $player);
  }


  public function argPlaceGullinbursti()
  {
    return [];
  }
}
