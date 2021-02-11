<?php
namespace NID\Game;
use Nidavellir;

class Stats
{
  protected static function init($type, $name, $value = 0){
    Nidavellir::get()->initStat($type, $name, $value);
  }

  public static function inc($name, $player = null, $value = 1, $log = true){
    $pId = is_null($player)? null : ( ($player instanceof \NID\Player)? $player->getId() : $player );
    Nidavellir::get()->incStat($value, $name, $pId);
  }


  protected static function get($name, $player = null){
    Nidavellir::get()->getStat($name, $player);
  }

  protected static function set($value, $name, $player = null){
    $pId = is_null($player)? null : ( ($player instanceof \NID\Player)? $player->getId() : $player );
    Nidavellir::get()->setStat($value, $name, $pId);
  }


  public static function setupNewGame(){
    /*
    TODO

    $stats = thecrew::get()->getStatTypes();

    self::init('table', 'turns_number');
    self::init('table', 'ending', 0);

    foreach ($stats['player'] as $key => $value) {
      if($value['id'] > 10 && $value['type'] == 'int' && $key != 'empty_slots_number')
        self::init('player', $key);
    }
    self::init('player', "empty_slots_number", 33);
    */
  }
}

?>
