<?php

class db {

	var $link;
	var $errors = array();
	var $debug = false;
	var $count;
	
	function db() {
	}

	function connect() {
		$link = mysql_connect(DBSERVER,DBUSER,DBPASS);
		if(!$link) {
			$this->setError("Couldn't connect to database server");
			return false;
		}
		if(!mysql_select_db(DBNAME,$link)) {
			$this->setError("Couldn't select database: ".DBNAME);
			return false;
		}
		$this->link = $link;
		return true;
	}

	function getError() {
		return $this->errors[count($this->errors)-1];
	}

	function setError($str) {
		array_push($this->errors,$str);
	}

	function _query($query) {
		if(!$this->link) {
			$this->setError("no active db connection");
			return false;
		}
		$result = mysql_query($query,$this->link);
		if(!$result)
			$this->setError("error: ".mysql_error());
		return $result;
	}

	function setQuery($query) {
		if(!$result = $this->_query($query)) 
			return false;
		return mysql_affected_rows($this->link);
		$this->close();
	}

	function getQuery($query) {
		if(!$result = $this->_query($query))
			return false;
		$ret = array();
		while($row = mysql_fetch_assoc($result))
			$ret[] = $row;
		return $ret;
	}

	function select($what, $table, $condition=" ", $sort=" ") {

		if( is_array( $table ) ) {

			foreach( $table as $key=>$is ) $tb[] = DBPRE.$is;

			$table = implode( ", " , $tb );

		} else $table = DBPRE.$table;
		
		$query =  "SELECT $what FROM $table";
		if($condition != " ")
		$query .= $this->_makeWhereList($condition);
		if($sort != " ")
			$query .= " $sort";
		$this->debug($query);
		$this->count++;
		return $this->getQuery($query,$error);
	}

	function insert($table, $add_array) {
		$table = DBPRE . $table;
		$this->count++;
		$add_array = $this->_quote_vals($add_array);
		$keys = "(".implode(array_keys($add_array),",").")";
		$values = "values(".implode(array_values($add_array),",").")";
		$query = "INSERT INTO $table $keys $values";
		$this->debug($query);
		return $this->setQuery($query);
	}

	function update($table, $update_array, $condition = " ") {
		$table = DBPRE . $table;
		$this->count++;		
		$update_pairs=array();
		foreach($update_array as $field=>$val)
			array_push($update_pairs, "$field=".$this->_quote_val($val));
		$query = "UPDATE $table set ";
		$query .= implode( ", ", $update_pairs);
		$query .= $this->_makeWhereList($condition);
		$this->debug($query);
		return $this->setQuery($query); 
	}

	function delete($table, $condition = " ") {
		$table = DBPRE . $table;
		$this->count++;		
		$query = "DELETE FROM $table";
		$query .= $this->_makeWhereList($condition);
		$this->debug($query);
		return $this->setQuery($query,$error);
	}

	function field($table, $condition = " ") {
		
		
		$query = "ALTER TABLE DBPRE.$table $condition";
		$this->debug($query);
		return $this->setQuery($query,$error);
	}


	function debug($msg) {
		if($this->debug)
			print "<i>$msg</i><br>";
	}

	function _makeWhereList($condition) {
		if(empty($condition)) 
			return " ";
		$retstr = " WHERE ";
		if(is_array($condition)) {
			$cond_pairs=array();
			foreach($condition as $field=>$val)
				array_push($cond_pairs, "$field=".$this->_quote_val($val));
			$retstr .= implode( " AND ", $cond_pairs);
		} elseif (is_string($condition) && !empty($condition))
			$retstr .= $condition;
		return $retstr;
	}

	function _makeSearchList($condition) {
		if(empty($condition)) 
			return " ";
		$retstr = " WHERE ";
		if(is_array($condition)) {
			$cond_pairs=array();
			foreach($condition as $field=>$val)
				array_push($cond_pairs, "$field LIKE ".$this->_quote_s_val($val));
			$retstr .= implode( " AND ", $cond_pairs);
		} elseif (is_string($condition) && !empty($condition))
			$retstr .= $condition;
		return $retstr;
	}

	function _quote_val($val) {
		if (is_numeric($val))
			return $val;
		return "'".addslashes($val)."'";
	}

	function _quote_s_val($val) {
		if (is_numeric($val))
			return "%".$val."%";

		return "'".addslashes($val)."'";
	}

	function _quote_vals($array) {
		foreach($array as $key=>$val)
			$ret[$key]=$this->_quote_val($val);
		return $ret;
	}

}

?>