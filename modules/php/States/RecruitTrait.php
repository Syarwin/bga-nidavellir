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


  public function actRecruitDwarf($cardId)
  {
    $this->checkAction("recruit");

    // Check if cards is in tavern
    $cards = $this->argRecruitDwarf()['cards'];
    if(!in_array($cardId, $cards))
      throw new \BgaUserException(_("You cannot recruit this dwarf"));

    // Move card in corresponding position and notify (useful for replay)
    $card = Cards::get($cardId);
    $player = Players::getCurrent();
    $player->recruit($card);

    $card = Cards::get($cardId); // Update location
    Notifications::recruit($player, $card);


    // TODO
    $nextState = $player->shouldTrade()? 'trade' : 'next';
    $this->gamestate->nextState($nextState);
  }

}
