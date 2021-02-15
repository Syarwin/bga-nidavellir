<?php
namespace NID\Game;
use Nidavellir;
use NID\Game\Globals;

class Notifications
{
  protected static function notifyAll($name, $msg, $data){
    self::updateArgs($data);
    Nidavellir::get()->notifyAllPlayers($name, $msg, $data);
  }

  protected static function notify($pId, $name, $msg, $data){
    self::updateArgs($data);
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


  public static function recruitStart($player, $order){
    self::notifyAll('recruitStart', '${player_name} is choosing in position ${order}', [
      'player' => $player,
      'order'  => $order,
    ]);
  }

  public static function recruit($player, $card){
    self::notifyAll('recruit', '${player_name} recruits ${card_name}', [
      'player' => $player,
      'card_name' => "test",
      'card'  => $card,
    ]);
  }


  /*
   * Automatically adds some standard field about player and/or card
   */
  public static function updateArgs(&$args){
    if(isset($args['player'])){
      $args['player_name'] = $args['player']->getName();
      $args['player_id'] = $args['player']->getId();
      unset($args['player']);
    }

    /*
    TODO : substitution on js
    if(isset($args['card'])){
      $c = $args['card'];

      $args['value'] = $c['value'];
      $args['value_symbol'] = $c['value'];// The substitution will be done in JS format_string_recursive function
      $args['color'] = $c['color'];
      $args['color_symbol'] = $c['color'];// The substitution will be done in JS format_string_recursive function
    }
    */
  }
}

?>
