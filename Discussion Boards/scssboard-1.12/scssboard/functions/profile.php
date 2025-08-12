<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<?php
if (!$_GET[u]) {
	$user = $current_user[users_id];
} else {
	$user = $_GET[u];
}

	$user_details = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_id = '$user'"));
	if ($user_details[users_level] == 1)
	$user_lvl_word = "Member";
	if ($user_details[users_level] == 2)
	$user_lvl_word = "Moderator";
	if ($user_details[users_level] == 3)
	$user_lvl_word = "Administrator";
	if ($user_details[users_level] == 4)
	$user_lvl_word = "Board Owner";
	if ($_MAIN[allow_sig_bbcode] == "yes") { $parsed_signature = BBCodeParser($user_details[users_signature]); } else {$parsed_signature = $user_details[users_signature]; }
	$scrambled_email = ereg_replace("@"," [[at]] ",$user_details[users_email]);
	echo "<div class='catheader' style='width:400px; padding:5px; margin-left:auto; margin-right:auto;'>Viewing $user_details[users_username]'s Profile</div>
			<div class='msg_content' style='width:390px; margin-left:auto; margin-right:auto;'>
				<br />
				&nbsp; <strong>Username:</strong> $user_details[users_username]<br /><br />
				&nbsp; <strong>Level:</strong> $user_lvl_word<br /><br />";
				if (($user_details[users_private_email] != 1) or ($current_user[users_level] >= 2)) {
					echo "&nbsp; <strong>E-Mail:</strong> $scrambled_email";
					if (($current_user[users_level] >= 2) and ($user_details[users_private_email] == 1))
						echo " <em>(Private)</em>";
					echo "<br /><br />";
				}
				echo "&nbsp; <strong>Real Name:</strong> $user_details[users_realname]<br /><br />
				&nbsp; <strong>Location:</strong> $user_details[users_location]<br /><br />
				&nbsp; <strong>Signature:</strong><br /><br />
				<div class='signature'>$parsed_signature</div><br />
			</div><br />";

			if(($user == $current_user[users_id]) or ($current_user[users_level] > 2)) {
			echo "<form method='post' action='index.php?act=profile&amp;update=now&amp;u=$user'>";
			echo "<div class='catheader' style='width:400px; padding:5px; margin-left:auto; margin-right:auto;'>Edit Profile</div>
	
			<div class='msg_content' style='width:390px; margin-left:auto; margin-right:auto;'>";
					$user_details[users_signature] = strip_tags($user_details[users_signature]);
					echo "&nbsp; Change Password:<br />
                    &nbsp; <input type='password' name='password' size='20' value='' class='input'> [ At Least 5 Chars ]<br /><br />";
					if (($user_details[users_level] != 4) and ($current_user[users_level] > 2)) {
					echo "&nbsp; User Level:<br />
					&nbsp; <select name='userlvl'>
						<option value='-1'";
						if($user_details[users_level] == -1) { echo " selected='selected'"; }
						echo ">-1 (Banned)</option>
						<option value='1'";
						if($user_details[users_level] == 1) { echo " selected='selected'"; }
						echo ">1 (Member)</option>
						<option value='2'";
						if($user_details[users_level] == 2) { echo " selected='selected'"; }
						echo ">2 (Moderator)</option>
						<option value='3'";
						if($user_details[users_level] == 3) { echo " selected='selected'"; }
						echo ">3 (Admin)</option>
					</select><br /><br />";
					}

                    echo "&nbsp; Email:<br />
                    &nbsp; <input type='email' name='email' size='20' value='$user_details[users_email]' class='input'><br />
						   <input type='checkbox' name='private_email' class='input'";
					if ($user_details[users_private_email] == 1) { echo " checked='true'"; }
					echo ">Keep E-Mail Private<br /><br />
                    &nbsp; Real Name:<br />
                    &nbsp; <input type='text' name='realname' size='20' value='$user_details[users_realname]' class='input'><br /><br />
                    &nbsp; Location:<br />
                    &nbsp; <input type='text' name='location' size='20' value='$user_details[users_location]' class='input'><br /><br />
                    &nbsp; Signature: (BBCode is <em>";
					if ($_MAIN[allow_sig_bbcode] == "yes") { echo "Allowed"; } else { echo "Not Allowed"; }
                    echo "</em>)<br />&nbsp; <textarea name='signature' cols='40' rows='4'>$user_details[users_signature]</textarea><br />
					</div>

					<div class='catheader' style='width:400px; padding:5px; margin-left:auto; margin-right:auto;'>Board Settings</div>
					<div class='msg_content' style='width:390px; margin-left:auto; margin-right:auto;'>
					&nbsp; Topics to display per page in Forum View:<br />
					&nbsp; <input type='text' name='tpp' size='2' value='$user_details[users_tpp]' class='input'><br /><br />
					&nbsp; Replies to display per page in Topic View:<br />
					&nbsp; <input type='text' name='rpp' size='2' value='$user_details[users_rpp]' class='input'><br /><br />
					&nbsp; Stylesheet:<br />
					&nbsp; <select name='style'>"; 

					$dir = opendir('./styles');  
					$css_files = array();  
					while(($file = readdir($dir)) !== false)  {  
						if(strlen($file) > 4 && substr(strtolower($file), strlen($file) - 4) === '.css' && !is_dir($file)) {  
							$css_files[] = $file;  
						}  
					}  
					closedir($dir); 
					foreach ($css_files as $filename) {
						echo "<option value='$filename'";
						if ($user_details[users_style] == $filename) {
							echo " selected='selected'";
						}
						echo ">$filename</option>";
					}
					echo "</select><br />
					</div>
					<div class='msg_content' style='width:390px; margin-left:auto; margin-right:auto;'>
                    &nbsp; <input type='submit' value='Update' class='form_button'>
					</div>
					</form>";
					}
                    if($_GET[update] == "now") {
						if (($current_user[users_level] < 3) and ($_GET[u] != $_COOKIE[scb_uid])) {
							die("You do not have the necessary permissions to modify this profile.");
						}
						echo "<center>";
						$user = $_GET[u];
                        $password = $_POST[password];
                        $email = $_POST[email];
						if($_POST[private_email]) { $pr_e = 1; } else { $pr_e = 0; }
                        $realname = $_POST[realname];
                        $location = $_POST[location];
                        $signature = $_POST[signature];
						$style = $_POST[style];
						$tpp = $_POST[tpp];
						$rpp = $_POST[rpp];
                        $getinfo = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_username = '$user'"));
                        if($password != "") {
							if(strlen($password) < 5) {
                            echo "Error: Password must be at least 5 characters in length. <a href='javascript:history.back()'>Back...</a>";
							die();
							} else {
							$pass_set = 1;
							}
						}
                        if($email == "") {
                            echo "Error: Email was left blank. <a href='javascript:history.back()'>Back...</a>";
                        } else {
                            $check_email = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_email = '$email' and users_id != '$user'"));
                            if($check_email) {
                                echo "Error: This e-mail address is already being used by a member. <a href='javascript:history.back()'>Back...</a>";
                            } else {
								if ($_POST[userlvl]) { $ulvlquery = " users_level = '$_POST[userlvl]',"; }
								if ($pass_set) {
									$password = md5($password);
									$signature = nl2br($signature);
									@mysql_query("update $_CON[prefix]users set users_password = '$password', users_email = '$email', users_realname = '$realname', users_location = '$location', users_signature = '$signature', users_style = '$style',$ulvlquery users_tpp = '$tpp', users_rpp = '$rpp', users_private_email = '$pr_e' where users_id = '$user'");
								} else {
									$signature = nl2br($signature);
									@mysql_query("update $_CON[prefix]users set users_email = '$email', users_realname = '$realname', users_location = '$location', users_signature = '$signature', users_style = '$style', users_tpp = '$tpp', users_rpp = '$rpp',$ulvlquery users_private_email = '$pr_e' where users_id = '$user'");
								}
								echo redirect("index.php?act=profile&amp;u=$user");
                            }
                        }
                    }
                    echo "<br />";
?>