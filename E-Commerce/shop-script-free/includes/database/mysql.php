<?php
/*****************************************************************************
 *                                                                           *
 * Shop-Script FREE                                                          *
 * Copyright (c) 2005 WebAsyst LLC. All rights reserved.                     *
 *                                                                           *
 ****************************************************************************/

//	database functions :: MySQL

function db_connect($host,$user,$pass) //create connection
{
	return mysql_connect($host,$user,$pass);
}

function db_select_db($name) //select database
{
	return mysql_select_db($name);
}

function db_query($s) //database query
{
	return mysql_query($s);
}

function db_fetch_row($q) //row fetching
{
	return mysql_fetch_row($q);
}

function db_insert_id()
{
	return mysql_insert_id();
}

function db_error() //database error message
{
	return mysql_error();
}

?>