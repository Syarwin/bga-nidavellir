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
    $this->subname = clienttranslate("The Seer");
    $this->tooltip = [
      clienttranslate("Add 9 points to your final Bravery Value."),
      clienttranslate("At each turn, during the bidding phase, you do not place your coins on your individual board and keep them in your hand. At the Taverns Resolution, you wait for the other Elvalands to reveal their coins to pick yours and place them on the corresponding Tavern location, face up. The resolution then happens normally."),
      clienttranslate("During a coin trade, you must choose, at the end of your turn, which coins, from your hand, you add up to make the trade. The trade takes place normally except that you immediately take over the newly acquired coin."),
      clienttranslate("Uline's power activates as soon as you take her card. This means that as soon as you choose Uline, you place her in your Command Zone and then immediately take control of your coins in the Tavern locations that are not resolved this turn."),
    ];
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
