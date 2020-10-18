<?php
namespace NID;
use Nidavellir;

/*
 * PlayerManager: all utility functions concerning players
 */

class PlayerManager extends \APP_DbObject
{
	public static function setupNewGame($players)	{
		self::DbQuery('DELETE FROM player');
		$gameInfos = Nidavellir::get()->getGameinfos();
		$sql = 'INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ';

		$values = [];
		$i = 0;
		foreach ($players as $pId => $player) {
			$color = $gameInfos['player_colors'][$i];
			$canal = $player['player_canal'];
			$name = $player['player_name'];
			$avatar = addslashes($player['player_avatar']);
			$name = addslashes($player['player_name']);
			$values[] = "($pId, '$color','$canal','$name','$avatar')";
		}
		self::DbQuery($sql . implode($values, ','));
    Nidavellir::get()->reattributeColorsBasedOnPreferences($players, $gameInfos['player_colors'] );
		Nidavellir::get()->reloadPlayersBasicInfos();
	}


	/*
	 * getPlayer : returns the BangPlayer object for the given player ID
	 */
	public static function getPlayer($playerId)	{
		$bplayers = self::getPlayers([$playerId]);
		return $bplayers[0];
	}

	/*
	 * getPlayers : Returns array of SantoriniPlayer for all/specified player IDs
	 * if $asArrayCollection is set to true it return the result as a map $id=>array
	 */
	public static function getPlayers($playerIds = null, $asArrayCollection = false) {
		$columns = ["id", "no", "name", "color", "eliminated", "score", "zombie"];
		$sqlcolumns = [];
		foreach($columns as $col) $sqlcolumns[] = "player_$col $col";
		$sql = "SELECT " . implode(", ", $sqlcolumns) . " FROM player" ;
		if (is_array($playerIds)) {
			$sql .= " WHERE player_id IN ('" . implode("','", $playerIds) . "')";
		}

		if($asArrayCollection) return self::getCollectionFromDB($sql);
		else return self::getObjectListFromDB($sql);
	}

	/*
	 * getUiData : get all ui data of all players
	 */
	public static function getUiData()	{
		return self::getPlayers(null, true);
	}


//  public static function updateUi(){
//    Nidavellir::get()->notifyAllPlayers("updatePlayersInfo", '', ['players' => self::getUiData() ]);
//  }
}
