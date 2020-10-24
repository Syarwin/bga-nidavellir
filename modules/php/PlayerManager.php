<?php
namespace NID;
use Nidavellir;

/*
 * PlayerManager: all utility functions concerning players
 */

class PlayerManager extends DB_Manager
{
	protected static $table = "player";
	protected static $cast = "NID\Player";

	public static function setupNewGame($players)	{
		$gameInfos = Nidavellir::get()->getGameinfos();

		$query = self::DB()->multipleInsert(['player_id', 'player_color', 'player_canal', 'player_name', 'player_avatar']);
		$values = [];
		$i = 0;
		foreach ($players as $pId => $player) {
			$color = $gameInfos['player_colors'][$i];
			$canal = $player['player_canal'];
			$name = $player['player_name'];
			$avatar = addslashes($player['player_avatar']);
			$name = addslashes($player['player_name']);
			$values[] = [$pId, $color, $canal, $name, $avatar];
		}
		$query->values($values);
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
	 * getPlayers : Returns array of players for all/specified player IDs
	 */
	public static function getPlayers($playerIds = null) {
		return self::DB()->whereIn($playerIds)->get();
	}

	/*
	 * getUiData : get all ui data of all players
	 */
	public static function getUiData()	{
		$ui = [];
		foreach(self::getPlayers() as $player){
			$ui[$player->getId()] = $player->getUiData();
		}
		return $ui;
	}


//  public static function updateUi(){
//    Nidavellir::get()->notifyAllPlayers("updatePlayersInfo", '', ['players' => self::getUiData() ]);
//  }


	public static function getUlineOwner(){
		return null; // TODO
	}
}
