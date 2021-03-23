<?php
namespace NID\Cards\Artifacts;
use NID\Coins;
use NID\Game\Notifications;

class Jarnglofi extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = JARNGLOFI;
    $this->age = 2;
    $this->name = 'Jarnglofi';
    $this->tooltip = [
      clienttranslate("Immediately discard your trading coin (0 or Special Hunter 3). Warning ! If this coin was placed on an unresolved tavern, your bid will not be present when it is resolved and you will not take any cards."),
      clienttranslate('At the end of Age 2, when counting points, add 24 points to your Final Bravery Value.')
    ];
    $this->grade = [ 24 ];
  }

  public function updateScores(&$scores, $player){
    $scores[ARTIFACT_SCORE] += 24;
  }


  public function applyEffect($player){
    // Get the minimal transformable coin
    $tradingCoin = null;
    foreach($player->getCoins() as $coin){
      if($coin['value'] == 0 || ($coin['value'] == 3 && $coin['type'] == COIN_DISTINCTION)){
        $tradingCoin = $coin;
      }
    }

    Coins::discard($tradingCoin);
    Notifications::discardTradingCoin($player, $tradingCoin);
  }
}
