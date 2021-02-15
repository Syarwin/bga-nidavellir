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
    return [
      'cards' => Cards::getInTavern(Globals::getTavern() )->getIds()
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
    Notifications::recruit($player, $card);


    // TODO
    $this->gamestate->nextState('next');
  }
}
