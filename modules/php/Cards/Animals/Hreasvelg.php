<?php
namespace NID\Cards\Animals;
use NID\Cards;
use NID\Game\Players;
use NID\Game\Notifications;

class Hreasvelg extends AnimalCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = HREASVELG;
    $this->name = 'Hreasvelg';
    $this->tooltip = [
      clienttranslate('Has 1 Blacksmith rank.'),
      clienttranslate('Take the special card Gullinbursti and place it in the column of your choice.'),
    ];
    $this->animalClass = BLACKSMITH;
    $this->grade = [null];
  }

  public function applyEffect($player)
  {
    $card = Cards::get(GULLINBURSTI);
    $player = Players::getActive();
    $player->recruit($card);
    Cards::refresh($card); // Update location
    Notifications::recruit($player, $card);
  }

  public function stateAfterRecruit($player)
  {
    return 'placeGullinbursti';
  }
}
