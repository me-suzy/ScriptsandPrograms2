<?PHP

if($_userlevel != 3){

    die("<center><span class=red>You need to be an admin to access this page!</span></center>");

}

	echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src='images/template_title.gif'><p>";



	$step = $_GET['step'];

	switch($step)

	{

		default:



			// GET TEMPLATE SETTINGS

			if($_SETTING['temp_setting'] == '0'){



				$set_options = "<input type='radio' name='tempset' value='0' checked='checked'> - <input type='radio' name='tempset' value='1'>";



			} else if($_SETTING['temp_setting'] == '1'){



				$set_options = "<input type='radio' name='tempset' value='0'> - <input type='radio' name='tempset' value='1' checked='checked'>";



			}



			$templates = NULL;



			if ($handle = opendir('../templates/')) {



			   while (false !== ($file = readdir($handle))) {



				   if ($file != "." && $file != "..") {



					   if($file == $_SETTING['template']){

					   

							$templates .= "<option value='$file' selected>$file</option>";



					   } else {



							$templates .= "<option value='$file'>$file</option>";



					   }



				   }



			   }

			   closedir($handle);

			}



			echo "<form name='ctemp' action='index.php?page=template&step=2' method='post'>

					<table width=90% class=category_table cellpadding=0 cellspacing=0 align=center>

						<tr>

							<td class=table_1_header>			

								<b>Template Options</b>			

							</td>

						</tr>

						<tr>

							<td>



							<table  width=100% border=0 cellpadding=0 cellspacing=0 align=center>

								<tr>

									<td width=60% valign=top class='row1'>

										<b>Choose Template:</b><br>

										<font size=1>(Template for guest skin or global skin)</font>

									</td>

									<td class='row1'>

										<select name='template' class='textbox'>

											$templates

										</select>

									</td>

								</tr>

								<tr>

									<td width=60% valign=top class='row2'>

										<b>Template Global Settings:</b> <br>

										<font size=1>(Override user settings or use just for guests)</font>

									</td>

									<td class='row2'>

										Guests $set_options Global

									</td>

								</tr>

								<tr>

									<td colspan=2 align=center class=table_bottom>

										<input type=submit name=submit value=Submit>

									</td>

								</tr>

							</table>

							</td>

						</tr>

					</table>";







		break;





		case '2':



			$temp_set = $_POST['tempset'];

			$temp_choice = $_POST['template'];





			// UPDATE AND WE'RE DONE

			$temp_set_check =  mysql_query("SELECT * FROM settings WHERE name='temp_setting'") or die(mysql_error());
			$temp_set_check = mysql_num_rows($temp_set_check);

			if($temp_set_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('temp_setting', '$temp_set')";
				$result = mysql_query($query_c);
			} else {
				$update_temp_settings = mysql_query("UPDATE settings SET value='$temp_set' WHERE name='temp_setting'") or die(mysql_error());
			}

			$temp_check =  mysql_query("SELECT * FROM settings WHERE name='template'") or die(mysql_error());
			$temp_check = mysql_num_rows($temp_check);

			if($temp_check == 0){
				$query_c = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('template', '$temp_choice')";
				$result = mysql_query($query_c);
			} else {
				$update_template = mysql_query("UPDATE settings SET value='$temp_choice' WHERE name='template'") or die(mysql_error());
			}
			

			echo "<br><br>";



			echo "<center><b>Template Settings Updated!</b></center>";





		break;



	}

?>