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
  protected static function init($name, $value)
  {
    Nidavellir::get()->setGameStateInitialValue($name, $value);
  }

  protected static function set($name, $value)
  {
    Nidavellir::get()->setGameStateValue($name, $value);
  }

  public static function get($name)
  {
    return Nidavellir::get()->getGameStateValue($name);
  }

  protected static function inc($name, $value = 1)
  {
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
    'campVisited' => 0,
    'brisingamens' => 0,
    'captureTokens' => 0,
  ];

  public static function declare($game)
  {
    // Game options label
    $labels = [
      'thingvellir' => OPTION_THINGVELLIR,
      'idavoll' => OPTION_IDAVOLL,
    ];

    // Add globals with indexes starting at 10
    $id = 10;
    foreach (self::$globals as $name => $initValue) {
      $labels[$name] = $id++;
    }
    $game->initGameStateLabels($labels);
  }

  /*
   * Init
   */
  public static function setupNewGame()
  {
    foreach (self::$globals as $name => $initValue) {
      self::init($name, $initValue);
    }
  }

  /*
   * Getters
   */
  public function isThingvellir()
  {
    return self::get('thingvellir') == THINGVELLIR;
  }

  public function isIdavoll()
  {
    return self::get('idavoll') == IDAVOLL;
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

  public function wasCampVisited()
  {
    return self::get('campVisited') == 1;
  }

  /*
   * Setters
   */

  public function startNewAge()
  {
    self::set('currentTurn', 0);
    return self::inc('currentAge');
  }

  public function setEndOfGame()
  {
    self::set('currentAge', 3);
  }

  public function startNewTurn()
  {
    self::inc('currentTurn');
    self::set('currentTavern', -1);
  }

  public function incTavern()
  {
    self::set('campVisited', 0);
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

  public function visitCamp()
  {
    self::set('campVisited', 1);
  }

  public function incBrisingamens()
  {
    return self::inc('brisingamens', 1);
  }

  /*
   * Giant tokens
   */
  public function getCaptureTokens()
  {
    $t = (int) self::get('captureTokens');
    $bin = [false];
    for ($i = 1; $i <= 5; $i++) {
      $bin[$i] = $t % 2 == 1;
      $t = intdiv($t, 2);
    }

    return $bin;
  }

  public function setCaptureToken($class, $value)
  {
    $t = self::getCaptureTokens();
    $t[$class] = $value;
    $n = $t[1] + 2 * $t[2] + 4 * $t[3] + 8 * $t[4] + 16 * $t[5];
    self::set('captureTokens', $n);
  }
}
