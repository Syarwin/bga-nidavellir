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
    $this->name = clienttranslate("King's Great Armorer");
    $this->tooltip = [
      clienttranslate("Immediately add the special blacksmith card with 2 ranks to your army."),
      clienttranslate("Placing this card may trigger a recruitement of Hero cards."),
    ];

  }

  public function applyEffect($player){
    $card = Cards::get(DISTINCTION_BLACKSMITH_SPECIAL);
    $player->recruit($card);

    Cards::refresh($card); // Update location
    Notifications::recruit($player, $card);
    Players::updateScores();
  }
}
