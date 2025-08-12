<?php

/*
	------------------------------------------------------------------------------------------------
	db_driver.PHP
	Version 1.0
	------------------------------------------------------------------------------------------------
	This driver is an abstraction layer for accessing a database server in a generic way.
	
	This file is the MySQL 4 version.

        License Information 
        ------------------- 
         
        Copyright (C) 2003-2005 Galistudio & Fabien Papleux.  All rights reserved.
         
        This program is free software; you can redistribute it and/or modify it under the terms 
        of the GNU General Public License as published by the Free Software Foundation, 
        version 2 of the License. 

        This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
        without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
        See the GNU General Public License for more details. 

        You should have received a copy of the GNU General Public License along with this program; 
        if not, you can get a copy at http://www.gnu.org/licenses/gpl.txt 
         
         
         
        Contact Information 
        ------------------- 
         
        Should you have any questions / remarks / suggestions, please don't hesitate to 
        contact me at info@galistudio.com or at http://www.galistudio.com.


	SYNOPSIS
	--------
	
	$link_id      = function db_connect ($server, $user, $pwd);
	$result       = function db_close ($link_id = 0);
	$error_text   = function db_error ($link_id = 0);
	$error_number = function db_errno ($link_id = 0);
	
	$result       = function db_select ($db_name, $link_id = 0);
	$result       = function db_create ($db_name, $link_id = 0);	
	$result       = function db_drop_database ($db_name, $link_id = 0);	
	
	$result       = function db_table_exists ($table_name, $database_name);
	$result       = function db_drop_table ($table_name, $database_name);
	$result       = function db_create_table ($table_name, $database_name);
					NOTE: The table creation function automatically creates the first
					field called 'ID', a autoincrement integer primary index
 
	$result_id    = function db_query ($query, $link_id = 0);
	$rows		  = function db_num_rows ($result_id);
	$row          = function db_fetch_row ($result_id);
	$array        = function db_fetch_array ($result_id);
	$id           = function db_insert_id ($link_id);
	

*/


//	------------------------------------------------------------------------------------------------
//	CONSTANTS DEFINITIONS
//	------------------------------------------------------------------------------------------------
	define("DB_DRIVER", 1);
	define("DB_DRIVER_VERSION", "MySQL - 1.0Beta");




//	------------------------------------------------------------------------------------------------
//	DB_CONNECT
//	------------------------------------------------------------------------------------------------
	function db_connect ($server, $user, $pwd) {
		return @mysql_connect($server, $user, $pwd);
	}


//	------------------------------------------------------------------------------------------------
//	DB_CLOSE
//	------------------------------------------------------------------------------------------------
	function close($link_id = 0) {
		if ($link_id) return @mysql_close($link_id);
		else return @mysql_close();
	}


//	------------------------------------------------------------------------------------------------
//	DB_ERROR
//	------------------------------------------------------------------------------------------------
	function db_error ($link_id = 0) {
		if ($link_id) return @mysql_error($link_id);
		else return @mysql_error();
	}

//	------------------------------------------------------------------------------------------------
//	DB_ERRNO
//	------------------------------------------------------------------------------------------------
	function db_errno ($link_id = 0) {
		if ($link_id) return @mysql_errno($link_id);
		else return @mysql_errno();
	}

//	------------------------------------------------------------------------------------------------
//	DB_SELECT
//	------------------------------------------------------------------------------------------------
	function db_select ($db_name, $link_id = 0) {
		if ($link_id) return @mysql_select_db($db_name, $link_id);
		else return @mysql_select_db($db_name);
	}


//	------------------------------------------------------------------------------------------------
//	DB_CREATE
//	------------------------------------------------------------------------------------------------
	function db_create ($db_name, $link_id = 0) {
		if ($link_id) return @mysql_create_db($db_name, $link_id);
		else return @mysql_create_db($db_name);
	}


//	------------------------------------------------------------------------------------------------
//	DB_DROP
//	------------------------------------------------------------------------------------------------
	function db_drop_database ($db_name, $link_id = 0) {
		if ($link_id) return @mysql_drop_db($db_name, $link_id);
		else return @mysql_drop_db($db_name);
	}




//	------------------------------------------------------------------------------------------------
//	DB_TABLE_EXISTS
//	------------------------------------------------------------------------------------------------
	function db_table_exists($table_name, $database_name) {
		$found = 0;
		$result_id = mysql_list_tables($database_name);
		while ($record = mysql_fetch_row($result_id))
			if (strtoupper($table_name) == strtoupper($record[0])) $found = 1;
		return $found;
	}



//	------------------------------------------------------------------------------------------------
//	DB_DROP_TABLE
//	------------------------------------------------------------------------------------------------
	function db_drop_table($table_name, $database_name) {
		$success = FALSE;
		if (db_table_exists($table_name, $database_name)) {
			if (db_select($database_name)) {
				$sql = "DROP TABLE `{$database_name}`.`{$table_name}`";
				$success = db_query($sql);
				}
			}
		return $success;
	}



//	------------------------------------------------------------------------------------------------
//	DB_CREATE_TABLE
//	------------------------------------------------------------------------------------------------
	function db_create_table($table_name, $database_name) {
		if (mysql_select_db($database_name)) {
			$sql = "CREATE TABLE `{$database_name}`.`{$table_name}` (
  					`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY);";
			db_query($sql);
			}
		if (db_errno() == 0) return TRUE;
		else return FALSE;
	}

//	------------------------------------------------------------------------------------------------
//	DB_QUERY
//	------------------------------------------------------------------------------------------------
	function db_query ($query, $link_id = 0) {
		if ($link_id) return @mysql_query($query, $link_id);
		else return @mysql_query($query);
	}


//	------------------------------------------------------------------------------------------------
//	DB_QUERY
//	------------------------------------------------------------------------------------------------
	function db_num_rows ($result_id) {
		return @mysql_num_rows($result_id);
	}

//	------------------------------------------------------------------------------------------------
//	DB_FETCH_ROW
//	------------------------------------------------------------------------------------------------
	function db_fetch_row ($result_id) {
		return @mysql_fetch_row ($result_id);
	}


//	------------------------------------------------------------------------------------------------
//	DB_FETCH_ARRAY
//	------------------------------------------------------------------------------------------------
	function db_fetch_array ($result_id) {
		return @mysql_fetch_array ($result_id);
	}


//	------------------------------------------------------------------------------------------------
//	DB_INSERT_ID
//	------------------------------------------------------------------------------------------------
	function db_insert_id ($link_id) {
		return @mysql_insert_id($link_id);
	}
