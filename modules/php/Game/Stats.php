<?php
namespace NID\Game;
use Nidavellir;
use NID\Game\Players;

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
    $stats = Nidavellir::get()->getStatTypes();

//    self::init('table', 'turns_number');

    foreach ($stats['player'] as $key => $value) {
      if($value['id'] > 10 && $value['type'] == 'int')
        self::init('player', $key);
    }
  }


  public function updateScores($scores){
    foreach($scores as $pId => $score){
      // Scores
      $assoc = [
        "score_coins" => 'coins',
        "score_blacksmith" => BLACKSMITH,
        "score_hunter" => HUNTER,
        "score_explorer" => EXPLORER,
        "score_miner" => MINER,
        "score_warrior" => WARRIOR,
        "score_neutral" => NEUTRAL
      ];

      foreach($assoc as $statName => $scoreName){
        $scoreVal = $score[$scoreName];
        self::set($scoreVal, $statName, $pId);
      }
    }
  }

  public function addTie($bidders){
    foreach($bidders as $player){
      self::inc("player_ties", $player);
    }
  }

  public function upgradeCoin($player){
    self::inc("coins_upgrades", $player);
  }

  public function recruitHero($player){
    self::inc("player_heroes", $player);
  }
}

?>
