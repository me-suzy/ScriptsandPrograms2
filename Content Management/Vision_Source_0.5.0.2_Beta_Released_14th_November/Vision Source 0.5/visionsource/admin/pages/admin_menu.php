<?
/*
//////////////////////////////////////////////////////////////
//															//
//		YourCMS v0.5 Beta									//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 3rd March 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: admin_menu.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class admin_menu
{

	var	$output = "";
	var $html = "";

	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $admin;
		
		//$this->html = $admin->load('skin_menu');
		$item = !empty($_GET['item']) ? $_GET['item'] : FALSE;
		switch ($item) {
				case "1":
					$this->home();
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
	
	
	function home() {
	
	global $db;
		$sesid = $_GET['ses'];
		
		$this->output .= '<a href="admin.php?ses='.$sesid.'&amp;do=3&amp;id=main" target="main">Home</a>
		<p><a href="admin.php?ses='.$sesid.'&amp;do=3&amp;id=manage_news" target="main">Manage News</a></p>
		<p><a href="admin.php?ses='.$sesid.'&amp;do=3&amp;id=manage_about" target="main">Manage About</a></p>
		<p><a href="admin.php?ses='.$sesid.'&amp;do=3&amp;id=manage_users" target="main">Manage Users</a></p>
		<p><a href="admin.php?ses='.$sesid.'&amp;do=3&amp;id=general" target="main">General Config</a></p>
		<p><a href="admin.php?ses='.$sesid.'&amp;do=3&amp;id=manage_skin" target="main">Manage Skins</a></p>
		<p><a href="admin.php?ses='.$sesid.'&amp;do=3&amp;id=manage_custom" target="main">Manage Custom Pages</a></p>
		<p><a href="admin.php?ses='.$sesid.'&amp;do=3&amp;id=manage_links" target="main">Manage Links</a></p>';

 
	}
	
} 
 
?>