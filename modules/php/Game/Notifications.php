<?php
namespace NID\Game;
use Nidavellir;
use NID\Game\Globals;

class Notifications
{
  protected static function notifyAll($name, $msg, $data){
    Nidavellir::get()->notifyAllPlayers($name, $msg, $data);
  }

  protected static function notify($pId, $name, $msg, $data){
    Nidavellir::get()->notifyPlayer($pId, $name, $msg, $data);
  }


  public static function message($txt, $args = []){
    self::notifyAll('message', $txt, $args);
  }

  public static function messageTo($player, $txt, $args = []){
    $pId = ($player instanceof \NID\Player)? $player->getId() : $player;
    self::notify($pId, 'message', $txt, $args);
  }


  public static function startNewTurn($cards){
    $msg = Globals::getAge() == 1? clienttranslate('Starting turn ${turn} of first age') : clienttranslate('Starting turn ${turn} of second age');
    self::notifyAll('newTurn', $msg, [
      'cards' => $cards,
      'turn' => Globals::getTurn(),
    ]);
  }


  public static function bid($player, $coin, $tavern){
    self::notify($player->getId(), 'playerBid', '', [
      'coin' => $coin,
      'tavern' => $tavern,
    ]);
  }


  public static function revealBids($coins, $tavern){
    $msgs = [
      GOBLIN_TAVERN => clienttranslate('Revealing the bids for the Laughing Goblin Tavern'),
      DRAGON_TAVERN => clienttranslate('Revealing the bids for the Dancing Dragon Tavern'),
      HORSE_TAVERN => clienttranslate('Revealing the bids for Shining Horse Tavern')
    ];

    self::notifyAll('revealBids', $msgs[$tavern], [
      'coins' => $coins,
      'tavern' => $tavern,
    ]);
  }
}

?>
