<?php
//	-----------------------------------------
// 	$File: mysql.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-02-23
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------
// DB class borrowed from punBB, author Rickard Andersson (rickard@punbb.org)
//
// Create the database object (and connect to/select db)
$db = new DBLayer($dbhost, $dbuser, $dbpw, $dbname, $db_prefix);

class DBLayer
{
	var $prefix;
	var $link_id;
	var $query_result;
	var $row = array();


	function DBLayer($dbhost, $dbuser, $dbpw, $dbname, $db_prefix)
	{
		$this->prefix = $db_prefix;

		$this->link_id = @mysql_connect($dbhost, $dbuser, $dbpw);

		if ($this->link_id)
		{
			if (@mysql_select_db($dbname, $this->link_id))
			{
				return $this->link_id;
			}
			else
			{
				error('Unable to select database. '.mysql_error(), __LINE__, __FILE__);
			}
		}
		else
		{
				error('Unable to connect to MySQL server. '.mysql_error(), __LINE__, __FILE__);
		}
	}


	function query($sql = '')
	{
		unset($this->query_result);

		if ($sql != '')
		{
			$this->query_result = @mysql_query($sql, $this->link_id);
		}

		if ($this->query_result)
		{
			unset($this->row[$this->query_result]);

			return $this->query_result;
		}
	}


	function result($query_id = 0, $row = 0)
	{
		if (!$query_id)
			$query_id = $this->query_result;

		return ($query_id) ? @mysql_result($query_id, $row) : false;
	}


	function fetch_array($query_id = 0)
	{
		if (!$query_id)
			$query_id = $this->query_result;

		if ($query_id)
		{
			$this->row[$query_id] = @mysql_fetch_array($query_id);
			return $this->row[$query_id];
		}
		else
			return false;
	}

	function fetch_row($query_id = 0)
	{
		if (!$query_id)
			$query_id = $this->query_result;

		if ($query_id)
		{
			$this->row[$query_id] = @mysql_fetch_row($query_id);
			return $this->row[$query_id];
		}
		else
			return false;
	}


	function num_rows($query_id = 0)
	{
		if (!$query_id)
			$query_id = $this->query_result;

		return ($query_id) ? @mysql_num_rows($query_id) : false;
	}

	function error()
	{
		$result['error'] = @mysql_error($this->link_id);
		$result['errno'] = @mysql_errno($this->link_id);

		return $result;
	}

	function close()
	{
		if ($this->link_id)
		{
			if ($this->query_result)
				@mysql_free_result($this->query_result);

			return @mysql_close($this->link_id);
		}
		else
			return false;
	}
}
	
?>
