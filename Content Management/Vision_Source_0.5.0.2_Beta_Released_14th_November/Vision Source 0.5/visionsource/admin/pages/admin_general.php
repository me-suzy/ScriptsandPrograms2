<?
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
//		Script: admin_general.php							//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class admin_general
{

	var	$output = "";
	var $html = "";

	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $admin;
		
		$this->html = $admin->load('skin_general');
		$item = !empty($_GET['item']) ? $_GET['item'] : FALSE;
		switch ($item) {
				case "1":
					$this->home();
				break;
				case "2":
					$this->do_update();
				break;
				default:
					$this->home();
				break;
			}
			
		$admin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	
	function home()
	{
	  global $info;
		
		$this->output .= $this->html->edit_form($info);
	}
	
	function do_update()
	{
	
		 $dbhost		= $_POST['dbhost']; 
		 $dbuser		= $_POST['dbuser']; 
		 $dbpass		= $_POST['dbpass']; 
		 $dbname		= $_POST['dbname'];
		 $prefix		= $_POST['prefix'];
		 $url			= $_POST['base_url'];
		 $adminemail	= $_POST['email'];
		 $title			= $_POST['title'];
		 $newslimit		= $_POST['news_limit'];
		 
		 	if (empty($dbhost) || empty($dbuser) || empty($dbpass) || empty($dbname) || empty($prefix) || empty($title) || empty($url) || empty($adminemail) || empty($newslimit))
			{
				$this->output .= $this->html->error('One or more fields were empty!');
				return;
			}
			
			else
			{
		 
		 		$writefile = '<?php 
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
//		Script: config.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( "DIRECT" ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

 $info["dbhost"]		= "'.$dbhost.'"; 
 $info["dbuser"]		= "'.$dbuser.'"; 
 $info["dbpass"]		= "'.$dbpass.'"; 
 $info["dbname"]		= "'.$dbname.'"; 
 $info["prefix"]		= "'.$prefix.'"; 
 $info["title"]			= "'.$title.'";
 $info["base_url"]		= "'.$url.'"; 
 $info["email"] 		= "'.$adminemail.'";
 $info["news_limit"] 	= "'.$newslimit.'"
  
?>';
   
		   $fileopen		= @fopen('includes/config.php','w');
				if (!$fileopen)
				{
					echo "Can't write to file. Please check permisions.";
					exit;
				}
				
				else
				{
					@fwrite($fileopen,$writefile);
					@fclose($fileopen);
					$this->output .= "Config Updated";
				}
		}
  	}
	
	
} 
 
?>