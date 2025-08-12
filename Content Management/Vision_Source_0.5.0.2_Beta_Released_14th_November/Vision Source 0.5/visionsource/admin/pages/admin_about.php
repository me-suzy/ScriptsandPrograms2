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
//		Script: admin_news.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class admin_about
{

	var	$output = "";
	var $html 	= "";
	var $sesid	= "";

	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $admin;
		
		$this->sesid = $_GET['ses'];
		$this->html = $admin->load('skin_about');
		$item = !empty($_GET['item']) ? $_GET['item'] : FALSE;
		switch ($item) {
				case "1":
					$this->manage_about();
				break;
				case "2":
					$this->do_edit();
				break;
				default:
					$this->manage_about();
				break;
			}
			
		$admin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	
	function manage_about()
	{
		global $db;
			
			$db->query('SELECT * FROM vsource_about');
			$row = $db->fetchrow();
			$info['content'] = ereg_replace('<br />', '', $row['content']);
			$this->output .= $this->html->showabout($info);
	}
	
	function do_edit()
	{
		global $db;
		
			$text	= nl2br($_POST['about_text']);
				
				if (empty($text))
				{
					$this->output .= $this->html->error('No message entered');
					return;
				}
				
		
		//---------------------------
		//  Set the about text in db
		// --------------------------
		
			$db->query('UPDATE vsource_about SET content="'.$text.'"');
			$this->output .= $this->html->edit_complete();
			
	}
	
} 
 
?>