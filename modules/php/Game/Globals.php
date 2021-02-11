<?php
namespace NID\Game;
use Nidavellir;
use \CREW\Cards;

/*
 * Globals
 */
class Globals extends \APP_DbObject
{
  /* Exposing methods from Table object singleton instance */
  protected static function init($name, $value){
    Nidavellir::get()->setGameStateInitialValue($name, $value);
  }

  protected static function set($name, $value){
    Nidavellir::get()->setGameStateValue($name, $value);
  }

  public static function get($name){
    return Nidavellir::get()->getGameStateValue($name);
  }

  protected static function inc($name, $value = 1){
    return Nidavellir::get()->incGameStateValue($name, $value);
  }


  /*
   * Declare globas (in the constructor of game.php)
   */
  private static $globals = [
    'currentAge' => 0,
    'currentTurn' => 0,
    'currentTavern' => 0,
  ];

  public static function declare($game){
    // Game options label
    $labels = [
//      "startingMission" => OPTION_MISSION,
//      "challenge" => OPTION_CHALLENGE,
    ];

    // Add globals with indexes starting at 10
    $id = 10;
    foreach(self::$globals as $name => $initValue){
      $labels[$name] = $id++;
    }
    $game->initGameStateLabels($labels);
  }

  /*
   * Init
   */
  public static function setupNewGame(){
    foreach(self::$globals as $name => $initValue){
      self::init($name, $initValue);
    }
  }

  /*
   * Getters
   */
  public function getAge()
  {
    return (int) self::get('currentAge');
  }

  public function getTurn()
  {
    return (int) self::get('currentTurn');
  }
  

  /*
   * Setters
   */

  public function startNewAge()
  {
    self::inc('currentAge');
    self::set('currentTurn', 0);
  }

  public function startNewTurn()
  {
    self::inc('currentTurn');
    self::set('currentTavern', 0);
  }

}
