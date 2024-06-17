<?php

namespace NID\Game;

use Nidavellir;

/*
 * Stack: a class that allows to have some memory about states
 */

class Stack
{
  private static function getGame()
  {
    return Nidavellir::get();
  }

  private static $stack = null;
  private static $lId = null;

  protected static function init()
  {
    Log::insert(-1, 'stackEngine', ['stack' => []]);
  }

  protected static function fetch()
  {
    $action = Log::getLastAction('stackEngine', -1);
    if ($action == null) {
      self::init();
      self::fetch();
    } else {
      self::$lId = $action['id'];
      self::$stack = $action['arg']['stack'];
    }
  }

  protected static function get()
  {
    if (self::$stack == null)
      self::fetch();

    return self::$stack;
  }

  protected static function save()
  {
    Log::DB()->update([
      'action_arg' => json_encode(['stack' => self::$stack], true),
    ], self::$lId);
  }


  public static function push($transition)
  {
    if (self::$stack == null)
      self::fetch();

    $sourceState = self::getGame()->gamestate->states[self::getGame()->gamestate->state_id()];
    $newState = $sourceState['transitions'][$transition];
    array_push(self::$stack, $newState);
    self::save();
  }

  public static function pop()
  {
    if (self::$stack == null)
      self::fetch();

    $elem = array_pop(self::$stack);
    self::save();
    return $elem;
  }


  public static function nextState($transition, $newState)
  {
    if ($newState != null) {
      self::push($transition);
    } else {
      $newState = $transition;
    }
    // Classic transition
    self::getGame()->gamestate->nextState($newState);
  }

  public static function resolve()
  {
    $state = self::pop();
    if ($state == null)
      throw new \feException("Stack engine is empty !");

    self::getGame()->gamestate->jumpToState($state);
  }
}
