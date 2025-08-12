<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////

// ############################################################ \\
|| MySQL Class                                                  ||
|| */ $mysqlClassVersion = '1.4.8'; /*                          ||
|| Written by: Brian Earley                                     ||
|| Build Date: 2004-06-04                                       ||
|| Copyright (c) 2003 UtopiaSoftware.net - All rights reserved  ||
\\ ############################################################ //

*/

require('config.inc.php');

// Set query count to 0, which will later rise upon each database query
$query_count = 0;

// +------------------------------------------------------------------+
// | Start Database Class                                             |
// +------------------------------------------------------------------+

class DB_Driver {
	// ********************************************************
	// Vars
	// ********************************************************
	var $database = '';
	var $hostname = 'localhost';
	var $user = 'root';
	var $password = '';
	var $persistent = '';
	var $db_link = 0;
	var $do_function;
	// ********************************************************
	// Database Connect
	// ********************************************************
	function connect()
	{
		if (0 == $this->db_link)
		{
			if ($this->password == '')
			{
				if ($this->persistent == '1')
				{
					$this->db_link = @mysql_pconnect($this->hostname, $this->user);
					if (!$this->db_link)
					{
						$this->dberror('Unable to connect to database host '.$this->hostname);
					}
				}
				else
				{
					$this->db_link = @mysql_connect($this->hostname, $this->user);
					if (!$this->db_link)
					{
						$this->dberror('Unable to connect to database host '.$this->hostname);
					}
				}
			}
			else
			{
				if ($this->persistent == '1')
				{
					$this->db_link = @mysql_pconnect($this->hostname, $this->user, $this->password);
					if (!$this->db_link)
					{
						$this->dberror('Unable to connect to database host '.$this->hostname);
					}
				}
				else
				{
					$this->db_link = @mysql_connect($this->hostname, $this->user, $this->password);
					if (!$this->db_link)
					{
						$this->dberror('Unable to connect to database host '.$this->hostname);
					}
				}
			}
		}
	}
	// ********************************************************
	// Database Select
	// ********************************************************
	function selectdb()
	{
		$this->do_function = @mysql_select_db($this->database, $this->db_link);
		if (!$this->do_function)
		{
			$this->dberror('Unable to select database '.$this->database);
		}
	}
	// ********************************************************
	// Database Query
	// ********************************************************
	function query($query, $surpress=0)
	{
		$this->do_function = @mysql_query($query, $this->db_link);
		if (!$this->do_function && ($surpress == 0))
		{
			$this->dberror('Invalid SQL Query: '. $query);
		}
		global $query_count;
		$query_count++;
		return $this->do_function;
	}
	// ********************************************************
	// Data Fetch Array
	// ********************************************************
	function fetch_array($querystr)
	{
		if (isset($querystr))
		{
			$this->do_function = @mysql_fetch_array($querystr);
			return $this->do_function;
		}
		else
		{
			$this->dberror('Invalid query specified.');
		}
	}
	// ********************************************************
	// Data Fetch Object
	// ********************************************************
	function fetch_object($querystr)
	{
		if (isset($querystr))
		{
			$this->do_function = @mysql_fetch_object($querystr);
			return $this->do_function;
		}
		else
		{
			$this->dberror('Invalid query specified.');
		}
	}
	// ********************************************************
	// Data Check - Single Row Return
	// ********************************************************
	function is_single_row($querystr)
	{
		if (isset($querystr))
		{
			$this->do_function = $this->num_rows($querystr);
			if ($this->do_function == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$this->dberror('Invalid query specified.');
		}
	}
	// ********************************************************
	// Data Rows Number
	// ********************************************************
	function num_rows($querystr)
	{
		$this->do_function = @mysql_num_rows($querystr);
		return $this->do_function;
	}
	// ********************************************************
	// Data Check - Single Row Return
	// ********************************************************
	function checkdb()
	{
		$this->do_function = $this->query("SHOW TABLE STATUS", 1);
		if ($this->num_rows($this->do_function) < 1)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	// ********************************************************
	// Escape String
	// ********************************************************
	function escape_string($string)
	{
		if (isset($string))
		{
			$string = str_replace("\\", "\\\\", $string);
			$string = str_replace("\0", '\0', $string);
			$string = str_replace("\n", '\n', $string);
			$string = str_replace("\r", '\r', $string);
			$string = str_replace("'", '\\\'', $string);
			$string = str_replace("\"", '\"', $string);
			$string = str_replace("\032", "\\Z", $string);
			return $string;
		}
		else
		{
			return false;
		}
	}
	// ********************************************************
	// Database Error Handler
	// ********************************************************
	function dberror($msg)
	{
		$this->errordesc = mysql_error();
		$this->errornum = mysql_errno();
		global $config;
		strlen($config['techemail']) ? $techemail = $config['techemail'] : $techemail = false;
		$message = 'Database error in Utopia News Pro:'."\n\n".$msg."\n";
		$message.= 'MySQL Error: '.$this->errordesc."\n\n";
		$message.= 'MySQL Error Number: '.$this->errornum."\n\n";
		$message.= 'Date: '.date('F j, Y h:i A')."\n";
		$message.= 'Script: '.$_SERVER['REQUEST_URI']."\n";
		if ($techemail)
		{
			@mail($techemail, 'Utopia News Pro Database Error', $message, "From: $techemail");
		}
		echo '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<title>Utopia News Pro Database Error</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<style type="text/css">
		body, p { 
			font-family: verdana,arial,helvetica,sans-serif;
			font-size: 11px;
		}
		</style>
		</head>
		<body>'."\n\n";
		echo '
		<blockquote><p><strong>Fatal error caused by fault in database.</strong><br />'."\n";
		echo '
		You may try this action again by pressing <a href="javascript:window.location=window.location;">refresh</a>.</p>'."\n";
		echo '
		This error message has been forwarded to our <a href="mailto: '.$techemail.'">technical administrator</a>.'."\n";
		echo '
		<p>We apologise for any inconvenience.</p>'."\n";
		echo '
		<form action="null"><textarea rows="10" cols="55">'.htmlspecialchars($message).'</textarea></form>';
		echo '</blockquote>
		</body>
		</html>';
		exit;
	}
}
?>