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
//		Script: admin_main.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class admin_main
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
	
		$this->output .= 'Welcome to Vision Source Admin Control Panel. Here you can control the whole cms and make any changes required.
						 <p>
						 Remeber Vision Source is still in beta stages and this current version ' . $ad_info['VSOURCE_VER'] .' is still in beta stages so not all features
						 have been added. Some features that will be added in the feature before the final release are:
						 <ul>
						 	<li>Fully skinned ACP with better navigation</li>
							<li>Inline CSS editor</li>
						 	<li>More control over users eg. Ability to ban users, Stop guest from commeting on news etc.</li>
							<li>Much, Much more</li>
						</ul>
						Remember that is just a small taste of whats to come so please visit us at <a href="http://www.visionsource.org" target="_blank">www.visionsource.org</a>
						where you can get any help that you will need.
						</p>
						';
 
	}
	
} 
 
?>