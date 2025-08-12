<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 20th July 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: admin.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

session_start(); //Start the session

//-------------------------
//  Error Reporting
//-------------------------

error_reporting(E_ERROR | E_PARSE | E_WARNING);
set_error_handler("my_error_handler");

//---------------------------
//  Set up info
//---------------------------

class info
{
	var $skin_id 	 = 'vsource';
	var $VSOURCE_VER = '0.5 Beta';
}

//-------------------------
//  Define some stuff
//-------------------------

define ( 'DIRECT'		, 1 );
define ( 'ACP'			, 1 );
define ( 'VSOURCE_VER'	, '0.5 Beta' );

//---------------------------
//  Get info stuff
//---------------------------

	$info 	 = array();
	$ad_info = new info;
	require("includes/config.php");
	
//-------------------------
//  Set up error handler
//-------------------------

	require("includes/error_handler.php");
	$error = new error_handler;
	
//-------------------------	
//  Set up the db
//-------------------------

	require("classes/class_db.php");
	$db	= new db;
	$db->connect();

//-------------------------	
//  Get classes files
//-------------------------

	require("admin/ad_functions.php");
	require("includes/functions.php");
	
//-------------------------
//  Now set up some oop
//-------------------------

	$admin	 = new ad_func;
	$vsource = new cmsfunc;
	
//------------------------------
//  Make sure they are an admin
//------------------------------
class load_page
{
	var $session_id	= "";
	var $html		= "";
	var $output 	= "";
	
	function load()
	{
		global $admin, $db;
		$this->html = $admin->load('skin_global');
		$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
		switch ($do)
		{
				case "1":
					$this->login();
				break;
				case "2":
					$this->do_login();
				break;
				case "3":
					$this->is_loggedin();
				break;
				default:
					$this->login();
				break;
		}
		$admin->do_output("$this->output");
	}
	
	function login()
	{
		$this->output .= $this->html->login();
	}
	
	function do_login()
	{
		global $db, $admin;
			if (empty($_POST['username'])) 
				{
					$this->output .= $this->html->custerror('You did not enter in a username');
					return;
				}
				
				if (empty($_POST['password']))
				{
					$this->output .= $this->html->custerror('You did not enter in a password');
					return;
				}
				
				else
				{
					$username = $_POST['username'];
					$password = md5( $_POST['password'] );
					$db->query('SELECT * FROM vsource_users WHERE username="'.$username.'" AND password="'.$password.'" AND admin="1"');
						if ($db->number_rows() == "1")
						{
							$sesid = md5(uniqid(microtime()));
							$db->query('UPDATE vsource_users SET session="'.$sesid.'" WHERE username="'.$username.'" AND password="'.$password.'" AND admin="1"');
							$row = $db->fetchrow('SELECT * FROM vsource_users WHERE username="'.$username.'" AND password="'.$password.'" AND admin="1"');
							$_SESSION['admin_userid'] = $row['id'];
							$_SESSION['admin_passhash'] = $row['password'];
							$db->freemysql();
							$admin->redirect("Your log in was succesfull", 'admin.php?ses='.$sesid.'&do=3');
						}
						
						else 
						{
							echo 'failed';
							$db->freemysql();
							exit();
						}
				}
	}
	
	function is_loggedin()
	{	
		global $admin, $db;
		$this->session_id = $_GET['ses'];
		$db->query('SELECT * FROM vsource_users WHERE id="'.$_SESSION['admin_userid'].'" AND password="'.$_SESSION['admin_passhash'].'" AND session="'.$this->session_id.'"');
	
			if ($db->number_rows() == "0")
			{
				echo 'Please log in';
			}
			
	//-----------------------------
	//  Lets set up the teh linkz
	//-----------------------------
	
		if ($db->number_rows() == "1")
		{
		$db->freemysql();
		$pages	 = array(
						'main' 			=> 'admin_main',
						'menu'			=> 'admin_menu',
						'general'		=> 'admin_general',
						'manage_news'	=> 'admin_news',
						'manage_users'	=> 'admin_users',
						'manage_about'	=> 'admin_about',
						'manage_skin'	=> 'admin_skin',
						'manage_custom'	=> 'admin_custom',
						'manage_links'	=> 'admin_links',
						);
		
	//-------------------------
	//  Load the module
	//-------------------------
	
			if(array_key_exists($_GET['id'], $pages))
			{
				foreach($pages as $id => $name)
				{
					$file = "admin/pages/".$name.".php";
					
						if($_GET['id'] == $id && file_exists($file))
						{
							include $file;
							$run = new $name;
							$run->pages();
						}
				 }
			}
		
	//----------------------
	//  Show the main page
	//----------------------
	
			else
			{
				$admin->compile_page();
			}
			
			
		}
		
	}

}

$run = new load_page;
$run->load();

//-------------------------
//  Load and compile skin
//-------------------------

	//$admin->compile_page();
	
//-------------------------
//  Error Handler
//-------------------------

	function my_error_handler($errno, $errstr, $errfile, $errline)
	{
		switch($errno)
		{
			case E_WARNING:
			echo "<div><b>YourCMS Warning [{$errno}]:</b> {$errstr} on line {$errline} in {$errfile}</div>";
			break;
			case E_PARSE:
			echo "<div><b>YourCMS Warning [{$errno}]:</b> {$errstr} on line {$errline} in {$errfile}</div>";
			break;
			case E_ERROR:
			echo "<div><b>YourCMS Error  [{$errno}]:</b> {$errstr} on line {$errline} in {$errfile}</div>";
			break;
		}
		
	}

	
?>