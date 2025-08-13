<?php
/**********************************************************************
ORIGINAL CODE:
-------------------------------
 Php Textfile DB API
 Copyright 2003 by c-worker.ch
 http://www.c-worker.ch
***********************************************************************/
/**********************************************************************
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
---------------------------------------------------------  
MODIFICATIONS: 
---------------------------------------------------------  
05/02/03 - Eric Gerdes - crafty_syntax@yahoo.com
I added a few functions to help make this class match the 
function names and the array structure of mysql_options.php
class. I also added support for the basic ALTER sql query .
Please look for this text in the code to see the code segments
created by me:
" BEGIN CODE INSERTED BY ERIC GERDES"
" END CODE INSERTED BY ERIC GERDES"

***********************************************************************/

include_once(API_HOME_DIR . "const.php");
include_once(API_HOME_DIR . "util.php");
include_once(API_HOME_DIR . "resultset.php");
include_once(API_HOME_DIR . "sql.php");
include_once(API_HOME_DIR . "expression.php");

/**********************************************************************
							Database
***********************************************************************/

// Represents a Database and has functions to execute Sql-Queries on it
class Database  {
    var $dbFolder;
    
    var $lastErrorMsgs=array();
   
   	/***********************************
		 		Constructor
	************************************/
	
    function Database($dbFolder="defaultDb/") {    	    	
    	$this->dbFolder= DB_DIR . $dbFolder;
    	if(last_char($this->dbFolder) != "/")
    		$this->dbFolder .= "/";
    }

/***********************************
BEGIN CODE INSERTED BY ERIC GERDES for ARRAY SUPPORT THAT MATCHES MYSQL_options.
************************************/
     
     
     // close connections
     function close_connect(){
     	return "";
     }
     
     // sql_query the data into assositive array of arrays.
     //-------------------------------------------------
     function sql_query($sql){
       return $this->executeQuery($sql);     
     }     
     
     // insert the data into assositive array of arrays.
     //-------------------------------------------------
     function insert($sql){
       return $this->executeQuery($sql);     
     }

     // select the data into assositive array of arrays.
     //-------------------------------------------------
     function select($sql){
     	
        $rs = $this->executeQuery($sql);
        $fieldnames = $rs->getColumnNames();
        $data_array = array();
        $dataarr = array();
        $dataarr_index = 0;    
        while($rs->next()) {
	  $tmp_array = $rs->getCurrentValues();			 

	  for($i=0;$i<count($tmp_array); $i++){
            $myfield = $fieldnames[$i];                      
            if ($myfield != ""){
            	// print "myfield = $myfield  value = $tmp_array[$i] ";
                  $data_array[$myfield] = $tmp_array[$i];
            }   
         }     
         $dataarr[$dataarr_index] = $data_array;         	 
         $dataarr_index++;
         $currentrow++; 
        } 
       return $dataarr;
     }

/***********************************
END CODE INSERTED BY ERIC GERDES for ARRAY SUPPORT THAT MATCHES MYSQL_options.
************************************/
    
     /***********************************
		 	Error Functions
	************************************/
	function addError($errorMessage) {
		$this->lastErrorMsgs[]=$errorMessage;
	}
	
	function addErrorPrint($errorMessage) {
		$this->lastErrorMsgs[]=$errorMessage;
		print_error($errorMessage);
	}

	function errorOccurred  () {
		return (count($this->lastErrorMsgs)>0);
	}
	
	function getLastErrors() {
		$arr=$this->lastErrorMsgs;
		$this->lastErrorMsgs=array();
		return $arr;
	}
   
    
    
    /***********************************
		 Table Lock functions
	************************************/

	// Lock's a Table
	// returns true if the table could be locked successfull
	function lockTable($tableName, $lockTimeout=LOCK_TIMEOUT) {
		$filename=$this->dbFolder . $tableName . LOCK_FILE_EXT;
		
		while($this->isLocked($tableName)) {
    		sleep(1);
        	$lockTimeout--;
        	if($lockTimeout==0) {
        		$this->addErrorPrint("Table $tableName is still Locked");
        		return false; 
        	}
    	}
		$fp=fopen ($filename, "w".TABLE_FILE_OPEN_MODE);
		fputs($fp,"lock file");
		fclose($fp);
		return true;
	}
	
	
	// Unlock's a Table
	// returns true or false
	function unlockTable($tableName) {
		clearstatcache();
		$filename=$this->dbFolder . $tableName . LOCK_FILE_EXT;
		if(file_exists($filename)) {
			$rc=unlink($filename);
			if(!$rc)
				$this->addErrorPrint("unlink failed on File $filename");
			return $rc;
		} else {
			$this->addErrorPrint("Cannot unlock Table, the File $filename does not exist");
			return false;
		}
	}
	
	// Returns true if the Table $tableName is locked
	function isLocked($tableName) {
		clearstatcache();
		$filename=$this->dbFolder . $tableName . LOCK_FILE_EXT;
		if(file_exists($filename)) {
			if(!($ts=filemtime($filename))) {
				$this->addErrorPrint("filemtime failed on $filename");
				return true;
			}
			if( (time()-$ts)>LOCKFILE_TIMEOUT) {
				debug_print("Deleting Lock File (Timeout)<br>");
				if(!unlink($filename)) {
					$this->addErrorPrint("unlink $filename failed");
					return true;
				}
				return false;
			}
			return true;
		}
		return false;
	}
	
	
	/***********************************
		 Table open/close Functions
	************************************/
	
	// Does open a locked Table for writing
	// Do only open a table, with this function, you have locked before !!!
	// openLockedTableWrite() does not check if the table is locked
	// you must call lockTable() first, and only if this succeeds
	// you should call openLockedTableWrite()
	function openLockedTableWrite($tableName, $openTimeout=OPEN_TIMEOUT) {
		debug_print("openLockedTableWrite<br>");
		$filename=$this->dbFolder . $tableName . TABLE_FILE_EXT;
    	return @fopen($filename,"w".TABLE_FILE_OPEN_MODE);
	}
	
	// same rules as for openLockedTableWrite()
	// does open a table for appending
	function openLockedTableAppend($tableName, $openTimeout=OPEN_TIMEOUT) {
		debug_print("openLockedTableAppend<br>");
		$filename=$this->dbFolder . $tableName . TABLE_FILE_EXT;
    	return @fopen($filename,"a".TABLE_FILE_OPEN_MODE);
	}
	
	// Does open a locked Table for reading
	// Do only open a table, with this function, you have locked before !!!
	// openLockedTableRead() does not check if the table is locked
	// you must call lockTable() first, and only if this succeeds
	// you should call openLockedTableRead()
	function openLockedTableRead($tableName, $openTimeout=OPEN_TIMEOUT) {
		debug_print("openLockedTableRead<br>");
		$filename=$this->dbFolder . $tableName . TABLE_FILE_EXT;
		return @fopen($filename,"r".TABLE_FILE_OPEN_MODE);
	}
	
	// Opens a Table for reading, this function does not open locked tables
	// This function is used by SELECT
	// returns a FilePointer or false
	function openTableRead($tableName, $openTimeout=OPEN_TIMEOUT) {
		debug_print("openTableRead<br>");
		$filename=$this->dbFolder . $tableName . TABLE_FILE_EXT;
     	while($this->isLocked($tableName)) {
    		sleep(1);
    		debug_print("next try<br>"); 
        	$openTimeout--;
        	if($openTimeout==0) {
        		$this->addErrorPrint("Table $tableName is still Locked");
        		return false; 
        	}
    	}
    	debug_print("Table $tableName opened (READ)<br>");
       	return @fopen($filename,"r".TABLE_FILE_OPEN_MODE);
	}
	
	// Closes a Table 
	// (do not forget to unlock with unlockTable() after the Table is closed)
	function closeTable($fp) {
		debug_print("Table $fp closed<br>");
		return fclose($fp);		
	}
	
	
	/***********************************
		 Table read/write Functions
	************************************/
	
	// Reads a Table into a ResultSet
	// Returns a ResultSet or null (the function opens an closes the file itself)
	// This functions can only read tables which are not locked (used in SELECT)
    function readTable($tableName) {
    	debug_print("readTable<br>");
      	$parser= new ResultSetParser();
      	if(!($fd=$this->openTableRead($tableName))) {
      		$this->addErrorPrint("readTable(): Cannot open Table $tableName");
      		return null;
    	}
    	$rs=$parser->parseResultSetFromFile($fd);
    	$this->closeTable($fd);
    	return $rs;
    }
    
    // Reads a locked Table into a ResultSet
    // Returns a ResultSet or null (the function opens an closes the file itself)
    // Do only call this funtion if you have locked the table before
    function readLockedTable($tableName) {
    	debug_print("readLockedTable<br>");
      	$parser= new ResultSetParser();
      	if(!($fd=$this->openLockedTableRead($tableName))) {
      		$this->addErrorPrint("readLockedTable(): Cannot open Table $tableName");
      		return null;
    	}
    	$rs=$parser->parseResultSetFromFile($fd);
    	$this->closeTable($fd);
    	return $rs;
    }
    
    // Reads a locked Table into a ResultSet
    // But only the column names and types + the last record
    // thats usefull for appending record
    function readLockedTableForAppend($tableName) {
    	debug_print("readLockedTable<br>");
      	$parser= new ResultSetParser();
      	if(!($fd=$this->openLockedTableRead($tableName))) {
      		$this->addErrorPrint("readLockedTable(): Cannot open Table $tableName");
      		return null;
    	}
    	$rs=$parser->parseResultSetFromFileForAppend($fd);
    	$this->closeTable($fd);
    	return $rs;
    }


    // writes the table by using the FilePointer $fd 
    // $fd has to be opened an closed by the caller
    // lock the table first !
    function writeLockedTable($fd, $resultSet) {
    	debug_print("writeLockedTable<br>");
    	$parser= new ResultSetParser();
    	return $parser->parseResultSetIntoFile($fd, $resultSet);
    }
    
    // $fd has to be opened an closed by the caller
    // lock the table first !
    function appendToLockedTable($fd, $resultSet,$recordCount) {
    	debug_print("writeLockedTable<br>");
    	$parser= new ResultSetParser();
    	while($resultSet->getRowCount()>$recordCount) {
    		$resultSet->reset();
    		$resultSet->next();
    		$resultSet->deleteCurrentRow();
    	}
    	return $parser->parseResultSetIntoFileAppend($fd, $resultSet);
    }
    
    
    
	
	/***********************************
		 	Query dispatcher
	************************************/
	
	// $sql_query_str is an unparsed SQL Query String
	// Return Values:
	// SELECT Queries: Returns a ResultSet Object or null
	// CREATE TABLE: Returns true or false
	// All other types: Returns the number of rows affected
	function executeQuery($sql_query_str) {
		
/***********************************
BEGIN CODE INSERTED BY ERIC GERDES for ALTER TABLE DROP/ADD FIELD..
************************************/		
	 // if this is an alter
	 if(ereg("^ALTER TABLE",$sql_query_str))
	          return $this->executeAlterQuery($sql_query_str);
/***********************************
END CODE INSERTED BY ERIC GERDES for ALTER TABLE DROP/ADD FIELD..
************************************/		
		
		// Parse Query
		$start=getmicrotime();
		
		$sqlParser= &new SqlParser($sql_query_str);
	   	$sqlQuery=$sqlParser->parseSqlQuery();
	   	
	   	debug_print("parseSqlQuery: " . (getmicrotime() - $start) . " seconds elapsed<br>");
	   	
	   	// Test Query
	   	if((!$sqlQuery) || (!$sqlQuery->test()))
			return null;
		
		$start=getmicrotime();
		
		if(TXTDBAPI_DEBUG) {
			$sqlQuery->dump();
		}
			
		// Dispatch
		switch($sqlQuery->type) {
			case "SELECT":
				$rc=$this->executeSelectQuery($sqlQuery);
				break;
			case "INSERT":
				$rc= $this->executeInsertQuery($sqlQuery);
				break;
			case "DELETE":
				$rc= $this->executeDeleteQuery($sqlQuery);
				break;
			case "UPDATE":
				$rc= $this->executeUpdateQuery($sqlQuery);
				break;
			case "CREATE TABLE":
				$rc= $this->executeCreateTableQuery($sqlQuery);
				break;
			case "DROP TABLE":
				$rc=$this->executeDropTableQuery($sqlQuery);
				break;
			case "CREATE DATABASE":
				$rc=$this->executeCreateDatabaseQuery($sqlQuery);
				break;
			case "DROP DATABASE":
				$rc=$this->executeDropDatabaseQuery($sqlQuery);
				break;
			case "LIST TABLES":
				$rc=$this->executeListTablesQuery($sqlQuery);
				break;
			default:
				$this->addErrorPrint("Invalid or unsupported Query Type: " . $sqlQuery->type);
				return false;
		}
		
		if(is_false($rc)) {
			$this->addErrorPrint("Query '" . $sql_query_str . "' failed");
		}
		
		debug_print("executeXYQuery: " . (getmicrotime() - $start) . " seconds elapsed<br>");
		
		return $rc;
	    
	}
	
	
	/***********************************
		 	Delete Query
	************************************/
	
	function executeDeleteQuery(&$sqlQuery) {
		
		// Lock Table
		if(!$this->lockTable($sqlQuery->tables[0])) {
			$this->addErrorPrint("Locking the Table " . $sqlQuery->tables[0] . " failed");
			return null;
		}

		// Read Table
		$rs=$this->readLockedTable($sqlQuery->tables[0]);
		if(!$rs) {
			$this->addErrorPrint("Reading the Table " . $sqlQuery->tables[0] . " failed");
			$this->unlockTable($sqlQuery->tables[0]);
			return null;
		}
		
		$rowsAffected=0;
		
		if(!$sqlQuery->where_expr || $sqlQuery->where_expr=="") {
			$rowsAffected=$rs->getRowCount();
			$rs->deleteAllRows();
		} else {
			// set row ids
			$rId=-1;
			$rs->reset();
			while($rs->next()) 
				$rs->setCurrentRowId(++$rId);
			$rs->reset();
			
			// apply WHERE Statement
			$ep=&new ExpressionParser();
			$rsFiltered=$ep->getFilteredResultSet($rs, $sqlQuery);
			
			if(!$rsFiltered) {
				$this->unlockTable($sqlQuery->tables[0]);
				return 0;
			}
			
			
			// Delete rows..
			$rsFiltered->reset();
			while($rsFiltered->next()) {
				$rowId=$rsFiltered->getCurrentRowId();
				$rs->deleteRow($rs->searchRowById($rowId));
			}
			
			$rowsAffected=$rsFiltered->getRowCount();
		} 	
		
		// Open Table
		$fp=$this->openLockedTableWrite($sqlQuery->tables[0]);
		if(!$fp) {
			$this->addErrorPrint("Open the Table " . $sqlQuery->tables[0] . " (for WRITE) failed");
			$this->unlockTable($sqlQuery->tables[0]);
			return null;
		}
		
		// Write Table
		$this->writeLockedTable($fp,$rs);
		$this->closeTable($fp);
		$this->unlockTable($sqlQuery->tables[0]);
		return $rowsAffected;
	}


	
	/***********************************
		 	Insert Query
	************************************/
	// returns the affected Row count or false
	function executeInsertQuery(&$sqlQuery) {

		// Lock Table
		if(!$this->lockTable($sqlQuery->tables[0])) {
			$this->addErrorPrint("Locking the Table " . $sqlQuery->tables[0] . " failed");
			return null;
		}
		
		// Read Table
		$rs=$this->readLockedTableForAppend($sqlQuery->tables[0]);
		
		if(TXTDBAPI_VERBOSE_DEBUG) {
		    echo "executeInsertQuery(): Last Record read for appending:<br>";
		    $rs->dump();
		    echo "<br>";
		}
		
		if(!$rs) {
			$this->addErrorPrint("Reading the Table " . $sqlQuery->tables[0] . " failed");
			$this->unlockTable($sqlQuery->tables[0]);
			return null;
		}
		
		// Open Table
		$fp=$this->openLockedTableAppend($sqlQuery->tables[0]);
		if(!$fp) {
			$this->addErrorPrint("Open the Table " . $sqlQuery->tables[0] . " (for APPEND) failed");
			$this->unlockTable($sqlQuery->tables[0]);
			return null;
		}
		
		array_walk($sqlQuery->fieldValues,"array_walk_remove_quotes");
		
		// a INSERT INTO table () VALUES () query: just write the default values
		if(count($sqlQuery->colNames)==0 && count($sqlQuery->fieldValues)==0) {
				$rs->append();
				$this->appendToLockedTable($fp,$rs,1);
				$this->closeTable($fp);
				$this->unlockTable($sqlQuery->tables[0]);
				return 1; // Error Handling ??
		}
				
		switch(count($sqlQuery->colNames)) {
			case 0:
				$rs->appendRow($sqlQuery->fieldValues);
				$this->appendToLockedTable($fp,$rs,1);
				$this->closeTable($fp);
				$this->unlockTable($sqlQuery->tables[0]);
				return 1; // Error Handling ??
				break;
			default:
				$rs->append();
				for($i=0;$i<count($sqlQuery->colNames);++$i) {
					$rs->setCurrentValueByName($sqlQuery->colNames[$i],$sqlQuery->fieldValues[$i]);
				}
				//$this->writeLockedTable($fp,$rs);
				$this->appendToLockedTable($fp,$rs,1);
				$this->closeTable($fp);
				$this->unlockTable($sqlQuery->tables[0]);
				return 1; // Error Handling ??
				break;
		}
	}
	
/***********************************
BEGIN CODE INSERTED BY ERIC GERDES for ALTER TABLE DROP/ADD FIELD..
************************************/		

	/***********************************
		 	Alter Query
	************************************/
    // this function needs a lot of work this is just a work around 
    // for now so I can add and drop fields.. 
 
    function executeAlterQuery(&$sqlQuery) {	
    	global $DB_DIR,$dbFolder;	
        //print "running: $sqlQuery<br><br>";
	//ALTER TABLE tablename ADD/DROP what
	$sqlQuery = ereg_replace("  "," ",$sqlQuery);
	$detail_query = split(" ",$sqlQuery);
	$filename = $DB_DIR . "$dbFolder/" . $detail_query[2] . ".txt";
	$filename_w = $DB_DIR . "$dbFolder/" . $detail_query[2] . ".new";	
	$fp = fopen ("$filename", "r");	
	$fp_w = fopen ("$filename_w", "w+");	
        $linenumber = 0;
        
	if($detail_query[3] == "ADD"){
           while (!feof($fp)){ 
                $currentline = fgets($fp,99999); 
                $currentline = ereg_replace("\r\n","",$currentline);
                $currentline = ereg_replace("\n","",$currentline);
                if($linenumber == 0){ $currentline .= $detail_query[4] . "#"; }
                if($linenumber == 1){ $currentline .= "str#"; }
                if($linenumber > 1){ $currentline .= "#";  }                
           $currentline .= "\n";
           $linenumber++;
           fwrite($fp_w, $currentline, strlen($currentline));
           } 
	}
	if($detail_query[3] == "DROP"){
           while (!feof($fp)){ 
                $currentline = fgets($fp,99999); 
                $currentline_n = "";
                if($linenumber == 0){                   
                  $fields = split("#",$currentline);   
                  for($i=0;$i<count($fields); $i++){
                    if($fields[$i] == $detail_query[4]){
                      $index_rm = $i;
                    } else {
                      $currentline_n .= $fields[$i] . "#";	
                    }
                  } // for loop
                } // if line == 0
                
                if($linenumber > 0){ 
                    $fields = split("#",$currentline); 
                    for($i=0;$i<count($fields); $i++){
                    if( $i == $index_rm){
                      // skipping.. 
                    } else {
                      $currentline_n .= $fields[$i] . "#";	
                    }
                  } // for loop
                } // if line > 0
                                                  
           $linenumber++;
           fwrite($fp_w, $currentline_n, strlen($currentline_n));
           } 	  	
	}	
	fclose($fp);
	fclose($fp_w);
	unlink($filename);
	copy($filename_w,$filename);
	unlink($filename_w);	
      }

/***********************************
END CODE INSERTED BY ERIC GERDES for ALTER TABLE DROP/ADD FIELD..
************************************/	
	
	/***********************************
		 	Update Query
	************************************/
	
	// returns the affected Row count or false
    function executeUpdateQuery(&$sqlQuery) {
		
		// Lock Table
		if(!$this->lockTable($sqlQuery->tables[0])) {
			$this->addErrorPrint("Locking the Table " . $sqlQuery->tables[0] . " failed");
			return null;
		}
		// Read Table
		$rs=$this->readLockedTable($sqlQuery->tables[0]);
		if(!$rs) {
			$this->addErrorPrint("Reading the Table " . $sqlQuery->tables[0] . " failed");
			$this->unlockTable($sqlQuery->tables[0]);
			return null;
		}
		
		array_walk($sqlQuery->fieldValues,"array_walk_remove_quotes");
		
		// No where_expr ? update all
		if( (!isset($sqlQuery->where_expr)) || (!$sqlQuery->where_expr) ) {
			// update 
			$rs->reset();
			while($rs->next()) {
				for($i=0;$i<count($sqlQuery->colNames);++$i) {
					$rs->setCurrentValueByName($sqlQuery->colNames[$i],$sqlQuery->fieldValues[$i]);
				}
			}
			// Open Table
			$fp=$this->openLockedTableWrite($sqlQuery->tables[0]);
			if(!$fp) {
				$this->addErrorPrint("Open the Table " . $sqlQuery->tables[0] . " (for WRITE) failed");
				$this->unlockTable($sqlQuery->tables[0]);
				return null;
			}
			// Write Table
			$this->writeLockedTable($fp,$rs);
			$this->closeTable($fp);
			$this->unlockTable($sqlQuery->tables[0]);
			return true;

		} else {
			// set row id's
			$rs->reset();
			$rId=-1;
			while($rs->next())
				$rs->setCurrentRowId(++$rId);
			
			// create a copy 
			$rsFiltered=$rs;

			// filter by where expression
			$ep=&new ExpressionParser();
			$rsFiltered=$ep->getFilteredResultSet($rsFiltered, $sqlQuery);
			
			if($rsFiltered->getRowCount()<1) {
				$this->unlockTable($sqlQuery->tables[0]);
				return 0;
			}
				
			// update 
			$rsFiltered->reset();
			while($rsFiltered->next()) {
				for($i=0;$i<count($sqlQuery->colNames);++$i) {
					$rsFiltered->setCurrentValueByName($sqlQuery->colNames[$i],$sqlQuery->fieldValues[$i]);
				}
			}
						
			// put filtered part back in the original ResultSet
			$rowNr=0;
			$putBack=0;
			$rs->reset();
			$rsFiltered->reset();
			while($rs->next()) {
				if(($rowNr=$rsFiltered->searchRowById($rs->getCurrentRowId())) !=NOT_FOUND) {
					$rs->setCurrentValues($rsFiltered->getValues($rowNr));
					$putBack++;
				}
			}
			if($putBack<$rsFiltered->getRowCount()) {
				$this->addErrorPrint("UPDATE: Could not put Back all filtered Values");
				$this->unlockTable($sqlQuery->tables[0]);
				return 0;
			}
			
			// Open Table
			$fp=$this->openLockedTableWrite($sqlQuery->tables[0]);
			if(!$fp) {
				$this->addErrorPrint("Open the Table " . $sqlQuery->tables[0] . " (for WRITE) failed");
				$this->unlockTable($sqlQuery->tables[0]);
				return null;
			}
			$this->writeLockedTable($fp,$rs);
			$this->closeTable($fp);
			$this->unlockTable($sqlQuery->tables[0]);
			return $rsFiltered->getRowCount();
		}
	}
	
	
	/***********************************
		 	Create Table Query
	************************************/
	
	// executes a SQL CREATE TABLE Statement
	// param: SqlQuery Object
	// returns True or False
	function executeCreateTableQuery(&$sqlQuery) {
		clearstatcache();
		$filename=$this->dbFolder . $sqlQuery->tables[0] . TABLE_FILE_EXT;
		
		// checks
		if(!$sqlQuery->tables[0]) {
			$this->addErrorPrint("Invalid Table " . $sqlQuery->tables[0]);
			return false;
		}
		if(file_exists($filename)) {
			$this->addErrorPrint("Table " . $sqlQuery->tables[0] . " allready exists");
			return false;
		}
		if(count($sqlQuery->colNames)!=count($sqlQuery->colTypes)) {
			$this->addErrorPrint("There's not a type defined for each column");
			return false;
		}
		for($i=0;$i<count($sqlQuery->colTypes);++$i) {
			$tmp= ($sqlQuery->colTypes[$i]=strtolower($sqlQuery->colTypes[$i]));
			if( !($tmp == COL_TYPE_INT || $tmp == COL_TYPE_STRING || $tmp==COL_TYPE_INC)) {
				$this->addErrorPrint("Column Type " . $tmp . " not supported");
				return false;
			}
		}
			
		
		// write file	
		$fp=fopen ($filename, "w".TABLE_FILE_OPEN_MODE);
		
		$rsParser=&new ResultSetParser();
		
		fwrite($fp, $rsParser->parseLineFromRow($sqlQuery->colNames));
		fwrite($fp, "\n");
		fwrite($fp, $rsParser->parseLineFromRow($sqlQuery->colTypes));
		fwrite($fp, "\n");
		fwrite($fp, $rsParser->parseLineFromRow($sqlQuery->fieldValues));
				
		fclose($fp);
		chmod($filename,0777);
		return true;	
	}
	
	/***********************************
		 	Drop Table Query
	************************************/
	
	// executes a SQL DROP TABLE Statement 
	// param: SqlQuery Object
	// returns True or False
	function executeDropTableQuery(&$sqlQuery) {
		clearstatcache();
		if(!isset($sqlQuery->colNames[0]))
			return false;
		
		for($i=0;$i<count($sqlQuery->colNames);++$i) {
			$rc=unlink($this->dbFolder. $sqlQuery->colNames[$i]  . TABLE_FILE_EXT);
			if(!$rc) {
				$this->addErrorPrint("DROP TABLE " . $sqlQuery->colNames[$i] . " failed");
				return false;
			}
		}
		return $rc;	
	}
	
	
	/***********************************
		 	List Tables Query
	************************************/
	
	// executes a LIST TABLES Statement 
	// param: SqlQuery Object
	// returns: A ResultSet Object with a single column "table"
	function executeListTablesQuery(&$sqlQuery) {
		$rs=new ResultSet();
		$rs->colNames=array("table");
		$rs->colAliases=array("");
		$rs->colTables=array("");
		$rs->colTableAliases=array("");
		$rs->colTypes=array(COL_TYPE_STRING);
		$rs->colDefaultValues=array("");
		
		
		$handle=opendir ($this->dbFolder);
		
		
		$rs->reset();
		while ($file = readdir ($handle)) { 
    		if ($file != "." && $file != ".." && is_file($this->dbFolder . $file)) { 
        		$rs->appendRow(array(substr($file,0,strlen($file)-strlen(TABLE_FILE_EXT))));
    		} 
		}
		
		// apply WHERE Statement
		if($sqlQuery->where_expr) {
			$ep=&new ExpressionParser();
			$rs=$ep->getFilteredResultSet($rs, $sqlQuery);
		} 
			
		// Order ResultSet
		if(count($sqlQuery->orderColumns)>0) {
			$rs->orderRows($sqlQuery->orderColumns,$sqlQuery->orderTypes);
		}

		// Group ResultSet (process GROUP BY)
		if(count($sqlQuery->groupColumns)>0) {
			$rs = $rs->groupRows($sqlQuery->groupColumns, $sqlQuery->limit);
		}

		// Apply Limit		
		$rs->reset();
		$rs = $rs->limitResultSet($sqlQuery->limit);
		
		
		closedir($handle); 
		$rs->reset();
		return $rs;
	}
	
	
	/***********************************
		 	Create Database Query
	************************************/
	
	// executes a SQL CREATE DATABASE Statement 
	// param: SqlQuery Object
	// returns True or False
	function executeCreateDatabaseQuery(&$sqlQuery) {
		clearstatcache();
		if($this->dbFolder!=DB_DIR .ROOT_DATABASE && $this->dbFolder!=DB_DIR .ROOT_DATABASE . "/") {
			$this->addErrorPrint("Databases can only be created with a ROOT_DATABASE instance!");
			return false;
		}
		if(!isset($sqlQuery->colNames[0]))
			return false;
			
		$directory=$this->dbFolder . $sqlQuery->colNames[0];
		$rc=mkdir ($directory, 0777);
		if(!$rc) {
			$this->addErrorPrint("Cannot create Database " . $sqlQuery->colNames[0]);
		}
		return $rc;	
	}
	
	/***********************************
		 	Drop Database Query
	************************************/
	
	// executes a SQL DROP DATABASE Statement 
	// param: SqlQuery Object
	// returns True or False
	function executeDropDatabaseQuery(&$sqlQuery) {
		clearstatcache();
		if($this->dbFolder!=DB_DIR .ROOT_DATABASE && $this->dbFolder!=DB_DIR .ROOT_DATABASE . "/") {
			$this->addErrorPrint("Databases can only be deleted with a ROOT_DATABASE instance!");
			return false;
		}
		if(!isset($sqlQuery->colNames[0]))
			return false;
		$directory=$this->dbFolder . $sqlQuery->colNames[0];
		
		// delete all tables
		$dirHandle=opendir($directory); 
			while ($file = readdir ($dirHandle)) { 
    			if ($file != "." && $file != ".." && is_file($directory . "/" . $file)) { 
        		if(!($rc=unlink($directory . "/" . $file))) {
					$this->addErrorPrint("Cannot drop Database: Deleting the table $file failed");
        		}
        		debug_print($file . "<br>");
    		} 
		}
		closedir($dirHandle); 

		
		$rc=rmdir ($directory);
		if(!$rc) {
			$this->addErrorPrint("Cannot drop Database " . $sqlQuery->colNames[0]);
		}
		return $rc;	
	}
	
	
	/***********************************
		 	Select Query
	************************************/
	
	// executes a SQL SELECT STATEMENT and returns a ResultSet 
	// param: SqlQuery Object
	function executeSelectQuery(&$sqlQuery) {		
	
		$resultSets=array();		
		
		// create a copy
		$aliases=$sqlQuery->colAliases;
			
		// Read all Tables
		for($i=0;$i<count($sqlQuery->tables);++$i) {
			debug_print ("<br>reading table " . $sqlQuery->tables[$i] ."<br>"); 
			if(!($resultSets[$i]=$this->readTable($sqlQuery->tables[$i]))) {
				$this->addErrorPrint("Reading Table " . $sqlQuery->tables[$i]. " failed");
				return null;
			}
			$resultSets[$i]->setColumnTableForAll($sqlQuery->tables[$i]);
			$resultSets[$i]->setColumnTableAliasForAll($sqlQuery->tableAliases[$i]);
			
			// set all aliases where table and column name matches
			for($j=0;$j<count($aliases);++$j) {
				if(!$aliases[$j])
					continue;
				if($sqlQuery->colTables[$j]==$sqlQuery->tables[$i]) {
					$colNr=$resultSets[$i]->findColNr($sqlQuery->colNames[$j]);
					if($colNr!=-1) {
						$resultSets[$i]->setColumnAlias($colNr,$aliases[$j]);
						$aliases[$j]="";
					} 
				}
			}
			if(TXTDBAPI_DEBUG)	$resultSets[$i]->dump();
		}
		
		// set remaining aliases where column name matches
		for($i=0;$i<count($resultSets);++$i) {
			for($j=0;$j<count($aliases);++$j) {
				if(!$aliases[$j])
					continue;
				if( ($colNr=$resultSets[$i]->findColNr($sqlQuery->colNames[$j])) !=-1) {
					$resultSets[$i]->setColumnAlias($colNr,$aliases[$j]);
					$aliases[$j]="";
				}
			}
		}
		
		// check if all aliases are used
		for($i=0;$i<count($aliases);++$i) {
			if($aliases[$i]) 
				$this->addErrorPrint("Could not attach alias " . $aliases[$i] . " contact developer");
		}
	
		
		// join the ResultSet's
		$rsMaster=$resultSets[0];
		for($i=1;$i<count($resultSets);++$i) {
			$rsMaster=$rsMaster->joinWithResultSet($resultSets[$i]);
		}
		
		// set row id's
		$rsMaster->reset();
		$rId=-1;
		while($rsMaster->next())
			$rsMaster->setCurrentRowId(++$rId);
			
		
		debug_print ("<br>Master ResultSet:<br>");
		if(TXTDBAPI_DEBUG) $rsMaster->dump();
		
		
		// apply WHERE Statement
		if($sqlQuery->where_expr) {
			
			$ep=&new ExpressionParser();

			$rsMaster=$ep->getFilteredResultSet($rsMaster, $sqlQuery);
		} 
		
		// return only the requested columns
		$rsMaster->filterByColumnNamesInSqlQuery($sqlQuery);
		
		// order columns (not their data)
		if(!$rsMaster->orderColumnsBySqlQuery($sqlQuery)) {
			$this->addErrorPrint("Ordering the Columns (themself) failed");
			return null;
		}
		
		
		// Order ResultSet
		if(count($sqlQuery->orderColumns)>0) {
			$rsMaster->orderRows($sqlQuery->orderColumns,$sqlQuery->orderTypes);
		}

		// Group ResultSet (process GROUP BY)
		if(count($sqlQuery->groupColumns)>0) {
			$rsMaster = $rsMaster->groupRows($sqlQuery->groupColumns, $sqlQuery->limit);
		}

		// process DISTINCT
		if($sqlQuery->distinct == 1) {
			$rsMaster = $rsMaster->makeDistinct($sqlQuery->limit);
		}
		
		// Apply Limit		
		$rsMaster->reset();
		$rsMaster = $rsMaster->limitResultSet($sqlQuery->limit);
		debug_print ("<br>Limited ResultSet:<br>");
		if(TXTDBAPI_DEBUG) $rsMaster->dump();

		
		$rsMaster->reset();
		return $rsMaster;
	}
    

}
?>