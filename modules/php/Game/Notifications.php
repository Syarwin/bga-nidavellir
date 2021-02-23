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
    self::notifyAll('recruitStart', clienttranslate('${player_name} is choosing in position ${order}'), [
      'player' => $player,
      'order'  => $order,
    ]);
  }

  public static function recruit($player, $card){
    self::notifyAll('recruit', clienttranslate('${player_name} recruits ${card_class}${card_class_symbol}'), [
      'player' => $player,
      'card'  => $card,
    ]);
  }


  public static function tradeGems($bid, $bidders, $trades){
    $msgs = [
      '', '',
      clienttranslate('${player_name} and ${player_name2} both bade with their ${bid} coin, and trade their gems as a tie'),
      clienttranslate('${player_name}, ${player_name2} and ${player_name3} all bade with their ${bid} coin, and trade their gems as a tie (${player_name2} keeps its gem)'),
      clienttranslate('${player_name}, ${player_name2}, ${player_name3} and ${player_name4} all bade with their ${bid} coin, and trade their gems as a tie'),
      clienttranslate('${player_name}, ${player_name2}, ${player_name3}, ${player_name4} and ${player_name5} all bade with their ${bid} coin, and trade their gems as a tie (${player_name3} keeps its gem)'),
    ];

    $data = [
      'bid' => $bid,
      'trades' => $trades,
      'player_name' => $bidders[0],
    ];
    for($i = 1; $i < count($bidders); $i++){
      $data['player_name'.($i + 1)] = $bidders[$i];
    }

    self::notifyAll('tradeGems', $msgs[count($bidders)], $data);
  }


  public static function tradeCoin($player, $coinMin, $coinMax, $newCoin){
//    self::notifyAll('tradeCoin', clienttranslate('${player_name} uses ${coin_min}${coin_min_type} and ${coin_max}${coin_max_type} to trade ${coin_max}${coin_max_type} into ${coin_new}${coin_new_type}'), [
    self::notifyAll('tradeCoin', clienttranslate('${player_name} uses ${coin_min}${coin_min_type} to trade ${coin_max}${coin_max_type} into ${coin_new}${coin_new_type}'), [
      'player' => $player,
      'coin_min' => $coinMin['value'],
      'coin_min_type' => $coinMin['type'],
      'coin_max' => $coinMax['value'],
      'coin_max_type' => $coinMax['type'],
      'coin_new' => $newCoin['value'],
      'coin_new_type' => $newCoin['type'],

      'max' => $coinMax,
      'new' => $newCoin,
    ]);
  }

  public static function transformCoin($player, $coinMax, $newCoin){
    self::notifyAll('tradeCoin', clienttranslate('${player_name} transforms ${coin_max}${coin_max_type} into ${coin_new}${coin_new_type}'), [
      'player' => $player,
      'coin_max' => $coinMax['value'],
      'coin_max_type' => $coinMax['type'],
      'coin_new' => $newCoin['value'],
      'coin_new_type' => $newCoin['type'],

      'max' => $coinMax,
      'new' => $newCoin,
    ]);
  }

  public static function discardCards($player, $cards){
    if($cards instanceof \NID\Cards\AbstractCard){
      self::notifyAll('discardCards', clienttranslate('${player_name} discards ${card_class}${card_class_symbol}'), [
        'player' => $player,
        'card'  => $cards,
      ]);
    } else {
      $t = $cards->toArray();
      self::notifyAll('discardCards', clienttranslate('${player_name} discards ${card_class}${card_class_symbol} and ${card2_class}${card2_class_symbol}'), [
        'player' => $player,
        'card'  => $t[0],
        'card2'  => $t[1],
      ]);
    }
  }



  public static function clearTurn(){
    self::notifyAll('clearTurn', '', []);
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

    $names = [
      BLACKSMITH => clienttranslate('a blacksmith'),
      HUNTER => clienttranslate('a hunter'),
      EXPLORER => clienttranslate('an explorer'),
      MINER => clienttranslate('a miner'),
      WARRIOR => clienttranslate('a warrior'),
      ROYAL_OFFER => clienttranslate('a royal offering'),
      HERO => clienttranslate('a hero'),
    ];

    if(isset($args['card'])){
      $c = $args['card'];
      $args['card_class'] = $names[$c->getClass()];
      $args['card_class_symbol'] = $c->getRecruitementZone();// The substitution will be done in JS format_string_recursive function
      $args['card'] = $c->getUiData();
    }

    if(isset($args['card2'])){
      $c = $args['card2'];
      $args['card2_class'] = $names[$c->getClass()];
      $args['card2_class_symbol'] = $c->getRecruitementZone();// The substitution will be done in JS format_string_recursive function
      $args['card2'] = $c->getUiData();
    }
  }
}

?>
