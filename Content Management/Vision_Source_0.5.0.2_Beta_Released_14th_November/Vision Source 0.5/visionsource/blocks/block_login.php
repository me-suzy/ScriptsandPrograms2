<?
/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 5th August 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: block_login.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

//-------------------------------------------------------
//	The blocks system is still under development,
//	this is just till fill in a block feature for now,
//	but this will be totally re-coded.
//--------------------------------------------------------

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class block_login {

	var $html = "";

	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages()
	{
		global $skin;
		$this->html = $skin->load('skin_blocks');
		
		$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
		switch ($do)
		{
				case "1":
					$this->login_block();
				break;
				default:
					$this->login_block();
				break;
		}
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
	
	function login_block()
	{
		global $db, $skin, $cms;
		
			if ($cms->member['is_member'] == 1)
			{
				$name = $cms->member['name'];
				$skin->blocks($this->html->login_ismem($name));
			}
			
			else
			{
				$skin->blocks($this->html->login_notmem());
			}
	
		//$skin->blocks($this->html->search());
	}
	
} 
 
?>