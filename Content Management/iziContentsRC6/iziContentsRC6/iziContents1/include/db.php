<?php

/***************************************************************************

 db.php
 -------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

require_once('includeSec.php');


include_once($GLOBALS["rootdp"].'include/adodb/adodb.inc.php');
$GLOBALS["dbDebug"] = False;


//  Write the details of every database statement executed to a log file if dbDebug is enabled
function dbWriteLog($message)
{
	global $_SERVER;

	$scriptref = $_SERVER["PHP_SELF"];
	$chainlink = explode('/',$scriptref);
	$script = array_pop($chainlink);

	$fp = fopen($GLOBALS["rootdp"]."ezc_dbAccess.log","ab");
	fwrite($fp,strftime('%x %X - ').$script.' - '.$message.chr(10));
	fclose($fp);
//	debug_msg(strftime('%x %X - ').$script.' - '.$message);
} // function dbWriteLog()


//  Special database functions
//  The 'create database' definition is used by the backup and multisite features
function dbTableDef($tablename)
{
	//  Create table statement
	$tabledef = "CREATE TABLE ".$tablename." (\n";

	//  Extract the list of fields from the table, and build the definitions into our 'create' string
	$TableDefs = $GLOBALS["dbConn"]->MetaColumns($tablename);
	$FieldList = $GLOBALS["dbConn"]->MetaColumnNames($tablename);
		
	foreach($FieldList as $ColumnName){
	//for ($j=0, $max=count($FieldList); $j<$max; $j++) {
		$ColumnName = strtoupper($ColumnName);
		$ColumnDef  = $TableDefs[$ColumnName];
			
		if ($ColumnDef->primary_key) { $PrimaryKey[] = $ColumnDef->name; }
		$FieldDef	= '		'.$ColumnDef->name.' '.$ColumnDef->type;
		if ($ColumnDef->max_length != -1) { $FieldDef .= '('.$ColumnDef->max_length.')'; }
		if ($ColumnDef->not_null) { $FieldDef .= ' NOT NULL'; }
		if ($ColumnDef->has_default) {
			if (($ColumnDef->type == 'varchar') || ($ColumnDef->type == 'char') || ($ColumnDef->type == 'text') || ($ColumnDef->type == 'datetime')) {
				$FieldDef .= ' DEFAULT \''.$ColumnDef->default_value.'\'';
			} 
			else {
				$FieldDef .= ' DEFAULT '.$ColumnDef->default_value;
			}
		} else {
			if (!$ColumnDef->auto_increment){ 
				if ($ColumnDef->not_null) {
					$FieldDef .= ' DEFAULT \'\'';
				} else {
					$FieldDef .= ' DEFAULT NULL';
				}
			}
		}
		if ($ColumnDef->auto_increment) { $FieldDef .= ' AUTO_INCREMENT'; }
		$tabledef .= $FieldDef;
		$tabledef .= ",\n";
	}
	$tabledef .= '		PRIMARY KEY ('.implode($PrimaryKey,',').')';
	$tabledef = ereg_replace(",\n$","",$tabledef);

	//  Generate a list of keys, excluding the primary (which we've already defined above)
	$sqlQuery = "SHOW KEYS FROM ".$tablename;
	$result = dbExecute($sqlQuery,true);
	while ($row = dbFetch($result)) {
		if ($row[Key_name] != 'PRIMARY') {
			$keyname = $row[Key_name];
			if ($row["Non_unique"] == 0) { $keyname="UNIQUE|".$keyname; }
			if (!isset($index[$keyname])) { $index[$keyname] = array(); }
			$index[$keyname][] = $row[Column_name];
		}
	}
	dbFreeResult($result);
	//  Add the key definitions to the 'create' string
	while(list($keytype, $columns) = @each($index)) {
		$tabledef .= ",\n";
		if (substr($keytype,0,6) == "UNIQUE") {
			$tabledef .= "		UNIQUE KEY ".substr($keytype,7)." (".implode($columns,", ").")";
		} else {
			$tabledef .= "		KEY ".$keytype." (".implode($columns,", ").")";
		}
	}

	//  Table type
	$tabledef .= "\n) TYPE=MyISAM;";
	
	//echo "<br> xx ".$tabledef."<br>";

	return $tabledef;
} // function dbTableDef()




//  Database functions
//  These are special functions for managing escaping strings and date conversion
//
function dbString($string)
{
	if (get_magic_quotes_gpc() == 0) {
		$rstring = addslashes($string);
	} else {
		$rstring = $string;
	}
	return $rstring;
} // function dbString()


function dbStr($string)
{
	$rstring = $GLOBALS["dbConn"]->qstr($string);
	$rstring = substr($rstring,1);
	$rstring = substr($rstring,0,-1);
	return $rstring;
} // function dbStr()


function dbDateTime($datetime)
{
	$rdatetime = $GLOBALS["dbConn"]->DBTimeStamp($datetime);
	$rdatetime = substr($rdatetime,1);
	$rdatetime = substr($rdatetime,0,-1);
	return $rdatetime;
} // function dbDate()




// Database error reporting
function dbError($errtrap,$errno,$errmess,$sqlQuery)
{
	if ($errtrap) {
		echo '<TABLE BORDER="1" BORDERCOLOR="BLACK" BGCOLOR="#900000" WIDTH="100%" CELLPADDING="2" CELLSPACING="2"><TR><TD>';
		echo '<TABLE BORDER="0" WIDTH="100%" CELLPADDING="3" CELLSPACING="3">';
		echo '<TR><TD align="'.$GLOBALS["right"].'" VALIGN="TOP"><FONT COLOR="WHITE"><B>DATABASE ERROR</B></FONT></TD><TD><FONT COLOR="WHITE"><B>'.$errno.' - '.$errmess.'</B></FONT></TD></TR>';
		echo '<TR><TD align="'.$GLOBALS["right"].'" VALIGN="TOP"><FONT COLOR="WHITE"><B>DATABASE QUERY WAS</B></FONT></TD><TD><FONT COLOR="WHITE"><B>'.$sqlQuery.'</B></FONT></TD></TR>';
		echo '</TABLE>';
		echo '</TD></TR></TABLE>';
		dbRollback();
		exit;
	}
} // function dbError()


//  Commit any outstanding transactions
function dbCommit()
{
	$GLOBALS["dbConn"]->CommitTrans();
	if ($GLOBALS["dbDebug"]) { dbWriteLog('COMMITTING TRANSACTION'); }
	$GLOBALS["dbConn"]->BeginTrans();
	if ($GLOBALS["dbDebug"]) { dbWriteLog('STARTING TRANSACTION'); }
} // function dbCommit()


//  Roll back any outstanding transactions
function dbRollback()
{
	$GLOBALS["dbConn"]->RollbackTrans();
	if ($GLOBALS["dbDebug"]) { dbWriteLog('ROLLING BACK TRANSACTION'); }
	$GLOBALS["dbConn"]->BeginTrans();
	if ($GLOBALS["dbDebug"]) { dbWriteLog('STARTING TRANSACTION'); }
} // function dbRollback()


//  Start timing a database
function dbTimeOn()
{
	if (isset($GLOBALS["gsTimegen_display"]) && (($GLOBALS["gsTimegen_display"] == 'Y') || ($GLOBALS["gsTimegen_display"] == 'F'))) {
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$GLOBALS["dbStartTime"] = $mtime;
	}
} // function dbTimeOn()


function dbTimeOff()
{
	if (isset($GLOBALS["gsTimegen_display"]) && (($GLOBALS["gsTimegen_display"] == 'Y') || ($GLOBALS["gsTimegen_display"] == 'F'))) {
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$GLOBALS["dbEndTime"] = $mtime;
		$totaltime = ($GLOBALS["dbEndTime"] - $GLOBALS["dbStartTime"]);
		$GLOBALS["dbTotalTime"] = $GLOBALS["dbTotalTime"] + $totaltime;
	}
	$GLOBALS["dbAccesses"]++;
} // function dbTimeOff()


// Connect to the database
function db_connect($DBServer,$DBName,$DBLogin,$DBPassword)
{
	dbTimeOn();
	$GLOBALS["dbConn"] = &ADONewConnection(strtolower($GLOBALS["ezContentsDB"]));
	if ($GLOBALS["dbPersistent"] == 'Y') {
		$GLOBALS["dbConn"]->PConnect($DBServer,$DBLogin,$DBPassword,$DBName)
			or die('<table border=0 cellpadding=8 cellspacing=8 width="100%"><tr><td align="center">Site unavailable. '.$GLOBALS["ezContentsDB"].' is not running.</td></tr></table>');
		dbRollback();
	} else {
		$GLOBALS["dbConn"]->Connect($DBServer,$DBLogin,$DBPassword,$DBName)
			or die('<table border=0 cellpadding=8 cellspacing=8 width="100%"><tr><td align="center">Site unavailable. '.$GLOBALS["ezContentsDB"].' is not running.</td></tr></table>');
		$GLOBALS["dbConn"]->BeginTrans();
	}
	if ($GLOBALS["dbDebug"]) { dbWriteLog('STARTING TRANSACTION'); }
	dbTimeOff();
} // function db_connect()


// Execute an SQL SELECT statement
function dbRetrieve($sqlQuery,$errtrap,$offset=0,$numrows=1)
{
	global $ADODB_FETCH_MODE;

	$GLOBALS["ADODB_FETCH_MODE"] = ADODB_FETCH_ASSOC;
	if ($GLOBALS["dbDebug"]) { dbWriteLog($sqlQuery.' - '.$offset.':'.$numrows); }
	dbTimeOn();
	if ($numrows > 0) {
		$result = &$GLOBALS["dbConn"]->SelectLimit($sqlQuery,$numrows,$offset);
	} else {
		$result = &$GLOBALS["dbConn"]->Execute($sqlQuery);
	}
	dbTimeOff();
	if ($result === False) { dbError($errtrap,
									 $GLOBALS["dbConn"]->ErrorNo(),
									 $GLOBALS["dbConn"]->ErrorMsg(),
									 $sqlQuery); }
	if ($GLOBALS["dbDebug"]) { dbWriteLog('Returned '.dbRowsReturned($result).' rows'); }
	return $result;
} // function dbRetrieve()


// Execute an SQL statement (e.g. INSERT or UPDATE)
function dbExecute($sqlQuery,$errtrap)
{
	global $ADODB_FETCH_MODE;

	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	if ($GLOBALS["dbDebug"]) { dbWriteLog($sqlQuery); }
	dbTimeOn();
	$result = &$GLOBALS["dbConn"]->Execute($sqlQuery);
	dbTimeOff();
	if ($result === False) { dbError($errtrap,
									 $GLOBALS["dbConn"]->ErrorNo(),
									 $GLOBALS["dbConn"]->ErrorMsg(),
									 $sqlQuery); }
	if ($GLOBALS["dbDebug"]) { dbWriteLog('Affected '.dbRowsAffected($result).' rows'); }
	return $result;
} // function dbExecute()


// The number of rows returned by the last SQL Select statement
function dbRowsReturned($result)
{
	$num_rows = $result->RecordCount();
	return $num_rows;
} // function dbRowsReturned()


// The number of rows affected by the last SQL Update or Delete statement
function dbRowsAffected($result)
{
	$num_rows = $GLOBALS["dbConn"]->Affected_Rows();
	return $num_rows;
} // function dbRowsReturned()


// Fetch the next result from a previously executed SQL statement
function dbFetch(&$result)
{
	if ($result->EOF) {
		$rs = False;
	} else {
		$rs = $result->fields;
		$result->MoveNext();
	}
	return $rs;
} // function dbFetch()


// Free the result set from a previously executed SQL statement
function dbFreeResult(&$result)
{
	$result->Close();
} // function dbFreeResult()


// Return the id value of the last autonumber inserted record
function dbInsertValue($sequence)
{
	$ival = $GLOBALS["dbConn"]->_InsertID();
	return $ival;
} // function dbInsertValue()

set_magic_quotes_runtime(False);

?>
