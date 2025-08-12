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
//		Script: index.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

//-------------------------
//  Error Reporting
//-------------------------

error_reporting(E_ERROR | E_PARSE | E_WARNING);
set_error_handler("my_error_handler");

//---------------------------
//  Set up the timer
//---------------------------

class timer
{
	function start()
	{
		global $start_timer;
		$start_timer = microtime(true);
	}
	
	function stop()
	{
		global $start_timer;
		$stop_timer	 	= microtime(true);
		$total_time		= number_format( ($stop_timer - $start_timer) ,4);
		return $total_time;
	}
}

class info
{
	var $member = array();
}

//-------------------------
//  Define some stuff
//-------------------------

define ( 'DIRECT'		, 1 );
define ( 'VSOURCE_VER'	, '0.5 Beta' );

//---------------------------
//  Ready, Set, GO GO GO!!!
//---------------------------

	$timer = new timer;
	$timer->start();
	
//---------------------------
//  Get info stuff
//---------------------------

	$info = array();
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

	require("classes/class_skin.php");
	require("classes/class_session.php");
	require("includes/functions.php");
	
//-------------------------
//  Now set up some oop
//-------------------------
	
	$cms	 = new info;
	$vsource = new cmsfunc;
	$skin 	 = new skin;
	$ses	 = new session;
	
//-------------------------
//  And some info stuff
//-------------------------

	$cms->member = $ses->mem_info();
	
//-------------------------
//  Blocks stuff (Im drunk while im coding this ^_^)
//-------------------------

	require("blocks/block_login.php");
	$block = new block_login;
	$block->pages();

//-----------------------------
//  Lets set up the teh linkz
//-----------------------------

	$home	 = 'news';
	$pages	 = array(
					'home' 			=> $home,
					'news'			=> 'news',
					'about' 		=> 'about',
					'contact'		=> 'contact',
					'links'			=> 'links',
					'login'			=> 'login',
					'ucp'			=> 'usercp',
					'custompage'	=> 'custom',
					);
	
//-------------------------
//  Load the module
//-------------------------

		if(array_key_exists($_GET['id'], $pages))
		{
			foreach($pages as $id => $name)
			{
				$file = "modules/".$name.".php";
				
					if($_GET['id'] == $id && file_exists($file))
					{
						include $file;
						$run = new $name;
						$run->pages();
					}
			 }
		}
	
//-----------------------------------------------
//  Show the home page, or if was an dead link.
//-----------------------------------------------

		else
		{
			$file = "modules/".$home.".php";
			
				if(file_exists($file))
				{
					include $file;
					$run  = new $home;
					$run->pages();
				}
		}

//-------------------------
//  Load and compile skin
//-------------------------

	$skin->load('skin_global');
	$mainskin = new skin_global;
	//$skin->skin_global();
	$skin->compile_page();
	$mainskin->footer($db->count_queries(), VSOURCE_VER);
	
//-------------------------
//  Error Handler
//-------------------------

	function my_error_handler($errno, $errstr, $errfile, $errline)
	{
		switch($errno)
		{
			/*case E_WARNING:
			echo "<div><b>YourCMS Warning [{$errno}]:</b> {$errstr} on line {$errline} in {$errfile}</div>";
			break;*/
			case E_PARSE:
			echo "<div><b>YourCMS Warning [{$errno}]:</b> {$errstr} on line {$errline} in {$errfile}</div>";
			break;
			case E_ERROR:
			echo "<div><b>YourCMS Error  [{$errno}]:</b> {$errstr} on line {$errline} in {$errfile}</div>";
			break;
		}
		
	}

	
?>
