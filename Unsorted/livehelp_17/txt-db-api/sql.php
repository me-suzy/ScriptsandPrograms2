<?php
/**********************************************************************
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
***********************************************************************/

include_once(API_HOME_DIR . "util.php");
include_once(API_HOME_DIR . "const.php");
include_once(API_HOME_DIR . "stringparser.php");


/**********************************************************************
							Global vars 
***********************************************************************/

// Special Strings in SQL Queries
// Insert Strings before Single Chars !! (e.g. >= before >)
$g_sqlComparisonOperators=array("<>","!=",">=","<=","=","<",">"," LIKE " );
$g_sqlQuerySpecialStrings = array_merge($g_sqlComparisonOperators, array("(",")",";",",","."));
$g_sqlQuerySpecialStringsMaxLen =6;


/**********************************************************************
							SqlParser
***********************************************************************/
// Used to parse an SQL-Query (as String) into an SqlObject
class SqlParser extends StringParser {
	

	/***********************************
		 		Constructor
	************************************/
	function SqlParser($sql_query_str) {
	    
		debug_print ("New SqlParser instance: $sql_query_str<br>");
		global $g_sqlQuerySpecialStrings;

	    $this->quoteChars=array("'","\"");
	    $this->escapeChar="\\";         
	    $this->whitespaceChars=array(" ","\n","\r","\t");   
	    $this->specialElements=$g_sqlQuerySpecialStrings;
	    $this->removeQuotes=false;
        $this->removeEscapeChars=true;
        $this->setString($sql_query_str);
	}
	
	/***********************************
		 	Parse Dispatcher
	************************************/
	// Returns a SqlQuery Object or null if an error accoured
	function parseSqlQuery() {
		$type="";
		if(!$type=$this->parseNextElement()) 
			return null;
		$type=strtoupper($type);
		switch($type) {
			case "SELECT":
				return $this->parseSelectQuery();
				break;
			case "INSERT":
				return $this->parseInsertQuery();
				break;
			case "DELETE":
				return $this->parseDeleteQuery();
				break;
			case "UPDATE":
				return $this->parseUpdateQuery();
				break;
			case "CREATE":
				if(strtoupper($this->peekNextElement())=="TABLE") {
					$this->skipNextElement();
					return $this->parseCreateTableQuery();
				}	
				if(strtoupper($this->peekNextElement())=="DATABASE") {
					$this->skipNextElement();
					return $this->parseCreateDatabaseQuery();
				}	
				break;
			case "DROP":
				if(strtoupper($this->peekNextElement())=="TABLE") {
					$this->skipNextElement();
					return $this->parseDropTableQuery();
				}	
				if(strtoupper($this->peekNextElement())=="DATABASE") {
					$this->skipNextElement();
					return $this->parseDropDatabaseQuery();
				}	
				break;
			case "LIST":
				if(strtoupper($this->peekNextElement())=="TABLES") {
					$this->skipNextElement();
					return $this->parseListTablesQuery();
				}	
				break;
			default:
				print_error("SQL Type $type not supported");
				return null;
		}
	}
	
	
	/***********************************
		 Select Query Parse Function
	************************************/
	
	// SELECT must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseSelectQuery() {
		$colNames=array();
		$colTables=array();
		$colAliases=array();
		$fieldValues=array();
		$tables=array();
		$tableAliases=array();
		$groupColumns=array();
		$orderColumns=array();
		$orderTypes=array();
		$where_expr="";
		$distinct=0;
		
		// parse Columns
		$colIndex=0;
		while(!is_empty_str($elem=$this->parseNextElement())) {
			if(strtoupper($elem)=="DISTINCT") {
				$distinct=1;
				continue;
			}
			if(strtoupper($elem)=="FROM")
				break;
			if($this->peekNextElement()==".") {
				$colTables[$colIndex]=$elem;
				$this->skipNextElement();
				$colNames[$colIndex]=$this->parseNextElement();
			} else {
				$colTables[$colIndex]="";
				$colNames[$colIndex]=$elem;
			}
			if(strtoupper($this->peekNextElement())=="AS") {
				$this->skipNextElement();
				$colAliases[$colIndex]=$this->parseNextElement();
			} else {
				$colAliases[$colIndex]="";
			}
			if($this->peekNextElement()==",") {
				$this->skipNextElement();
			}
			$colIndex++;
		}
		
		// parse Tables
		$arrElements=array();
		while($this->parseNextElements(",",array("GROUP","WHERE","ORDER","LIMIT",";"),$arrElements)) {
			$tables[]=$arrElements[0];
			if(count($arrElements)>2 && strtoupper($arrElements[1])=="AS") {
				$tableAliases[]=$arrElements[2];
			// mysql like Table aliasing support, without AS
			} else if(count($arrElements)>1) {
				$tableAliases[]=$arrElements[1];
			// end of mysql like Table aliasing support
			} else {
				$tableAliases[]="";
			}
		}
		
				
		// parse Where statement (Raw, because the escape-chars are needend in the ExpressionParser)
		if(strtoupper($this->peekNextElement()) == "WHERE") {
			$this->skipNextElement();
			while(!is_empty_str($elem=$this->peekNextElementRaw())) {
				if(strtoupper($elem)=="GROUP" || strtoupper($elem)=="ORDER" || $elem==";" || strtoupper($elem)=="LIMIT" )
					break;
				$this->skipNextElement();
				
				// no " " on points
				if($elem==".") {
					remove_last_char($where_expr);
					$where_expr .= $elem;
				} else {
					$where_expr .= $elem . " ";
				}
			}
		}
		debug_print( "WHERE EXPR: $where_expr<br>");
		
		// parse GROUP BY
		$groupColumnIndex=0;
		if(strtoupper($this->peekNextElement()) == "GROUP") {
			$this->skipNextElement();
			if(strtoupper($this->parseNextElement())!="BY") {
				print_error("BY expected");
				return null;
			}
			
			while(!is_empty_str($elem=$this->peekNextElement())) {
				if($elem==";" || strtoupper($elem)=="LIMIT" || strtoupper($elem)=="ORDER")
					break;
				$this->skipNextElement();
				if($elem==",") {
					$groupColumnIndex++;
				}
				else {
					if(!isset($groupColumns[$groupColumnIndex])) 
						$groupColumns[$groupColumnIndex]=$elem;
					else
						$groupColumns[$groupColumnIndex].=$elem;
				}	
			}
		}
		
		// parse ORDER BY
		$orderColumnIndex=0;
		if(strtoupper($this->peekNextElement()) == "ORDER") {
			$this->skipNextElement();
			if(strtoupper($this->parseNextElement())!="BY") {
				print_error("BY expected");
				return null;
			}
			
			while(!is_empty_str($elem=$this->peekNextElement())) {
				if($elem==";" || strtoupper($elem)=="LIMIT")
					break;
				$this->skipNextElement();
				if($elem==",") {
					$orderColumnIndex++;
				}
				else if(strtoupper($elem)=="ASC") 
					$orderTypes[$orderColumnIndex]=ORDER_ASC;
				else if(strtoupper($elem)=="DESC")
					$orderTypes[$orderColumnIndex]=ORDER_DESC;
				else {
					if(!isset($orderColumns[$orderColumnIndex])) 
						$orderColumns[$orderColumnIndex]=$elem;
					else
						$orderColumns[$orderColumnIndex].=$elem;
					$orderTypes[$orderColumnIndex]=ORDER_ASC;
				}	
			}
		}
		// parse LIMIT
		$limit = array();
		if(strtoupper($this->peekNextElement()) == "LIMIT") {
			$this->skipNextElement();
			while(!is_empty_str($elem=$this->peekNextElement())) {
				if($elem==";")
					break;
				$this->skipNextElement();
				if($elem!=",") {
					$limit[] = $elem;
				}
			}
		}

		$sqlObj = new SqlQuery("SELECT", $colNames, $tables, $colAliases, $colTables, $where_expr, $groupColumns, $orderColumns, $orderTypes, $limit);
		$sqlObj->tableAliases=$tableAliases;
		$sqlObj->distinct=$distinct;
		
		return $sqlObj;

	}


	/***********************************
		 Insert Query Parse Function
	************************************/
	
	// INSERT must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseInsertQuery() {
		
		$colNames=array();
		$colTables=array();
		$colAliases=array();
		$fieldValues=array();
		$tables=array();
		$insertType="";
		
		
		// remove INTO
		if(strtoupper($this->peekNextElement())=="INTO") 
			$this->skipNextElement();
				
		// Read Table				
		$tables[0]=$this->parseNextElement();
		
		// Read Column Names between ()'s
		$colIndex=0;
		if($this->peekNextElement()=="(") {
			$this->skipNextElement();
			while(($elem=$this->parseNextElement())!=")") {
				if($elem==",")
					$colIndex++;
				else 
					$colNames[$colIndex]=$elem;
			}
		}
	
		
		// read Insert Type
		$insertType=$this->parseNextElement();
		
		switch(strtoupper($insertType)) {
			case "SET":
				$commaCheck=5;  // table.column=xy 
				// Read Columns and Values
				$colIndex=0;
				while( !is_empty_str(($elem=$this->parseNextElement())) && ($elem != ";")) {
					if($elem==",") {
						$commaCheck=5;
						$colIndex++;
					} else if($elem=="=") {
						$commaCheck=2;
						$fieldValues[$colIndex]=$this->parseNextElement();
					} else {
						$colNames[$colIndex]=$elem;
					}
					$commaCheck--;
					if($commaCheck<=0) {
						print_error(", Expected");
						return null;
					}
				}
				break;
				
			case "VALUES":
				if($this->parseNextElement()!="(") {
					print_error("VALUES in the INSERT Statement must be in Braces \"(,)\"");
					return null;
				}
				$fieldValuesIndex=0;
				while(($elem=$this->parseNextElement())!=")") {
					if(is_empty_str($elem)) {
						print_error(") Expected");
						return null;
					}
					
					if($elem==",")
						$fieldValuesIndex++;
					else 
						$fieldValues[$fieldValuesIndex]=$elem;
				}
				break;
			default:
				print_error("Insert Type " . $insertType . " not supported");
				return null;
		}
		$sqlObj = new SqlQuery();
		$sqlObj->type = "INSERT";
		$sqlObj->colNames=$colNames;
		$sqlObj->colAlias=$colAliases;
		$sqlObj->colTables=$colTables;
		$sqlObj->fieldValues=$fieldValues;
		$sqlObj->insertType=$insertType;
		$sqlObj->tables=$tables;
		
		return $sqlObj;
	}
	
	
	/***********************************
		 Delete Query Parse Function
	************************************/
	
	// DELETE must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseDeleteQuery() {
		
		$tables=array();
		$where_expr="";
		
		if(strtoupper($this->parseNextElement())!="FROM") {
			print_error("FROM expected");
			return null;
		}
		$tables[0]=$this->parseNextElement();
		
		// Because the Where Statement is not parsed with 
		// the parseXX Functions, this equals a Raw-Parse,
		// as needed for the ExpressionParser
		if(strtoupper($this->parseNextElement())=="WHERE") {
			$where_expr=rtrim($this->workingStr);
			debug_print("where_expr: $where_expr<br>");
			if(last_char($where_expr)==";")
				remove_last_char($where_expr);
		} else if ($elem=$this->parseNextElement()) {
			print_error("Nothing more expected: $elem");
			return null;
		}
		
		$sqlObj = new SqlQuery();
		$sqlObj->type = "DELETE";
		$sqlObj->tables=$tables;
		$sqlObj->where_expr=$where_expr;
		
		return $sqlObj;
	}
	
	
	/***********************************
		 Update Query Parse Function
	************************************/
	
	// UPDATE must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseUpdateQuery() {
		
		$colNames=array();
		$fieldValues=array();
		$tables=array();
		$where_expr="";
		
		// Read Table				
		$tables[0]=$this->parseNextElement();
		
		// Remove SET
		if(strtoupper($this->parseNextElement())!="SET") {
			print_error("SET expected");
			return null;
		}
		
		// Read Columns and Values
		$elem="";
		$colIndex=0;
		while( !is_empty_str(($elem=$this->parseNextElement())) && ($elem != ";") && (strtoupper($elem) != "WHERE")) {
			if($elem==",")
				$colIndex++;
			else if($elem=="=") {
				$fieldValues[$colIndex]=$this->parseNextElement();
			} else {
				$colNames[$colIndex]=$elem;
			}
		}
		
		// Raw-Parse Where Statement
		if(strtoupper($elem)=="WHERE") {
			$where_expr=rtrim($this->workingStr);
			debug_print("where_expr: $where_expr<br>");
			
			if(last_char($where_expr)==";")
				remove_last_char($where_expr);
		}


		$sqlObj = new SqlQuery();
		$sqlObj->type = "UPDATE";
		$sqlObj->colNames=$colNames;
		$sqlObj->fieldValues=$fieldValues;
		$sqlObj->tables=$tables;
		$sqlObj->where_expr=$where_expr;
		
		return $sqlObj;
	}
	
	
	/***********************************
	  Create Table Query Parse Function
	************************************/
	
	// CREATE TABLE must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseCreateTableQuery() {
		$colNames=array();
		$colTypes=array();
		$colDefaultValues=array();
		$tables=array();
	
		$tables[0]=$this->parseNextElement();	
		
		if($this->parseNextElement()!="(") {
			print_error("( expected");
			return null;
		}

		$index=0;
		
		$arrElements=array();
		while($this->parseNextElements(",",array(";"),$arrElements)) {
			$colNames[]=$arrElements[0];
			$colTypes[]=$arrElements[1];
			if(count($arrElements)>3 && strtoupper($arrElements[2])=="DEFAULT") {
				if(has_quotes($arrElements[3]))
					remove_quotes($arrElements[3]);
				$colDefaultValues[]=$arrElements[3];
			} else {
				$colDefaultValues[]="";
			}
		}
		
	
		
		
		$sqlObj = new SqlQuery();
		$sqlObj->type = "CREATE TABLE";
		$sqlObj->colNames=$colNames;
		$sqlObj->colTypes=$colTypes;
		$sqlObj->fieldValues=$colDefaultValues;
		$sqlObj->tables=$tables;
	
		return $sqlObj;		
	}
	
	/***********************************
	  Create Database Query Parse Function
	************************************/
	
	// CREATE DATABASE must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseCreateDatabaseQuery() {
	
		$dbName=$this->parseNextElement();	
		
		$sqlObj = new SqlQuery();
		$sqlObj->type = "CREATE DATABASE";
		$sqlObj->colNames=array($dbName);
		return $sqlObj;		
	}
	
	/***********************************
	  Drop Database Query Parse Function
	************************************/
	
	// DROP DATABASE must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseDropDatabaseQuery() {
	
		$dbName=$this->parseNextElement();	
		
		$sqlObj = new SqlQuery();
		$sqlObj->type = "DROP DATABASE";
		$sqlObj->colNames=array($dbName);
		return $sqlObj;		
	}
	
	/***********************************
	  Drop Table Query Parse Function
	************************************/
	
	// DROP TABLE must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseDropTableQuery() {
	
		$tables=array();
		$i=0;
		
		while($this->peekNextElement() != ";" && !is_empty_str($elem=$this->parseNextElement())) {
			if($elem==",")
				++$i;
			else
				$tables[$i]=$elem;
		}
		
		$sqlObj = new SqlQuery();
		$sqlObj->type = "DROP TABLE";
		$sqlObj->colNames=$tables;
		return $sqlObj;		
	}
	
	/***********************************
	  List Tables Query Parse Function
	************************************/
	
	// LIST TABLES must be removed here (do not call this Function directly !!!)
	// returns a SqlQuery Object
	function parseListTablesQuery() {
		$sqlObj = new SqlQuery();
		$sqlObj->type = "LIST TABLES";
		
		$colNames=array();
		$colTables=array();
		$colAliases=array();
		$fieldValues=array();
		$tables=array();
		$groupColumns=array();
		$orderColumns=array();
		$orderTypes=array();
		$where_expr="";
		$distinct=0;
		
		
		// parse Where statement (Raw, because the escape-chars are needend in the ExpressionParser)
		if(strtoupper($this->peekNextElement()) == "WHERE") {
			$this->skipNextElement();
			while(!is_empty_str($elem=$this->peekNextElementRaw())) {
				if(strtoupper($elem)=="ORDER" || $elem==";" || strtoupper($elem)=="LIMIT" )
					break;
				$this->skipNextElement();
				
				// no " " on points
				if($elem==".") {
					remove_last_char($where_expr);
					$where_expr .= $elem;
				} else {
					$where_expr .= $elem . " ";
				}
			}
		}
		
		// parse ORDER BY
		$orderColumnIndex=0;
		if(strtoupper($this->peekNextElement()) == "ORDER") {
			$this->skipNextElement();
			if(strtoupper($this->parseNextElement())!="BY") {
				print_error("BY expected");
				return null;
			}
			
			while(!is_empty_str($elem=$this->peekNextElement())) {
				if($elem==";" || strtoupper($elem)=="LIMIT")
					break;
				$this->skipNextElement();
				if($elem==",") {
					$orderColumnIndex++;
				}
				else if(strtoupper($elem)=="ASC") 
					$orderTypes[$orderColumnIndex]=ORDER_ASC;
				else if(strtoupper($elem)=="DESC")
					$orderTypes[$orderColumnIndex]=ORDER_DESC;
				else {
					if(!isset($orderColumns[$orderColumnIndex])) 
						$orderColumns[$orderColumnIndex]=$elem;
					else
						$orderColumns[$orderColumnIndex].=$elem;
					$orderTypes[$orderColumnIndex]=ORDER_ASC;
				}	
			}
		}
		// parse LIMIT
		$limit = array();
		if(strtoupper($this->peekNextElement()) == "LIMIT") {
			$this->skipNextElement();
			while(!is_empty_str($elem=$this->peekNextElement())) {
				if($elem==";")
					break;
				$this->skipNextElement();
				if($elem!=",") {
					$limit[] = $elem;
				}
			}
		}
		
		
		$sqlObj = new SqlQuery("LIST TABLES", $colNames, $tables, $colAliases, $colTables, $where_expr, $groupColumns, $orderColumns, $orderTypes, $limit);
		return $sqlObj;		
	}

	
	/***********************************
		 	Parse Helper Functions
	************************************/
		
	// does not remove Escape Chars
	function parseNextElementRaw() {
	    $this->removeEscapeChars=false;
		$ret= $this->parseNextElement(true);
		$this->removeEscapeChars=true;
		return $ret;
	}
	
	// does not remove Escape Chars
	function peekNextElementRaw() {
		$this->removeEscapeChars=false;
		$ret= $this->parseNextElement(false);
		$this->removeEscapeChars=true;
		return $ret;
	}
	
}

/**********************************************************************
								SqlQuery
***********************************************************************/
// Represents an SQL Query 
// Fields should be accessed directly here -> faster 

class SqlQuery {
	
	
	/***********************************
			Member variables
	************************************/

	var $type;
	
	var $colNames=array();
	var $colAliases=array();
	var $colTables=array();
	
	var $colTypes=array();		// At the Moment only used in CREATE TABLE (int, string OR inc)
								// may also used in other Queries 
	var $fieldValues=array(); 	// Used in: INSERT, UPDATE, CREATE TABLE (as default values)
	
	var $insertType=""; 		// Used in: INSERT ("VALUES", "SET" or "SELECT")
	
	var $groupColumns=array();	// Used by: GROUP BY
	var $distinct=0;			// will be set if SELECT query contains a DISTINCT
	
	var $orderColumns=array(); 	// Used by: ORDER BY
	var $orderTypes=array();	// Used by: ORDER BY
	
	var $tables=array();
	var $tableAliases=array();
	var $where_expr;
	
	var $limit=array();

	
	
	
	/***********************************
				Constructor
	************************************/
	
	function SqlQuery($type="SELECT", $colNames=array(), $tables=array(), $colAliases=array(), $colTables=array(), $where_expr="", $groupColumns=array(), $orderColumns=array(),$orderTypes=array(),$limit=array()) {
	
		$this->type=$type;
		$this->colNames=$colNames;
		$this->tables=$tables;
		$this->where_expr=$where_expr;
		$this->colAliases=$colAliases;
		$this->colTables=$colTables;
		$this->groupColumns=$groupColumns;
		$this->orderColumns=$orderColumns;
		$this->orderTypes=$orderTypes; 
		$this->limit=$limit;
		
	}
	
	function getSize() {
		return count($this->colNames);
	}
	
	
	/***********************************
				Test
	************************************/
	// NOT Up to Date
	// Test's if the SqlQuery is valid
	// TRUE if ok, FALSE if not ok
	function test() {
		reset($this->colNames);
		for($i=0;$i<count($this->colNames);++$i) {
			if($this->colNames[$i]=="*")
			{
				if($this->colAliases[$i]) {
					print_error("Cannot define Alias by a *");
					return FALSE;
				}
				continue;
			}
			if($key=array_search  ($this->colNames[$i], $this->colNames)) {
				if($i==$key)
					continue;
				if($this->colAliases[$i] == $this->colAliases[$key]) {
					print_error("Two Columns with the same name use no or the same alias ('" . $this->colNames[$i] . "', '" . $this->colNames[$key] . "')");
					return FALSE;
				}
				if(!$this->colTables[$i]) {
					print_error("Column " . $this->colNames[$i] . " could belong to multiple Tables");
					return FALSE;
				}
			}
		}
		reset($this->colAliases);
		for($i=0;$i<count($this->colAliases);++$i) {
			if($key=array_search  ($this->colAliases[$i], $this->colAliases)) {
				if($i==$key || $this->colAliases[$i]=="")
					continue;
				print_error("Two Columns (" . $this->colNames[$i] . ", " . $this->colNames[$key] . ") use the same alias");
				return FALSE;
			}
		}
		
		reset($this->colNames);
		// TODO: error ..?!?  SELECT nr, tabelle1.nr As nr FROM ....
		// produces no Error !
		for($i=0;$i<count($this->colAliases);++$i) {
			if(($key=array_search($this->colAliases[$i], $this->colNames))) {
				if($i==$key) {
					continue;
				}
				print_error("Alias is the name from another column (" . $this->colAliases[$i] . ")");
				return FALSE;
			}
		}
		return TRUE;
		
	}
	
	function dump() {
		echo "<pre>";
		echo "SqlQuery dump:<br>";
		echo "type:  $this->type<br>";	
		echo "<br>colNames:<br>"; 
		print_r($this->colNames);
		echo "<br>colAliases:<br>"; 
		print_r($this->colAliases);
		echo "<br>colTables:<br>"; 
		print_r($this->colTables);
		echo "<br>colTypes:<br>"; 
		print_r($this->colTypes);
		echo "<br>fieldValues:<br>"; 
		print_r($this->fieldValues);
		echo "<br>insertType: " . $this->insertType . "<br>"; 
		echo "<br>groupColumns:<br>"; 
		print_r($this->groupColumns);
		echo "<br>distinct: " . $this->distinct . "<br>"; 
		echo "<br>orderColumns:<br>"; 
		print_r($this->orderColumns);
		echo "<br>orderTypes:<br>"; 
		print_r($this->orderTypes);
		echo "<br>tables:<br>"; 
		print_r($this->tables);
		echo "<br>tableAliases:<br>"; 
		print_r($this->tableAliases);
		echo "<br>limit:<br>"; 
		print_r($this->limit);
		echo "<br>where_expr: " . $this->where_expr ."<br>"; 
		echo "</pre>";
	}

}

?>