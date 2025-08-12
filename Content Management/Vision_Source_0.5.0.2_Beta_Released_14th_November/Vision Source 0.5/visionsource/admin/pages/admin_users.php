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
class admin_users
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
		$this->html = $admin->load('skin_users');
		$item = !empty($_GET['item']) ? $_GET['item'] : FALSE;
		switch ($item) {
				case "1":
					$this->home();
				break;
				case "2":
					$this->add_user();
				break;
				case "3":
					$this->do_add_user();
				break;
				case "4":
					$this->edit_user();
				break;
				case "5":
					$this->do_edit_user();
				break;
				case "6":
					$this->delete_user();
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
	  global $db;
	  
	  	$db->query('SELECT * FROM vsource_users ORDER BY id ASC');
		$this->output .= $this->html->manage_top();
		
			while ($row = $db->fetchrow())
			{
				$this->output .= $this->html->manage($row);
			}
			
		$this->output .= $this->html->manage_bottom();
	}
	
	function add_user()
	{
		$this->output .= $this->html->add_user();			
	}
	
	function do_add_user()
	{
		global $db, $vsource, $error;

			
			//-------------------------
			//  Store some vars!
			//-------------------------	
			
			$username	= $_POST['username'];
			$password	= $_POST['password'];
			$email		= $_POST['email'];
			$admin		= $_POST['admin'];
		
			//-------------------------
			//  check for errors!
			//-------------------------	
			
			if (empty($username))
			{
				$this->output .= $error->error($error = 'You did not enter in a username');
				$this->output .= $this->html->add_user();			
				return;
			}
			
			if (empty($password))
			{
				$this->output .= $error->error($error = 'You did not enter in a password');
				$this->output .= $this->html->add_user();
				return;
			}
			
			if (empty($_POST['password_check']))
			{
				$this->output .= $error->error($error = 'You did not enter in a second password');
				$this->output .= $this->html->add_user();
				return;
			}
			
			if (empty($email))
			{
				$this->output .= $error->error($error = 'You did not enter in a email address');
				$this->output .= $this->html->add_user();
				return;
			}
			
			if (strlen($username) > 32)
			{
				$this->output .= $error->error($error = 'Your username must not be more than 32 characters');
				$this->output .= $this->html->add_user();
				return;
			}
			
			if (strlen($password) < 3)
			{
				$this->output .= $error->error($error = 'Your password must be more than 3 characters');
				$this->output .= $this->html->add_user();
				return;
			}
			
			if (strlen($email) < 6)
			{
				$this->output .= $error->error($error = 'The email address you entered was incorrect');
				$this->output .= $this->html->add_user();
				return;
			}
			
			if ($password != $_POST['password_check'])
			{
				$this->output .= $error->error($error = 'Your passwords did not match');
				$this->output .= $this->html->add_user();
				return;
			}
			
			else
			{
			
				//------------------------------------------
				//	Now check for any illegal characters
				//------------------------------------------
				
				if (preg_match("/[^a-z0-9_-]/i", $username))
				{
					$this->output .= $error->error('The username contained illegal characters.');
					$this->output .= $this->html->add_user();
					return;
				}
				
				//-----------------------------------
				//	See if the user is an admin
				//-----------------------------------
				
				if ($admin == "on")
				{
					$admin = 1;
				}
				
				else
				{
					$admin = 0;
				}
			
				//----------------------------
				//  Now check db for errors!
				//----------------------------
				
				$password = md5( $password );
				
				$db->query('SELECT username FROM vsource_users WHERE username="'.$username.'"');
				
					if ($db->number_rows() == "1")
					{
						$this->output .= $error->error($error = 'Username already taken, Please choose another one.');
						$this->output .= $this->html->add_user();
					}
				
				//----------------------------
				//  Add user to db!
				//----------------------------
					
					else
					{
						$db->query('INSERT INTO vsource_users SET username="'.$username.'", password="'.$password.'", email="'.$email.'", reg_ip="127.0.0.1", admin="'.$admin.'"');
						$this->output .= $this->html->reg_complete();
					}
					
				//$db->freemysql();
			}
		
		}
		
		function edit_user()
		{
		  global $db;
		  
		  	$userid = intval($_GET['userid']);
			$db->query('SELECT * FROM vsource_users WHERE id="'.$userid.'"');
			$row = $db->fetchrow();
			
				if ($row['admin'] == "1")
				{
					$admin = 1;
				}
				
				else
				{
					$admin = 0;
				}
				
		 	$this->output .= $this->html->edit_user($row, $admin);
		}
		
		function do_edit_user()
		{
		  global $db, $error;
		  
			//-------------------------
			//  Store some vars!
			//-------------------------	
			
			$username	= $_POST['username'];
			$password	= $_POST['password'];
			$email		= $_POST['email'];
			$admin		= $_POST['admin'];
			$userid		= $_POST['userid'];
		
			//-------------------------
			//  check for errors!
			//-------------------------	
			
				if (empty($username))
				{
					$this->output .= $error->error($error = 'You did not enter in a username', $back = true);
					return;
				}
				
				if (empty($email))
				{
					$this->output .= $error->error($error = 'You did not enter in a email address', $back = true);
					return;
				}
				
				if (strlen($username) > 32)
				{
					$this->output .= $error->error($error = 'Your username must not be more than 32 characters', $back = true);
					return;
				}
				
				if (strlen($email) < 6)
				{
					$this->output .= $error->error($error = 'The email address you entered was incorrect', $back = true);
					return;
				}
				
				else
				{
				
						//------------------------------------------
						//	Now check for any illegal characters
						//------------------------------------------
						
						if (preg_match("/[^a-z0-9_-]/i", $username))
						{
							$this->output .= $error->error('The username contained illegal characters.', $back = true);
							return;
						}
						
						//-----------------------------------
						//	See if the user is an admin
						//-----------------------------------
						
						if ($admin == "on")
						{
							$admin = 1;
						}
						
						else
						{
							$admin = 0;
						}
				
					//----------------------------
					//  Now check db for errors!
					//----------------------------
					
					$db->query('SELECT username FROM vsource_users WHERE username="'.$username.'" AND id != "'.$userid.'"');
					
						if ($db->number_rows() == "1")
						{
								$this->output .= $error->error($error = 'Username already taken, Please choose another one.');
								return;
						}
					
					//----------------------------
					//  Add user to db!
					//----------------------------
				
							if (empty($password))
							{
								$db->query('UPDATE vsource_users SET username="'.$username.'", email="'.$email.'", admin="'.$admin.'" WHERE id="'.$userid.'"');
								$this->output .= $this->html->edit_complete();
							}
							
							else
							{
								$password = md5( $password );
								$db->query('UPDATE vsource_users SET username="'.$username.'", password="'.$password.'", email="'.$email.'", admin="'.$admin.'" WHERE id="'.$userid.'"');
								$this->output .= $this->html->edit_complete();
							}
						
					//$db->freemysql();
				}
		}
		
		function delete_user()
		{
		  global $db, $error;
		  	
			$userid = intval($_GET['userid']);
			
			$db->query('SELECT id FROM vsource_users WHERE id="'.$userid.'"');
			
				if ($db->number_rows() == 1)
				{
					$db->query('DELETE FROM vsource_users WHERE id="'.$userid.'" LIMIT 1');
					$this->output .= $this->html->delete_complete();
				}
				
				else
				{
					$error->error('Im sorry, we were unable to delete that user. Please check the user id.', $back = true);
				}
		}
	
} 
 
?>