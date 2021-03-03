<?php
namespace NID\Cards;
use NID\Coins;
use NID\Game\Notifications;

class Uline extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = ULINE;
    $this->name = 'Uline';
    $this->heroClass = NEUTRAL;
    $this->advanced = true;
    $this->grade = [
      9
    ];
  }

  public function applyEffect($player){
    $movedCoins = [];
    foreach($player->getCoins() as $coin){
      if($coin['location'] == 'bid'){
        Coins::move($coin['id'], 'hand');
        $movedCoins[] = $coin['id'];
      }
    }
    Notifications::ulineRecruited($player, $movedCoins);
  }
}
