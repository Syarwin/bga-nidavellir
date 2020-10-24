<?php
namespace NID\Helpers;

/*
 * This is a generic class to manage game pieces.
 *
 * On DB side this is based on a standard table with the following fields:
 * %prefix_%key (string), %prefix_%location (string), %prefix_%state (int)
 *
 *
 * CREATE TABLE IF NOT EXISTS `token` (
 * `token_key` varchar(32) NOT NULL,
 * `token_location` varchar(32) NOT NULL,
 * `token_state` int(10),
 * PRIMARY KEY (`token_key`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 * CREATE TABLE IF NOT EXISTS `card` (
 * `card_key` varchar(32) NOT NULL,
 * `card_location` varchar(32) NOT NULL,
 * `card_state` int(10),
 * PRIMARY KEY (`card_key`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 */

class Pieces extends DB_Manager {
  protected static $table = null;
  protected static $cast = null;
  protected static $prefix = "piece_";
  protected static $autoremovePrefix = true;
  protected static $autoreshuffle = false; // If true, a new deck is automatically formed with a reshuffled discard as soon at is needed
  protected static $autoreshuffleListener = null; // Callback to a method called when an autoreshuffle occurs
    // autoreshuffleListener = array( 'obj' => object, 'method' => method_name )
    // If defined, tell the name of the deck and what is the corresponding discard (ex : "mydeck" => "mydiscard")
  protected static $autoreshuffleCustom = [];
  protected static $customFields = [];
  protected static $gIndex = [];



  /************************************
  *************************************
  ********* QUERY BUILDER *************
  *************************************
  ************************************/

  /****
   * Return the basic select query fetching basic fields and custom fields
   */
  final static function getSelectQuery() {
    $basic = ['key' => static::$prefix."key", 'location' => static::$prefix."location", 'state' => static::$prefix."state"];
    if(!self::$autoremovePrefix)
      $basic = array_values($basic);

    return self::DB()->select(array_merge($basic, self::$customFields));
  }

  final static function getUpdateQuery($keys = [], $location = null, $state = null){
    $data = [];
    if(!is_null($location))
      $data[static::$prefix.'location'] = $location;
    if(!is_null($state))
      $data[static::$prefix.'state'] = $state;

    if(!is_array($keys))
      $keys = [$keys];

    return self::DB()->update($data)->whereIn(static::$prefix.'key', $keys);
  }

  /****
   * Return a select query with a where condition
   */
  protected function addWhereClause(&$query, $key = null, $location = null, $state = null) {
    if(!is_null($key)){
      $whereOp = strpos($key, "%") !== false? "LIKE" : "=";
      $query = $query->where(static::$prefix."key", $whereOp, $key);
    }

    if(!is_null($location)){
      $whereOp = strpos($location, "%") !== false? "LIKE" : "=";
      $query = $query->where(static::$prefix."location", $whereOp, $location);
    }

    if(!is_null($state)){
      $query = $query->where(static::$prefix."state", $state);
    }

    return $query;
  }


  /****
   * Append the basic select query with a where clause
   */
  public static function getSelectWhere($key = null, $location = null, $state = null) {
    $query = self::getSelectQuery();
    self::addWhereClause($query, $key, $location, $state);
    return $query;
  }



  /************************************
  *************************************
  ********* SANITY CHECKS *************
  *************************************
  ************************************/

  /*
   * Check that the location only contains alphanum and underscore character
   *  -> if the location is an array, implode it using underscores
   */
  final static function checkLocation(&$location, $like = false) {
    if (is_null($location))
      throw new \BgaVisibleSystemException("Class Pieces: location cannot be null");

    if(is_array($location))
      $location = implode("_", $location);

    $extra = $like? "%" : "";
    if (preg_match("/^[A-Za-z${extra}][A-Za-z_0-9${extra}-]*$/", $location) == 0)
      throw new \BgaVisibleSystemException("Class Pieces: location must be alphanum and underscore non empty string '$location'");
  }

  /*
   * Check that the key is alphanum and underscore
   */
  final static function checkKey(&$key, $like = false) {
    if (is_null($key))
      throw new \BgaVisibleSystemException("Class Pieces: key cannot be null");

    $extra = $like? "%" : "";
    if (preg_match("/^[A-Za-z_0-9${extra}]+$/", $key) == 0)
      throw new \BgaVisibleSystemException("Class Pieces: key must be alphanum and underscore non empty string '$key'");
  }

  final function checkKeyArray($arr) {
    if (is_null($arr))
      throw new \BgaVisibleSystemException("Class Pieces: tokens cannot be null");

    if (!is_array($arr)){
      throw new \BgaVisibleSystemException("Class Pieces: tokens must be an array");
      foreach ($arr as $key) {
        self::checkKey($key);
      }
    }
  }



  /*
   * Check that the state is an integer
   */
  final static function checkState($state, $canBeNull = false) {
    if (is_null($state) && !$canBeNull)
      throw new \BgaVisibleSystemException("Class Pieces: state cannot be null");

    if (!is_null($state) && preg_match("/^-*[0-9]+$/", $state) == 0)
      throw new \BgaVisibleSystemException("Class Pieces: state must be integer number");
  }


  /*
   * Check that a given variable is a positive integer
   */
  final static function checkPosInt($n) {
    if ($n && preg_match("/^[0-9]+$/", $n) == 0) {
      throw new \BgaVisibleSystemException("Class Pieces: number of pieces must be integer number");
    }
  }




  /************************************
  *************************************
  ************** GETTERS **************
  *************************************
  ************************************/

  /**
   * Get specific piece by key
   */
  public static function get($keys) {
    if(!is_array($keys))
      $keys = [$keys];

    self::checkKeyArray($keys);
    if (empty($keys))
        return [];

    $result = self::getSelectQuery()->whereIn(static::$prefix."key", $keys)->get();
    if (count($result) != count($keys))
      throw new \BgaVisibleSystemException("Class Pieces: getMany, some pieces have not been found !");

    return $result;
  }


  /**
   * Get specific piece by key
   */
  public static function getState($key) {
    $res = self::get($key);
    return is_null($res)? null : $res[(static::$autoremovePrefix? '' : static::$prefix).'state'];
  }


  public static function getLocation($key) {
    $res = self::get($key);
    return is_null($res)? null : $res[(static::$autoremovePrefix? '' : static::$prefix).'location'];
  }



  /**
   * Get max or min state of the specific location
   */
  public static function getExtremePosition($getMax, $location, $key = null) {
    $whereOp = self::checkLocation($location, true);
    $query = self::DB();
    self::addWhereClause($query, $key, $location);
    return $query->func($getMax? "MAX" : "MIN", static::$prefix.'state') ?? 0;
  }

  /**
   * Return "$nbr" piece on top of this location, top defined as item with higher state value
   */
  public static function getTopOf($location, $n = 1) {
    self::checkLocation($location);
    self::checkPosInt($nbr);
    return self::getSelectWhere(null, $location)->limit($n)->get();
  }



  /**
   * Return all pieces in specific location
   * note: if "order by" is used, result object is NOT indexed by ids
   */
  public static function getInLocation($location, $state = null, $orderBy = null) {
    self::checkLocation($location, true);
    self::checkState($state, true);

    $query = self::getSelectWhere(null, $location, $state);
    if(!is_null($orderBy)){
      $query = $query->orderBy($orderBy);
    }

    return $query->get();
  }

  /**
   * Return number of pieces in specific location
   */
  public static function countInLocation($location, $state = null) {
    self::checkLocation($location, true);
    self::checkState($state, true);
    return self::getSelectWhere(null, $location, $state)->count();
  }




  /************************************
  *************************************
  ************** SETTERS **************
  *************************************
  ************************************/
  public static function setState($key, $state) {
    self::checkState($state);
    self::checkKey($key);
    return self::getUpdateQuery($key, null, $state)->run();
  }


  /*
   * Move one (or many) pieces to given location
   */
  public static function move($keys, $location, $state = 0) {
    if(!is_array($keys))
      $keys = [$keys];

    self::checkLocation($location);
    self::checkState($state);
    self::checkKeyArray($keys);
    return self::getUpdateQuery($keys, $location, $state)->run();
  }


  /*
   *  Move all tokens from a location to another
   *  !!! state is reset to 0 or specified value !!!
   *  if "fromLocation" and "fromState" are null: move ALL cards to specific location
   */
  public static function moveAllInLocation($fromLocation, $toLocation, $fromState = null, $toState = 0) {
    if (!is_null($fromLocation != null))
      self::checkLocation($fromLocation);
    self::checkLocation($toLocation);

    $query = self::getUpdateQuery(null, $toLocation, $toState);
    self::addWhereClause($query, null, $fromLocation, $fromState);
    return $query->run();
  }

  /**
   * Move all pieces from a location to another location arg stays with the same value
   */
  public static function moveAllInLocationKeepState($fromLocation, $toLocation) {
    self::checkLocation($from_location);
    self::checkLocation($to_location);
    return self::moveAllInLocation($fromLocation, $toLocation, null, null);
  }



  /*
   * Pick the first "$nbr" pieces on top of specified deck and place it in target location
   * Return pieces infos or void array if no card in the specified location
   */
  public static function pickForLocation($nbr, $fromLocation, $toLocation, $state = 0, $deckReform = true) {
    $pieces = self::getTopOf($fromLocation, $nbr);
    $keys = array_map(function($piece){ return $piece[(static::$autoremovePrefix? '' : static::$prefix).'key']; }, $pieces);
    self::getUpdateQuery($keys, $toLocation, $state);

    // No more pieces in deck & reshuffle is active => form another deck
    if (isset(static::$autoreshuffleCustom[$fromLocation]) && count($pieces) < $nbr && static::$autoreshuffle && $deckReform){
      $missing = $nbr - count($tokens);
      self::reformDeckFromDiscard($fromLocation);
      $pieces = array_merge($pieces, self::pickForLocation($missing, $fromLocation, $toLocation, $state, false)); // Note: block anothr deck reform
    }

    return $pieces;
  }


  /*
   * Reform a location from another location when enmpty
   */
  public static function reformDeckFromDiscard($fromLocation) {
    self::checkLocation($fromLocation);
    if (!array_key_exists($fromLocation, static::$autoreshuffleCustom))
      throw new \BgaVisibleSystemException("Class Pieces:reformDeckFromDiscard: Unknown discard location for $fromLocation !");

    $discard = static::autoreshuffleCustom[$fromLocation];
    self::checkLocation($discard);
    self::moveAllInLocation($discard, $fromLocation);
    self::shuffle($fromLocation);
    if (static::$autoreshuffleListener) {
      $obj = static::$autoreshuffleListener['obj'];
      $method = static::$autoreshuffleListener['method'];
      $obj->$method($fromLocation);
    }
  }


  /*
   * Shuffle pieces of a specified location, result of the operation will changes state of the piece to be a position after shuffling
   */
  public static function shuffle($location) {
    self::checkLocation($location);
    $pieces = self::getInLocation($location);
    shuffle($pieces);
    foreach($pieces as $state => $piece){
      $key = $piece[(static::$autoremovePrefix? '' : static::$prefix).'key'];
      self::getUpdateQuery($key, null, $state)->run();
    }
  }


  // Move a card to a specific location where card are ordered. If location_arg place is already taken, increment
  // all tokens after location_arg in order to insert new card at this precise location
  public static function insertAt($key, $location, $state = 0) {
    self::checkLocation($location);
    self::checkState($state);
    $p = static::$prefix;
    self::DB()->inc([$p.'state' => 1])->where($p.'location', $location)->where($p.'state', '>=', $state)->run();
    self::move($key, $location, $state);
  }

  public static function insertOnTop($key, $location) {
    $pos = self::getExtremePosition(true, $location);
    self::insertAt($key, $location, $pos + 1);
  }

  public static function insertAtBottom($key, $location) {
    $pos = self::getExtremePosition(false, $location);
    self::insertAt($key, $location, $pos - 1);
  }



  /************************************
  ******** CREATE NEW PIECES **********
  ************************************/

  /* This inserts new records in the database.
   * Generically speaking you should only be calling during setup
   *  with some rare exceptions.
   *
   * Pieces is an array with at least the following fields:
   * [
   *   [
   *     "key" => <unique key>    // This unique alphanum and underscore key, use {INDEX} to replace with index if 'nbr' > 1, i..e "meeple_{INDEX}_red"
   *     "nbr" => <nbr>           // Number of tokens with this key, optional default is 1. If nbr >1 and key does not have {INDEX} it will throw an exception
   *     "nbrStart" => <nbr>           // Optional, if the indexing does not start at 0
   *     "location" => <location>       // Optional argument specifies the location, alphanum and underscore
   *     "state" => <state>             // Optional argument specifies integer state, if not specified and $token_state_global is not specified auto-increment is used
   */

  function create($pieces, $globalLocation = null, $globalState = null, $globalKey = null) {
    $pos = is_null($globalLocation)? 0 : (self::getExtremePosition(true, $globalLocation) + 1);

// TODO custom fields

    $values = [];
    $keys = [];
    foreach ($pieces as $info){
      $n = $info['nbr'] ?? 1;
      $start = $info['nbrStart'] ?? 0;
      $key = $info['key'] ?? $globalKey;
      $location = $info['location'] ?? $globalLocation;
      $state = $info['state'] ?? $globalState;
      if(is_null($state)){
        $state = ($location == $globalLocation)? $pos++ : 0;
      }

      // SANITY
      if (is_null($key))
        throw new \BgaVisibleSystemException("Class Pieces: create: key cannot be null");

      if (is_null($location))
        throw new \BgaVisibleSystemException("Class Pieces : create location cannot be null (set per token location or location_global");
      self::checkLocation($location);


      for ($i = $start; $i < $n + $start; $i++) {
        $nKey = preg_replace("/\{INDEX\}/", $key == $globalKey? count($keys) : $i, $key);
        self::checkKey($nKey);
        $values[] = [$nKey, $location, $state];
        $keys [] = $nKey;
      }
    }

    $p = static::$prefix;
    self::DB()->multipleInsert([$p."key", $p."location", $p."state"])->values($values);
    return $keys;
  }

  /*
   * Create a single token
   */
  function singleCreate($key, $location, $state = null) {
    self::checkState($state);
    return self::create(['key' => $key, 'location' => $location, 'state' => $state]);
  }
}
