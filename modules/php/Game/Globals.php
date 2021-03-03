<?php
namespace NID\Game;
use Nidavellir;
//use \CREW\Cards;

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
   * Declare globals (in the constructor of game.php)
   */
  private static $globals = [
    'currentAge' => 0,
    'currentTurn' => 0,
    'currentTavern' => -1,
    'currentPlayerIndex' => -1,
    'transformValue' => 0,
    'currentDistinction' => 0,
    'sourceState' => 0,
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
  public function isExpansion()
  {
   return false;
  }


  public function getAge()
  {
    return (int) self::get('currentAge');
  }

  public function getTurn()
  {
    return (int) self::get('currentTurn');
  }

  public function getTavern()
  {
    return (int) self::get('currentTavern');
  }

  public function getCurrentPlayerIndex()
  {
    return (int) self::get('currentPlayerIndex');
  }

  public function getTransformValue()
  {
    return (int) self::get('transformValue');
  }

  public function getSourceState()
  {
    return (int) self::get('sourceState');
  }


  /*
   * Setters
   */

  public function startNewAge()
  {
    self::set('currentTurn', 0);
    return self::inc('currentAge');
  }

  public function startNewTurn()
  {
    self::inc('currentTurn');
    self::set('currentTavern', -1);
  }

  public function incTavern()
  {
    return self::inc('currentTavern', 1);
  }


  public function incCurrentPlayerIndex($step = 1)
  {
    return self::inc('currentPlayerIndex', $step);
  }

  public function setCurrentPlayerIndex($value)
  {
    self::set('currentPlayerIndex', $value);
  }

  public function resetCurrentPlayerIndex()
  {
    self::setCurrentPlayerIndex(-1);
  }

  public function setTransformValue($val)
  {
    self::set('transformValue', $val);
  }

  public function setCurrentDistinction($val)
  {
    self::set('currentDistinction', $val);
  }

  public function nextDistinction()
  {
    return self::inc('currentDistinction', 1);
  }

  public function setSourceState($state)
  {
    self::set('sourceState', $state);
  }
}
