<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 17th March 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: class_db.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}


class db {

	var $query_count = 0;
	var $cache;
	
	function connect()
	{
	  global $info, $error;
			
			//-----------------
			//	Connect to db
			//-----------------
			
			if (!mysql_connect ("{$info['dbhost']}", "{$info['dbuser']}", "{$info['dbpass']}"))
			{
				$error->msg('Unable to connect to database. MYSQL ERROR: ' . mysql_error());
			}
			
			//---------------------
			// Select database
			//---------------------
			
			if (!mysql_select_db ("{$info['dbname']}"))
			{
				$error->msg('Unable to select database.');
			}
	}
    
    function query($query)
    {
	  global $info, $error;
	  
	  		//-------------------------------------------------------------------------------
			//	Check prefix info, and if not vsource..change to correct prefix in query 
			//-------------------------------------------------------------------------------
			
			if ($info['prefix'] != 'vsource')
			{
				$query = preg_replace('/\svsource(\S+?)([\s\.,]|$)/', ' '.$info['prefix'].'\\1\\2', $query);
			}
			
		//----------------------
		// Store query in cache
		//----------------------
			
		$this->cache = mysql_query($query);
		
			//---------------------------------------
			// Give error if query was didn't work
			//---------------------------------------
			
			if (!$this->cache)
			{
				  $error->sqlerror(mysql_error(), $query);
			}
				
        $this->query_count++;
        return $this->cache;
    }
	
	function query_noerror($query)
    {
		//---------------------------------------------------------------------------------------------------------
		// Old function before query() was re-written. Here for backup purposes, highly suggest you do not use it.
		//----------------------------------------------------------------------------------------------------------
		
        $this->cache = mysql_query($query);
        $this->query_count++;
        return $this->cache;
    }
    
    function fetchrow($query = '')
    {
	  
	  	//-------------------------------------
		// If query in function, then use it!!
		//-------------------------------------
		
        if(!empty($query))
        {	
			$this->query($query);
            return mysql_fetch_array($this->cache );    
        }
		
		//------------------------------------------
		// If no query in function, then use cache
		//------------------------------------------
		
        else
        {
			return mysql_fetch_array($this->cache);
        }
    }
    
    function number_rows($query = '')
    {
		//-------------------------------------
		// If query in function, then use it!!
		//-------------------------------------
		
        if(!empty($query))
		{
            return @mysql_num_rows($query);    
        }
		
		//------------------------------------------
		// If no query in function, then use cache
		//------------------------------------------
		
        else
        {
            return @mysql_num_rows($this->cache);
        }
    }
	
	function check_input($input)
	{
	
		//---------------------------------------------------------------------------------
		//	Checking query for sql injection (Recommended to use this for all user input)
		//---------------------------------------------------------------------------------
		
		if (get_magic_quotes_gpc())
		{
			$input = stripslashes($input);
		}
		
   		//-------------------------------
		// Quote it, if it's not integer
		//-------------------------------
		
   		if (!is_numeric($input))
		{
       		$input = mysql_real_escape_string($input);
   		}
		
		return $input;
	}
 
	
	function freemysql($query = '')
	{
		//--------------------
		// Free all results
		//--------------------
		
		if(!empty($query))
        {
            return @mysql_free_result($query);    
        }
		
        else
        {
            return @mysql_free_result($this->cache);
        }
		
	}
	
  	function count_queries()
	{
		//-----------------------------------
		// Send the number of queries used.
		//-----------------------------------
		
		return $this->query_count;
	}
}


?>
