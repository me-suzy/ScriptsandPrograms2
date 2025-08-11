<?php 
// ----------------------------------------------------------------------
// ModName: cls_dbase.php
// Purpose: Database Class for MySQL
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [cls_dbase.php] file directly...");

define('ADODB_FETCH_NUM',1);
define('ADODB_FETCH_ASSOC',2);
define('TIMESTAMP_FIRST_YEAR',100);

$ADODB_FETCH_MODE = ADODB_FETCH_NUM;


class DBase 
{
	var $conn = false;
	var $host = '';
	var $name = '';
	var $user = '';
	var $password = '';
	var $isoDates = false; 					// accepts dates in ISO format
	
	var $replaceQuote = "\\'"; 				// string to use to replace quotes
	var $fmtDate 	  = "'Y-m-d'";			// used by DBDate() as the default date format used by the database
	var $fmtTimeStamp = "'Y-m-d, h:i:s A'"; // used by DBTimeStamp as the default timestamp fmt.

	function Connect($argHost, $argUser, $argPassword, $argDbName) 
	{
		$this->Disconnect();
		$this->conn = @mysql_connect($argHost,$argUser,$argPassword);
		if ($this->conn)
		{
			if (@mysql_select_db($argDbName, $this->conn))
			{
 				$this->host = $argHost;
				$this->name = $argDbName;
				$this->user = $argUser;
				$this->password = $argPassword;
				return true;
			}
			else
				die("Could not select database '$argDbName'.");
		}
		else
			die("Could not connect to database at '$argHost'.");

		return false;
	}
 
	function Disconnect()
	{
		if ($this->conn != false)
		{
			@mysql_close($this->conn);
		}
		$this->conn = false;
		$this->host = '';
		$this->name = '';
		$this->user = '';
		$this->password = '';

		return true;
	}

	function Execute($sql)
	{
		//echo "Execute: ".$sql."<br>\n";

		$rst = false;

		$handle = @mysql_query($sql, $this->conn);
		if ($handle)
		{
			$rst = new Recordset($this->conn, $handle, $sql);
		}
		else
		{
			//echo $this->ErrorMsg()."<br>\n";
			//echo "SQL: $sql<br>\n";

			//die();
		}
		return $rst;
	}	

	function ErrorNo()
	{
		return @mysql_errno();
	}

	function ErrorMsg()
	{
		return 'DBError('.@mysql_errno().'): '.@mysql_error();
	}

	function qstr($s,$magic_quotes=false)
	{	
		if (!$magic_quotes) {
		
			if ($this->replaceQuote[0] == '\\'){
				// only since php 4.0.5
				$s = str_replace(array('\\',"\0"),array('\\\\',"\\\0"),$s);
				//$s = str_replace("\0","\\\0", str_replace('\\','\\\\',$s));
			}
			return  "'".str_replace("'",$this->replaceQuote,$s)."'";
		}
		
		// undo magic quotes for "
		$s = str_replace('\\"','"',$s);
		
		if ($this->replaceQuote == "\\'")  // ' already quoted, no need to change anything
			return "'$s'";
		else {// change \' to '' for sybase/mssql
			$s = str_replace('\\\\','\\',$s);
			return "'".str_replace("\\'",$this->replaceQuote,$s)."'";
		}
	}


	/**
	 * Converts a date "d" to a string that the database can understand.
	 *
	 * @param d	a date in Unix date time format.
	 *
	 * @return  date string in database date format
	 */
	function DBDate($d)
	{
	
		if (empty($d) && $d !== 0) return 'null';

		if (is_string($d) && !is_numeric($d)) 
			if ($this->isoDates) return "'$d'";
			else $d = $this->UnixDate($d);
			
		return adodb_date($this->fmtDate,$d);
	}
	
	
	/**
	 * Converts a timestamp "ts" to a string that the database can understand.
	 *
	 * @param ts	a timestamp in Unix date time format.
	 *
	 * @return  timestamp string in database timestamp format
	 */
	function DBTimeStamp($ts)
	{
		if (empty($ts) && $ts !== 0) return 'null';

		if (is_string($ts) && !is_numeric($ts)) 
			if ($this->isoDates) return "'$ts'";
			else $ts = $this->UnixTimeStamp($ts);
			
		return adodb_date($this->fmtTimeStamp,$ts);
	}
	
	/**
	 * Also in ADORecordSet.
	 * @param $v is a date string in YYYY-MM-DD format
	 *
	 * @return date in unix timestamp format, or 0 if before TIMESTAMP_FIRST_YEAR, or false if invalid date format
	 */
	function UnixDate($v)
	{
		if (!preg_match( "|^([0-9]{4})[-/\.]?([0-9]{1,2})[-/\.]?([0-9]{1,2})|", 
			($v), $rr)) return false;

		if ($rr[1] <= TIMESTAMP_FIRST_YEAR) return 0;
		// h-m-s-MM-DD-YY
		return @adodb_mktime(0,0,0,$rr[2],$rr[3],$rr[1]);
	}
	

	/**
	 * Also in ADORecordSet.
	 * @param $v is a timestamp string in YYYY-MM-DD HH-NN-SS format
	 *
	 * @return date in unix timestamp format, or 0 if before TIMESTAMP_FIRST_YEAR, or false if invalid date format
	 */
	function UnixTimeStamp($v)
	{
		if (!preg_match( 
			"|^([0-9]{4})[-/\.]?([0-9]{1,2})[-/\.]?([0-9]{1,2})[ -]?(([0-9]{1,2}):?([0-9]{1,2}):?([0-9\.]{1,4}))?|", 
			($v), $rr)) return false;
		if ($rr[1] <= TIMESTAMP_FIRST_YEAR && $rr[2]<= 1) return 0;
	
		// h-m-s-MM-DD-YY
		if (!isset($rr[5])) return  adodb_mktime(0,0,0,$rr[2],$rr[3],$rr[1]);
		return  @adodb_mktime($rr[5],$rr[6],$rr[7],$rr[2],$rr[3],$rr[1]);
	}
	
	/**
	 * Also in ADORecordSet.
	 *
	 * Format database date based on user defined format.
	 *
	 * @param v  	is the character date in YYYY-MM-DD format, returned by database
	 * @param fmt 	is the format to apply to it, using date()
	 *
	 * @return a date formated as user desires
	 */
	 
	function UserDate($v,$fmt='Y-m-d')
	{
		$tt = $this->UnixDate($v);
		// $tt == -1 if pre TIMESTAMP_FIRST_YEAR
		if (($tt === false || $tt == -1) && $v != false) return $v;
		else if ($tt == 0) return $this->emptyDate;
		else if ($tt == -1) { // pre-TIMESTAMP_FIRST_YEAR
		}
		
		return adodb_date($fmt,$tt);
	
	}


}


class Recordset 
{
	var $handle = false;
	var $fields = false; 	/// holds the current row data
	var $sql; 				/// sql text
	var $EOF = false;		/// Indicates that the current record position is after the last record in a Recordset object. 
	var $rows = 0;
	var $cols = 0;
	var $currow = 0;
	var $fetch_mode = 0;

	function Recordset($argConn, $argHandle, $argSql)
	{
		global $ADODB_FETCH_MODE;
		$this->fetch_mode = $ADODB_FETCH_MODE;

		$this->conn = $argConn;
		$this->handle = $argHandle;
		$this->sql = @trim($argSql);

		$sql_lower = @strtolower($this->sql);
		$pos = strpos($sql_lower, 'select');

		if ($pos === false)
		{
			$this->rows = @mysql_affected_rows($this->conn);
			$this->cols = 0;
			$this->EOF = true;
		}
		else
		{
			$this->rows = @mysql_num_rows($this->handle);
			$this->cols = @mysql_num_fields($this->handle);
			if ($this->rows > 0)
				$this->EOF = false;
			else
				$this->EOF = true;

			$this->currow = -1;
			$this->MoveNext();
		}
	}

	function Close()
	{
		@mysql_free_result($this->handle);
	}

	function MoveNext()
	{
		$rst = false;

		$this->currow++;

		if ($this->currow < $this->rows)
		{
			$rst = @mysql_data_seek($this->handle, $this->currow);
			if ($rst === false)
			{
				die("mysql_data_seek failed.");
			}
			else
			{
				//echo "MoveTo ".$this->currow."<br>\n";

				$this->fields = false;
				if ($this->fetch_mode == ADODB_FETCH_NUM)
					$this->fields = @mysql_fetch_row($this->handle);
				else
					$this->fields = @mysql_fetch_array($this->handle, MYSQL_ASSOC);

				if ($this->fields === false)
					die("mysql_fetch_row failed.");
			}
		}
		else
		{
			//echo "EOF ".$this->currow."<br>\n";

			$this->fields = false;
			$this->EOF = true;
		}
	}


	function GetRows($nRows = -1)
	{
		$results = array();
		$cnt = 0;
		while (!$this->EOF && $nRows != $cnt) 
		{
			$results[] = $this->fields;
			$this->MoveNext();
			$cnt++;
		}
		return $results;
	}

	function RowCount()
	{
		return $this->rows;
	}

	function FieldCount()
	{
		return $this->cols;	
	}

	function FetchField($index)
	{
		return @mysql_fetch_field($this->handle, $index); 
	}

	function FieldAsDateTimeFmt($index, $fmt)
	{
		return adodb_date($fmt, $this->FieldAsDateTime($index));
	}

	function FieldAsDateTime($index)
	{
		$ts = $this->fields[$index];
		return adodb_mktime(substr($ts, 8, 2), substr($ts, 10, 2), substr($ts, 12, 2), substr($ts, 4, 2), substr($ts, 6, 2), substr($ts, 0, 4));
	}

	function FieldAsTimeStamp($index)
	{
		$ts = $this->fields[$index];
		return substr($ts, 0, 4).'-'.substr($ts, 4, 2).'-'.substr($ts, 6, 2).' '.
				substr($ts, 8, 2).':'.substr($ts, 10, 2).':'.substr($ts, 12, 2);
	}

	function FieldAsDate($index)
	{
		$ts = $this->fields[$index];
		return substr($ts, 0, 4).'-'.substr($ts, 4, 2).'-'.substr($ts, 6, 2);
	}
}

?>