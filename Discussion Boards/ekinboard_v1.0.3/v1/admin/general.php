<?PHP

if($_userlevel == 3){




echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/general_title.gif\" border=0 alt=\"\"><p>";



	if ($_GET['step'] == 2) { // Update the database

			$temp_set_check =  mysql_query("SELECT * FROM settings WHERE name='organization'") or die(mysql_error());
			$temp_set_check = mysql_num_rows($temp_set_check);

			if($temp_set_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('organization', '$_POST[organization]')";
				$result = mysql_query($query_c);
			} else {
				$update_temp_settings = mysql_query("UPDATE `settings` SET `value`='$_POST[organization]' WHERE `name`='organization'") or die(mysql_error());
			}

			$temp_check =  mysql_query("SELECT * FROM settings WHERE name='main_email'") or die(mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('main_email', '$_POST[forum_email]')";
				$result = mysql_query($query_c);
			} else {
				$update_template = mysql_query("UPDATE settings SET value='$_POST[forum_email]' WHERE name='main_email'") or die(mysql_error());
			}

			$temp_check =  mysql_query("SELECT * FROM settings WHERE name='main_location'") or die(mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('main_location', '$_POST[forum_location]')";
				$result = mysql_query($query_c);
			} else {
				$update_template = mysql_query("UPDATE settings SET value='$_POST[forum_location]' WHERE name='main_location'") or die(mysql_error());
			}

			$temp_check =  mysql_query("SELECT * FROM settings WHERE name='acitvate_accounts'") or die(mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('acitvate_accounts', '$_POST[activate]')";
				$result = mysql_query($query_c);
			} else {
				$update_template = mysql_query("UPDATE settings SET value='$_POST[activate]' WHERE name='acitvate_accounts'") or die(mysql_error());
			}

			$temp_check =  mysql_query("SELECT * FROM settings WHERE name='terms'") or die(mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('terms', '$_POST[terms]')";
				$result = mysql_query($query_c);
			} else {
				$update_template = mysql_query("UPDATE settings SET value='$_POST[terms]' WHERE name='terms'") or die(mysql_error());
			}

			$temp_check =  mysql_query("SELECT * FROM settings WHERE name='allow_attch'") or die(mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('allow_attch', '$_POST[allow_attch]')";
				$result = mysql_query($query_c);
			} else {
				$update_template = mysql_query("UPDATE settings SET value='$_POST[allow_attch]' WHERE name='allow_attch'") or die(mysql_error());
			}

			$temp_check = mysql_query("SELECT * FROM settings WHERE name='attch_exts'") or die (mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('attch_exts', '$_POST[attch_exts]')";
				$result = mysql_query($query_c);
			} else {
				if($_SETTING['allow_attch'] == 1){
					$update_template = mysql_query("UPDATE settings SET value='$_POST[attch_exts]' WHERE name='attch_exts'") or die (mysql_error());
				}
			}

			$temp_check = mysql_query("SELECT * FROM settings WHERE name='attch_max_size'") or die (mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name`, `value` ) VALUES ('attch_max_size', '$_POST[attch_max_size]')";
				$result = mysql_query($query_c);
			} else {
				if($_SETTING['allow_attch'] == 1){
					$update_template = mysql_query("UPDATE settings SET value='$_POST[attch_max_size]' WHERE name='attch_max_size'") or die (mysql_error());
				}
			}

		    $temp_check = mysql_query("SELECT * FROM settings WHERE name='upload_avatars'") or die(mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('upload_avatars', '$_POST[upload_avatars]')";
				$result = mysql_query($query_c);
			} else {
				$update_template = mysql_query("UPDATE settings SET value='$_POST[upload_avatars]' WHERE name='upload_avatars'") or die(mysql_error());
			}
			
			$temp_check = mysql_query("SELECT * FROM settings WHERE name='avatar_exts'") or die (mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('avatar_exts', '$_POST[avatar_exts]')";

				$result = mysql_query($query_c);
			} else {
				if($_SETTING['upload_avatars'] == '1'){
					$update_template = mysql_query("UPDATE settings SET value='$_POST[avatar_exts]' WHERE name='avatar_exts'") or die (mysql_error());
				}
			}
				
			$temp_check = mysql_query("SELECT * FROM settings WHERE name='avatar_max_size'") or die (mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('avatar_max_size', '$_POST[avatar_max_size]')";
				$result = mysql_query($query_c);
			} else {
				if($_SETTING['upload_avatars'] == '1'){
					$update_template = mysql_query("UPDATE settings SET value='$_POST[avatar_max_size]' WHERE name='avatar_max_size'") or die (mysql_error());
				}
			}



		header("Location: index.php?page=general");

	} else { // Echo the current settings



		if($_SETTING['acitvate_accounts'] == '0'){

			 $a_checked = " checked=checked";

		} else if($_SETTING['acitvate_accounts'] == '1'){

			 $b_checked = " checked=checked";

		} else {

			 $b_checked = " checked=checked";

		}

        
        if($_SETTING['upload_avatars'] == '0'){
            $aa_checked = " checked=checked";
        } else if($_SETTING['upload_avatars'] == '1'){
            $bb_checked = " checked=checked";
			$avatar_extras = "<tr><td class=row2><b>Avatar File Types (extensions):</b><br>(Separate extensions with commas)</td><td class=row2><input type=text name=avatar_exts size=50 value='".$_SETTING['avatar_exts']."'></td></tr>
			<tr><td class=row2><b>Avatar Maximum Size:</b><br>(This is in bytes, <b>not kilobytes!</b>)</td><td class=row2><input type=text name=avatar_max_size size=50 value='".$_SETTING['avatar_max_size']."'></td></tr>";
		} else {
            $aa_checked = " checked=checked";
        }

        if($_SETTING['allow_attch'] == '0'){
            $aaa_checked = " checked=checked";
        } else if($_SETTING['allow_attch'] == '1'){
            $bbb_checked = " checked=checked";
			$attch_extras = "<tr><td class=row1><b>Attachment File Types (extensions):</b><br>(Separate extensions with commas)</td><td class=row1><input type=text name=attch_exts size=50 value='".$_SETTING['attch_exts']."'></td></tr>
			<tr><td class=row1><b>Attachment Maximum Size:</b><br>(This is in bytes, <b>not kilobytes!</b>)</td><td class=row1><input type=text name=attch_max_size size=50 value='".$_SETTING['attch_max_size']."'></td></tr>";
		} else {
            $aaa_checked = " checked=checked";
        }


echo "<table width=100% class=category_table cellpadding=0 cellspacing=0 align=center>

		<tr>



			<td class=table_1_header colspan=2>			



				<b>General Configuration</b>			

				<form action=index.php?page=general&step=2 method=post>

			</td>



		</tr>

		<tr>

			<td class=row1>

				<b>Forum Organization:</b><br>

				Will be displayed as the site title

			</td>

			<td class=row1>

				<input type=text class=text name=organization size='50' value='". $_SETTING['organization'] ."'>

			</td>

		</tr>

		<tr>

			<td class=row2>

				<b>Forum Email Address:</b><br>

				Will be used to send out activation emails

			</td>

			<td class=row2>

				<input type=text class=text name=forum_email size='50' value='". $_SETTING['main_email'] ."'>

			</td>

		</tr>

		<tr>

			<td class=row1>

				<b>Forum Location:</b><br>

				The directory where the forum is located (without the '/' at the end)

			</td>

			<td class=row1>

				<input type=text class=text name=forum_location size='50' value='". $_SETTING['main_location'] ."'>

			</td>

		</tr>

		<tr>

			<td class=row2>

				<b>Account Activation:</b><br>

				Force users to activate their account upon registration

			</td>

			<td class=row2>

				<input type='radio' class='form' name='activate' value='0'$a_checked> <i>No</i><br><input type='radio' class='form' name='activate' value='1'$b_checked> <i>Yes</i>



			</td>

		</tr>

		<tr>

			<td class=row1>

				<b>Allow Attachments:</b><br>

				Whether or not users can upload attachments

			</td>

			<td class=row1>

				<input type='radio' class='form' name='allow_attch' value='0'$aaa_checked> <i>No</i><br><input type='radio' class='form' name='allow_attch' value='1'$bbb_checked> <i>Yes</i>



			</td>

		</tr>

		$attch_extras

		<tr>

			<td class=row2>

				<b>Allow Avatar Uploads:</b><br>

				This is your choice, on whether or not you'd like <Br>to give your users the option to upload their avatars<br><b>NOTE:</b> <i> This will cost you domain space, but probably not much</i>

			</td>

			<td class=row2>

				<input type='radio' class='form' name='upload_avatars' value='0'$aa_checked> <i>No</i><br><input type='radio' class='form' name='upload_avatars' value='1'$bb_checked> <i>Yes</i>

			</td>

		</tr>
		
		$avatar_extras

		<tr>

			<td class=row1 valign=top>

				<b>Registration Terms:</b><br>

				The terms that the user has to accept before registration

			</td>

			<td class=row1>

				<textarea name=terms rows=15 cols=60>$_SETTING[terms]</textarea>

			</td>

		</tr>

		<tr>

			<td class=table_bottom align=center colspan=2>

				<input type=submit name=submit value=\"Save > >\">

				</form>

			</td>

		</tr>

	</table>";



	}

				

} else {

	echo "<center><span class=error>You need to be an admin to access this page!</span></center>";

}

?>