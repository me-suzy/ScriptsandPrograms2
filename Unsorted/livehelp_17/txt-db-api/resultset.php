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

include_once(API_HOME_DIR . "const.php");
include_once(API_HOME_DIR . "util.php");


/**********************************************************************
								Row
***********************************************************************/

// ResultSet->rows should be of this type (not really used at the moment)
class Row {
	var $id;   			 // unique id for the row
	var $fields=array(); // fields of the row
}

/**********************************************************************
							ResultSet
***********************************************************************/

// Represents a Table
class ResultSet {
	
	/***********************************
		 	Mebmer Variables
	************************************/
	// columns
	var $colNames=array();
	var $colAliases=array();
	var $colTables=array();
	var $colTableAliases=array();
	var $colTypes=array();
	var $colDefaultValues=array();
	
	// rows 
	var $rows=array();  // to use as array of type Row (see above)
	
	// position in the ResultSet
	var $pos=-1;
		
	// informations how this resultSet is ordered 
	// at the momemt only used by cmpRows()
	var $orderColNrs=array(); // Column Nr
	var $orderTypes=array();  // ORDER_ASC or ORDER_DESC
	
	
	
	
	/***********************************
		 	row id functions 
	************************************/
	function setRowId($rowNr, $id) {
		$this->rows[$rowNr]->id=$id;
	}
	function getRowId($rowNr) {
		return $this->rows[$rowNr]->id;
	}
	function setCurrentRowId($id) {
		$this->rows[$this->pos]->id=$id;
	}
	function getCurrentRowId() {
		return $this->rows[$this->pos]->id;
	}
	function searchRowById($id) {
		for($i=0;$i<count($this->rows);++$i) {
			if(isset($this->rows[$i]->id) && $this->rows[$i]->id==$id)
				return $i;
		}
		return NOT_FOUND;
	}
   
	
	/***********************************
		 	Navigate Functions
	************************************/
	function getPos() {
		return $this->pos;
	}
	function setPos($pos) {
		$this->pos=$pos;
	}
	function reset() {
		$this->pos=-1;
	}
	// Moves to the next Row, and returns true if there was a next row
	// or false if there was no next row
	function next() {
		if(++$this->pos<$this->getRowCount()) 
			return true;
		else {
			$this->pos--;
			return false;
		}
	}
	
 	function prev() {
		if(--$this->pos<$this->getRowCount() && $this->pos>-1)
			return true;
 		else
			return false;
	}
 	function end() {
		$this->pos=$this->getRowCount()-1;
 	}
 	function start() {
 		$this->reset();
 	}
 	
 	// Appends a row to the ResultSet
 	function append($setDefaultValues=true) {
 		$this->end();
 		if(++$this->pos!=$this->getRowCount()) {
 			print_error("append() failed, not at the end of the ResultSet");
 			$pos--;
 			return false;
 		}
 		
 		if(!$setDefaultValues)
 			return;
 			
 		// Set initial values
 		for($i=0;$i<count($this->colTypes);++$i) {
 			// inc
 			if($this->colTypes[$i]==COL_TYPE_INC) {
 				if($this->pos==0) 
 					$this->rows[$this->pos]->fields[$i]=1;
 				else
 					$this->rows[$this->pos]->fields[$i]=$this->rows[$this->pos-1]->fields[$i]+1;
 			// int
 			} else if($this->colTypes[$i]==COL_TYPE_INT) {			
 				// make sure the default value is a number
 				$this->rows[$this->pos]->fields[$i]=intval($this->colDefaultValues[$i]);
 			// str
 			} else {
 				$this->rows[$this->pos]->fields[$i]=$this->colDefaultValues[$i];;
 			}
 		}
	}
  
  

  	/***********************************
		 	Column Functions (find)
	************************************/
	
	// Finds the number of the Column by its Name or Alias
	// table.colum is not supported here 
	// returns NOT_FOUND if not found
	function findColNr($colName) {
				
		// first search in the aliases
		$colNr=array_search ($colName,$this->colAliases);
		
		// if not found, search in colNames
		// check for NULL and FALSE, because the return value
		// of array_search() changed in PHP 4.2.0
		if(is_false($colNr) || _is_null($colNr))
			$colNr=array_search ($colName,$this->colNames);
			
		if(is_false($colNr) || _is_null($colNr)) {
			print_error("Column '$colName' not found");
			return NOT_FOUND;
		}
		return $colNr;
	}
	
	// here $colName can also contain a table name (slower then findColNr)
	// if $colName contains no "." findColNr is returned.
	// returns NOT_FOUND if not found
	function findColNrByFullName($colName) {
		if(!is_false ($ppos=strpos($colName,".")) ) {
			$table=substr($colName,0,$ppos);
			$colName=substr($colName,$ppos+1);
		} else {
			return $this->findColNr($colName);
		}
		debug_print ("<br>Searching col: Table=$table colname=$colName<br>");
		for($i=0;$i<count($this->colNames);++$i) {
			if($this->colNames[$i]==$colName)
				if($table==$this->colTables[$i] || $table==$this->colTableAliases[$i])
					return $i;
		}
		print_error("Column '" . $table . "." . $colName . "' not found");
		return NOT_FOUND;
	}
	
	
	/***********************************
		 Column Functions (set/get)
	************************************/
	
	// names
	function getColumnNames() {
		return $this->colNames;
	}
	function setColumnNames($colNames) {
		$this->colNames=$colNames;
	}
	
	// aliases
	function getColumnAliases() {
		return $this->colAliases;
	}
	function setColumnAliases($colAliases) {
		$this->colAliases=$colAliases;
	}
	function setColumnAlias($colNr, $colAlias) {
		$this->colAliases[$colNr]=$colAlias;
	}
	
	// tables
	function getColumnTables() {
		return $this->colTables;
	}
	function setColumnTables($colTables) {
		$this->colTables=$colTables;
	}
	function setColumnTableForAll($colTable) {
		$this->colTables=create_array_fill(count($this->colNames),$colTable);
	}
	
	// table aliases
	function getColumnTableAliases() {
		return $this->colTableAliases;
	}
	function setColumnTableAliases($colTableAliases) {
		$this->colTableAliases=$colTableAliases;
	}
	function setColumnTableAliasForAll($colTableAlias) {
		$this->colTableAliases=create_array_fill(count($this->colNames),$colTableAlias);
	}
	
	// types	
	function getColumnTypes() {
		return $this->colTypes;
	}
	function setColumnTypes($colTypes) {
		$this->colTypes=$colTypes;
	}
	
	// default values
	function getColumnDefaultValues() {
		return $this->colDefaultValues;
	}
	function setColumnDefaultValues($colDefaultValues) {
		$this->colDefaultValues=$colDefaultValues;
	}

	
	
	/***********************************
		 Column Functions (other)
	************************************/
	
	// copies all column data from another ResultSet
	function copyColumData($otherResultSet) {
		$this->setColumnNames($otherResultSet->getColumnNames());
		$this->setColumnAliases($otherResultSet->getColumnAliases());
		$this->setColumnTables($otherResultSet->getColumnTables());
		$this->setColumnTableAliases($otherResultSet->getColumnTableAliases());
		$this->setColumnTypes($otherResultSet->getColumnTypes());
		$this->setColumnDefaultValues($otherResultSet->getColumnDefaultValues());
	}
	
	
	// Removes a Column from the ResultSet 
	// after removeColumn is called, the colNr's of the other Columns change !
	function removeColumn($colNr) {
		
		// save Pos
		$tmpPos=$this->pos;
		
		$this->reset();
		while($this->next()) {
			array_splice ($this->rows[$this->pos]->fields, $colNr,1);
		}
		
		// restore Pos
		$this->pos=$tmpPos;
		
		debug_print ("Removing colum nr $colNr <br>");		

		// remove in Column Data
		array_splice($this->colNames,$colNr,1);
		array_splice($this->colAliases,$colNr,1);
		array_splice($this->colTables,$colNr,1);
		array_splice($this->colTableAliases,$colNr,1);
		array_splice($this->colTypes,$colNr,1);
		array_splice($this->colDefaultValues,$colNr,1);
	}
	

	// Orders the Columns (themself e.g. [Nr] [Name] [UserId] -> [Name] [Nr] [UserId])
	// by the Order the Columns have in the SqlQuery Object $sqlQuery
	function orderColumnsBySqlQuery(&$sqlQuery) {
		$fullColNames=array();
		for($i=0;$i<count($sqlQuery->colNames);++$i) {
			if($sqlQuery->colAliases[$i])
				$fullColNames[$i]=$sqlQuery->colAliases[$i];
			else if($sqlQuery->colTables[$i])
				$fullColNames[$i]=$sqlQuery->colTables[$i] . "." . $sqlQuery->colNames[$i];
			else 
				$fullColNames[$i]=$sqlQuery->colNames[$i];
		}
		return $this->orderColumns($fullColNames);
	}
	
	// Orders the Columns (themself e.g. [Nr] [Name] [UserId] -> [Name] [Nr] [UserId])
	// Returns false if an Error accoured
	function orderColumns($fullColumnNames) {
		
		$newColNames=array();
		$newColAliases=array();
		$newColTables=array();
		$newColTableAliases=array();
		$newColTypes=array();
		$newColDefaultValues=array();
		
		$colPos=-1;
		$currentColumn=-1; // current Column 
		$oldRows=$this->rows;
	
			
		if(count($fullColumnNames)==1 && $fullColumnNames[0]=="*")
			return true;
		
		for($i=0;$i<count($fullColumnNames);++$i) {
			$currentColumn++;
			
			// Handling for table.*
			if(substr_right($fullColumnNames[$i],2)==".*") {
				$tableName=substr($fullColumnNames[$i],0,strlen($fullColumnNames[$i])-2);
				for($j=0;$j<count($this->colTables);++$j) {
					if($tableName==$this->colTables[$j]) {
						
						$newColNames[$currentColumn]=$this->colNames[$j];
						$newColAliases[$currentColumn]=$this->colAliases[$j];
						$newColTables[$currentColumn]=$this->colTables[$j];
						$newColTableAliases[$currentColumn]=$this->colTableAliases[$j];
						$newColTypes[$currentColumn]=$this->colTypes[$j];
						$newColDefaultValues[$currentColumn]=$this->colDefaultValues[$j];
						
							
						for($k=0;$k<count($oldRows);$k++) {
							$this->rows[$k]->id=$oldRows[$k]->id;
							$this->rows[$k]->fields[$currentColumn]=$oldRows[$k]->fields[$j];
						}			
						$currentColumn++;
					}					
				}
				$currentColumn--;
				continue;	
			}
			
			
			if( ($colPos=$this->findColNrByFullName($fullColumnNames[$i]))==-1) {
				print_error("Column '" . $fullColumnNames[$i] . "' not found");
				return false;
			}
			$newColNames[$currentColumn]=$this->colNames[$colPos];
			$newColAliases[$currentColumn]=$this->colAliases[$colPos];
			$newColTables[$currentColumn]=$this->colTables[$colPos];
			$newColTableAliases[$currentColumn]=$this->colTableAliases[$colPos];
			$newColTypes[$currentColumn]=$this->colTypes[$colPos];
			$newColDefaultValues[$currentColumn]=$this->colDefaultValues[$colPos];
			
			for($j=0;$j<count($oldRows);++$j) {
				$this->rows[$j]->id=$oldRows[$j]->id;
				$this->rows[$j]->fields[$currentColumn]=$oldRows[$j]->fields[$colPos];
			}			
		}

		$this->colNames=$newColNames;
		$this->colAliases=$newColAliases;
		$this->colTables=$newColTables;
		$this->colTableAliases=$newColTableAliases;
		$this->colTypes=$newColTypes;
		$this->colDefaultValues=$newColDefaultValues;
		
		return true;		
	}
	
	
  	/***********************************
	Row Size Functions (Field Count per Row)
	************************************/
	function getRowSize() {
		if(count($this->rows)>0)
			return count($this->rows[0]->fields);
		else
			return 0;
	}
	
	/***********************************
			Row Count Functions
	************************************/
  	function getRowCount() {
		return count($this->rows);
 	}
 	
 	
 	/***********************************
			Field Access Functions
	************************************/
 	
 	// Get Value by Name
 	function getCurrentValueByName($colName) {
 		if(($colNr=$this->findColNrByFullName($colName))==-1)	
 			return;
 		else 			
 			return $this->rows[$this->pos]->fields[$colNr];
 	}
 	function getValueByName($rowNr,$colName) {
 		 if(($colNr=$this->findColNrByFullName($colName))==-1)	
 			return;
 		else
 			return $this->rows[$rowNr]->fields[$colNr];
 	}
 	
 	// Get Value by Nr
 	function getCurrentValueByNr($colNr) {
 		return $this->rows[$this->pos]->fields[$colNr];
 	}
 	function getValueByNr($rowNr, $colNr) {
 		return $this->rows[$rowNr]->fields[$colNr];
 	}
 	
 	// Set Value by Name 
 	function setCurrentValueByName($colName, $value) {
 		if(($colNr=$this->findColNrByFullName($colName))==-1)	
 			return;
 		else
 			$this->rows[$this->pos]->fields[$colNr] = $value;
 	}
 	function setValueByName($rowNr,$colName,$value) {
 		if(($colNr=$this->findColNrByFullName($colName))==-1)	
 			return;
 		else
 			$this->rows[$rowNr]->fields[$colNr]= $value;
 	}
 	
 	// Set Value by Nr 
 	function setCurrentValueByNr($colNr, $value) {
 		$this->rows[$this->pos]->fields[$colNr] = $value;
 	}
 	function setValueByNr($rowNr, $colNr, $value) {
 		$this->rows[$rowNr]->fields[$colNr] = $value;
 	}
 	
 	// Get whole row
	function getCurrentValues() {
		return $this->rows[$this->pos]->fields;
	}
	function getValues($rowNr) {
		return $this->rows[$rowNr]->fields;
	}
	
	// Get whole row as hash
	function getCurrentValuesAsHash()  {
		foreach ($this->rows[$this->pos]->fields as $key => $value) 
			$newhash[$this->colNames[$key]]=$value; 
		return $newhash; 
	}
	
	// Set whole row
	function setCurrentValues($values) {
		$this->rows[$this->pos]->fields = $values;
	}
	function setValues($rowNr,$values) {
		$this->rows[$rowNr]=$values;
	}

	// Append Row 
	// Here inc values wont be set, caller must supply all values !
	function appendRow($values, $id=-1) {
		
		if(count($values)==count($this->getColumnNames()))
			$setDefaults = false;
		else                                                                      
			$setDefaults = true;  
		
		// if id is -1 do a simple append
		if($id==-1) {
			$this->append($setDefaults);
			$this->rows[$this->pos]->fields=$values;
			$this->rows[$this->pos]->id=$id;
		// else, if the id exists let the ResultSet untouched..
		} else if($this->searchRowById($id)==-1) {
			$this->append($setDefaults);
			$this->rows[$this->pos]->fields=$values;
			$this->rows[$this->pos]->id=$id;
		} 
	}
	
	/***********************************
			Row Delete Functions
	************************************/
	function deleteRow($rowNr) {
		array_splice ($this->rows, $rowNr,1);
	}
	function deleteCurrentRow() {
		$this->deleteRow($this->pos);
	}
	function deleteAllRows() {
		$this->rows=array();
	}
	

	/***********************************
		 	Limit Functions
	************************************/
	function limitResultSet($ar_limit) {
		if(!isset($ar_limit[0]) && !isset($ar_limit[1])) return $this;
		if(count($ar_limit) == 1) {
			$ar_limit[1] = $ar_limit[0];   // because LIMIT 30 is equal to
			$ar_limit[0] = 0;              // LIMIT 0,30
		}
		
		$rowCount = $this->getRowCount();
		if ($ar_limit[0]+$ar_limit[1] > $rowCount)
			$ar_limit[1] = $rowCount - $ar_limit[0];

		$rs=new ResultSet();
		$rs->copyColumData($this);
		
		$this->pos = $ar_limit[0];         // we begin at the offset

		for($i=0; $i<$ar_limit[1]; ++$i) {
			$rs->append(0);
			$rs->rows[$rs->pos]->fields=$this->getCurrentValues();
			$rs->rows[$rs->pos]->id=$this->getCurrentRowId();
			
			$this->next();
		}
		return $rs;
	}

	
	/***********************************
			Group Functions
	************************************/

	function groupRows($groupColumns, $ar_limit) {
		if(!isset($ar_limit[0]) && !isset($ar_limit[1]))
			$limit = -1;
		else if(count($ar_limit) > 1) 
			$limit = $ar_limit[0]+$ar_limit[1];	
		else 
			$limit = $ar_limit[0];
			
		$rs=new ResultSet();
		$rs->copyColumData($this);

		$groupedRows=array();
		$this->reset();
		while($this->next()) {
			$currentValues=array();
			foreach($groupColumns as $groupCol)
				array_push($currentValues, $this->getCurrentValueByName($groupCol));
			$joinedValues=join("",$currentValues);
			if(!array_key_exists($joinedValues, $groupedRows)) {
				$groupedRows[$joinedValues] = 1;
				$rs->append(false);
				$rs->rows[$rs->pos]->fields=$this->getCurrentValues();
				$rs->rows[$rs->pos]->id=$this->getCurrentRowId();
			}
			if($limit != -1)
				if($rs->getRowCount() >= $limit)
					break;
		}
		
		return $rs;
	}
	
	
	function makeDistinct($ar_limit) {
		
		$colNames = $this->getColumnNames();
		
		if(!isset($ar_limit[0]) && !isset($ar_limit[1]))
			$limit = -1;
		else if(count($ar_limit) > 1) 
			$limit = $ar_limit[0]+$ar_limit[1];	
		else 
			$limit = $ar_limit[0];

		$rs=new ResultSet();
		$rs->copyColumData($this);

		$distinctRows=array();
		$this->reset();
		while($this->next()) {
			$currentValues=array();
			foreach($colNames as $col)
				array_push($currentValues, $this->getCurrentValueByName($col));
			$joinedValues=join("",$currentValues);
			if(!array_key_exists($joinedValues, $distinctRows)) {
				$distinctRows[$joinedValues] = 1;
				$rs->append(false);
				$rs->rows[$rs->pos]->fields=$this->getCurrentValues();
				$rs->rows[$rs->pos]->id=$this->getCurrentRowId();
			}
			if($limit != -1)
				if($rs->getRowCount() >= $limit)
					break;
		}
		
		return $rs;
	}


	
	/***********************************
			Filter Functions
	************************************/

	// $colNames is an array of column Names to keep..
	// filters itself.. (no copy returned)
	function filterByColumnNames($colNamesToKeep) {
		$colNrsToKeep=array();
		if(in_array ("*",$colNamesToKeep)) {
			debug_print("Keeping all Columns <br>");
			return;
		 }
		
		for($i=0;$i<count($colNamesToKeep);++$i) {
			// keep all of a table ?
			if(($pos=strpos($colNamesToKeep[$i], ".*"))) {
				$table=substr($colNamesToKeep[$i],0,$pos);
				debug_print("Keeping all Columns of Table $table: ");
				for($j=0;$j<count($this->colTables);++$j) {
					if($this->colTables[$j]==$table) {
						$colNrsToKeep[]=$j;
					}
				}
				debug_print("<br>");
			} else {
			
				$colNr=$this->findColNrByFullName($colNamesToKeep[$i]);
				if($colNr==-1) {
					print_error("filterByColumnNames(): Column '" . $colNamesToKeep[$i] . "' not found");
					return null;
				} else {
					$colNrsToKeep[]=$colNr;
				}
			}
		}
		
		// remove from last element to first (cause colNr's change afer a removeColumn() call
		for($i=count($this->colNames)-1;$i>=0;$i--) 
			if(!in_array($i,$colNrsToKeep))
				$this->removeColumn($i);
	}
	
	
	
	// Takes the Columns to keep from an SqlQuery Object
	function filterByColumnNamesInSqlQuery(&$sqlQuery) {
		$colNames=array();
		for($i=0;$i<count($sqlQuery->colNames);++$i) {
			if($sqlQuery->colAliases[$i])
				$colNames[$i]=$sqlQuery->colAliases[$i];
			else if($sqlQuery->colTables[$i])
				$colNames[$i]=$sqlQuery->colTables[$i] . "." . $sqlQuery->colNames[$i];
			else 
				$colNames[$i]=$sqlQuery->colNames[$i];
		}
		return $this->filterByColumnNames($colNames);
	}


	// Filters the Rows by 1-n And Conditions
	// parameters: array of columns and array of values for this columns
	//             + an array of operators (=,<,>)
	// returns: ResultSet with filtered Records (copy) ($this is left unchanged)
	function filterRowsByAndConditions($columns, $values, $operators) {
		$rs=new ResultSet();
		$rs->copyColumData($this);
		
		$this->reset();
		
		$colNrs=array();
		for($i=0;$i<count($columns);++$i) {
			if(($colNrs[$i]=$this->findColNrByFullName($columns[$i]))==-1) {
				print_error("Column '" . $columns[$i] . "' not found");
				return null;
			}
		}
		
		// find field to field criterias
		$colNrs2=array();
		for($i=0;$i<count($values);++$i) {
			// value is a value ?
			if(has_quotes($values[$i]) || $values[$i]=="0" || intval ($values[$i]) != 0) {
				if(has_quotes($values[$i]))
					remove_quotes($values[$i]);
				$colNrs2[$i]=-1;
			} else {
				if(($colNrs2[$i]=$this->findColNrByFullName($values[$i]))==-1) {
					print_error("Column '" . $values[$i] . "' not found");
					return null;
				}
			}
		}
			
		$this->reset();
		while($this->next()) {
			$recMetsConds=true;
			for($i=0;$i<count($columns);++$i) {
				// field to field criteria ?
				if($colNrs2[$i]!=-1) {
					if(!compare($this->rows[$this->pos]->fields[$colNrs[$i]],
						$this->rows[$this->pos]->fields[$colNrs2[$i]],$operators[$i])) {
						$recMetsConds=false;
						break;
					}
				} else {
					if(!compare($this->rows[$this->pos]->fields[$colNrs[$i]],$values[$i],$operators[$i])) {
						$recMetsConds=false;
						break;
					}
				}
			}
			
			if($recMetsConds) {
				//$rs->appendRow($this->getCurrentValues(), $this->getCurrentRowId());
				$rs->append(false);
				$rs->rows[$rs->pos]->fields=$this->getCurrentValues();
				$rs->rows[$rs->pos]->id=$this->getCurrentRowId();
				
			}
		}
		// reset ResultSet's
		$this->reset();
		$rs->reset();
		return $rs;
	}

	
	// Removes all rows from $this, which are not contained
	// in $otherResultSet. 
	// The $rows->id var is used to check if 2 Rows match.
	// parameter: $otherResultSet with !!! row->id's set !!!
	
	function filterResultSetAndWithAnother(&$otherResultSet) {
		
		$this->reset();
		while($this->next()) {
			if($otherResultSet->searchRowById($this->getCurrentRowId())==NOT_FOUND) {
				$this->deleteCurrentRow();
				$this->prev(); // Because the current Row was delete, check again at this position
			}
		}		
	}
	




	/***********************************
			ResultSet join Functions
	************************************/

	// Returns a ResultSet which Contains the Columns and Rows
	// of $this and $otherResultSet (a new ResultSet is returned).
	// The ResultSet itself ($this) is left Unchanged
	// For each row in $this each row in $otherResultSet will be duplicated
	// Exapmle:
	// 1	Test	Hello
	// 2 	Test2	Hello2 
	//  joined with
	// 10	Blabla
	// 11 	Foo_Bar
	// 13   Bar_foo
	//  results in
	// 1	Test	Hello	10	Blabla
	// 1	Test	Hello	11	Foo_Bar
	// 1	Test	Hello	13	Bar_foo
	// 2 	Test2	Hello2 	10	Blabla
	// 2 	Test2	Hello2 	11	Foo_Bar
	// 2 	Test2	Hello2 	13	Bar_foo
	//
	function joinWithResultSet(&$otherResultSet) {

		
		if($this->getRowCount()<1)
			print_warning("Joining emtpy ResultSet (results in empty ResultSet)");
			
		$newResultSet=new ResultSet();
		// columns
		$newResultSet->setColumnNames(array_merge ($this->getColumnNames(), $otherResultSet->getColumnNames()));
		$newResultSet->setColumnAliases(array_merge ($this->getColumnAliases(), $otherResultSet->getColumnAliases()));
		$newResultSet->setColumnTables(array_merge ($this->getColumnTables(), $otherResultSet->getColumnTables()));
		$newResultSet->setColumnTableAliases(array_merge ($this->getColumnTableAliases(), $otherResultSet->getColumnTableAliases()));
		$newResultSet->setColumnTypes(array_merge ($this->getColumnTypes(), $otherResultSet->getColumnTypes()));
		$newResultSet->setColumnDefaultValues(array_merge ($this->getColumnDefaultValues(), $otherResultSet->getColumnDefaultValues()));
				
		$otherResultSet->reset();
		$this->reset();
		$newResultSet->reset();
		
		while($this->next()) {
			$otherResultSet->reset();
			while($otherResultSet->next()) {
				$row=array_merge($this->getCurrentValues(),$otherResultSet->getCurrentValues());
				$newResultSet->appendRow($row);
			}
		}
		
		return $newResultSet;
		
	}	
	
	
	
	/***********************************
			Row Order Functions
	************************************/
	// $orderCols array of Columns to Order
	// $orderTypes Type of order for the column (ORDER_ASC or ORDER_DESC)
	function orderRows($orderCols,$orderTypes) {
		
		// return if the ResultSet size is 0
		if(count($this->rows)<1)
			return;
		
		$colNrs=array();
		for($i=0;$i<count($orderCols);++$i) {
			if(($colNrs[$i]=$this->findColNrByFullName($orderCols[$i]))==-1) {
				print_error("orderRows(): Column '" . $orderCols[$i] . "' not found");
				return null;
			}
		}
		
		// set orderColNrs and oderTypes
		$this->orderColNrs=$colNrs;
		$this->orderTypes=$orderTypes;
		
		$rowNrs=array();
		$i=-1;
		$rowCount=$this->getRowCount();
		while(++$i<$rowCount) $rowNrs[] = $i;
		
		// sort
		debug_print("<br>BEFORE SORT:<br>");
		if(TXTDBAPI_DEBUG) print_r($rowNrs);
		
		$this->rowQuicksort($rowNrs, 0, $rowCount-1);
			
		debug_print("<br>AFTER SORT:<br>");
		if(TXTDBAPI_DEBUG) print_r($rowNrs);

		$oldRows=$this->rows;		
		for($i=0;$i<count($rowNrs);++$i) {
			$this->rows[$i]=$oldRows[$rowNrs[$i]];
		}		
	}
	
	// Helper Function for rowQuicksort()
	//
	// $row1 and $row2 must be the NUMBERS of the rows to compare
	//
	// the following member vars should be set before callings this function:
	// $this->orderColNrs must be the Column Nr's to Order AND
	// $this->oderTypes must be an array of Order Types  ( ORDER_ASC or ORDER_DESC )
	// 
	// return value (ORDER_ASC)
	//			-1 if $row1 < $row2
	// 			0 if $row1 = $row2
	// 			1 if $row1 > $row2
	// (just the opposite for ORDER_DESC)
	function cmpRows($row1,$row2) {
		for($i=0;$i<count($this->orderColNrs);++$i) {
			$currentCol=$this->orderColNrs[$i];
			if(ORDER_CASE_SENSITIVE) {
				$row1Field=$this->rows[$row1]->fields[$currentCol];
				$row2Field=$this->rows[$row2]->fields[$currentCol];
			} else {
				$row1Field=strtolower($this->rows[$row1]->fields[$currentCol]);
				$row2Field=strtolower($this->rows[$row2]->fields[$currentCol]);
			}
			
			if($row1Field > $row2Field) {
				if($this->orderTypes[$i]==ORDER_ASC)
					return 1;
				else
					return -1;			
			} else if ($row1Field < $row2Field) {
				if($this->orderTypes[$i]==ORDER_ASC)
					return -1;
				else
					return 1;
			} else if($i== (count($this->orderColNrs)-1)) {
				return 0;
			}
		}
	}
	
	
	// Quicksort's the array $rowNrs (array of Row Numbers)
	// $lo ist the low Index, hi is the high Index of the
	// array part to sort
	function rowQuicksort(&$rowNrs,$lo,$hi) {
		
	    $i=$lo;
	    $j=$hi;
	    $x=$rowNrs[($lo+$hi)/2];
	    
	    do {
	    	while($this->cmpRows($rowNrs[$i],$x)==-1) $i++;
	    	while($this->cmpRows($rowNrs[$j],$x)==1) $j--;
	    	if($i<=$j) {
	    		$h=$rowNrs[$i]; $rowNrs[$i]=$rowNrs[$j]; $rowNrs[$j]=$h;
	    		++$i; --$j;
	    	}
	    } while ($i<=$j);
	   
		if($lo<$j) $this->rowQuicksort($rowNrs,$lo,$j);
		if($i<$hi) $this->rowQuicksort($rowNrs,$i,$hi);
	}


	
	/***********************************
			Debug Functions
	************************************/

	// Dump's the ResultSet 
	function dump() {
		$size=30;
		$format="%-" . $size . "s";
		$id_size=5;
		$id_format="%-" . $id_size ."s";
		
		echo "<pre><br><b>";

		printf($id_format,"ID");		
		// Column Names
		reset($this->colNames);
		while (list ($key, $val) = each ($this->colNames))
			printf($format, "$val");			
		echo "</b><br>";
		
		printf($id_format,"");
		reset($this->colNames);
		while (list ($key, $val) = each ($this->colNames))  {
			printf($format, "(al=" .$this->colAliases[$key] . ", tbl=". $this->colTables[$key] .")");			
		}
		echo "<br>";
		
		printf($id_format,"");
		reset($this->colNames);
		while (list ($key, $val) = each ($this->colNames))  {
			printf($format, "(tba=" .$this->colTableAliases[$key] . ", ty=". $this->colTypes[$key]  .", def=". $this->colDefaultValues[$key] .")");			
		}
		echo "<br>";

		 
		
		printf("%'-" . $id_size . "s","|");
		
		for($i=0;$i<count($this->colNames);++$i)
			printf("%'-" . $size . "s","|");
		echo "<br><br>";
		
		$this->reset();
		
		if(!isset($this->rows))
			return;
		
		while($this->next()) {
			reset($this->rows[$this->pos]->fields);
			if(isset($this->rows[$this->pos]->id))
				printf($id_format,$this->rows[$this->pos]->id . ": ");
			while (list ($key, $val) = each ($this->rows[$this->pos]->fields)) 
				printf($format, "$val");			
			
			echo "<br>";
		}
		echo "</pre>";
		$this->reset();
	}

 	
}


/**********************************************************************
							ResultSetParser
***********************************************************************/

// Used to parse a ResultSet Object into, and from Files
class ResultSetParser {
	
	var $escapeCodeWrite;
	var $replaceWithWrite;
	
	var $escapeCodeRead;
	var $replaceWithRead; 
	
	
	/***********************************
			Line Parse Functions
	************************************/
	
	function ResultSetParser() {
		$this->escapeCodeRead=array(TABLE_FILE_ESCAPE_CHAR."h", 
									TABLE_FILE_ESCAPE_CHAR."n",
									TABLE_FILE_ESCAPE_CHAR."r", 
									TABLE_FILE_ESCAPE_CHAR."p");
		
		$this->replaceWithRead=array("#", "\n", "\r", TABLE_FILE_ESCAPE_CHAR);
		
		$this->escapeCodeWrite=array_reverse($this->escapeCodeRead);
		$this->replaceWithWrite=array_reverse($this->replaceWithRead);
		
		
	}

	/***********************************
			Line Parse Functions
	************************************/
	
	function parseRowFromLine($line) {
		if($line=="" || $line=="\n" || $line=="\r" || $line=="\r\n" || $line=="\n\r" || $line=="\xa" || $line=="\xd")
		   return false;
		
		// handle Windows \x0D\x0A (\r\n) newlines
		$line=rtrim($line);
		$row=explode('#', $line);
				
		$row=str_replace($this->escapeCodeRead, $this->replaceWithRead, $row);
		
		return $row;
	}
	

	function parseLineFromRow($row) {
				
		$row=str_replace($this->replaceWithWrite, $this->escapeCodeWrite, $row);
		return implode("#", $row);
			
	}



	/***********************************
			File Parse Functions
	************************************/
	
	// $fd is a File Descriptor (returned by fopen)
	function parseResultSetFromFile($fd) {
		
		$start=getmicrotime();
		
		$rs = new ResultSet();
		$buf="";
		// read in the whole file
		fseek($fd,0,SEEK_END);
		$size=ftell($fd);
		fseek($fd,0,SEEK_SET);
		$wholeFile=fread($fd,$size);
				
		$lines=explode("\n",$wholeFile);
		
		$rec=$this->parseRowFromLine($lines[0]);
   		$rs->setColumnNames($rec);
   		
   		$rec=$this->parseRowFromLine($lines[1]);
   		$rs->setColumnTypes($rec);
   		
   		$rec=$this->parseRowFromLine($lines[2]);
   		$rs->setColumnDefaultValues($rec);
   		
   		$rs->reset();
   		
		$lineCount=count($lines);
  		for($i=3;$i<$lineCount;++$i) {
  			$rec=$this->parseRowFromLine($lines[$i]);		
  			if($rec) {
	 			$rs->appendRow($rec);	
	 		}
  		}
  		debug_print("<i>II: parseResultSetFromFile: " . (getmicrotime() - $start) . " seconds elapsed</i><br>");
  		
  		$rs->setColumnAliases(create_array_fill(count($rs->colNames),""));
  		$rs->setColumnTableAliases(create_array_fill(count($rs->colNames),""));
	
		return $rs;	
	}
	
	
	
	// $fd is a File Descriptor (returned by fopen)
	function parseResultSetIntoFile($fd, &$resultSet) {
    
    	debug_print( "parseResultSetIntoFileFD<br>");
		fwrite($fd, $this->parseLineFromRow($resultSet->getColumnNames()));
		fwrite($fd, "\n");
		
		fwrite($fd, $this->parseLineFromRow($resultSet->getColumnTypes()));
		fwrite($fd, "\n");
		
		fwrite($fd, $this->parseLineFromRow($resultSet->getColumnDefaultValues()));
		fwrite($fd, "\n");
		
		$resultSet->reset();
		while($resultSet->next()) {
			fwrite($fd, $this->parseLineFromRow($resultSet->getCurrentValues()));
			
			if($resultSet->getPos()<$resultSet->getRowCount()-1)
				fwrite($fd, "\n");
		}		
	}
	
	
	// $fd is a File Descriptor (returned by fopen)
	// Parses only the Column Names and Data Types and some of the last rows,
	// so the ResultSet can be used to append records only.
	function parseResultSetFromFileForAppend($fd) {
		
		$start=getmicrotime();
		$rs = new ResultSet();
		
		
		// COLUMN NAMES
		
		// read with a maximum of 1000 bytes, until there is a newline included (or eof)
		$buf="";
		while(is_false(strstr($buf,"\n"))) {
		    $buf.=fgets($fd,1000);
		    if(feof($fd)) {
		        print_error("Invalid Table File!<br>");
		        return null;
		    }
		}
		// remove newline
		remove_last_char($buf);
		
		$rec=$this->parseRowFromLine($buf);
   		$rs->setColumnNames($rec);
   		
   		
   		
   		// COLUMN TYPES
   		
   		// read with a maximum of 1000 bytes, until there is a newline included (or eof)
   		$buf="";
		while(is_false(strstr($buf,"\n"))) {
		    $buf.=fgets($fd,1000);
		    if(feof($fd)) {
				print_error("Invalid Table File!<br>");
		        return null;
		    }
		}
		
		// remove newline
		remove_last_char($buf);
			
		$rec=$this->parseRowFromLine($buf);
   		$rs->setColumnTypes($rec);
   		
   		
   		// COLUMN DEFAULT VALUES
   		
   		// read with a maximum of 1000 bytes, until there is a newline included (or eof)
   		$buf="";
		while(is_false(strstr($buf,"\n"))) {
		    $buf.=fgets($fd,1000);
		    if(feof($fd)) {
		        break; // there's no newline after the colum types => empty table
		    }
		}
		
		// remove newline
		if(last_char($buf)=="\n")
			remove_last_char($buf);
			
			
		$rec=$this->parseRowFromLine($buf);
   		$rs->setColumnDefaultValues($rec);
   		
   		
   		// get file size		
		fseek($fd,0,SEEK_END);
		$size=ftell($fd);
		
		$lastRecSize=min($size,ASSUMED_RECORD_SIZE);
		
		$lastRecPos=false;
		while(is_false($lastRecPos)) {
		    fseek($fd,-$lastRecSize,SEEK_END);
		    $buf=fread($fd,$lastRecSize);
		    $lastRecSize*=2;
		    $lastRecSize=min($size,$lastRecSize);
		    $lastRecPos=$this->getLastRecordPosInString($buf);
		    if(TXTDBAPI_VERBOSE_DEBUG) {
		        echo "<hr>pass! <br>";
		        echo "lastRecPos: " . $lastRecPos . "<br>";
		        echo "buf: " . $buf . "<br>";
            }
		    
		    
		}		
		
		$buf=trim(substr($buf,$lastRecPos));
		
		
		
		verbose_debug_print("buf after substr() and trim(): " . $buf . "<br>");
		   		
   		$rs->reset();
   		$row=$this->parseRowFromLine($buf);
   		
   		if(TXTDBAPI_VERBOSE_DEBUG) {
   		    echo "parseResultSetFromFileForAppend(): last Row:<br>";
   		    print_r($row);
   		    echo "<br>";
        }
        
   		
   		$rs->appendRow($row);	
   	
   		debug_print("<i>III: parseResultSetFromFileForAppend: " . (getmicrotime() - $start) . " seconds elapsed</i><br>");
   		
  		return $rs;	
	}
	
	
	// $fd is a File Descriptor (returned by fopen)
	function parseResultSetIntoFileAppend($fd, &$resultSet) {
        
    			
    	fwrite($fd, "\n");
		$resultSet->reset();
		while($resultSet->next()) {
			fwrite($fd, $this->parseLineFromRow($resultSet->getCurrentValues()));
			
			if($resultSet->getPos()<$resultSet->getRowCount()-1)
				fwrite($fd, "\n");
		}		
	}
	
	// returns an offset into $str, where the last record beginns
	// if $str doesn't contain one valid record, false is returned
	// (attention: may also return 0, which has not the same meaning as
	// false)
	function getLastRecordPosInString($str) {
	   
	    // contains other chars then whitespaces ?
	    if(strlen(trim($str))==0)
            return false;
        
        $pos=strlen($str)-1;
        
        while($str{$pos}=="\n" || $str{$pos}=="\r" || $str{$pos}=="\t" || $str{$pos}==" ") {
        	--$pos;
        	if($pos==-1)
        		return false;
        }
        while($str{$pos}!="\n" && $str{$pos}!="\r") {
        	--$pos;
        	if($pos==-1)
        		return false;
        }
        return $pos+1;
    }
	
}	

	
?>