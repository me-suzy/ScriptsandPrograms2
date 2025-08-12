<?php
class user
{
	function register($user_rank)
	{
		$config = new functions();
		$exitp = new functions();
		$settings = $config->settings();
		if($settings['allow_register'] == "no")
		{
			echo "Registration has been disabled.";
			$exitp->exitp($user_rank);
		}
		if(isset ($_POST['register']))
		{
			if(!empty ($_POST['user']) && !empty ($_POST['pass']) && !empty ($_POST['email']))
			{
				$get_exist = mysql_query("SELECT * FROM imgup_users WHERE name='" . $_POST['user'] . "'");
				$check_exist = mysql_fetch_array($get_exist);
				if($check_exist == Null)
				{
					mysql_query("INSERT INTO imgup_users(name, pass, email, user_group)
								 VALUES('" . $_POST['user'] . "', '" . $_POST['pass'] . "', '" . $_POST['email'] . "', 'normal')");
					umask(0);
					if(mkdir($_POST['user'], 0777))
					{
						echo "You are now registered. Click " . '<a href="' . $_SERVER['PHP_SELF'] . '?user=login">here</a>
						  to log in.';
						$exitp->exitp($user_rank);
					}
				} else {
					echo "The username, <b>" . $_POST['user'] . "</b> is already taken.<br />";
				}
			} else {
				echo "You did not fill out a required field!<br />";
			}
		}
		echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?user=register" method="post" />
			  Username: <input type="text" name="user" /><br />
			  Password: <input type="password" name="pass" /><br />
			  E-mail: <input type="text" name="email" /><br />
			  <input type="submit" name="register" value="Register" />
			  </form></p>';
	}
	
	function login($user_rank)
	{
		if(isset ($_POST['login']))
		{
			$exitp = new functions();
			$get_user = mysql_query("SELECT * FROM imgup_users WHERE name='" . $_POST['user'] . "'");
			$user_arr = mysql_fetch_array($get_user);
			if($user_arr == Null)
			{
				echo "The username you provided does not exist<br />";
			} elseif(($user_arr['name'] == $_POST['user']) && ($user_arr['pass'] == $_POST['pass']))
			{
				$_SESSION['imgup_loggedin'] = $user_arr['id'] . ":" . $_POST['pass'] . ":" . $user_arr['user_group'];
				echo 'You are now logged in. Click <a href="' . $_SERVER['PHP_SELF'] . '">here</a> to begin.';
				$exitp->exitp($user_rank);
			} else {
				echo "The password provided is invalid.<br />";
			}
		}
		echo '<p><form action"' . $_SERVER['PHP_SELF'] . '?user=login" method="post" />
			  Username: <input type="text" name="user" /><br />
			  Password: <input type="password" name="pass" /><br />
			  <input type="submit" name="login" value="Log In" />
			  </form></p>';
	}
	
	function logout($user_rank)
	{
		unset($_SESSION);
		session_destroy();
		echo "You are now logged out. Click " . '<a href="' . $_SERVER['PHP_SELF'] . '">here</a> to go back.';
		$exitp = new functions();
		$exitp->exitp($user_rank);
	}
	
	function editpro($user_rank, $user_name)
	{
		$config = new functions();
		$exitp = new functions();
		$settings = $config->settings();
		
		if($settings['allow_edit'] == "no")
		{
			echo "Profile editing has been disabled.";
			$exitp->exitp($user_rank);
		}
		
		if(isset ($_POST['proedit']))
		{
			$edit_failed = False;
			if(!empty ($_POST['editpass']))
			{
				// Well, they want to change the pass...so be it...but...kill..teh sessionx0rz
				mysql_query("UPDATE imgup_users SET pass='" . $_POST['editpass'] . "' WHERE name='" . $user_name . "'");
				unset($_SESSION);
				session_destroy();
				echo "Because you changed your password, you will need to log in again.<br />";
			}
			if(empty ($_POST['edituser']))
			{
				echo "Your username can't be left empty.<br />";
				$edit_failed = True;
			}
			if(empty ($_POST['editemail']))
			{
				echo "Your E-Mail address can't be left empty.<br />";
				$edit_failed = True;
			}
			$check_exist = mysql_query("SELECT * FROM imgup_users WHERE name='" . $_POST['edituser'] . "'");
			$user_exist = mysql_fetch_array($check_exist);
			if (($user_exist['name'] != $user_name) && ($user_exist['name'] == $_POST['edituser']))
			{
				echo "That username is already in use.";
				$edit_failed = True;
			}
			
			if($edit_failed == False)
			{
				rename($user_name, $_POST['edituser']);
	
				mysql_query("UPDATE imgup_users SET name='" . $_POST['edituser'] . "' WHERE name='" . $user_name . "'");
				mysql_query("UPDATE imgup_users SET email='" . $_POST['editemail'] . "' WHERE name='" . $user_name . "'");
				
				echo "Your profile has been updated. Click " . '<a href="' . $_SERVER['PHP_SELF'] . '">here</a> to return to main.';
				
				$exitp = new functions();
				$exitp->exitp($user_rank);
			}
		}
		
		$profile_info = mysql_query("SELECT * FROM imgup_users WHERE name='" . $user_name . "'");
		$profile = mysql_fetch_array($profile_info);
		
		echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?user=profile" method="post">
			  Username: <input type="text" name="edituser" value="' . $profile['name'] . '" /><br />
			  E-Mail: <input type="text" name="editemail" value="' . $profile['email'] . '" /><br /><br />
			  Leave this empty if you want to keep your current password.<br />
			  New Password: <input type="text" name="editpass" /><br /><br />
			  <input type="submit" name="proedit" value="Edit profile" />
			  </form></p>';
	}
	
	function process_user()
	{
		$user_loggedin = False;
		$user_rank = "guest";
		if(!empty ($_SESSION['imgup_loggedin']))
		{
			$split_data = explode(':', $_SESSION['imgup_loggedin']);
			$get_user = mysql_query("SELECT * FROM imgup_users WHERE id='" . $split_data[0] . "'");
			$check_valid = mysql_fetch_array($get_user);
			if(($check_valid['id'] == $split_data[0]) && ($check_valid['pass'] == $split_data[1]))
			{
				if($check_valid['user_group'] == "admin")
				{
					$user_rank = "admin";
				} elseif ($check_valid['user_group'] == "normal")
				{
					$user_rank = "normal";
				}
				$user_name = $check_valid['name'];
			} else {
				unset($_SESSION);
				session_destroy();
				echo "Your session information did not match any on database. Your session has been destroed.";
				$exitp = new functions();
				$exitp->exitp($user_rank);
			}
		} else {
			$user_loggedin = False;
			$user_rank = "guest";
			$user_name = "guest";
		}
		$user = $user_loggedin . ":" . $user_rank . ":" . $user_name;
		return $user;
	}
}
?>