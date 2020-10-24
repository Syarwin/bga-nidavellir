<?php
namespace NID;
use Nidavellir;

class Log extends \APP_DbObject
{
////////////////////////////////
////////////////////////////////
//////////   Adders   //////////
////////////////////////////////
////////////////////////////////

  /*
   * insert: add a new log entry
   * params:
   *   - $playerId: the player who is making the action
   *   - $dominoId : the piece whose is making the action
   *   - string $action : the name of the action
   *   - array $args : action arguments (eg space)
   */
  public function insert($playerId, $dominoId, $action, $args = [])
  {
    // TODO
    $playerId = $playerId == -1 ? $this->game->getActivePlayerId() : $playerId;
    $moveId = self::getUniqueValueFromDB("SELECT `global_value` FROM `global` WHERE `global_id` = 3");
    $round = $this->game->getGameStateValue("currentRound");
    $actionArgs = json_encode($args);

    self::DbQuery("INSERT INTO log (`round`, `move_id`, `player_id`, `domino_id`, `action`, `action_arg`) VALUES ('$round', '$moveId', '$playerId', '$dominoId', '$action', '$actionArgs')");
  }


  public function addTie($bidders)
  {
    // TODO
    // $this->insert(-1, $piece['id'], 'movePiece', $args);
  }


/////////////////////////////////
/////////////////////////////////
//////////   Getters   //////////
/////////////////////////////////
/////////////////////////////////

  /*
   * getLastActions : get works and actions of player (used to cancel previous action)
   */
  public function getLastActions($actions = ['applyLaw', 'movePiece'], $pId = null, $offset = null)
  {
    $pId = $pId ?? $this->game->getActivePlayerId();
    $offset = $offset ?? 0;
    $actionsNames = "'" . implode("','", $actions) . "'";

    return self::getObjectListFromDb("SELECT * FROM log WHERE `action` IN ($actionsNames) AND `player_id` = '$pId' AND `round` = (SELECT round FROM log WHERE `player_id` = $pId AND `action` = 'startTurn' ORDER BY log_id DESC LIMIT 1) - $offset ORDER BY log_id DESC");
  }


  public function getLastAction($action, $pId = null, $offset = null)
  {
    $actions = $this->getLastActions([$action], $pId, $offset);
    return count($actions) > 0 ? json_decode($actions[0]['action_arg'], true) : null;
  }


  public function getLastMove()
  {
    return $this->getLastAction('movePiece');
  }

  public function getLastLaw()
  {
    return $this->getLastAction('applyLaw');
  }


////////////////////////////////
////////////////////////////////
//////////   Cancel   //////////
////////////////////////////////
////////////////////////////////
  /*
   * cancelTurn: cancel the last actions of active player of current turn
   */
  public function cancelTurn()
  {
    $pId = $this->game->getActivePlayerId();
    $logs = self::getObjectListFromDb("SELECT * FROM log WHERE `player_id` = '$pId' AND `round` = (SELECT round FROM log WHERE `player_id` = $pId AND `action` = 'startTurn' ORDER BY log_id DESC LIMIT 1) ORDER BY log_id DESC");

    $ids = [];
    $moveIds = [];
    foreach ($logs as $log) {
      $args = json_decode($log['action_arg'], true);

      if($log['action'] == 'startTurn'){
        for ($x = 0; $x < 4; $x++) {
        for ($y = 0; $y < 4; $y++) {
          self::DbQuery("UPDATE board SET piece = '{$args["board"][$x][$y]}' WHERE x = $x AND y = $y");
        }}

        foreach($args['dominos'] as $domino){
          self::DbQuery("UPDATE domino SET location = '{$domino["location"]}', type = '{$domino["type"]}', cause00 = {$domino["cause00"]}, cause01 = {$domino["cause01"]}, cause10 = {$domino["cause10"]}, cause11 = {$domino["cause11"]},".
           "effect00 = {$domino["effect00"]}, effect01 = {$domino["effect01"]}, effect10 = {$domino["effect10"]}, effect11 = {$domino["effect11"]} WHERE id = {$domino["id"]}");
        }
      }

      // Undo statistics
      if (array_key_exists('stats', $args)) {
        $this->incrementStats($args['stats'], -1);
      }

      $ids[] = intval($log['log_id']);
      if ($log['action'] != 'startTurn') {
        $moveIds[] = array_key_exists('move_id', $log)? intval($log['move_id']) : 0; // TODO remove the array_key_exists
      }
    }

    // Remove the logs
    self::DbQuery("DELETE FROM log WHERE `player_id` = '$pId' AND `log_id` IN (" . implode(',', $ids) . ")");

    // Cancel the game notifications
    self::DbQuery("UPDATE gamelog SET `cancel` = 1 WHERE `gamelog_move_id` IN (" . implode(',', $moveIds) . ")");
    return $moveIds;
  }

  /*
   * getCancelMoveIds : get all cancelled move IDs from BGA gamelog, used for styling the notifications on page reload
   */
  public function getCancelMoveIds()
  {
    $moveIds = self::getObjectListFromDb("SELECT `gamelog_move_id` FROM gamelog WHERE `cancel` = 1 ORDER BY 1", true);
    return array_map('intval', $moveIds);
  }
}

?>
