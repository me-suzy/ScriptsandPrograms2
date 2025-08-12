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
//		Script: about.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class about {

	var $output = "";
	var $html 	= "";
	
	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $skin;
		
		$this->html = $skin->load('skin_about');
		$skin->do_title("About");
		$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
		
			switch ($do)
			{
				case "main":
					$this->home();
				break;
				default:
					$this->home();
				break;
			}
			
		$skin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	function home() {

		global $db;
		$db->query("SELECT * FROM vsource_about");
		$row		= $db->fetchrow();	
		$content	= $row['content']; 

		$this->output .= $this->html->showabout($content);	
		$db->freemysql();

	}
	
}
 

?>