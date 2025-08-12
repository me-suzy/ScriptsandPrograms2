<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 27th June 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: class_session.php							//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class session {

	var $member = array();

	function mem_info()
	{
		global $cms, $db;
		
			//---------------------------
			// Set up member info array
			//---------------------------
			
			$this->member['cookie_id']			= $_COOKIE['unameid'];
			$this->member['cookie_passhash']	= $_COOKIE['passhash'];
			$db->query('SELECT * FROM vsource_users WHERE id="'.$this->member['cookie_id'].'" AND password="'.$this->member['cookie_passhash'].'"');
				
					if ($db->number_rows() == 1)
					{
						$row = $db->fetchrow();
						$this->member['id']		  	= $row['id'];
						$this->member['is_member'] 	= 1;
						$this->member['skinid']		= $row['skinid'];
						$this->member['name']		= $row['username'];
							
							if ($row['admin'] == 1)
							{
								$this->member['is_admin']  = 1;
							}
							
							else
							{
								$this->member['is_admin']  = 0;
							}	
					}
					
					else
					{		
						$this->member['id']		  	= "Guest";
						$this->member['is_member'] 	= 0;
						$this->member['is_admin']  	= 0;
						$this->member['skinid']		= "";
					}
					
			$this->member['ip'] = $_SERVER['REMOTE_ADDR'];
			
			return $this->member;
						
	}

	function set_usercookie($userid, $password)
	{
		global $db, $skin, $cms;
						
			$db->query('SELECT id, username, session, password FROM vsource_users WHERE password="'.$password.'" AND username="'.$userid.'"');
							
				if ($db->number_rows() == "1")
				{
					$row 					  = $db->fetchrow();
					$id						  = $row['id'];
					$pswdhash				  = $row['password'];
					$username				  = $row['username'];
					$this->member['is_member'] = 1;
					$this->member['id']		  = $id;
					
						if ($row['admin'] == 1)
						{
							$this->member['is_admin'] == 1;
						}
						
						else
						{
							$this->member['is_admin'] == 0;
						}
						
					$db->freemysql();
								
						setcookie(unameid, $id, time()+365*24*60*60);
						setcookie(passhash, $pswdhash, time()+365*24*60*60);
						$skin->redirect("Thanks for logging in: ".$username, "index.php");
				}
								
				else
				{
					echo "<script type='text/javascript'>window.alert('Login not successfull'); window.location = 'index.php';</script>";
					$db->freemysql();
				}
					
	}
	
	function unset_usercookie()
	{
		setcookie('unameid', "0", time()-1800);
		setcookie('passhash', "0", time()-1800);
		
	}
	
	function set_cookie($name, $data, $time)
	{
		 setcookie($name, $data, time()+$time);
	}
	
}


?>