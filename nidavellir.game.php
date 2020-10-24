<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nidavellir implementation : © Timothée Pecatte tim.pecatte@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * nidavellir.game.php
 *
 */


use NID\PlayerManager;
use NID\Cards;
use NID\Log;

$swdNamespaceAutoload = function ($class) {
   $classParts = explode('\\', $class);
   if ($classParts[0] == 'NID') {
       array_shift($classParts);
       $file = dirname(__FILE__) . "/modules/php/" . implode(DIRECTORY_SEPARATOR, $classParts) . ".php";
       if (file_exists($file)) {
           require_once($file);
       }
   }
};
spl_autoload_register($swdNamespaceAutoload, true, true);

require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class Nidavellir extends Table
{
	use NID\States\BidsTrait;
	use NID\States\RecruitTrait;

	public static $instance = null;
	public function __construct() {
		parent::__construct();
		self::$instance = $this;

		self::initGameStateLabels([
      'currentTavern' => CURRENT_TAVERN, // TODO init and reset
      'currentPlayerIndex' => CURRENT_PLAYER_INDEX, // TODO init
    ]);
//		 'optionTeam' => OPTION_TEAM_SIZE,
//		 'optionHint' => OPTION_HINT_MODE,
	}
	public static function get(){
	 return self::$instance;
	}

	protected function getGameName( )
	{
		return "nidavellir";
	}

	/*
	 * setupNewGame:
   */
	protected function setupNewGame( $players, $options = [] ){
		PlayerManager::setupNewGame($players);
    Cards::setupNewGame($players);
	}


  public function stStartOfTurn(){
    $this->gamestate->nextState('');
  }

	/*
	 * getAllDatas:
	 */
	protected function getAllDatas(){
		return [
			'players' => NID\PlayerManager::getUiData(),
      'cards' => NID\Cards::getInLocation(['age',"%"]),
		];
	}

	/*
	 * getGameProgression:
	 */
	function getGameProgression(){
		return 50; // TODO
	}


	////////////////////////////////////
	////////////   Zombie   ////////////
	////////////////////////////////////
	/*
	 * zombieTurn:
	 *   This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
	 *   You can do whatever you want in order to make sure the turn of this player ends appropriately
	 */
	public function zombieTurn($state, $activePlayer) {
		if (array_key_exists('zombiePass', $state['transitions'])) {
			$this->gamestate->nextState('zombiePass');
		} else {
			throw new BgaVisibleSystemException('Zombie player ' . $activePlayer . ' stuck in unexpected state ' . $state['name']);
		}
	}

	/////////////////////////////////////
	//////////   DB upgrade   ///////////
	/////////////////////////////////////
	// You don't have to care about this until your game has been published on BGA.
	// Once your game is on BGA, this method is called everytime the system detects a game running with your old Database scheme.
	// In this case, if you change your Database scheme, you just have to apply the needed changes in order to
	//   update the game database and allow the game to continue to run with your new version.
	/////////////////////////////////////
	/*
	 * upgradeTableDb
	 *  - int $from_version : current version of this game database, in numerical form.
	 *      For example, if the game was running with a release of your game named "140430-1345", $from_version is equal to 1404301345
	 */
	public function upgradeTableDb($from_version) {
	}
}
