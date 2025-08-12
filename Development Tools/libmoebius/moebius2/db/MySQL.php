<?php
/*  
 * MySQL.php	
 * Copyright (C) 2003-2005, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages the mysql transactions layer.
 *
 * Author(s):
 *   Alejandro Espinoza <aespinoza@structum.com.mx>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; either version 2.1 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 */

import("moebius2.base.ObjectManager");
import("moebius2.base.Date");

/* --- Constants --- */
// Info Types (IT)
define("IT_CLIENT", 0);
define("IT_HOST",   1);
define("IT_PROTO",  2);
define("IT_SERVER", 3);
define("IT_STAT",   4);
define("IT_QUERY",  5);

// Mod Types (MT)
define("MT_CREATE", "CREATE");
define("MT_DROP",   "DROP");

/**
  * Class that manages the mysql transactions layer.
  *
  * @class		MySQL
  * @package	moebius2.data
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	2.3
  * @extends	ObjectManager
  * @requires	ObjectManager
  * @see		ObjectManager
  */
class MySQL
{
	/* ---Attibutes--- */
	var $host;
	var $database;
	var $user;
	var $passwd;

	//Results Array
	var $resultsArray;
	var $rowCount;
	var $colCount;
	
	//Conection States
	var $dataFetched;
	var $connected;
	var $connIsValid;

	//Conectioion Ids.
	var $connId;
	var $queryId;

	//Error Handling
	var $sqlErrorText;
	var $sqlErrorNum;
	var $lastError;

	/* --- Methods --- */	
	/**
	  * Constructor, initializes the class, and opens a connection if parameters set.
	  * @method		MySQL
	  * @param		optional string host
	  * @param		optional string database
	  * @param		optional string user
	  * @param		optional string passwd	  
	  * @returns	none.
	  */
	function MySQL($host="", $database="", $user="", $passwd="")
	{
		ObjectManager::ObjectManager("moebius2.data", "MySQL");
		
		//Default Values
		$this->dataFetched = false;
		$this->connected = false;
		$this->connIsValid = false;

		$this->resultsArray = array();
		$this->rowCount = 0;
		$this->colCount = 0;

		$this->connId = 0;
		$this->queryId = 0;

		$this->Open($host, $database, $user, $passwd);
	}

	/**
	  * Opens a conection to a mysql database.
	  *
	  * @method		Open
	  * @param		string host
	  * @param		string database
	  * @param		string user
	  * @param		string passwd
	  * @returns	true if the database connection was successfull, false otherwise.
	  */	
	function Open($host, $database, $user, $passwd)
	{
		if($this->Connect($host, $user, $passwd)) {
			if(!$this->SelectDb($database)) {
				$this->Error("Open", "Couldn't select database (".$database.")");
				$this->Close();
			}
		}

		return $this->connected;		
	}

	/**
	  * Makes a connection to a mysql server.
	  *
	  * NOTE: This method does not select a database for use. You can use either SelectDb after the Connect method or use directly
	  * the Open method.
	  *
	  * @method		Open
	  * @param		string host
	  * @param		string user
	  * @param		string passwd
	  * @returns	true if connection was successfull, false otherwise.
	  */	
	function Connect($host, $user, $passwd)
	{
		if(empty($host) || empty($user)) {
			$this->connected = false;
		} else {
			$this->connId = mysql_connect($host, $user, $passwd);

			if($this->connId) {
				$this->host = $host;
				$this->user = $user;
				$this->passwd = $passwd;

				$this->connected = true;
			}
		}

		$this->connIsValid = true;
		
		return $this->connected;
	}

	/**
	  * Closes any mysql connections open.
	  *
	  * @method		Close
	  * @returns	true if the connection was closed successfully, false otherwise.
	  */	
	function Close()
	{
		$success = mysql_close($this->connId);

		if($success) {
			$this->Free();

			$this->dataFetched = false;
			$this->connected = false;

			$this->resultsArray = array();
			$this->rowCount = 0;
			$this->colCount = 0;
			
			$this->connId = 0;
			$this->queryId = 0;

			$this->connIsValid = false;
		}

		return $success;
	}

	/**
	  * Changes the database in use.
	  *
	  * NOTE: This method requires a connection to the server to be made; otherwise it will fail.
	  *
	  * @method		SelectDb
	  * @param		string database
	  * @returns	true if database was selected, false otherwise.
	  */	
	function SelectDb($database)
	{
		$success = false;

		if($this->connected) {
			if(@mysql_select_db($database, $this->connId)) {
				$this->database = $database;
				$success = true;
			}
		}
		return $success;
	}

	/**
	  * Returns the set date in the format required by mysql in queries. If the the date is not set,
	  * then the current that will be returned.
	  *
	  * @method		GetDate
	  * @param		optional object date
	  * @returns	true if database was selected, false otherwise.
	  */	
	function GetDate($date=null)
	{
		if(is_null($date)) {
			$date =& new Date();
		}

		return $date->GetFormatDate("%Y-%m-%d");
	}

	/**
	  * Executes a query and fetches results if exist.
	  *
	  * @method		SqlExec
	  * @param		string query
	  * @param		optional boolean fetchResults
	  * @returns	true if the query was executed, false otherwise.
	  */
	function SqlExec($query, $fetchResults=true)
	{
		$success = false;
		$this->dataFetched = false;
		$this->resultsArray = array();
		$this->rowCount = 0;
		$this->colCount = 0;

		if($this->queryId) {
			$this->Free();
		}
		
		if($query != "") {
			// Check for connection
			if($this->connected) {
				$this->queryId = mysql_query($query, $this->connId);
				
				$this->sqlErrorNum   = mysql_errno();
				$this->sqlErrorText = mysql_error();

				if($this->queryId) {
					if($fetchResults) {
						$this->rowCount = mysql_num_rows($this->queryId);
						$this->colCount = mysql_num_fields($this->queryId);
						$this->FetchData();
					}
					$success = true;
				} else {
					if($fetchResults) {
						$this->Error("SqlExec", "Query didn't have results : " . $query);
					}
				}
			} else {
				$this->Error("SqlExec", "There is no active connection.");
			}
		} else {
			$this->Error("SqlExec", "Cannot execute empty query.");
		}
		
		return $success;
	}

	/**
	  * Returns the data from the selected row from the results array.
	  *
	  * @method		GetRowData
	  * @param		integer row
	  * @returns	array containing the row data.
	  */
	function GetRowData($row)
	{
		$rowData = array();

		if($this->dataFetched) {
			$rowData = $this->resultsArray[$row];
		} else {
			$this->Error("GetRowData", "No Query Results Available.");
		}

		return $rowData;
	}

	/**
	  * Returns the data from the selected row + column from the results array.
	  *
	  * @method		GetData
	  * @param		integer row
	  * @param		integer col	  
	  * @returns	string containing the row data.
	  */	
	function GetData($row, $col)
	{
		$rowData = $this->GetRowData($row);

		return $rowData[$col];
	}

	/**
	  * Returns the results array from fetched data.
	  *
	  * @method		GetArrResult
	  * @returns	array containing the results data.
	  */	
	function GetArrResult()
	{
		return $this->resultsArray;
	}	

	/**
	  * Returns the number of rows from the fetched data.
	  *
	  * @method		GetRowsCount  
	  * @returns	number containing row count.
	  */	
	function GetRowsCount()
	{
		return $this->rowCount;
	}

	/**
	  * Returns the number of cols from the fetched data.
	  *
	  * @method		GetColsCount  
	  * @returns	number containing col count.
	  */	
	function GetColsCount()
	{
		return $this->colCount;
	}


	/**
	  * Fetches the data returned by a successful query.
	  *
	  * @method		FetchData
	  * @returns	none.
	  */	
	function FetchData()
	{
		$rowData = array();
		
		for($i = 0; $rowData = @mysql_fetch_array($this->queryId, MYSQL_NUM); $i++) {
			$this->resultsArray[$i] = $rowData;
			
			$this->sqlErrorNum   = mysql_errno();
			$this->sqlErrorText = mysql_error();
		}

		$this->dataFetched = true;
		
		$this->Free();
	}

	/**
	  * Frees the result from the mysql query.
	  *
	  * @method		Free
	  * @returns	none.
	  */	
	function Free()
	{
		if($this->queryId) {
			@mysql_free_result($this->queryId);
			$this->queryId = 0;
		}
	}

	/**
	  * Sends a silent error to be fetched with GetLastError.
	  *
	  * @method		Error
	  * @param		string method
	  * @param		string msg  
	  * @returns	none.
	  */	
	function Error($method, $msg)
	{
		$this->sqlErrorNum   = mysql_errno();
		$this->sqlErrorText = mysql_error();

		$dump = "( Error #" . $this->sqlErrorNum . " ) : " . $this->sqlErrorText . "<br /><br />";
		$msg .= "<br /><br />" . $dump;

		// FIXME: The message is still saved in the lastError variable, to keep backward compatibility
		// with the modules that do an extensive use of GetLastError. There should be a better process
		// to resolve this matter.
		$this->lastError = $method."::".$msg;
	}

	/**
	  * Returns last error sent by mysql.
	  *
	  * @method		GetLastError
	  * @returns	none.
	  */	
	function GetLastError()
	{
		if(empty($this->lastError)) {
			$strRet = "MySQL::Error #".mysql_errno()." -> ".mysql_error();
		} else {
			$ret = $this->lastError;
		}
		return $ret;
	}

	/**
	  * Returns true if the connection to mysql is valid.
	  *
	  * @method		IsConnectionValid
	  * @returns	true if the connection is valid, false otherwise.
	  */	
	function IsConnectionValid()
	{
		return $this->connIsValid;
	}

	/**
	  * Returns information of the selected type. This method serves as an interface for mysql information.
	  *
	  * Types:
	  * IT_CLIENT - Information on the client. (Default)
	  * IT_HOST - Information on the host.
	  * IT_PROTO - Information on the protocol.
	  * IT_SERVER - Information on the server.
	  * IT_QUERY - Information on the most recent query.
	  * IT_STAT - Information on the current status.
	  *
	  * @method		GetInfo
	  * @param		optional constant  infoType
	  * @returns	string containing the requested information.
	  */	
	function GetInfo($infoType = IT_CLIENT)
	{
		if(empty($infoType)) {
			$infoType = 0;
		}
		
		if($this->connected) {
			switch($infoType)
			{
			case 0:     //IT_CLIENT
				$infoTxt = mysql_get_client_info();
				break;
			case 1:     //IT_HOST
				$infoTxt = mysql_get_host_info();
				break;
			case 2:     //IT_PROTO
				$infoTxt = mysql_get_proto_info();
				break;
			case 3:     //IT_SERVER
				$infoTxt = mysql_get_server_info();
				break;
			case 4:     //IT_QUERY
				$infoTxt = mysql_info();
				break;
			case 5:     //IT_STAT
				$infoTxt = mysql_stat($this->connId);
				break;

			default:
				break;
			}
		} else {
			$this->Error("GetInfo", "There is no active connection.");
		}

		return $infoTxt;	
	}

	/**
	  * Returns the databases count in the mysql server.
	  *
	  * @method		GetDbCount
	  * @returns	integer contaning the database count.
	  */	
	function GetDbCount()
	{
		$count = 0;
			
		if($this->connected) {
			$dbListId = mysql_list_dbs($this->connId);

			if($dbListId) {
				while(mysql_fetch_object($dbListId)) {
					$count++;
				}
				mysql_free_result($dbListId);
			}
		} else {
			$this->Error("GetDbCount", "There is no active connection.");
		}

		return $count;
	}

	/**
	  * Returns existing databases' names.
	  *
	  * @method		GetDbNames
	  * @returns	array containing databases' names.
	  */	
	function GetDbNames()
	{
		$dbs = array();

		if($this->connected) {
			$dbListId = mysql_list_dbs($this->connId);
		
			if($dbListId) {
				for($i = 0; $i < $this->GetDbCount(); $i++) {
					$dbs[$i] = mysql_db_name($dbListId, $i);
				}				
				mysql_free_result($dbListId);
			}
		} else {
			$this->Error("GetDbNames", "There is no active connection.");
		}
		
		return $dbs;	
	}

	/**
	  * Returns the table count for the selected database. 
	  *
	  * @method		GetTableCount
	  * @param		optional string database
	  * @returns	integer containing the table count.
	  */	
	function GetTableCount($database=null)
	{
	     $count = 0;

		 if(is_null($database)) {
			 $database =& $this->database;
		 }

		if($this->connected) {
			$idTableList = mysql_list_tables($database);
			
			if($idTableList) {
				$count = mysql_num_rows($idTableList);
				mysql_free_result($idTableList);
			} else {
				$this->Error("GetTableCount", "Database $strDb doesn't exist");
			}
		} else {
			$this->Error("GetTableCount", "There is no active connection.");
		}

		return $count;
	}

	/**
	  * Returns existing tables' names for the selected database.
	  *
	  * @method		GetTableNames
	  * @param		optional string database	  
	  * @returns	array containing tables' names.
	  */	
	function GetTableNames($database=null)
	{
		$tables = array();

		 if(is_null($database)) {
			 $database =& $this->database;
		 }		
		
		$tableListId = mysql_list_tables($database);
		
		if($tableListId) {
			for($i = 0; $i < $this->GetTableCount($database); $i++) {
				$tables[$i] = mysql_tablename($tableListId, $i);
			}
			mysql_free_result($tableListId);
		} else {
			$this->Error("GetTableNames", "Database $database doesn't exist.");
		}

		return $tables;
	}

	/**
	  * Returns the selected table's field count.
	  *
	  * @method		GetFieldCount
	  * @param		string table
	  * @param		optional string database	  
	  * @returns	integer containing field count.
	  */	
	function GetFieldCount($table, $database=null)
	{
	     $count = 0;

		 if(is_null($database)) {
			 $database =& $this->database;
		 }
		 
		if($this->connected) {
			if($database != $this->database) {
				if(!$this->SelectDb($database)) {
					$this->Error("GetFieldCount", "Couldn't select the database $database");
				}
			}
			
			$queryId = @mysql_query("Select * From $table", $this->connId);
			if($queryId) {
				$count = mysql_num_fields($queryId);
				mysql_free_result($queryId);
			} else {
				$this->Error("GetFieldCount", "Table $table doesn't exist.");
			}
		} else {
			$this->Error("GetFieldCount", "There is no active connection.");
		}

		return $count;
	}

	/**
	  * Returns the selected table's field names.
	  *
	  * @method		GetFieldNames
	  * @param		string table
	  * @param		optional string database	  
	  * @returns	array containing field names.
	  */	
	function GetFieldNames($table, $database=null)
	{
		$fields = array();

		 if(is_null($database)) {
			 $database =& $this->database;
		 }		

		$fieldListId = mysql_list_fields($database, $table);
		
		if($fieldListId) {
			for($i = 0; $i < $this->GetFieldCount($database, $table); $i++) {
				$fields[$i] = mysql_field_name($fieldListId, $i);
			}
			mysql_free_result($fieldListId);
		} else {
			$this->Error("GetFieldNames",  "List Fields Execution Faied.");
		}

		return $fields;
	}

	/**
	  * Modifies the selected database depending on the modification type.
	  *
	  * Types:
	  * MT_CREATE - Creates the set database. (Default)
	  * MT_DROP - Drops the selected database.
	  *
	  * @method		ModDb
	  * @param		string database
	  * @param		optional constant modType
	  * @returns   true if the operation was successful, false othewise.
	  */	
	function ModDb($database, $modType = MT_CREATE)
	{
		$success = false;

		if($this->connected) {
			if($this->SqlExec($modType." DATABASE ".$database, false)) {
				$success = true;
			} else {
				$this->Error("ModDb", "Query Execution Failed.");
			}
		} else {
			$this->Error("ModDb", "There is no active connection.");
		}
		
		return $success;
	}

	/**
	  * Creates a new database.
	  *
	  * @method		CreateDb
	  * @param		string database	  
	  * @returns	true if creation was successful, false otherwise.
	  */	
	function CreateDb($database)
	{
		return $this->ModDb($database);
	}

	/**
	  * Drop the set database. If the database is not selected, drops the selected database.
	  *
	  * @method		DropDb
	  * @param		optional string database	  
	  * @returns	true if deletion was successful, false otherwise.
	  */	
	function DropDb($database=null)
	{
		if(is_null($database)) {
			$database =& $this->database;
		}
		
		return $this->ModDb($database, MT_DROP);
	}

	/**
	  * Modifies the set table from the selected database depending on the modification type.
	  * --- WARNING: This method is not finished. ---
	  *
	  * Types:
	  * MT_CREATE - Creates the set database. (Default)
	  * MT_DROP - Drops the selected database.
	  *
	  * @method		ModTable
	  * @param		string table
	  * @param		optional string database
	  * @param		optional constant modType
	  * @returns   true if the operation was successful, false othewise.
	  */	
	function ModTable($table, $database=null, $modType = MT_CREATE)
	{
		// TODO : Add Fields Insertion in the argument list.		
		$success = false;

		if(is_null($database)) {
			$database =& $this->database;
		}		

		if($this->connected) {
			if($this->SqlExec($modType." TABLE ".$table, false)) {
				$success = true;
			} else {
				$this->Error("ModTable", "Query Execution Failed.");
			}
		} else {
			$this->Error("ModTable", "There is no active connection.");
		}
		
		return $success;	
	}

	/**
	  * Creates the set table from the set database.
	  *
	  * @method		CreateTable
	  * @param		string table
	  * @param		optional string database	  
	  * @returns	true if creation was successful, false otherwise.
	  */	
	function CreateTable($table)
	{
		if(is_null($database)) {
			$database =& $this->database;
		}
		
		return $this->ModTable($table);
	}

	/**
	  * Drop the set table.
	  *
	  * @method		DropTable
	  * @param		string table	  
	  * @param		optional string database	  
	  * @returns	true if deletion was successful, false otherwise.
	  */	
	function DropTable($table)
	{
		if(is_null($database)) {
			$database =& $this->database;
		}
		
		return $this->ModTable($table, MT_DROP);
	}

	/**
	  * Empties or truncates the table's information.
	  *
	  * @method		EmptyTable
	  * @param		string table	  
	  * @param		optional string database	  
	  * @returns	true if deletion was successful, false otherwise.
	  */	
	function EmptyTable($table, $database=null)
	{
		$success = false;

		if(is_null($database)) {
			$database =& $this->database;
		}		

		if($this->connected) {
			if($this->SqlExec("DELETE FROM $table", false)) {
				$success = true;
			} else {
				$this->Error("EmptyTable", "Query Execution Failed.");
			}
		} else {
			$this->Error("ModTable", "There is no active connection.");
		}

		return $success;
	}
}

?>