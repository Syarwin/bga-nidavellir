<?php
namespace NID\Game;
use Nidavellir;

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


  public function setupNewGame($players)
  {
    // Create players
    self::DB()->delete();

    $gameInfos = Nidavellir::get()->getGameinfos();
    $colors = $gameInfos['player_colors'];
    $query = self::DB()->multipleInsert(['player_id', 'player_color', 'player_canal', 'player_name', 'player_avatar', 'player_score']);
    $values = [];
    foreach ($players as $pId => $player) {
      $color = array_shift($colors);
      $values[] = [ $pId, $color, $player['player_canal'], $player['player_name'], $player['player_avatar'], 1];
    }
    $query->values($values);
    Nidavellir::get()->reattributeColorsBasedOnPreferences($players, $gameInfos['player_colors']);
    Nidavellir::get()->reloadPlayersBasicInfos();
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

  public function getUlineOwner()
  {
    return null; // TODO
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
}
