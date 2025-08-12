<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------------------+
// | WebCards Version 1.0 - A powerful, easy to configure e-card system               |
// | Copyright (C) 2003  Chris Charlton (corbyboy@hotmail.com)                        |
// |                                                                                  |
// |     This program is free software; you can redistribute it and/or modify         |
// |     it under the terms of the GNU General Public License as published by         |
// |     the Free Software Foundation; either version 2 of the License, or            |
// |     (at your option) any later version.                                          |
// |                                                                                  |
// |     This program is distributed in the hope that it will be useful,              |
// |     but WITHOUT ANY WARRANTY; without even the implied warranty of               |
// |     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                |
// |     GNU General Public License for more details.                                 |
// |                                                                                  |
// |     You should have received a copy of the GNU General Public License            |
// |     along with this program; if not, write to the Free Software                  |
// |     Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    |
// |                                                                                  |
// | Authors: Chris Charlton <corbyboy@hotmail.com>                                   |
// | Official Homepage: http://webcards.sourceforge.net                               |
// | Project Homepage: http://www.sourceforge.net/projects/webcards                   |
// +----------------------------------------------------------------------------------+
//
// $Id: MySQL.php,v 1.00 2004/06/07 22:52:43 chrisc Exp $

class DB {

	var $dbhost;
	var $dbuser;
	var $dbpass;
	var $dbname;
	var $persistent_connection = false;
	var $connection = null;
	var $result = null;
	var $count = 0;
	var $row_count = 0;
	var $query_array = array();

function DB ($dbhost, $dbuser, $dbpass, $dbname, $persistent_connection = false)
{
	$this->dbhost = $dbhost;
	$this->dbuser = $dbuser;
	$this->dbpass = $dbpass;
	$this->dbname = $dbname;
	$this->persistent_connection = $persistent_connection;
}

function connect()
// Connects to the required database
{
	if ($this->persistent_connection == "true")
	{
		$type = "mysql_pconnect";
	}
	else
	{
		$type = "mysql_connect";
	}
	$this->connection = @$type($this->dbhost, $this->dbuser, $this->dbpass);
	if (!$this->connection)
	{
		return false;
	}
	if (!@mysql_select_db($this->dbname, $this->connection))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function disconnect()
// disconnects from the database
{
	return(@mysql_close($this->connection));
}

function error()
// displays errors as we need
{
	$output = "<h3>MySQL said: " . mysql_error() . "<br />Error number: " . mysql_errno() . "</h3>";
	return $output;
}

function query($sql)
{
	$this->result = @mysql_query($sql, $this->connection);
	$this->count++;
	$this->query_array[] = htmlspecialchars($sql);
	return $this->result;
}

function row_count($sql)
{
	$this->row_count = @mysql_query($sql);
	$this->row_count = @mysql_result($this->row_count, 0);
	$this->count++;
	$this->query_array[] = htmlspecialchars($sql);
	return $this->row_count;
}

//Some functions for showing updated results.

function affected()
{
	return (@mysql_affected_rows($this->connection));
}

function num_rows()
{
	return (@mysql_num_rows($this->result));
}

function next_value()
{
	return (@mysql_insert_id($this->connection));
}

function fetch_array()
// A function to pull results
{
	return (@mysql_fetch_array($this->result, MYSQL_BOTH));
}

function fetch_row()
{
	return (@mysql_fetch_row($this->result));
}

function finished()
{
	return $this->count;
}

function show_queries()
{
	return $this->query_array;
}

}
?>