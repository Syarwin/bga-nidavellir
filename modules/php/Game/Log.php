<?php
namespace NID\Game;
use Nidavellir;

/*
 * Log: a class that allows to log some actions
 *   and then fetch these actions latter
 */
class Log extends \NID\Helpers\DB_Manager
{
  protected static $table = 'log';
  protected static $primary = 'log_id';
  protected static $associative = false;
  protected static function cast($row)
  {
    return [
      'id' => (int) $row['log_id'],
      'pId' => $row['player_id'],
      'turn' => (int) $row['turn'],
      'action' => $row['action'],
      'arg' => json_decode($row['action_arg'], true),
    ];
  }

  /*
   * Utils : where filter with player and current turn
   */
  private function getFilteredQuery($pId){
    return self::DB()->where('player_id', $pId)->where('turn', Globals::getTurn() )->orderBy("log_id", "DESC");
  }

////////////////////////////////
////////////////////////////////
//////////   Adders   //////////
////////////////////////////////
////////////////////////////////

  /*
   * insert: add a new log entry
   * params:
   *   - mixed $player : either the id or an object of the player who is making the action
   *   - string $action : the name of the action
   *   - array $args : action arguments
   */
  public static function insert($player, $action, $args = [])
  {
    $pId = (is_integer($player) || is_null($player))? $player : $player->getId();
    $turn = Globals::getTurn();
    $actionArgs = json_encode($args);
    self::DB()->insert([
      'turn' => $turn,
      'player_id' => $pId ?? -1,
      'action' => $action,
      'action_arg' => $actionArgs,
    ]);
  }

  public static function storeOrder($order, $ties)
  {
    self::insert(null, "turnOrder", [ 'order' => $order, 'ties' => $ties]);
  }


  public static function storeEnlistOrder($order)
  {
    self::insert(null, "enlistOrder", [ 'order' => $order ]);
  }


/////////////////////////////////
/////////////////////////////////
//////////   Getters   //////////
/////////////////////////////////
/////////////////////////////////
  public static function getLastActions($pId)
  {
    return self::getFilteredQuery($pId)->get();
  }

  public static function getLastAction($action, $pId, $limit = 1)
  {
    return self::getFilteredQuery($pId)->where('action', $action)->limit($limit)->get($limit == 1);
  }

  public static function getLastActionArg($action, $pId, $limit = 1)
  {
    return self::getLastAction($action, $pId, $limit)['arg'] ?? [];
  }



  public static function getTurnOrder()
  {
    return self::getLastActionArg('turnOrder', -1)['order'] ?? null;
  }

  public static function getEnlistOrder()
  {
    return self::getLastActionArg('enlistOrder', -1)['order'];
  }

  public static function getTies()
  {
    return self::getLastActionArg('turnOrder', -1)['ties'];
  }

}
