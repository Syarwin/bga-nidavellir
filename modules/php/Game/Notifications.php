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
      'age' => Globals::getAge(),
      'turn' => Globals::getTurn(),
      'tavern' => -1,
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

  public static function revealUlineBid($coin, $tavern){
    self::notifyAll('revealUlineBid', clienttranslate('Revealing Uline\'s bid for current tavern'), [
      'coin' => $coin,
      'tavern' => $tavern,
    ]);
  }


  public static function newOrder($order){
    $msg = [
      '', '',
      clienttranslate('Recruiting of current tavern will take place in following order : ${player_name1} then ${player_name2}'),
      clienttranslate('Recruiting of current tavern will take place in following order : ${player_name1}, ${player_name2} then ${player_name3}'),
      clienttranslate('Recruiting of current tavern will take place in following order : ${player_name1}, ${player_name2}, ${player_name3} then ${player_name4}'),
      clienttranslate('Recruiting of current tavern will take place in following order : ${player_name1}, ${player_name2}, ${player_name3}, ${player_name4} then ${player_name5}'),
    ];
    $data = [
      'order' => $order,
    ];
    $players = Players::getAll();
    foreach($order as $i => $pId)
      $data['player_name' . ($i + 1)] = $players[$pId]->getName();

    self::notifyAll('recruitOrder', $msg[count($order)], $data);
  }



  public static function recruitStart($player, $order){
    self::notifyAll('recruitStart', clienttranslate('${player_name} is choosing in position ${order}'), [
      'player' => $player,
      'order'  => $order,
    ]);
  }

  public static function recruit($player, $card){
    $type = $card->getClass() == HERO? 'recruitHero' : 'recruit';
    self::notifyAll($type, clienttranslate('${player_name} recruits ${card_class}${card_class_symbol}'), [
      'player' => $player,
      'card'  => $card,
      'line' => $player->getHeroLine(),
    ]);
  }


  public static function ZolkurEffect($player, $card){
    self::notifyAll('recruit', clienttranslate('Zolkur\'s effect allows ${player_name} to trade its lower coin'), [
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

  public static function discardHofud($player, $card, $warriors){
    self::notifyAll('discardHofud', clienttranslate('${player_name} discards ${card_class}${card_class_symbol} for Hofud\'s effect'), [
      'player' => $player,
      'card'  => $card,
      'warriors' => array_map(function($card){ return $card->getId(); }, $warriors),
    ]);
  }



  public static function clearTavern($tavern){
    self::notifyAll('clearTavern', '', [
      'tavern' => $tavern,
    ]);
  }

  public static function clearCamp(){
    self::notifyAll('clearCamp', '', []);
  }

  public static function clearTurn(){
    self::notifyAll('clearTurn', '', []);
  }


  public static function distinctionTie($distinction, $maxRank){
    self::notifyAll('distinctionTie', clienttranslate('${n} players are tied to the majority of ${card_class}${card_class_symbol} hence no one get the distinction card'), [
      'n' => count($maxRank),
      'card' => $distinction,
    ]);
  }


  public static function distinction($player, $card){
    self::notifyAll('distinction', clienttranslate('${player_name} has the strict majority of ${card_class}${card_class_symbol} and wins the corresponding distinction'), [
      'player' => $player,
      'card'  => $card,
    ]);
  }


  public static function distinctionGem($player){
    self::notifyAll('distinctionGem', clienttranslate('${player_name} get the special value gem 6'), [
      'player' => $player,
      'gem' => $player->getGem(),
    ]);
  }


  public static function distinctionCoin($player, $oldCoin, $newCoin){
    self::notifyAll('tradeCoin', clienttranslate('${player_name} trades its ${coin_max}${coin_max_type} with the special ${coin_new}${coin_new_type}'), [
      'player' => $player,
      'coin_max' => $oldCoin['value'],
      'coin_max_type' => $oldCoin['type'],
      'coin_new' => $newCoin['value'],
      'coin_new_type' => $newCoin['type'],

      'max' => $oldCoin,
      'new' => $newCoin,
    ]);
  }


  public static function discardTradingCoin($player, $tradingCoin){
    self::notifyAll('discardCoin', clienttranslate('${player_name} discard its ${coin_max}${coin_max_type} (Jarnglofi\'s effect)'), [
      'player' => $player,
      'coin_max' => $tradingCoin['value'],
      'coin_max_type' => $tradingCoin['type'],
      'max' => $tradingCoin,
    ]);
  }



  public static function discardCardExplorerDistinction($card){
    self::notifyAll('discardCards', clienttranslate('A ${card_class}${card_class_symbol} is discarded from the age 2 deck'), [
      'card' => $card,
    ]);
  }


  public static function ulineRecruited($player, $movedCoins){
    self::notify($player->getId(), 'ulineRecruited', '', [
      'coins' => $movedCoins,
    ]);
  }


  public static function changeColumn($player, $card, $silent){
    $msg = [
      THRUD => clienttranslate('${player_name} moves Thrud'),
      YLUD => clienttranslate('${player_name} moves Ylud'),
      OLWYN_DOUBLE1 => clienttranslate('${player_name} places first Olwyn\'s double'),
      OLWYN_DOUBLE2 => clienttranslate('${player_name} places second Olwyn\'s double'),
    ];

    $msg = $silent? '' : $msg[$card->getId()];
    self::notifyAll('recruit', $msg, [
      'player' => $player,
      'card'  => $card,
    ]);
  }



  public static function updateScores($scores, $ranks){
    self::notifyAll('updateScores', '', [
      'scores' => $scores,
      'ranks' => $ranks,
    ]);
  }



  /**********************
  ***** THINGVELLIR *****
  **********************/
  public static function newEnlistOrder($order){
    $msg = [
      '', '', '',
      clienttranslate('Enlisting of mercenaries will take place in following order : ${player_name1} then ${player_name2}'),
      clienttranslate('Enlisting of mercenaries will take place in following order : ${player_name1}, ${player_name2} then ${player_name3}'),
      clienttranslate('Enlisting of mercenaries will take place in following order : ${player_name1}, ${player_name2}, ${player_name3} then ${player_name4}'),
      clienttranslate('Enlisting of mercenaries will take place in following order : ${player_name1}, ${player_name2}, ${player_name3}, ${player_name4} then ${player_name5}'),
    ];
    $data = [
      'order' => $order,
    ];
    $players = Players::getAll();
    foreach($order as $i => $pId)
      $data['player_name' . ($i + 1)] = $players[$pId]->getName();

    self::notifyAll('enlistOrder', $msg[count($order)], $data);
  }


  /**********************
  ******* IDAVOLL *******
  **********************/

  public static function denyCapture($player, $card, $giant){
    self::notifyAll('denyCapture', clienttranslate('${player_name} does not capture the dwarf and lose the giant\'s effect'), [
      'player' => $player,
      'card'  => $card,
      'giant' => $giant,
    ]);
  }


  public static function capture($player, $giant, $card){
    self::notifyAll('capture', clienttranslate('${player_name} captures ${card_class}${card_class_symbol} and trigger ${giant_name}\'s effect'), [
      'player' => $player,
      'card'  => $card,
      'giant' => $giant->getUiData(),
      'giant_name' => $giant->getName(),
    ]);
  }


  public static function useAsePower($player, $ase){
    self::notifyAll('useAsePower', clienttranslate('${player_name} uses ${ase_name}\'s effect'), [
      'player' => $player,
      'ase_name' => $ase->getName(),
      'aseId' => $ase->getId(),
    ]);
  }


  public static function increaseForce($player, $valkyrie, $force){
    self::notifyAll('increaseForce', clienttranslate('${player_name} increases ${valkyrie_name}\'s force'), [
      'player' => $player,
      'valkyrie_name' => $valkyrie->getName(),
      'valkyrieId' => $valkyrie->getId(),
      'force' => $force,
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


    if(isset($args['card'])){
      $args['i18n'][] = 'card_class';
      $c = $args['card'];
      $args['card_class'] = $c->getNotifString();
      $args['card_class_symbol'] = $c->getNotifSymbol();// The substitution will be done in JS format_string_recursive function
      $args['card'] = $c->getUiData();
    }

    if(isset($args['card2'])){
      $args['i18n'][] = 'card2_class';
      $c = $args['card2'];
      $args['card2_class'] = $c->getNotifString();
      $args['card2_class_symbol'] = $c->getNotifSymbol();// The substitution will be done in JS format_string_recursive function
      $args['card2'] = $c->getUiData();
    }
  }
}

?>
