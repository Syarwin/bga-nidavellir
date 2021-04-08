<?php
namespace NID\Cards\Heroes;
use NID\Cards;

class Khrad extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->expansion = true;
    $this->id = KHRAD;
    $this->name = 'Khrad';
    $this->subname = clienttranslate("The Beggar");
    $this->tooltip = [
      clienttranslate("Add 4 points to your Final Bravery Value."),
      clienttranslate("When you recruit him, immediately add +10 to your lowest value coin.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      4
    ];
  }

  public function applyEffect($player){
    // Get the minimal transformable coin
    $min = 30;
    $minCoin = null;
    foreach($player->getCoins() as $coin){
      if($coin['value'] < $min && $coin['value'] != 0 && ($coin['value'] != 3 || $coin['type'] != COIN_DISTINCTION)){
        $min = $coin['value'];
        $minCoin = $coin;
      }
    }

    // Add +10
    $up = 10;
    if(Cards::getJarikaOwner() == $player->getId())
      $up += 2;

    $player->tradeCoin($up, $up);
  }
}
