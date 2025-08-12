<?php
// --------------------------------------------
// | The EP-Dev Forum News script        
// |                                           
// | Copyright (c) 2002-2004 EP-Dev.com :           
// | This program is distributed as free       
// | software under the GNU General Public     
// | License as published by the Free Software 
// | Foundation. You may freely redistribute     
// | and/or modify this program.               
// |                                           
// --------------------------------------------

/* ------------------------------------------------------------------ */
//	MySQL Access Class
//
//	Controls single database connection / obj. By doing this the script
//	can easily manage multiple databases as well as easily update db
//	access as needed.
/* ------------------------------------------------------------------ */


class EP_Dev_Forum_News_MYSQL
{
	var $ERROR;

	var $link;
	var $result;

	function EP_Dev_Forum_News_MYSQL($username, $password, $host, $name, $prefix, &$error_handle)
	{
		// +------------------------------
		//	Initialize error handle
		// +------------------------------

		$this->ERROR = $error_handle;

		
		// +------------------------------
		//	Initialize database connection
		// +------------------------------

		$this->prefix = $prefix;
		
		// connect to mysql
		$this->link = @mysql_connect($host, $username, $password, true)
			or $this->ERROR->stop("mysql_connect_error");

		// select database
		@mysql_select_db($name, $this->link)
			or $this->ERROR->stop("mysql_db_error");
	}

	/* ------------------------------------------------------------------ */
	//	query
	//  Runs query on database
	/* ------------------------------------------------------------------ */
	
	function query($query)
	{
		$this->result = @mysql_query($query, $this->link)
			or $this->ERROR->kill("MYSQL ERROR: " . mysql_errno() . " : " . mysql_error() . " QUERY: " . $query);
	}


	/* ------------------------------------------------------------------ */
	//	rows
	//  Return number of rows in result
	/* ------------------------------------------------------------------ */
	
	function rows($result = NULL)
	{
		if ($result == NULL)
			$result = $this->result;

		return mysql_num_rows($result);
	}


	/* ------------------------------------------------------------------ */
	//	fetch_array
	//  Returns result in array form
	/* ------------------------------------------------------------------ */
	
	function fetch_array($result = NULL)
	{
		if ($result == NULL)
			$result = $this->result;

		$array_result = mysql_fetch_array($result);

		return $array_result;
	}


	/* ------------------------------------------------------------------ */
	//	value
	//  returns first value of result
	/* ------------------------------------------------------------------ */
	
	function value($result = NULL)
	{
		$array_result = $this->fetch_array($result) ;
		return $array_result[0];
	}


	/* ------------------------------------------------------------------ */
	//	Custom Function
	//  Allows for custom function to be called on database
	/* ------------------------------------------------------------------ */
	
	function custom($func, $result = NULL)
	{
		if ($result == NULL)
			$result = $this->result;
		
		$this->result = @"mysql_".$func($result);

		return $this->result;
	}


}