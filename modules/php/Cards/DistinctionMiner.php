<?php
namespace NID\Cards;
use NID\Game\Globals;
use NID\Game\Notifications;

class DistinctionMiner extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = MINER;
  }

  public function applyEffect($player){
    $player->setGemValue(6);
    Notifications::distinctionGem($player);
  }

}
