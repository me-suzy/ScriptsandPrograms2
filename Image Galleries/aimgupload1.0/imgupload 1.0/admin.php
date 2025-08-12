<?php
class admin
{
	function adduser($user_rank)
	{
		if(isset($_POST['new_submit']))
		{
			if(!empty ($_POST['new_user']) && !empty($_POST['new_pass']))
			{
				// check if the user exists
				$get_check = mysql_query("SELECT * FROM imgup_users WHERE name='" . $_POST['new_user'] . "'");
				$check_user = mysql_fetch_array($get_check);
				umask(0);
				
				if($check_user['name'] == Null)
				{
					mysql_query("INSERT INTO imgup_users(name, pass, email, user_group)
					VALUES('" . $_POST['new_user'] . "', '" . $_POST['new_pass'] . "', '" . $_POST['new_email'] . "', '" . $_POST['new_level'] . "')");
					
					if(mkdir($_POST['new_user'], 0777))
					{
						echo $_POST['new_user'] . "'s account and directory have been created<br />";
					}
				} else {
					// Uh-oh...the user exists...dun dun dun...well, check if the user wants to destroy existing users
					if($_POST['overwrite_user'] == True)
					{
						mysql_query("DELETE FROM imgup_users WHERE name='" . $_POST['new_user'] . "'");
						mysql_query("INSERT INTO imgup_users(name, pass, email, user_group)
						VALUES('" . $_POST['new_user'] . "', '" . $_POST['new_pass'] . "', '" . $_POST['new_email'] . "', '" . $_POST['new_level'] . "')");
						
						// Clear all files, then destroy and remake the directory
						$open_dir = opendir($_POST['new_user']);
						while($file_name = readdir($open_dir))
						{
							if(($file_name != ".") && ($file_name != ".."))
							{
								unlink($_POST['new_user'] . "/" . $file_name);
							}
						}
						
						rmdir($_POST['new_user']);
						
						if(mkdir($_POST['new_user'], 0777))
						{
							echo $_POST['new_user'] . "'s account and directory have been created<br />";
						}
					} else {
						echo "The user you are trying to create already exists!<br />Please check the option to overwrite existing users or choose a different username.";
					}
				}
			} else {
				echo "You did not fill out a required field.";
			}
		}
		
		echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?admin=newuser" method="post">
		Usermame: <input type="text" name="new_user" /><br />
		Password: <input type="password" name="new_pass" /><br />
		E-Mail(optional): <input type="text" name="new_email" /><br />
		User level: <select name="new_level">
		<option value="normal">Normal</option>
		<option value="admin">Admin</option>
		</select><br />Overwrite existing user? <input type="checkbox" value="true" name="overwrite_user" /><br /><br />
		<input type="submit" name="new_submit" value="Add User" /><br /></p>';
	}
	
	function edituser($user_rank, $user_name)
	{
		$obtain_user = mysql_query("SELECT * FROM imgup_users WHERE name='" . $_GET['edituser'] . "'");
		$user_array = mysql_fetch_array($obtain_user);
		
		if($user_array['name'] != Null)
		{
			// check that someone is not trying to edit the main admin
			$get_editor = mysql_query("SELECT * FROM imgup_users WHERE name='" . $user_name . "'");
			$editor = mysql_fetch_array($get_editor);
			
			if(($editor['id'] != 1) && ($user_array['id'] == 1))
			{
				echo "You may not edit the main admin's account.";
				$exitp = new functions();
				$exitp->exitp($user_rank);
			}
			
			if(isset ($_POST['edit_final']))
			{
				if($_POST['edit_deleteuser'] == True)
				{
					mysql_query("DELETE FROM imgup_users WHERE name='" . $_POST['olduser'] . "'");
					
					// Clear all files, destroy the directory, and tell the admin what they have done...lol
					$open_dir = opendir($_POST['olduser']);
					while($file_name = readdir($open_dir))
					{
						if(($file_name != ".") && ($file_name != ".."))
						{
							unlink($_POST['olduser'] . "/" . $file_name);
						}
					}
					
					rmdir($_POST['olduser']);
					echo $_POST['olduser'] . "'s account and directory have succesfully been deleted<br />";
					echo 'Click <a href="' . $_SERVER['PHP_SELF'] . '?admin=userlist">here</a> to go back.';
					
					$exitp = new functions();
					$exitp->exitp($user_rank);
				} elseif ($_POST['edit_password'] != Null)
				{
					mysql_query("UPDATE imgup_users SET pass='" . $_POST['edit_password'] . "' WHERE name='" . $_POST['olduser'] . "'");
					echo $_POST['olduser'] . "'s password has been updated.<br />";
				}
					
				mysql_query("UPDATE imgup_users SET name='" . $_POST['edit_username'] . "' WHERE name='" . $_POST['olduser'] . "'");
				mysql_query("UPDATE imgup_users SET email='" . $_POST['edit_emailaddr'] . "' WHERE name='" . $_POST['olduser'] . "'");
				mysql_query("UPDATE imgup_users SET user_group='" . $_POST['edit_userlvl'] . "' WHERE name='" . $_POST['olduser'] . "'");
				rename($_POST['olduser'], $_POST['edit_username']);
				echo $_POST['olduser'] . "'s profile has been updated.<br />";
			}
			
			echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?admin=edituser&edituser=' . $_GET['edituser'] . '" method="post">
				  Username: <input type="post" name="edit_username" value="' . $user_array['name'] . '" /><br />
				  E-mail: <input type="post" name="edit_emailaddr" value="' . $user_array['email'] . '" /><br />
				  <input type="hidden" name="olduser" value="' . $_GET['edituser'] . '" /><br />
				  Access Level: <select name="edit_userlvl">';
			switch($user_array['user_group']) 
			{
				case admin:
					echo '<option value="' . $user_array['user_group'] . '">' . $user_array['user_group'] . '</option>
					      <option value="normal">normal</option>';
				break;
				case normal:
					echo '<option value="' . $user_array['user_group'] . '">' . $user_array['user_group'] . '</option>
					<option value="admin">admin</option>';
				break;
			}
			echo '</select></p><p>The field below may be left empty if you want to keep the original password.<br />
			New Password: <input type="password" name="edit_password" /><br /><br />
			Only check the box below if you want to delete this user and their directory.<br />
			Delete User: <input type="checkbox" name="edit_deleteuser" /><br /><br />
			<input type="submit" name="edit_final" value="Finish Edit" /></form></p>';
		}
	}
	
	function viewusers($user_rank)
	{	
		$get_all = mysql_query("SELECT * FROM imgup_users");
		while($users = mysql_fetch_array($get_all))
		{	
			$userdir = opendir($users['name']);
			
			$used_space = 0;
			$total_files = 0;
			while($users_dir = readdir($userdir))
			{
				if(($users_dir != "..") && ($users_dir != "."))
				{
					$filesize = filesize($users['name'] . "/" . $users_dir);
					$used_space = $used_space + $filesize;
					$total_files++;
					unset($filesize);
				}
			}
			
			echo "<b>" . $users['name'] . "</b><br /><p align='left'>
				<i>E-mail:</i> ";
			  
			if($users['email'] == NULL)
			{	 
				echo "none "; 
			} else { 
				echo $users['email']; 
			} 
			echo "<br /><i>Level:</i> " . $users['user_group'] . "<br /><i>Files uploaded:</i> " . $total_files . "<br /><i>Space used:</i> ";
			$get_mbkb = new functions();
			$get_mbkb->size_check($used_space);
			echo '</p><a href="' . $_SERVER['PHP_SELF'] . '?admin=edituser&edituser=' . $users['name'] . '">Edit ' . $users['name'] . '\'s account</a><hr />';
			
			unset($get_mbkb);
			unset($used_space);
			unset($total_files);
		}
	}
	
	function settings($user_rank)
	{
		$get_settings = mysql_query("SELECT * FROM imgup_config");
		$settings = mysql_fetch_array($get_settings);
		if(isset ($_POST['editset']))
		{
			if(!empty ($_POST['dirupload']) && !empty ($_POST['imgsize_limit']))
			{
				// Update directory size and Imgsize_limit
				mysql_query("UPDATE imgup_config SET directory_limit='" . $_POST['dirupload'] . ":" . $_POST['mb_gb_dir'] . "'");
				mysql_query("UPDATE imgup_config SET max_upload='" . $_POST['imgsize_limit'] . ":" . $_POST['mb_gb_imgsize'] . "'");
				
				// Use extensions
				mysql_query("UPDATE imgup_config SET useext='" . $_POST['use_ext'] . "'");
				
				// Allowed extensions
				mysql_query("UPDATE imgup_config SET allowed_ext='" . $_POST['allowedext'] . "'");
				
					// Imgtype update
					$split_imgtypes = explode(',', $settings['allowed_img']);
					$count_imgtypes = count($split_imgtypes);
					
					$imgtype_query = "UPDATE imgup_config SET allowed_img='";
					for($i = 0;$i<$count_imgtypes;$i++)
					{
						$split_again = explode(':', $split_imgtypes[$i]);
						if($split_again[0] != "IMAGETYPE_GIF")
						{
							$imgtype_query .= ',';
						}
						$imgtype_query .= $split_again[0];
						if($split_again[0] != Null)
						{
							if($_POST[$split_again[0]] == True)
							{
								$imgtype_query .= ":allow:";
							} else {
								$imgtype_query .= ":invalid:";
							}
						} else {
							// Exit if for SOME reason, it decides to add a null imgtype to the query
							$i = $count_imgtypes + 1000;
						}
					
						$imgtype_query .= $split_again[2];
					}
					
				// Finish query
				$imgtype_query .= "'";
				
				
				// Run query
				mysql_query($imgtype_query);
				
				// Update email
				mysql_query("UPDATE imgup_config SET admin_email='" . $_POST['admin_email'] . "'");
				
				// Allow profile edit
				mysql_query("UPDATE imgup_config SET allow_edit='" . $_POST['allow_edit'] . "'");
				
				// Allow register
				mysql_query("UPDATE imgup_config SET allow_register='" . $_POST['allow_register'] . "'");
				
				// Use custom guest, display login, custom guest message
				mysql_query("UPDATE imgup_config SET display_guest='" . $_POST['custom_guest'] . "'");
				mysql_query("UPDATE imgup_config SET display_login='" . $_POST['display_login'] . "'");
				
				$guest_message = str_replace("\n", "<br />", $_POST['guest_message']);
				
				mysql_query("UPDATE imgup_config SET final_guest_message='" . $guest_message . "'");
				mysql_query("UPDATE imgup_config SET guest_custom_message='" . $_POST['guest_message'] . "'");
				
				// Use Custom global, custom global message
				mysql_query("UPDATE imgup_config SET display_global='" . $_POST['display_global'] . "'");
				
				$global_message = str_replace("\n", "<br />", $_POST['global_message']);
				mysql_query("UPDATE imgup_config SET final_global_message='" . $global_message . "'");
				mysql_query("UPDATE imgup_config SET global_message='" . $_POST['global_message'] . "'");
				echo "Settings updated successfully! Click " . '<a href="' . $_SERVER['PHP_SELF'] . '?admin=settings">here</a> to go back.';
			} else {
				echo "Directory limit and(or) Image size limit may not be left empty. Click " . '<a href="' . $_SERVER['PHP_SELF'] . '?admin=settings">here</a> to go back.';
			}
			$exitp = new functions();
			$exitp->exitp($user_rank);
		}
		
		$dir_limit = explode(':', $settings['directory_limit']);
		$img_size = explode(':', $settings['max_upload']);
		echo '<form action="' . $_SERVER['PHP_SELF'] . '?admin=settings" method="post" />
			  <p align="left"><u>Upload settings:</u></p><p>
			  Directory limit: <input type="text" name="dirupload" value="' . $dir_limit[0] . '" /> ';
		switch($dir_limit[1])
		{
			case KB:
				echo '<select name="mb_gb_dir">
				  <option value="KB">KB</option>
				  <option value="MB">MB</option>
				  </select>';
			break;
			case MB:
				echo '<select name="mb_gb_dir">
				  <option value="MB">MB</option>
				  <option value="KB">KB</option>
				  </select>';
			break;
		}
		echo "<a href=\"\" onclick=\"window.open('help.html#dirsize','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=500, height=200')\">What's this?</a>";
		echo '<br />Image size limit: <input type="text" name="imgsize_limit" value="' . $img_size[0] . '" /> ';
		switch($img_size[1])
		{
			case KB:
				echo '<select name="mb_gb_imgsize">
				  <option value="KB">KB</option>
				  <option value="MB">MB</option>
				  </select>';
			break;
			case MB:
				echo '<select name="mb_gb_imgsize">
				  <option value="MB">MB</option>
				  <option value="KB">KB</option>
				  </select>';
			break;
		}
		echo "<a href=\"\" onclick=\"window.open('help.html#imgsize_limit','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=500, height=200')\">What's this?</a><br />";
		
		switch($settings['useext'])
		{
			case yes:
				echo '<p>Use extensions?: Yes<input type="radio" name="use_ext" value="yes" checked>
					  No<input type="radio" name="use_ext" value="no" ><br />';
			break;
			case no:
				echo '<p>Use extensions?: Yes<input type="radio" name="use_ext" value="yes" />
					  No<input type="radio" name="use_ext" value="no" checked><br />';
			break;
			default:
				echo '<p>Use extensions?: Yes<input type="radio" name="use_ext" value="yes" />
					  No<input type="radio" name="use_ext" value="no" /><br />';
			break;
		}
		
		echo 'Allowed extensions: <input type="text" name="allowedext" value="' . $settings['allowed_ext'] . '" />';
		echo "<a href=\"\" onclick=\"window.open('help.html#allowed_ext','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=500, height=200')\">What's this?(Read before using)</a></p><p>";
		
		$split_imgtypes = explode(',', $settings['allowed_img']);
		$count_imgtypes = count($split_imgtypes);
		echo '<p>Allowed Image types: <br />';
		for($i = 0;$i<$count_imgtypes;$i++)
		{
			$type_data = explode(':', $split_imgtypes[$i]);
			switch($type_data[1])
			{
				case allow:
					echo $type_data[2] . '<input type="checkbox" name="' . $type_data[0] . '" checked><br />';
				break;
				case invalid:
					echo $type_data[2] . '<input type="checkbox" name="' . $type_data[0] . '" /><br />';
				break;
			}
		}
		
		echo '</p></p><p align="left"><u>User and guest settings:</u></p><p>';
		echo 'Admin E-mail: <input type="text" size="45" name="admin_email" value="' . $settings['admin_email'] . '" /><br />';
		
		echo 'Allow users to edit their profile: ';
		switch($settings['allow_edit'])
		{
			case yes:
				echo 'Yes<input type="radio" name="allow_edit" value="yes" checked>
					  No<input type="radio" name="allow_edit" value="no" />';
			break;
			case no:
				echo 'Yes<input type="radio" name="allow_edit" value="yes" />
					  No<input type="radio" name="allow_edit" value="no" checked>';
			break;
		}
		echo "<a href=\"\" onclick=\"window.open('help.html#editpro','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=500, height=200')\">What's this?</a><br />";
		
		echo 'Enable registration: ';
		switch($settings['allow_register'])
		{
			case yes:
				echo 'Yes<input type="radio" name="allow_register" value="yes" checked>
					  No<input type="radio" name="allow_register" value="no" />';
			break;
			case no:
				echo 'Yes<input type="radio" name="allow_register" value="yes" />
					  No<input type="radio" name="allow_register" value="no" checked>';
			break;
		}
		echo "<a href=\"\" onclick=\"window.open('help.html#enable_reg','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=500, height=200')\">What's this?</a><br />";
		
		echo '<p align="left"><u>Display settings:</u></p><p>';
		echo '<p>Use custom guest welcome message: ';
		
		switch($settings['display_guest'])
		{
			case yes:
				echo 'Yes<input type="radio" name="custom_guest" value="yes" checked>
					  No<input type="radio" name="custom_guest" value="no" />';
			break;
			case no:
				echo 'Yes<input type="radio" name="custom_guest" value="yes" />
					  No<input type="radio" name="custom_guest" value="no" checked>';
			break;
		}
		
		echo "<a href=\"\" onclick=\"window.open('help.html#custom_guest','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=500, height=200')\">What's this?</a><br />";
		
		echo 'Display login form: ';
		switch($settings['display_login'])
		{
			case yes:
				echo 'Yes<input type="radio" name="display_login" value="yes" checked>
					  No<input type="radio" name="display_login" value="no" />';
			break;
			case no:
				echo 'Yes<input type="radio" name="display_login" value="yes" />
					  No<input type="radio" name="display_login" value="no" checked>';
			break;
		}
		
		echo "<a href=\"\" onclick=\"window.open('help.html#display_form','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=500, height=200')\">What's this?</a>";
		echo '<br /><p>Guest welcome message:<br />';
		echo '<textarea name="guest_message" rows="3" cols="40">' . $settings['guest_custom_message'] . '</textarea></p>';
		
		echo "<p>Use custom user welcome message: ";
		switch($settings['display_global'])
		{
			case yes:
				echo 'Yes<input type="radio" name="display_global" value="yes" checked>
					  No<input type="radio" name="display_global" value="no" />';
			break;
			case no:
				echo 'Yes<input type="radio" name="display_global" value="yes" />
					  No<input type="radio" name="display_global" value="no" checked>';
			break;
		}
		
		echo "<a href=\"\" onclick=\"window.open('help.html#custom_user','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=500, height=200')\">What's this?</a>";
		echo '<br /><p>User welcome message:<br />';
		echo '<textarea name="global_message" rows="3" cols="40">' . $settings['global_message'] . '</textarea></p></p>';
		echo '<input type="submit" name="editset" value="Save settings" /></form>';
	}
	
	function check_update($user_rank)
	{
		include("http://hellscythe.net/hellscythelabs/updater/imgup/updates.txt");
	}
}
?>