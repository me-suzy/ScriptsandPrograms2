<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		YourCMS v0.5 Beta									//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 29th June 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: usercp.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class usercp {

	var	$output = "";
	var $html   = "";
	
	function pages()
	{
		global $skin, $cms;
			$this->html = $skin->load('skin_ucp');
			$skin->do_title('UCP');
			
				if ($cms->member['is_member'] !== 1)
				{
					$skin->setheader('index.php?id=login');
				}
				
			$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
			switch ($do)
			{
					case "1":
						$this->home();
					break;
					case "2":
						$this->edit_profile();
					break;
					case "3":
						$this->do_update();
					break;
					case "4":
						$this->changepass();
					break;
					case "5":
						$this->do_changepass();
					break;
					break;
					default:
						$this->home();
					break;
			}
			
			$skin->do_output("$this->output");
					
	}
	 
			
		function home()
		{
			global $vsource;
			$this->output .= $this->html->welcome();
		}
		
		function edit_profile()
		{
			global $vsource, $db, $skin;

					$row = $vsource->get_mem_info();
					$this->output .= $this->html->edit_form($row);
					$skin->do_title('Update Profile');
				
		}
		
		function do_update()
		{
		  global $vsource, $db, $skin;
			  $email       = $_POST['email'];
			  $skinchoice  = $_POST['skin_selector'];
				  if (empty($email))
				  {
					$this->output .= $this->html->error('Your email address was empty.');
				  }
			  
				  if (empty($skinchoice))
				  {
					$this->output .= $this->html->error('You did not select a skin.');
				  }
				  
				  else
				  {
					$m = $vsource->get_mem_info();
					$db->query('UPDATE vsource_users SET email="'.$email.'", skinid="'.$skinchoice.'" WHERE id="'.$m['id'].'"');
					$skin->redirect("Your profile has been updated.", "index.php?id=ucp&do=2");
				  }
    	}
		
		function changepass()
		{
		  	$this->output .= $this->html->changepass();
		}
		
		function do_changepass()
		{
		  global $db, $cms, $skin;
		  	
			if (empty($_POST['old_password']))
			{
				$this->output .= $this->html->error('You did not enter in an old password.');
				return;
			}
			
			if (empty($_POST['new_password1']))
			{
				$this->output .= $this->html->error('You did not enter in a new password.');
				return;
			}
			
			if (empty($_POST['new_password2']))
			{
				$this->output .= $this->html->error('You did not confirm your new password.');
				return;
			}
			
			if ($_POST['new_password1'] !== $_POST['new_password2'])
			{
				$this->output .= $this->html->error('Your new password did not match.');
				return;
			}
			
			$old_pass	= md5($_POST['old_password']);
			$new_pass	= md5($_POST['new_password1']);
			
			$db->query('SELECT password FROM vsource_users WHERE id="'.$cms->member['id'].'" AND password="'.$old_pass.'"');
			
				if ($db->number_rows() == 1)
				{
					$db->query('UPDATE vsource_users SET password="'.$new_pass.'" WHERE id="'.$cms->member['id'].'"');
					$skin->redirect('Your password was successfully changed.', 'index.php?id=ucp');
				}
				
				else
				{
					$this->output .= $this->html->error('Your old password did not match. Please try again.');
					return;
				}
		}
			
}


?>
