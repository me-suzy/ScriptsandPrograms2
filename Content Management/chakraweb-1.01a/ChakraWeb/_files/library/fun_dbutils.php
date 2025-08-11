<?php
// ----------------------------------------------------------------------
// ModName: fun_dbutils.php
// Purpose: Simplify database call procedures
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_dbutils.php] file directly...");


define(BLOB_WRITE_CHUNK, 256);

function DbGetUniqueID($code)
{
	global $db;
	$ui_id = 1;	

	$sql = "SELECT ui_id from sysuids where ui_code='$code'";
	$rs = &$db->Execute($sql);
	if ($rs === false) DbFatalError('DbGetUniqueID'); 

	if ($rs->EOF)
	{
		//the table name not yet inserted
		$ui_id = 1;
		$sql = "insert into sysuids (ui_code, ui_id) values ('$code', 2)";
		//print $sql;
		$db->Execute($sql);
	}
	else
	{
		$ui_id = $rs->fields[0];
		$sql = "update sysuids set ui_id=ui_id+1 where ui_code='$code'";
		$db->Execute($sql);
	}
	return $ui_id;
}

function DbUpdateBlobFile($table, $column, $filename, $where)
{
	global $db;

	$fh = fopen($filename, "rb");
	$size = filesize($filename);

	//now this is bullshit, but have to read the file piece by piece and insert because 
	//the mysql server is set up to only handle 1meg inserts (small buffer and packet).

	$buffer = addslashes(fread($fh, BLOB_WRITE_CHUNK));
	$sql = "UPDATE $table SET $column = '$buffer' where $where";
	//PrintLine($sql, 'SQL');
	$db->Execute($sql);

	while (ftell($fh) < $size) 
	{
		$buffer = addslashes(fread($fh, BLOB_WRITE_CHUNK));
		//$buffer = str_replace(chr(0), chr(1), $buffer);
		$sql = "UPDATE $table SET $column = concat($column,'$buffer') where $where";
		//PrintLine($sql, 'SQL');
		$db->Execute($sql);
	}
	fclose ($fh);

	return true;
}

function DbUpdateBlobContent($table, $column, $content, $where)
{
	global $db;

	$length = strlen($content);
	$pos = 0;

	$buffer = addslashes(substr($content, $pos, BLOB_WRITE_CHUNK));
	$sql = "UPDATE $table SET $column = '$buffer' where $where";
	//PrintLine($sql, 'SQL');
	$db->Execute($sql);

	$pos += BLOB_WRITE_CHUNK;
	while($pos < $length) 
	{
		$buffer = addslashes(substr($content, $pos, BLOB_WRITE_CHUNK));
		$sql = "UPDATE $table SET $column = concat($column,'$buffer') where $where";
		//print $buffer."<br>\r\n";
		//PrintLine($sql, 'SQL');
		$db->Execute($sql);

		$pos += BLOB_WRITE_CHUNK;
		//print $pos."<br>\r\n";
	}
	return true;
}

function DbCurrentTime()
{
	global $db;
	return $db->DBTimeStamp(time());
}

function DbCurrentDate()
{
	global $db;
	return $db->DBTimeStamp(adodb_date('Y-m-d 00:00:00', time()));
}

function DbFatalError($section, $msg='')
{
	global $db;

	if (!empty($msg))
		$msg .= '. ';

	$msg .= $db->ErrorMsg();

	DbLogWrite($section, '.MSG:', $msg);
	SystemFatalError($section, $msg);
}

//return field value of the row in associated array format
//this function ensure that recordset return the 
//associated format, overcome bug in $ADODB_FETCH_MODE
function DbGetFieldValues($rs)
{
	$arrOut = array();

	$fldCount = $rs->FieldCount();
	for($i=0; $i<$fldCount; $i++)
	{
		$fld = $rs->FetchField($i);
		//PrintLine($fld);
		$arrOut[$fld->name] = $rs->fields[$i];
	}
	return $arrOut;
}

function DbGetOneValue($sql, $default=false)
{
	global $db;

	DbLogWrite('SQL:', $sql);

	$rs = $db->Execute($sql);

	if ($rs && !$rs->EOF)
	{
		return $rs->fields[0];
	}

	return $default;
}

function DbExecute($sql)
{
	global $db;

	$rs = $db->Execute($sql);
   
	$sql = StrStripWhiteSpaces($sql);
	DbLogWrite('SQL:', $sql);
	return $rs;
}

function DbSqlSelect($table, $columns, $where='', $order='')
{
	global $db;

	$sql = "select $columns from $table ";
	if (!empty($where))
		$sql .= ' where ('.$where.')';
	if (!empty($order))
		$sql .= ' order by '.$order;

	$rs = $db->Execute($sql);

	$sql = StrStripWhiteSpaces($sql);
	DbLogWrite('SQL:', $sql);

	return $rs;
}

function DbSqlInsert($table, $columns, $values)
{
	global $db;

	$sql = "insert into $table ($columns) values ($values)";
	DbLogWrite('SQL:', $sql);

	return $db->Execute($sql);
}

function DbSqlUpdate($table, $colvalues, $where)
{
	global $db;

	$sql = "update $table set $colvalues where ($where)";
	DbLogWrite('SQL:', $sql);
	return $db->Execute($sql);
}

function DbSqlDelete($table, $where)
{
	global $db;

	$sql = "delete from $table where ($where)";
	DbLogWrite('SQL:', $sql);
	return $db->Execute($sql);
}


function DbLogFileOpen()
{
	global $gBaseLocalPath;

    //PrintLine("Enter DbLogFileOpen");

	$sep = GetLocalPathSeparator();
	$filename = $gBaseLocalPath.'logs'.$sep.DBLOG_PREFIX.date('Y-m-d', time()).'.log';

	$fh = @fopen($filename, 'a');
	if (!$fh)
		SystemFatalError('DbLogFileOpen', 'Unable to open/create file '.$filename.'<br>WebMaster, ensure the existance of directory and access right');

    //PrintLine("Leave DbLogFileOpen");

	return $fh;
}

function DbLogWrite()
{
	global $gLogDBase;

    //PrintLine("Enter DbLogWrite");

	if ($gLogDBase)
	{
		$fh = DbLogFileOpen();

		@fputs($fh, date('H:i:s', time())."\t".UserGetName()."\t");
    	foreach (func_get_args() as $var) 
			@fputs($fh, $var);

		@fputs($fh, "\n");
		@fclose($fh);
	}

    //PrintLine("Leave DbLogWrite");
}




?>
