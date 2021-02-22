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

    $card = Cards::get($cardId); // Update location
    Notifications::recruit($player, $card);


    $nextState = $player->canRecruitHero()? 'hero' : 'trade';
    $this->gamestate->nextState($nextState);
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
    ];
  }
}
