<?php
namespace NID;
use Nidavellir;

class StatManager extends \APP_DbObject
{
  protected static function init($type, $name, $value = 0){
    Nidavellir::get()->initStat($type, $name, $value);
  }

  protected static function inc($name, $player = null, $value = 1){
    Nidavellir::get()->incStat($value, $name, $player);
  }

  protected static function get($name, $player = null){
    Nidavellir::get()->getStat($name, $player);
  }

  protected static function set($value, $name, $player = null){
    Nidavellir::get()->setStat($value, $name, $player);
  }


  public static function setupNewGame(){
    /*
    self::init('table', 'table_turns_number');
		self::init('table', 'table_largest_carpet_zone');
		self::init('table', 'table_highest_taxes_collected');
		self::init('player', 'player_turns_number');
		self::init('player', 'player_money_paid');
		self::init('player', 'player_money_earned');
		self::init('player', 'player_largest_carpet_zone');
		self::init('player', 'player_highest_taxes_collected');
    */
  }
}

?>
