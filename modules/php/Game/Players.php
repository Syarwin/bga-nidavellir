<?php
namespace NID\Game;
use Nidavellir;
use NID\Cards;

/*
 * Players manager : allows to easily access players ...
 *  a player is an instance of Player class
 */
class Players extends \NID\Helpers\DB_Manager
{
  protected static $table = 'player';
  protected static $primary = 'player_id';
  protected static function cast($row)
  {
    return new \NID\Player($row);
  }


  public function setupNewGame($players, $isAsync)
  {
    // Create players
    self::DB()->delete();

    $gameInfos = Nidavellir::get()->getGameinfos();
    $colors = $gameInfos['player_colors'];
    $query = self::DB()->multipleInsert(['player_id', 'player_color', 'player_canal', 'player_name', 'player_avatar', 'player_score', 'player_gem', 'player_autopick']);

    $gems = [1,2,3,4,5];
    array_splice($gems, 0, 5 - count($players));
    shuffle($gems);
    $values = [];
    foreach ($players as $pId => $player) {
      $color = array_shift($colors);
      $values[] = [ $pId, $color, $player['player_canal'], $player['player_name'], $player['player_avatar'], 19, array_shift($gems), $isAsync? 1 : 0];
    }
    $query->values($values);
    Nidavellir::get()->reattributeColorsBasedOnPreferences($players, $gameInfos['player_colors']);
    Nidavellir::get()->reloadPlayersBasicInfos();


    if(false){
      $pId = array_keys($players)[0];
      Cards::addClass($pId, BLACKSMITH);
      Cards::addClass($pId, BLACKSMITH);
      Cards::addClass($pId, BLACKSMITH);
      Cards::addClass($pId, BLACKSMITH);
      Cards::addClass($pId, BLACKSMITH);
      Cards::addClass($pId, BLACKSMITH);
      Cards::addClass($pId, BLACKSMITH);
//      Cards::addClass($pId, HUNTER);
//      Cards::addClass($pId, EXPLORER);
//      Cards::addClass($pId, MINER);
//      Cards::addClass($pId, WARRIOR);
    }
  }



  public function getActiveId()
  {
    return Nidavellir::get()->getActivePlayerId();
  }

  public function getCurrentId()
  {
    return Nidavellir::get()->getCurrentPId();
  }

  public function getAll(){
    return self::DB()->get(false);
  }

  /*
   * get : returns the Player object for the given player ID
   */
  public function get($pId = null)
  {
    $pId = $pId ?: self::getActiveId();
    return self::DB()->where($pId)->getSingle();
  }

  public function getActive()
  {
    return self::get();
  }

  public function getCurrent()
  {
    return self::get(self::getCurrentId());
  }

  public function getNextId($player)
  {
    $table = Nidavellir::get()->getNextPlayerTable();
    return $table[$player->getId()];
  }


  public function getMaxWarriorRank()
  {
    $maxWarrior = 0;
    foreach(Players::getAll() as $player){
      $maxWarrior = max($maxWarrior, $player->getRanks()[WARRIOR]);
    }
    return $maxWarrior;
  }

  /*
   * Return the number of players
   */
  public function count()
  {
    return self::DB()->count();
  }


  /*
   * getUiData : get all ui data of all players : id, no, name, team, color, powers list, farmers
   */
  public function getUiData($pId)
  {
    return self::getAll()->assocMap(function($player) use ($pId){ return $player->getUiData($pId); });
  }


  /*
   * Trade gems: given a list of trade (player1, player2), proceeds to the exchange of gems
   */
  public function tradeGems($trades)
  {
    foreach($trades as $trade){
      // $trade = [p1_id, p1_gem, p2_id, p2_gem]
      self::DB()->update(['player_gem' => $trade[3]], $trade[0]);
      self::DB()->update(['player_gem' => $trade[1]], $trade[2]);
    }
  }


  /*
   * Update scores UI
   */
  public function updateScores()
  {
    $scores = [];
    $ranks = [];
    foreach(self::getAll() as $pId => $player){
      $scores[$pId] = $player->getScores();
      $ranks[$pId] = $player->getRanks();
      self::DB()->update(['player_score' => $scores[$pId]['total']], $pId);
    }

    Notifications::updateScores($scores, $ranks);
  }
}
