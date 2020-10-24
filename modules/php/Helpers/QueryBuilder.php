<?php
namespace NID\Helpers;

class QueryBuilder extends \APP_DbObject {
	private $table, $cast, $columns, $sql, $bindValues,
	$where, $orWhere, $whereCount=0, $isOrWhere = false, $limit, $orderBy;


	public function __construct($table, $cast)
	{
    $this->table = $table;
		$this->cast = $cast;
    $this->columns = null;
    $this->sql = null;
    $this->bindValues = [];
    $this->limit = null;
    $this->orderBy = null;
    $this->where = null;
    $this->orWhere = null;
    $this->whereCount = 0;
    $this->isOrWhere = false;
	}


  private function interpolateQuery() {
    $keys = [];

    # build a regular expression for each parameter
    foreach ($this->bindValues as $key => &$value) {
      if (is_string($key)) {
        $keys[] = '/:'.$key.'/';
      } else {
        $keys[] = '/[?]/';
      }
      // TODO protect value
    }

    $query = preg_replace($keys, $this->bindValues, $this->sql, 1, $count);
    return $query;
  }


  public function run($id = null)
  {
    if (isset($id)) {
      $this->computeWhereClause($id);
    }
    $this->sql .= $this->where;
    self::DbQuery($this->interpolateQuery());
    return self::DbAffectedRow();
  }


  protected function computeWhereClause($id)
  {
		if ($this->whereCount == 0) {
			$this->where .= " WHERE ";
		}
		$this->whereCount++;

    // if there is an ID
    if (is_numeric($id)) {
      $this->sql .= " `id` = ?";
      $this->bindValues[] = $id;
    // if there is an Array
    } elseif (is_array($id)) {
      $arr = $id;
      $count_arr = count($arr);

      foreach ($arr as  $param) {
        if($this->whereCount > 1) {
          $this->where .= $this->isOrWhere? " OR " : " AND ";
        }
				$this->whereCount++;
        $count_param = count($param);

        if ($count_param == 1) {
          $this->where .= "`id` = '?'";
          $this->bindValues[] =  addslashes($param[0]);
        } elseif ($count_param == 2) {
          $operators = explode(',', "=,>,<,>=,>=,<>");
          $operatorFound = false;

          foreach ($operators as $operator) {
            if ( strpos($param[0], $operator) !== false ) {
              $operatorFound = true;
              break;
            }
          }

          if ($operatorFound) {
            $this->where .= $param[0]." ?";
          }else{
            $this->where .= "`".trim($param[0])."` = '?'";
          }

          $this->bindValues[] =  $param[1];
        }elseif ($count_param == 3) {
          $this->where .= "`".trim($param[0]). "` ". $param[1]. " '?'";
          $this->bindValues[] = addslashes($param[2]);
        }
      }
    }
  }


	public function delete($id=null)
	{
		$this->sql = "DELETE FROM `{$this->table}`";
    return isset($id)? $this->run($id) : $this;
	}


	public function update($fields = [], $id = null)
	{
		$values = [];
		foreach ($fields as $column => $field) {
			$values[] = "`$column` = '$field'";
		}

		$this->sql = "UPDATE `{$this->table}` SET " . implode(',', $values);
    return isset($id)? $this->run($id) : $this;
	}

	public function inc($fields = [], $id = null)
	{
		$values = [];
		foreach ($fields as $column => $field) {
			$values[] = "`$column` = `$column` + $field";
			$this->bindValues[] = $field;
		}

		$this->sql = "UPDATE `{$this->table}` SET " . implode(',', $values);
    return isset($id)? $this->run($id) : $this;
	}


	public function insert($fields = [] )
	{
		$keys = implode('`, `', array_keys($fields));
		$values = '';
		$x=1;
		foreach ($fields as $field => $value) {
			$values .='?';
			$this->bindValues[] =  $value;
			if ($x < count($fields)) {
				$values .=', ';
			}
			$x++;
		}

		$this->sql = "INSERT INTO `{$this->table}` (`{$keys}`) VALUES ({$values})";
    self::DbQuery($this->interpolateQuery());
		return self::DbGetLastId();
	}


	public function multipleInsert($fields = [])
	{
		$keys = implode('`, `', array_values($fields));
		$this->sql = "INSERT INTO `{$this->table}` (`{$keys}`) VALUES";
		return $this;
	}

	public function values($rows = [])
	{
		$vals = [];
		foreach($rows as $row){
			 $vals[] = "('". implode("','", array_map('addslashes', $row)) ."')";
		}

		$this->sql .= implode(',', $vals);
		self::DbQuery($this->sql);
		return self::DbAffectedRow();
	}


	public function select($columns)
	{
		$cols = [];

		if(!is_array($columns)){
			$cols = [$columns];
		} else {
			// Assoc array
			if(array_diff_key($columns,array_keys(array_keys($columns)))){
				foreach($columns as $alias => $col)
					$cols[] = "$col AS `$alias`";
			} else {
				foreach($columns as $col)
					$cols[] = "$col";
			}
		}

		$this->columns = implode(' , ', $cols);
		return $this;
	}

	public function where()
	{
		$this->isOrWhere = false;
		$num_args = func_num_args();
		$args = func_get_args();
		$this->computeWhereClause($num_args == 1? $args[0] : [$args]);
		return $this;
	}


	public function whereIn()
	{
    if ($this->whereCount == 0) {
      $this->where .= " WHERE ";
    } else {
      $this->where .= $this->isOrWhere? " OR " : " AND ";
    }
    $this->whereCount++;

		$num_args = func_num_args();
		$args = func_get_args();
		$field = ($num_args == 1)? 'id' : $args[0];
		$values = ($num_args == 1)? $args[0] : $args[1];
		if(is_null($values))
			return $this;

		$this->where .= "`$field` IN ('". implode("','", $values) ."')";
		return $this;
	}

	public function orWhere()
	{
		$this->isOrWhere = true;
		$num_args = func_num_args();
		$args = func_get_args();
    $this->computeWhereClause($num_args == 1? $args[0] : [$args]);
		return $this;
	}


	public function get($returnValueIfOnlyOneRow = true)
	{
		$this->assembleQuery();
    $res = self::getObjectListFromDB($this->interpolateQuery());
		$oRes = [];
		foreach($res as $row){
			array_push($oRes, is_null($this->cast)? $row : ($this->cast == "object"? ((object) $row) : new $this->cast($row)));
		}

		if($returnValueIfOnlyOneRow && count($oRes) <= 1)
			return count($oRes) == 1? $oRes[0] : null;
		else
			return $oRes;
	}

	private function assembleQuery()
	{
		if ( $this->columns !== null ) {
			$select = $this->columns;
		} else {
			$select = "*";
		}

		$this->sql = "SELECT $select FROM `$this->table`";
    $this->assembleQueryClause();
	}

	private function assembleQueryClause()
	{
		if ($this->where !== null) {
			$this->sql .= $this->where;
		}

		if ($this->orderBy !== null) {
			$this->sql .= $this->orderBy;
		}

		if ($this->limit !== null) {
			$this->sql .= $this->limit;
		}
	}


	public function limit($limit, $offset=null)
	{
		if ($offset ==null ) {
			$this->limit = " LIMIT {$limit}";
		}else{
			$this->limit = " LIMIT {$limit} OFFSET {$offset}";
		}

		return $this;
	}


	public function orderBy()
	{
		$num_args = func_num_args();
		$args = func_get_args();

		$field_name = '';
		$order = 'ASC';
		if($num_args == 1){
			if(is_array($args[0])){
				$field_name = trim($args[0][0]);
				$order = trim(strtoupper($args[0][1]));
			} else {
				$field_name = trim($args[0]);
			}
		} else {
			$field_name = trim($args[0]);
			$order =  trim(strtoupper($args[1]));
		}

		// validate it's not empty and have a proper valuse
		if ($field_name !== null && ($order == 'ASC' || $order == 'DESC')) {
			if ($this->orderBy ==null ) {
				$this->orderBy = " ORDER BY $field_name $order";
			} else {
				$this->orderBy .= ", $field_name $order";
			}
		}

		return $this;
	}

	/*
	 * ONLY for unary function : COUNT, MAX, MIN
	 */
	public function func($func, $field = null)
	{
		$field = is_null($field)? "*" : "`$field`";

		$this->sql = "SELECT $func($field) FROM `$this->table`";
		$this->assembleQueryClause();
		return (int) self::getUniqueValueFromDB($this->interpolateQuery());
	}


	public function count($field = null)
	{
    return self::func('COUNT', $field);
	}

	public function min($field)
	{
		return self::func('MIN', $field);
	}

	public function max($field)
	{
		return self::func('MAX', $field);
	}
}
