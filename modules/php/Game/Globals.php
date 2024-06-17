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
    'thor' => 0,
    'loki' => 0,
    'olrun' => 0,
    'olwyn' => 0,
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
  public static function isThingvellir()
  {
    return self::get('thingvellir') == THINGVELLIR;
  }

  public static function isIdavoll()
  {
    return self::get('idavoll') == IDAVOLL;
  }

  public static function getAge()
  {
    return (int) self::get('currentAge');
  }

  public static function getTurn()
  {
    return (int) self::get('currentTurn');
  }

  public static function getTavern()
  {
    return (int) self::get('currentTavern');
  }

  public static function getCurrentPlayerIndex()
  {
    return (int) self::get('currentPlayerIndex');
  }

  public static function getTransformValue()
  {
    return (int) self::get('transformValue');
  }

  public static function getSourceState()
  {
    return (int) self::get('sourceState');
  }

  public static function wasCampVisited()
  {
    return self::get('campVisited') == 1;
  }

  public static function getThorCardId()
  {
    return self::get('thor');
  }

  public static function getLokiCardId()
  {
    return self::get('loki');
  }

  public static function getOlrunClass()
  {
    return (int) self::get('olrun');
  }

  public static function getOlwynLeft()
  {
    return self::get('olwyn');
  }

  /*
   * Setters
   */

  public static function startNewAge()
  {
    self::set('currentTurn', 0);
    return self::inc('currentAge');
  }

  public static function setEndOfGame()
  {
    self::set('currentAge', 3);
  }

  public static function startNewTurn()
  {
    self::inc('currentTurn');
    self::set('currentTavern', -1);
  }

  public static function incTavern()
  {
    self::set('campVisited', 0);
    return self::inc('currentTavern', 1);
  }

  public static function incCurrentPlayerIndex($step = 1)
  {
    return self::inc('currentPlayerIndex', $step);
  }

  public static function setCurrentPlayerIndex($value)
  {
    self::set('currentPlayerIndex', $value);
  }

  public static function resetCurrentPlayerIndex()
  {
    self::setCurrentPlayerIndex(-1);
  }

  public static function setTransformValue($val)
  {
    self::set('transformValue', $val);
  }

  public static function setCurrentDistinction($val)
  {
    self::set('currentDistinction', $val);
  }

  public static function nextDistinction()
  {
    return self::inc('currentDistinction', 1);
  }

  public static function setSourceState($state)
  {
    self::set('sourceState', $state);
  }

  public static function visitCamp()
  {
    self::set('campVisited', 1);
  }

  public static function incBrisingamens()
  {
    return self::inc('brisingamens', 1);
  }

  public static function setThor($cardId)
  {
    self::set('thor', $cardId);
  }

  public static function setLoki($cardId)
  {
    self::set('loki', $cardId);
  }

  public static function setOlrun($column)
  {
    self::set('olrun', $column);
  }

  public static function setOlwynLeft($val)
  {
    self::set('olwyn', $val);
  }
}
