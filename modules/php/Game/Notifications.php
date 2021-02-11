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
}

?>
