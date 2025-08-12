<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 3rd March 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: functions.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class cmsfunc {

	function is_member() {
		global $db, $cms;
			
			if ($cms->member['is_member'] == 1)
			{
				return 1;
			}
			
			else
			{
				return 0;
			}
			
			/* Old Stuff (deleted due to slows up time with too many queries.
				$userid			= $_COOKIE['unameid'];
				$password		= $_COOKIE['passhash'];
				
					if (empty($userid) OR empty($password))
					{
						return;
					}
					
				$db->query('SELECT id, username, session, password FROM vsource_users WHERE password="'.$password.'" AND id='.$userid.'');
	
					if ($db->number_rows() == "1")
					{
						$db->freemysql();
						return 1;
					}
							
					else
					{
						$db->freemysql();
						return 0;
					}*/

	}
	
	function is_admin()
	{
		global $db;
		
			$userid			= $_COOKIE['unameid'];
			$password		= $_COOKIE['passhash'];
			
				if (empty($userid) OR empty($password))
				{
						return;
				}
				
			$db->query('SELECT id, username, session, password, admin FROM vsource_users WHERE password="'.$password.'" AND id='.$userid.' AND admin="1"');
	
				if ($db->number_rows() == "1")
				{
					$db->freemysql();
					return 1;
				}
							
				else
				{
					$db->freemysql();
					return 0;
				}
	}
	
	function get_mem_info()
	{
		global $db;
		$userid			= $_COOKIE['unameid'];
		$password		= $_COOKIE['passhash'];
			
			if (empty($userid) OR empty($password))
			{
				return;
			}
				
		$db->query('SELECT * FROM vsource_users WHERE password="'.$password.'" AND id="'.$userid.'"');
		return $db->fetchrow();
	}
	
	function get_mem_name()
	{
		global $db;
		$userid			= $_COOKIE['unameid'];
		$password		= $_COOKIE['passhash'];
			
			if (empty($userid) OR empty($password))
			{
				return;
			}
				
		$db->query('SELECT * FROM vsource_users WHERE password="'.$password.'" AND id="'.$userid.'"');
		$m = $db->fetchrow();
		$db->freemysql();
		return $m['username'];
	}
}


?>