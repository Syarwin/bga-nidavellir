<?php
namespace NID\Cards;
use NID\Game\Notifications;
use NID\Game\Players;
use NID\Cards;

class DistinctionBlacksmith extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = BLACKSMITH;
  }

  public function applyEffect($player){
    $card = Cards::get(DISTINCTION_BLACKSMITH_SPECIAL);
    $player->recruit($card);

    Cards::refresh($card); // Update location
    Notifications::recruit($player, $card);
    Players::updateScores();
  }
}
