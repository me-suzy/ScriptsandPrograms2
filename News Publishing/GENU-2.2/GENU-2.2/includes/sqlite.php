<?php
// -------------------------------------------------------------
//
// $Id: sqlite.php,v 1.3 2005/05/05 07:24:45 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

class sqlite
{
	// ---------------------------
	var $database = SQL_DATABASE;
	// ---------------------------
	var $link_id;
	var $num_queries = 0;
	var $query_id;
	// ---------------------------

	function close()
	{
		return ($this->link_id) ? @sqlite_close($this->link_id) : false;
	}

	function connect()
	{
		$this->link_id = @sqlite_open(decode($this->database));
		return ($this->link_id) ? $this->link_id : $this->error('');
	}

	function error($sql)
	{
		if (!$this->link_id)
		{
			echo '<p>Connection to SQLite database failed.</p>';
			exit();
		}
		if (!$this->query_id)
		{
			printf('<p>Error in query "<code>%s</code>".</p>', $sql);
			exit();
		}
	}

	function fetch()
	{
		return ($this->query_id) ? $this->shorten(@sqlite_fetch_array($this->query_id)) : false;
	}

	function insert_id()
	{
		return ($this->link_id) ? @sqlite_last_insert_rowid($this->link_id) : false;
	}

	function num_queries()
	{
		return $this->num_queries;
	}

	function num_rows()
	{
		return ($this->query_id) ? @sqlite_num_rows($this->query_id) : false;
	}

	function query($sql = '')
	{
		if (!$this->connect())
		{
			return false;
		}
		elseif ($sql != '')
		{
			$this->num_queries++;
			$this->query_id = @sqlite_query($this->link_id, $sql);
			if ($this->query_id)
			{
				return $this->query_id;
			}
			else
			{
				return $this->error($sql);
			}
		}
		else
		{
			return false;
		}
	}

	function shorten($array)
	{
		if (is_array($array))
		{
			foreach ($array as $k => $v)
			{
				$k = substr($k, strpos($k, '.') + 1);
				$array[$k] = $v;
			}
		}
		return $array;
	}
}

?>