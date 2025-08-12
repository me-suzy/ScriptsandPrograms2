<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


/** Objects of this class are used for generating and executing database queries.
* This class is the database abstraction layer.
* Currently only mySQL is supported by PntQueryHandler, in future other 
* superclasses may support more databases.   
* @see http://www.phppeanuts.org/site/index_php/Pagina/50
*
* This abstract superclass provides behavior for the concrete
* subclass Comparator in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @package pnt/db
*/
class PntQueryHandler {
	var $query = '';
	var $aantalRecords=0; //assigned in _runQuery
	var $aantalVelden=0;  //assigned in getFieldNames()
	var $result;		  //assigned in _runQuery
	var $insertId;        //assigned in _runQuery
	var $error;           // de errorMessage of null, assigned in _runQuery
	// we did not factor out the default error message because it is not meant for the end users anyway
	
	function PntQueryHandler($query=null)
	{
		$this->query = $query;
	}
	
	function runQuery($query='', $error="Query error") 
	{
		if ($query)
			$this->query = $query;
		$this->_runQuery($error);
		if ($this->error)
			trigger_error($this->error, E_USER_ERROR);
	}

	function _runQuery($error="Query error") 
	{
		global $queryCount;
		$queryCount++;
		$this->error = null;
//print "<BR>\n $queryCount $this->query";
		$this->result =& mysql_query($this->query);
		if ($this->result) {
			if (strtolower(substr(trim($this->query),0,6))=="select") {			
				$this->aantalRecords = mysql_num_rows($this->result);
			} else {
				if (strtolower(substr(trim($this->query),0,11))=="insert into") {			
					$this->insertId = mysql_insert_id();
				}
			}
		} else {
			$this->error = $error."<BR>$this->query<BR>".mysql_error();
//			print $this->error;
		}
	}
	
	/* Return the field names.
	* this function resets record pointer
	*/
	function getFieldNames() {
			
		if ($this->aantalRecords>0) {
			$row=mysql_fetch_assoc($this->result);
			$velden = array_keys($row);
			$this->aantalVelden=count($velden);
			//result weer terug op eerste record zetten
			$this->dataSeek(0);
			return $velden;
		}			
	}
	
	function getRowCount() {
		return $this->aantalRecords;	
	}
	
	function getColumnCount() {
		return $this->aantalVelden;	
	}

	function dataSeek($index)
	{
		mysql_data_seek($this->result,$index);
	}
	
	/** Return the error message or null if no error */
	function getError() {
		return $this->error;
	}
	
	function getSingleValue($query='', $error="Query error") {
		global $queryCount;
		$queryCount++;
		
		if ($query)
			$this->query = $query;
		$this->_runQuery($error);
		
		if ($this->error)
			trigger_error($this->error, E_USER_ERROR);

		if ($this->aantalRecords>0) {
			$row=mysql_fetch_row($this->result);
			//reset record pointer
			mysql_data_seek($this->result,0) ;
			return $row[0];	
		} 
		return null;
	}
	
	/** Return the id of the new record after an insert */
	function getInsertId() {
		return $this->insertId;	
	}
	
	/** Return an new array with prefixes added to the supplied columnNames 
	* separate prefix and columnname by the separarator appropriate 
	* for this database (usually a dot).
	* Retain the keys so that if the columnNames array is a fielMap, 
	* the result will map the fields to prefixed columnNames
	* @param $colNames Array with columnnames as the values
	* @param $prefix, usually the table name
	* @result Array with prefixed columnNames
	*/
	function prefixColumnNames(&$colNames, $prefix) {
		reset($colNames);
		while (list($key, $name) = each($colNames)) 
			$result[$key] = "$prefix.$name";
		return $result;
	}

	/** append a SQL string to the query field. Return the added SQL.
	* @param columnNames Array with columnNames. If the names need to be prefixed, they must already be prefixed.
	* @param tableName String May also hold a String with a Join of tablenames
	*/
	function select_from($columnNames, $tableName)
	{	
		$sql  = 'SELECT ';
		$sql .= implode(', ', $columnNames);
		$sql .= " FROM $tableName";
		
		$this->query .= $sql;
		
		return $sql;
	}
		
	/** append a SQL string to the query field. Return the added SQL.
	* @param string columnName The name of the column. If the name needs to be prefixed, it must already be prefixed.
	* @param mixed $value 
	*/
	function where_equals($columnName, $value)
	{
		$sql = " WHERE ($columnName = ";
		$sql .= $this->convertConditionArgumentToSql($value);
		$sql .= ')';
		
		$this->query .= $sql;
		
		return $sql;
	}
	
	/** append a SQL string to the query field. Return the added SQL.
	* @param number $rowCount The maximum number of rows to retrieve
	* @param number $offset The index of the first row to retrieve
	*/
	function limit($rowCount, $offset=0)
	{
		$sql = " LIMIT $offset, $rowCount";
		$this->query .= $sql;
		return $sql;
	}

	function joinAllById($tableMap, $baseTable)
	{
		if (count($tableMap) == 1) return '';
		$sql = '';
		reset($tableMap);
		while (list($table) = each($tableMap))
			if ($table != $baseTable)
				$sql .= "\n INNER JOIN $table ON $table.id = $baseTable.id";
		$this->query .= $sql;
		return $sql;
	}

	//NOT TESTED
	function in($columnName, $values)
	{
		$sql = " ($columnName IN (";
		reset($values);
		while (list($key, $value) = each($value))
			$sql .= $this->convertConditionArgumentToSql($value);
		$sql .= '))';

		$this->query .= $sql;

		return $sql;
	}

	function convertToSql($value) {
		//date and timestamp are actually represented as strings

		if (is_string($value))
			//allways use magic quotes in sql
			return "'".addSlashes($value)."'";
		else
			if ($value === null)
				return "NULL";  //Empty String does not activate defaults in MySql
			else 
				return "'$value'";
	}

	/* @depricated implementation is now equal to convertToSql */
	function convertConditionArgumentToSql($value) {
		//date and timestamp are actually represented as strings

		if (is_string($value))
			//allways use magic quotes in sql
			return "'".addSlashes($value)."'";
		else
			if ($value === null)
				return "NULL";
			else 
				return "'$value'";
	}


	/** Set the query field to a SQL string that saves the specified object field values in the database.
	* @param anObject Object whose field values need to be saved
	* @tableName String
	* @param fieldMap Associative Array mapping fieldName to columnName
	* @param insert wheather a record for the object should be inserted. If false the objects record will be updated
	*/
	function setQueryToSaveObject_table_fieldMap(&$anObject, $tableName, &$fieldMap, $insert)
	{
		// MySQL implementation

		$sql  = $insert ? 'insert into ' : 'update ';
		$sql .= $tableName;
		$sql .= ' SET ';

		$sep = "";
		reset($fieldMap);
		forEach($fieldMap as $field => $column) {
			//insert of a not-new object is assumed to be insert in secondary table
			if ($field != 'id' || ($insert && !$anObject->isNew()) ) {
				$sql .= $sep;
				$sql .= $column;
				$sql .= '=';
				$sql .= $this->convertToSql($anObject->$field);
				$sep = ', ';
			}
		}
		$this->query = $sql;
		if (!$insert)
			$this->where_equals(
				$fieldMap['id'] // the column name for field 'id'
				, $anObject->id
			);

	}

	/** Set the query field to a SQL string that saves the specified object field values in the database. 
	* @param anObject Object whose field values need to be saved
	* @tableName String
	* @param columnMap Array 
	*/
	function setQueryToDeleteFrom_where_equals($tableName, $columnName, $value)
	{
		// MySQL implementation
		$this->query = 'DELETE FROM '. $tableName;
		$this->where_equals($columnName, $value);
	}		

	/** Add both the join clauses, the WHERE clause and eventual ORDER BY clause
	* from the suppleid SQL spec.
	* @paran PntSqlSpec $spec Object that sepecifies the query and may generate the SQL
	*/
	function addSqlFromSpec($spec)
	{
		$this->query .= $spec->getSqlForJoin();
		$this->query .= ' WHERE ';
		$this->query .= $spec->getSql();
	}

	/** Gets rows starting at current pointer position 
	* Resets record pointer
	* @param number $max The maximum number of rows to get. If null all remaining rows are returned.
	* Returns an array of associative row arrays (indexed[rowIndex][rowName])
	*/
	function &getAssocRows($max=null)
	{
		$result = array();
		if ($this->aantalRecords>0) {
			$i = 0;
			while ( ($max === null || $i < $max) && ($row = mysql_fetch_assoc($this->result)) ) {
				$result[] = $row;
				$i++;
			}
			//reset record pointer
			mysql_data_seek($this->result,0) ;
		}
		return $result;						
	}
}
?>