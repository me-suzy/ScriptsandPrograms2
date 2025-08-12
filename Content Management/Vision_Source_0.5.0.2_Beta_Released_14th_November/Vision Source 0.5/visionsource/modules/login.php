<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 11th April 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: login.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class login {

	var	$output = "";
	var $html   = "";
	
	function pages()
	{
		global $skin;
		
			$this->html = $skin->load('skin_login');
			$skin->do_title("Login");
			$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
			switch ($do)
			{
					case "1":
						$this->signin();
					break;
					case "2":
						$this->register();
					break;
					case "3":
						$this->logout();
					break;
					case "4":
						$this->do_login();
					break;
					case "5":
						$this->do_register();
					break;
					default:
						$this->signin();
					break;
			}
			
			$skin->do_output("$this->output");
	}
	 
			
		function signin()
		{
			global $vsource, $skin;
							
				if ($vsource->is_member() == 1)
				{
					$this->output .= $this->html->welcome();
					$skin->do_title("Already logged in");
				}
					
				else
				{
					$this->output .= $this->html->home();
					$this->output .= $this->html->login();
				}
			
		}
			
		function register()
		{
			global $cms, $error;
			
			if ($cms->member['is_member'] == 1)
			{
				$this->output .= $error->error('You have already registered');
				return;
			}
			
			$this->output .= $this->html->regtop();
		}
				
		function logout()
		{
			global $ses, $skin, $vsource;
			$redirect = $_GET['redirect'];
			$ses->unset_usercookie();
			$skin->redirect('We are now loggin you out', 'index.php');
			$skin->do_title('Logout');
			
		}
		
		function do_login()
		{
			global $ses, $db;
		
			if (empty($_POST['username'])) 
			{
				$this->output .= $this->html->custerror($error = 'You did not enter in a username');
				return;
			}
			
			if (empty($_POST['password']))
			{
				$this->output .= $this->html->custerror($error = 'You did not enter in a password');
				return;
			}
			
			else
			{
				$username = $db->check_input($_POST['username']);
				$password = md5( $_POST['password'] );
				
					if (empty($_GET['redirect']))
					{				
						$ses->set_usercookie($username, $password, 'index.php');
					}
					
					else
					{
						$ses->set_usercookie($username, $password, $redirect);
					}
				
			}
			
		}
		
		function do_register()
		{
			global $db, $cms, $error;
			
			if ($cms->member['is_member'] == 1)
			{
				$this->output .= $error->error('You have already registered');
				return;
			}
			
			//-------------------------
			//  Store some vars!
			//-------------------------	
			
			$username	= $_POST['username'];
			$password	= $_POST['password'];
			$email		= $_POST['email'];
			$ip			= $_SERVER['REMOTE_ADDR'];
		
			//-------------------------
			//  check for errors!
			//-------------------------	
			
			if (empty($username))
			{
				$this->output .= $this->html->custerror($error = 'You did not enter in a username');
				return;
			}
			
			if (empty($password))
			{
				$this->output .= $this->html->custerror($error = 'You did not enter in a password');
				return;
			}
			
			if (empty($_POST['password_check']))
			{
				$this->output .= $this->html->custerror($error = 'You did not enter in a second password');
				return;
			}
			
			if (empty($email))
			{
				$this->output .= $this->html->custerror($error = 'You did not enter in a email address');
				return;
			}
			
			if (empty($_POST['email_check']))
			{
				$this->output .= $this->html->custerror($error = 'You did not enter in a second email address');
				return;
			}
			
			if (strlen($username) > 32)
			{
				$this->output .= $this->html->custerror($error = 'Your username must not be more than 32 characters');
				return;
			}
			
			if (strlen($password) < 3)
			{
				$this->output .= $this->html->custerror($error = 'Your password must be more than 3 characters');
				return;
			}
			
			if (strlen($email) < 6)
			{
				$this->output .= $this->html->custerror($error = 'The email address you entered was incorrect');
				return;
			}
			
			if ($password != $_POST['password_check'])
			{
				$this->output .= $this->html->custerror($error = 'Your passwords did not match');
				return;
			}
			
			if ($email != $_POST['email_check'])
			{
				$this->output .= $this->html->custerror($error = 'Your email address did not match');
				return;
			}
			
			else
			{
			
				//------------------------------------------
				//	Now check for any illegal characters
				//------------------------------------------
				
				if (preg_match("/[^a-z0-9_-]/i", $username))
				{
					$this->output .= $this->html->custerror('Your username contained illegal characters.');
					return;
				}
			
				//----------------------------
				//  Now check db for errors!
				//----------------------------
				
				$password = md5( $password );
				
				$db->query('SELECT username FROM vsource_users WHERE username="'.$username.'"');
				
					if ($db->number_rows() == "1")
					{
						$this->output .= $this->html->custerror($error = 'Username already taken, Please choose another one.');
					}
				
				//----------------------------
				//  Add user to db!
				//----------------------------
					
					else
					{
						$db->query('INSERT INTO vsource_users SET username="'.$username.'", password="'.$password.'", email="'.$email.'", reg_ip="'.$ip.'", admin="0"');
						$this->output .= $this->html->reg_complete();
					}
					
				//$db->freemysql();
			}
			
			
		}

}


?>