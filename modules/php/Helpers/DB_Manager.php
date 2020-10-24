<?php
namespace NID\Helpers;

class DB_Manager {
	protected static $table = null;
  protected static $cast = null;

  public static function DB($table = null){
    if(is_null($table)){
      if(is_null(static::$table)){
        throw new \feException("You must specify the table you want to do the query on");
      }
      $table = static::$table;
    }

    return new QueryBuilder($table, static::$cast);
  }
}
