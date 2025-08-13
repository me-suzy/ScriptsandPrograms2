<?php
/*
V1.31 20 August 2001 (c) 2000, 2001 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence.
  Set tabs to 4
*/ 

if (! defined("_ADODB_PEAR_LAYER")) {
 define("_ADODB_PEAR_LAYER", 1 );
 
 include('DB.php');

class ADODB_pear extends ADODBConnection {
	var $databaseDriver = 'mysql';
	var $databaseType = 'pear';
    var $hasInsertID = true;
    var $hasAffectedRows = true;	
	var $metaTablesSQL = "SHOW TABLES";	
	var $metaColumnsSQL = "SHOW COLUMNS FROM %s";
	var $fmtTimeStamp = "'Y-m-d H:i:s'";
	var $hasLimit = false;
	var $hasMoveFirst = false;
	var $_errorNo = 0;
	
	function ADODB_pear() 
	{			
	}
	
		// returns true or false
	function _connect($argHostname, $argUsername, $argPassword, $argDatabasename)
	{
		$dsn = "mysql://$argUsername:$argPassword@$argHostname/$argDatabasename";
		$this->_connectionID = &DB::connect($dsn,false);
		if (DB::isError($this->_connectionID)) {
			//$err  = $this->_connectionID->getDebugInfo();
			// getDebugInfo returns the database password - idiots!
			$this->_errorMsg = $this->_connectionID->getMessage()." ($err)";
			$this->_errorNo = (integer) $this->_connectionID->getCode();
			return false;
		}
		return true;
	}
	
	// returns true or false
	function _pconnect($argHostname, $argUsername, $argPassword, $argDatabasename)
	{
		$dsn = "mysql://$argUsername:$argPassword@$argHostname/$argDatabasename";
		$this->_connectionID = &DB::pconnect($dsn,true);
		if (DB::isError($this->_connectionID)) {
			//$err  = $this->_connectionID->getDebugInfo();
			// getDebugInfo returns the database password - idiots!
			$this->_errorMsg = $this->_connectionID->getMessage()." ($err)";
			$this->_errorNo = (integer) $this->_connectionID->getCode();
			return false;
		}
		return true;	
	}
	
	function BeginTrans()
	{
		$this->_connectionID->autoCommit(false);
	}
	 
	function CommitTrans()
	{
		$rez = $this->_connectionID->commit();
		$this->_connectionID->autoCommit(true);
		return $rez == DB_OK;
	}
	
	function RollbackTrans()
	{
		$rez = $this->_connectionID->rollback();
		$this->_connectionID->autoCommit(true);
		return $rez == DB_OK;
	}
	
    function _insertid()
    {
    	return $this->_connectionID->nextId('seq',false);
    }
        
    function _affectedrows()
    {
    	return $this->_connectionID->affectedRows();
   	}
  
  	function &MetaDatabases()
	{
		return array();
	}
	

 	function &MetaColumns($table) 
	{
		return false;
	}
	
	function qstr($s)
	{
		return $this->_connectionID->quoteString($s);
	}
	
	// returns queryID or false
	function &_query($sql,$inputarr)
	{
		$this->_errorNo = 0;
		if ($inputarr) {
			$obj = $this->_connectionID->prepare($sql);
			if (!DB::isError($obj))
				$obj = $obj->execute($sql,$inputarr);
		} else
			$obj = $this->_connectionID->query($sql);
			
		if (DB::isError($obj)) {
			$err  = $obj->getDebugInfo();
			$this->_errorMsg = $obj->getMessage() . " ($err)";
			$this->_errorNo = (integer) $obj->getCode();
			return false;
		}
		return $obj;
	}

	/*	Returns: the last error message from previous database operation	*/	
	function ErrorMsg() 
	{
	    return $this->_errorMsg;
	}
	
	/*	Returns: the last error number from previous database operation	*/	
	function ErrorNo() 
	{
		return $this->_errorNo;
	}
	
	// returns true or false
	function _close()
	{
		return $this->_connectionID->disconnect();
	}
		
}
	
/*--------------------------------------------------------------------------------------
	 Class Name: Recordset
--------------------------------------------------------------------------------------*/

class ADORecordSet_pear extends ADORecordSet{	
	
	var $databaseType = "pear";
	var $canSeek = false;
	var $tableInfo;
	
	function ADORecordSet_mysql($queryID) {
		$this->ADORecordSet($queryID);
	}
	
	function _initrs()
	{
	GLOBAL $ADODB_COUNTRECS;
		$this->_numOfRows = ($ADODB_COUNTRECS) ? $this->_queryID->numRows():-1;
		if (DB::isError($this->_numOfRows)) $this->_numOfRows = -1;
		
		$this->_numOfFields = $this->_queryID->numCols();
	}
	
	function &FetchField($fieldOffset = -1) {
	
		if (!isset($this->tableInfo)) {
			$this->tableInfo = &$this->_queryID->tableInfo();
		}
		if ($fieldOffset != -1) {
			$a = $this->tableInfo;
			$o = new ADODBFieldObject;
			$o->name = $a[$fieldOffset]['name'];
			$o->type = $a[$fieldOffset]['type'];
			$o->max_length = $a[$fieldOffset]['len'];
		}
		//print_r($o);
		return $o;
	}

	function _seek($row)
	{
		return false;
	}
	
	
	function _fetch($ignore_fields=false)
	{
		$this->fields = $this->_queryID->fetchRow();
		//print_r($this->fields);
		return (is_array($this->fields));
	}
	
	function _close() {
		$this->_queryID->free();		
	}


}
}
?>