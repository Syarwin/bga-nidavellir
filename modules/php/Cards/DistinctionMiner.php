<?php
namespace NID\Cards;
use NID\Game\Globals;
use NID\Game\Notifications;

class DistinctionMiner extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = MINER;
    $this->name = clienttranslate("Crown Jeweler");
    $this->tooltip = [
      clienttranslate("Place the special value gem 6 on your current gem. You will add 3 points to your Final Bravery Value."),
      clienttranslate("This gem will never be traded, even in the event of a tie with another Elvaland, and will allow you to win all ties at Tavern Resolutions."),
    ];
  }

  public function applyEffect($player){
    $player->setGemValue(6);
    Notifications::distinctionGem($player);
  }

}
