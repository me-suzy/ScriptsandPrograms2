<?php
// -------------------------------------------------------------
//
// $Id: mysql.php,v 1.6 2005/05/05 07:25:10 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

class mysql
{
	// ---------------------------
	var $host = SQL_HOST;
	var $port = SQL_PORT;
	var $user = SQL_USER;
	var $password = SQL_PASSWORD;
	var $database = SQL_DATABASE;
	// ---------------------------
	var $link_id;
	var $num_queries = 0;
	var $query_id;
	// ---------------------------

	function close()
	{
		return ($this->link_id) ? @mysql_close($this->link_id) : false;
	}

	function connect()
	{
		$server = $this->host . (($this->port) ? ':' . $this->port : '');
		$this->link_id = @mysql_connect($server, decode($this->user), decode($this->password));
		@mysql_select_db(decode($this->database), $this->link_id);
		return ($this->link_id) ? $this->link_id : $this->error('');
	}

	function error($sql)
	{
		if (!$this->link_id)
		{
			echo '<p>Connection to MySQL server failed.</p>';
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
		return ($this->query_id) ? @mysql_fetch_array($this->query_id) : false;
	}

	function insert_id()
	{
		return ($this->link_id) ? @mysql_insert_id($this->link_id) : false;
	}

	function num_queries()
	{
		return $this->num_queries;
	}

	function num_rows()
	{
		return ($this->query_id) ? @mysql_num_rows($this->query_id) : false;
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
			$this->query_id = @mysql_query($sql, $this->link_id);
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

	function version()
	{
		return substr(@mysql_get_server_info($this->link_id), 0, 7);
	}
}

?>