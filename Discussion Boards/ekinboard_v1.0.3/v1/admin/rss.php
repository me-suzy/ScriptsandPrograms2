<?PHP

if($_userlevel == 3){



if(isset($_POST['submit'])){



}



echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/rss_title.gif\" border=0 alt=\"\"><p>";



	if ($_GET['step'] == 2) { // Update the database

			$temp_set_check =  mysql_query("SELECT * FROM settings WHERE name='allow_feed'") or die(mysql_error());
			$temp_set_check = mysql_num_rows($temp_set_check);

			if($temp_set_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('allow_feed', '$_POST[allow_feed]')";
				$result = mysql_query($query_c);
			} else {
				$update_temp_settings = mysql_query("UPDATE `settings` SET `value`='$_POST[allow_feed]' WHERE `name`='allow_feed'") or die(mysql_error());
			}

			$temp_set_check =  mysql_query("SELECT * FROM settings WHERE name='feed_display'") or die(mysql_error());
			$temp_set_check = mysql_num_rows($temp_set_check);

			if($temp_set_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('feed_display', '$_POST[feed_display]')";
				$result = mysql_query($query_c);
			} else {
				$update_temp_settings = mysql_query("UPDATE `settings` SET `value`='$_POST[feed_display]' WHERE `name`='feed_display'") or die(mysql_error());
			}

		header("Location: index.php?page=rss");

	} else { // Echo the current settings

        
        if($_SETTING['allow_feed'] == '1'){
            $a_checked = " checked=checked";
        } else if($_SETTING['upload_avatars'] == '0'){
            $b_checked = " checked=checked";
        } else {
            $a_checked = " checked=checked";
        }

echo "<table width=100% class=category_table cellpadding=0 cellspacing=0 align=center>

		<tr>



			<td class=table_1_header colspan=2>			



				<b>RSS Feed Configuration</b>			

				<form action=index.php?page=rss&step=2 method=post>

			</td>



		</tr>

		<tr>

			<td class=row2>

				<b>Feeds:</b><br>

				Allows others to view the feeds

			</td>

			<td class=row2>

				<input type='radio' class='form' name='allow_feed' value='1'$a_checked> <i>Yes</i><br><input type='radio' class='form' name='allow_feed' value='0'$b_checked> <i>No</i>



			</td>

		</tr>

			<td class=row2>

				<b>Feeds to display:</b><br>

				The ammount of RSS Feeds to show at one time

			</td>

			<td class=row2>
				<select name='feed_display' size='1'>";

		for($i=1;$i<=30;$i++){
			if(($i <= 10) || ($i == 15) || ($i == 20) || ($i == 25) || ($i == 25)){
				if($i == $_SETTING['feed_display']){
					$feed_sel = " SELECTED";
				} else {
					$feed_sel = NULL;
				}
				echo "<option value='$i'$feed_sel>$i</option>";
			}
		}
				echo "</select>
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