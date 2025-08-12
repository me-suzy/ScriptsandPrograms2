<?PHP

//error_reporting (E_ALL); // comment this if we are no testing

//echo "<pre>\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"</pre>";


// Include the configuration file.
include "config.php";

// Include the file needed to update the users online status.
include "updateonline.php";

// Name the page. Needed for the update online script.
$page_name = "User Control Panel";

// Now, actually update the users status.
updateonline('0','0','0', $_userid, $_username, $page_name);

// Start the timer needed for script execution time.
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

// Include the tempalte class files.
include ("class/template.class.php");
include ("class/mini_template.class.php");

// Create the new template.
$template = new Template ();

// Set the template files ( In order of display ).
$template->add_file ("header.tpl");
$template->add_file ("cp.tpl");

// Tell the template class what theme to use.
$template->set_template ("template", $user["theme"]);

// Set a few variables in the template file.
$template->set_template ("page_title", $_SETTING['organization']);
$template->set_template ("from_url", getenv(HTTP_REFERER));

// Include the ad page.
include ("ad.php");

// Check if the user is banned.  If so display a banned notice.
if ($_banned == TRUE) {

	// Defines the loop.
	$notice_str = $template->get_loop ("notice");

	// Then plugs the information into the loop.
	$template->end_loop ("notice", $notice_str);
	$template->set_template ("notice_message", "Your account has been banned for:<p>". pmcode('[redtable]'. $_banned_reason. '[/redtable]'));

// If the user is not banned than we dont need the notice loop. So, lets end it.
} else {
	$template->end_loop ("notice", "");
}

$mini_menu_guest = $template->get_loop ("guest_mini_menu");
$mini_menu_registered = $template->get_loop ("registered_mini_menu");
$mini_menu_admin = $template->get_loop ("admin_mini_menu");
$mini_menu_mod = $template->get_loop ("mod_mini_menu");

// Check if the user is logged in.
if($_userid != null){

	// Plugs the information into the page.
	$template->set_template ("new_messages", $new_mail);
	$template->set_template ("user_name", $_username);
	$template->set_template ("user_id", $_userid);

	// Plugs the information into the loop.
	$template->end_loop ("registered_mini_menu", $mini_menu_registered);
	$template->end_loop ("guest_mini_menu", "");
} else {
	$template->end_loop ("guest_mini_menu", $mini_menu_guest);
	$template->end_loop ("registered_mini_menu", "");
}

// If the user is an administrator show the admin menu.
if ($_userlevel == 3) {
	$template->end_loop ("admin_mini_menu", $mini_menu_admin);
// Otherwise hide it.
} else {
	$template->end_loop ("admin_mini_menu", "");
}


if ($new_mail > 0) {

	$new_message_table = $template->get_loop ("new_message");


	$n_m_result = mysql_query("select * from inbox WHERE reciever_id='". escape_string($_userid) ."' AND message_read='0' ORDER BY id DESC LIMIT 1");



	$n_m_row = mysql_fetch_array($n_m_result);



	$n_m_message = $n_m_row[message];

	if (strlen($n_m_message) > 200) {

		$n_m_message = substr($n_m_message, 0, 200) . "...";

	}



	$template->set_template ("new_message_count", $new_mail);


	$template->set_template ("new_message_id", $n_m_row[id]);


	$template->set_template ("new_message_subject", $n_m_row[subject]);


	$template->set_template ("new_message_from", $n_m_row[sender]);


	$template->set_template ("new_message_from_id", $n_m_row[sender_id]);


	$template->set_template ("new_message_date", date("l, F jS, Y", strtotime($n_m_row[date], "\n")));


	$template->set_template ("new_message_message", pmcode($n_m_message));


	$template->end_loop ("new_message", $new_message_table);


} else {


	$template->end_loop ("new_message", "");


}




$n_result = mysql_query("SELECT * FROM forums WHERE news='1'");





if (mysql_num_rows($n_result) > 0){



	$news_table = $template->get_loop ("news");



	$n_row = mysql_fetch_assoc($n_result);



	$nidi = $n_row['id'];



	$n_result = mysql_query("SELECT * FROM topics WHERE fid='". $nidi ."'");



	if (mysql_num_rows($n_result) > 0){



		$n_result = mysql_query("SELECT * FROM forums WHERE news='1' AND hidden='0' ORDER BY id DESC LIMIT 1");



		$n_row = mysql_fetch_array($n_result);



		$n_fid = $n_row['id'];







		$n_result = mysql_query("select * from topics WHERE fid='". $n_fid ."' ORDER BY id DESC LIMIT 1");



		$n_row = mysql_fetch_array($n_result);



		$n_poster = $n_row['poster'];



		$n_date = $n_row['date'];



		$n_date = date("l, F jS, Y", strtotime($n_date, "\n"));



		$n_title = $n_row['title'];



	    $n_tid = $n_row['id'];



		$n_message = $n_row['message'];



		$n_message = ekincode($n_message,$user['theme']);





		$template->set_template ("news_id", $n_tid);



		$template->set_template ("news_title", $n_title);



		$template->set_template ("news_poster", $n_poster);



		$template->set_template ("news_date", $n_date);



		$template->set_template ("news_message", $n_message);



		$template->end_loop ("news", $news_table);



	}	else {



		$template->end_loop ("news", "");



	}

}	else {



		$template->end_loop ("news", "");



}



if(isset($_userid)){

	$home_str = $template->get_loop ("home");

	$edit_pro_str = $template->get_loop ("edit_pro");

	$change_pass_str = $template->get_loop ("change_pass");

	$pass_changed_str = $template->get_loop ("pass_changed");

	$change_email_str = $template->get_loop ("change_email");

	$edit_sig_str = $template->get_loop ("edit_sig");

	$edit_ava_str = $template->get_loop ("edit_avatar");

	$upload_ava_str = $template->get_loop ("upload_avatar");



	if($_GET[act] == "editpro"){

		if($_GET[step] == 2){

			$display_name = htmlspecialchars($_POST['display_name']);

			$website_url = htmlspecialchars($_POST['website']);

			$aimsn = htmlspecialchars($_POST['aimsn']);

			$msnsn = htmlspecialchars($_POST['msnsn']);

			$yahoosn = htmlspecialchars($_POST['yahoosn']);

			$icq = htmlspecialchars($_POST['icq']);

			if($website_url != NULL){

	 			if (eregi("http://", $website_url)){

				} else {

					$website_url = "http://" . $website_url;

				}

			}

			$q = "SELECT * FROM users WHERE id='$_userid'";

			$res = mysql_query($q);

			$fetch = mysql_fetch_assoc($res);

			$user_id = $fetch['id'];



			$query = "UPDATE users SET display_name='". $display_name ."', website_url='". $website_url ."', aim='". $aimsn ."', msn='". $msnsn ."', yahoo='". $yahoosn ."', icq='". $icq ."' WHERE id='". $user_id ."' LIMIT 1";

			$result = mysql_query($query);



			header("Location: cp.php?act=editpro");



		} else {

			$pro_result = mysql_query("select * from users WHERE id='$_userid'");

			$pro_row = mysql_fetch_array($pro_result);

			$display_name = $pro_row['display_name'];

			$website = $pro_row['website_url'];

			$aim_sn = $pro_row['aim'];

			$msn_sn = $pro_row['msn'];

			$yim_sn = $pro_row['yahoo'];

			$icq_sn = $pro_row['icq'];



			$template->set_template ("display_name_error", $display_name_error);

			$template->set_template ("display_name_error_message", $display_name_error_message);



			$template->set_template ("display_name", $display_name);

			$template->set_template ("website", $website);

			$template->set_template ("aim_sn", $aim_sn);

			$template->set_template ("msn_sn", $msn_sn);

			$template->set_template ("yim_sn", $yim_sn);

			$template->set_template ("icq_sn", $icq_sn);



			$template->end_loop ("home", "");

			$template->end_loop ("edit_pro", $edit_pro_str);

			$template->end_loop ("change_pass", "");

			$template->end_loop ("pass_changed", "");

			$template->end_loop ("change_email", "");

			$template->end_loop ("email_notice", "");

			$template->end_loop ("edit_sig", "");

			$template->end_loop ("edit_avatar", "");

			$template->end_loop ("upload_avatar", "");

			$template->end_loop ("error", "");

		}

	} else if($_GET[act] == "changepass"){

		if($_GET[step] == 2){

			if (!$_POST[old_pass] || !$_POST[new_pass] || !$_POST[confirm]){

				if(!$_POST[old_pass]){

					$old_pass_error = 1;

					$old_pass_error_message = "You need to enter your old password!";

				}

				if(!$_POST[old_email]){

					$new_pass_error = 1;

					$new_pass_error_message = "Please enter your new password!";

				}

				if(!$_POST[new_email]){

					$confirm_error = 1;

					$confirm_error_message = "You need to confim your new password!";

				}

			} else {

				if($_POST[new_pass] != $_POST[confirm]){

					$new_pass_error = 1;

					$new_pass_error_message = "";

					$confirm_error = 1;

					$confirm_error_message = "Your new passowords do not match!";

				} else {

					$q = "SELECT * FROM users WHERE id='". $_userid ."'";

					$res = mysql_query($q);

					$fetch = mysql_fetch_assoc($res);



					if($fetch[password] != md5($_POST[old_pass])){

						$old_pass_error = 1;

						$old_pass_error_message = "Your old password is incorrect!";

					} else {

						$new_pass = md5($_POST[new_pass]);

						$q = "UPDATE users SET password='$new_pass' WHERE id='". $_userid ."'";

						$res = mysql_query($q);



						header("Location: index.php");

					}

				}

			}

		}

			$template->set_template ("old_pass_error", $old_pass_error);

			$template->set_template ("old_pass_error_message", $old_pass_error_message);

			$template->set_template ("submitted_old_pass", $_POST[old_pass]);

			$template->set_template ("new_pass_error", $new_pass_error);

			$template->set_template ("new_pass_error_message", $new_pass_error_message);

			$template->set_template ("submitted_new_pass", $_POST[new_pass]);

			$template->set_template ("confirm_error", $confirm_error);

			$template->set_template ("confirm_error_message", $confirm_error_message);

			$template->set_template ("submitted_confirm", $_POST[confirm]);



			$template->end_loop ("home", "");

			$template->end_loop ("edit_pro", "");

			$template->end_loop ("change_pass", $change_pass_str);

			$template->end_loop ("pass_changed", "");

			$template->end_loop ("change_email", "");

			$template->end_loop ("email_notice", "");

			$template->end_loop ("edit_sig", "");

			$template->end_loop ("edit_avatar", "");

			$template->end_loop ("upload_avatar", "");

			$template->end_loop ("error", "");

	} else if($_GET[act] == "changeemail"){

		if($_GET[step] == 2){

			if (!$_POST[password] || !$_POST[old_email] || !$_POST[new_email] || !$_POST[confirm_email]){

				if(!$_POST[password]){

					$password_error = 1;

					$password_error_message = "Your password is needed to ensure security!";

				}

				if(!$_POST[old_email]){

					$old_email_error = 1;

					$old_email_error_message = "Please enter your old e-mail address!";

				}

				if(!$_POST[new_email]){

					$new_email_error = 1;

					$new_email_error_message = "Please enter the e-mail address you would like to change to!";

				}

				if(!$_POST[confirm_email]){

					$email_confirm_error = 1;

					$email_confirm_error_message = "You need to confirm the new e-mail address!";

				}

			} else {

				$q = "SELECT * FROM users WHERE id='$_userid'";

				$res = mysql_query($q) or die (mysql_error());

				$fetch = mysql_fetch_assoc($res);



				if ((md5($_POST[password]) != $fetch['password']) || ($_POST[old_email] != $fetch['email'])){

					if (md5($_POST[password]) != $fetch['password']){

						$password_error = 1;

						$password_error_message = "Your password is incorrect!";

					}

					if ($_POST[old_email] != $fetch['email']){

						$old_email_error = 1;

						$old_email_error_message = "Your old e-mail address is incorrect!";

					}

				} else {

					$search_emails = "SELECT * FROM users WHERE email='". $_POST[new_email] ."'";

					$search_e_res = mysql_query($search_emails) or die (mysql_error());



					if (mysql_num_rows($search_e_res) > 0){

						$new_email_error = 1;

						$new_email_error_message = "This e-mail address already exists!";

					} else {

						if ($_POST[new_email] != $_POST[confirm_email]){

							$email_confirm_error = 1;

							$email_confirm_error_message = "This does not match your new e-mail address!";

						} else {

							$ran_num = rand();

							mysql_query("UPDATE users SET new_email='$_POST[new_email]', email_code='$ran_num' WHERE id='$_userid'");

							$_pass = md5($_POST[password]);

							echo "You're being forwarded..";



							    $date_month = date(m);

							    $date_year = date(Y);

							    $date_day = date(d);

							    $time_hour = date(H);

							    $time_min = date(i);



							    $Date = "$date_day/$date_month/$date_year - $time_hour:$time_min";



							    $subject = "Confirm Your E-mail Change";



							    $headers = "From: noreply@ekinboard.com\n";

							    $headers .= "Reply-To: noreply@ekinboard.com\n";

							    $headers .= "Organization: EKINdesigns\n";

							    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";



							    $design = "

							<HTML>

							<HEAD>

							<TITLE>EKINboard - Confirm Email Change</TITLE>

							<META HTTP-EQUIV=Content-Type CONTENT=\"text/html; charset=iso-8859-1\">

							<link rel=\"stylesheet\" type=text/css href=". $_SETTING['main_location'] ."/templates/". $_SETTING['template'] ."/style.css>

							</HEAD>

							<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>

							<center>

							<TABLE WIDTH=350 BORDER=0 CELLPADDING=0 CELLSPACING=0>

							<tr><td>

							Dear $first_name,

							<p>

							You were sent this email, in order to confirm your email change.

							<p>

							In order to confirm your email change, click the link below.

							<p>

							<a href=". $_SETTING['main_location'] ."/cp.php?act=emailconfirm&id=$_userid&ctp=$_pass&email_code=$ran_num>Confirm Email Change!</a>

							<p>

							Once you have clicked that link, the email change will take into affect!

							Sincerely yours,

							EKINboard

							<p>

							-----------------------------------------------------

							<p>

							Please do not reply to this e-mail. Mail sent to this address cannot be answered.

							</td></tr>



							</TABLE>

							</center>

							</BODY>

							</HTML>";



							    mail($new_email, $subject, $design, $headers);



							header("Location: cp.php?act=changeemail&step=3");

						}

					}

				}

			}



			$template->set_template ("password_error", $password_error);

			$template->set_template ("password_error_message", $password_error_message);

			$template->set_template ("submitted_password", $_POST[password]);

			$template->set_template ("old_email_error", $old_email_error);

			$template->set_template ("old_email_error_message", $old_email_error_message);

			$template->set_template ("submitted_old_email", $_POST[old_email]);

			$template->set_template ("new_email_error", $new_email_error);

			$template->set_template ("new_email_error_message", $new_email_error_message);

			$template->set_template ("submitted_new_email", $_POST[new_email]);

			$template->set_template ("email_confirm_error", $email_confirm_error);

			$template->set_template ("email_confirm_error_message", $email_confirm_error_message);

			$template->set_template ("submitted_email_confirm", $_POST[confirm_email]);



			$template->end_loop ("home", "");

			$template->end_loop ("edit_pro", "");

			$template->end_loop ("change_pass", "");

			$template->end_loop ("pass_changed", "");

			$template->end_loop ("change_email", $change_email_str);

			$template->end_loop ("email_notice", "");

			$template->end_loop ("edit_sig", "");

			$template->end_loop ("edit_avatar", "");

			$template->end_loop ("upload_avatar", "");

			$template->end_loop ("error", "");



		} else if($_GET[step] == 3){

			$email_notice_str = $template->get_loop ("email_notice");



			$template->set_template ("notice_message", "A confirmation email has been sent to the <b>new</b> email address.  Click the link in that email to confirm your email change.");





			$template->end_loop ("home", "");

			$template->end_loop ("edit_pro", "");

			$template->end_loop ("change_pass", "");

			$template->end_loop ("pass_changed", "");

			$template->end_loop ("change_email", "");

			$template->end_loop ("email_notice", $email_notice_str);

			$template->end_loop ("edit_sig", "");

			$template->end_loop ("edit_avatar", "");

			$template->end_loop ("upload_avatar", "");

			$template->end_loop ("error", "");

		} else {

			$template->set_template ("password_error", $password_error);

			$template->set_template ("password_error_message", $password_error_message);

			$template->set_template ("submitted_password", $_POST[password]);

			$template->set_template ("old_email_error", $old_email_error);

			$template->set_template ("old_email_error_message", $old_email_error_message);

			$template->set_template ("submitted_old_email", $_POST[old_email]);

			$template->set_template ("new_email_error", $new_email_error);

			$template->set_template ("new_email_error_message", $new_email_error_message);

			$template->set_template ("submitted_new_email", $_POST[new_email]);

			$template->set_template ("email_confirm_error", $email_confirm_error);

			$template->set_template ("email_confirm_error_message", $email_confirm_error_message);

			$template->set_template ("submitted_email_confirm", $_POST[confirm_email]);



			$template->end_loop ("home", "");

			$template->end_loop ("edit_pro", "");

			$template->end_loop ("change_pass", "");

			$template->end_loop ("pass_changed", "");

			$template->end_loop ("change_email", $change_email_str);

			$template->end_loop ("email_notice", "");

			$template->end_loop ("edit_sig", "");

			$template->end_loop ("edit_avatar", "");

			$template->end_loop ("upload_avatar", "");

			$template->end_loop ("error", "");

		}

	} else if($_GET[act] == "editsig"){

		if($_GET[step] == 2){

			$sig = htmlspecialchars($_POST['signature']);

			mysql_query("UPDATE users SET sig='". $sig ."' WHERE id='". $_userid ."'") or die (mysql_error());

			header("Location: cp.php?act=editsig");

		} else {

			$sig_result = mysql_query("SELECT * FROM users WHERE id='". $_userid ."'");

			$sig_row = mysql_fetch_array($sig_result);

			$current_sig_code = $sig_row['sig'];

			$current_sig = ekincode($current_sig_code,$user[theme]);



			$template->set_template ("current_sig", $current_sig);

			$template->set_template ("current_sig_code", $current_sig_code);



			$template->end_loop ("home", "");

			$template->end_loop ("edit_pro", "");

			$template->end_loop ("change_pass", "");

			$template->end_loop ("pass_changed", "");

			$template->end_loop ("change_email", "");

			$template->end_loop ("email_notice", "");

			$template->end_loop ("edit_sig", $edit_sig_str);

			$template->end_loop ("edit_avatar", "");

			$template->end_loop ("upload_avatar", "");

			$template->end_loop ("error", "");

		}

	} else if($_GET[act] == "editavatar"){

		if($_GET[step] == 2){
			
			if(($_SETTING['upload_avatars'] == 1) && ($_FILES['upload_avatars']['name'] != NULL)){
	
				$template->set_template ("upload_error_message", "");
	
				$sql_avatar_q = mysql_query("SELECT * FROM users WHERE id='$_userid'");
	
				$avatar_row = mysql_fetch_assoc($sql_avatar_q);
	
				$current_ava_str = $avatar_row['avatar'];
	
				list($filename, $file_ext) = explode(".", $_FILES['upload_avatars']['name']);
	
				$newfilename = $filename . "_" . $_userid.".".$file_ext;
	
				$old = "uploaded/avatars/" . $_FILES['upload_avatars']['name'];
	
				$new = "uploaded/avatars/" . $newfilename;
	
				if($new!=$current_ava_str){
						
			
					$max_size = $_SETTING['avatar_max_size'];
			
					$upload_path = "uploaded/avatars";
			
					$extensions = $_SETTING['avatar_exts'];
		
					$extensions = str_replace(" ", "", $extensions);
			
					$extensions = explode(",", $extensions);
			
					$ext_is = pathinfo($_FILES['upload_avatars']['name']);
			
					$ext_is = $ext_is['extension'];
			
					for($i=0; $i<count($extensions); $i++){
							
						if($ext_is==$extensions[$i]){	
							$ext_ok = "1";
						}
					
					}
					if($ext_ok=="1"){
							
						$filesize = $_FILES['upload_avatars']['size'];
			
						if($filesize<($max_size+1)){
								
							if(is_uploaded_file($_FILES['upload_avatars']['tmp_name'])){
				
								move_uploaded_file($_FILES['upload_avatars']['tmp_name'], $upload_path."/".$_FILES['upload_avatars']['name']);
			
							} else {
			
								$is_upload_error = TRUE;
			
								$upload_error = "Error uploading file!";
			
							}
			
						} else {
			
							$is_upload_error = TRUE;
			
							$upload_error = "File is too big!";
			
						}
			
					} else {
			
						$is_upload_error = TRUE;
			
						$upload_error = "Incorrect file type!";
					}
	
				
					if($is_upload_error != TRUE){
			
						list($filename, $file_ext) = explode(".", $_FILES['upload_avatars']['name']);
			
						$newfilename = $filename . "_" . $_userid.".".$file_ext;
			
						$old = $upload_path . "/" . $_FILES['upload_avatars']['name'];
			
						$new = $upload_path . "/" . $newfilename;
				
						rename($old, $new);
			
						unlink($current_ava_str);
			
						$redo_avatar = mysql_query("UPDATE users SET avatar='$new', ava_uploaded='1' WHERE id='$_userid'");
			
						header("Location: cp.php?act=editavatar");
			
			
					} else {
			
						$template->end_loop ("home", "");
			
						$template->end_loop ("edit_pro", "");
			
						$template->end_loop ("change_pass", "");
			
						$template->end_loop ("pass_changed", "");
					
						$template->end_loop ("change_email", "");
			
						$template->end_loop ("email_notice", "");
			
						$template->end_loop ("edit_sig", "");
			
						$template->end_loop ("edit_avatar", "");
				
						$template->end_loop ("upload_avatar", "");
			
						$ava_error = $template->get_loop ("error");
			
						$template->end_loop ("error", $ava_error);
						
						$template->set_template ("error_message", $upload_error."<br><br><a href=javascript:history.back();>Back</a>");
			
					}
					
					

				} else {

					header("Location: cp.php?act=editavatar");

				}
			
				} else {
					$avatar = $_POST['avatar_link'];
			
					if($avatar){
			
						$row_avatar = mysql_query("SELECT * FROM users WHERE id='$_userid'");
			
						$row_ava = mysql_fetch_assoc($row_avatar);
			
						if($row_ava['ava_uploaded'] == "1"){
			
							$current_ava_uploaded = $row_ava['avatar'];
			
							unlink($current_ava_uploaded);
			
					}
			
					mysql_query("UPDATE users SET avatar='$avatar', ava_uploaded='0' WHERE id='$_userid'");
	
				}
	
				$avatar_alt = $_POST['avatar_alt'];
				
				mysql_query("UPDATE users SET avatar_alt='". $avatar_alt ."' WHERE id='". $_userid ."'") or die (mysql_error());
					
				header("Location: cp.php?act=editavatar");
	
			}
		
	

		 } else {

			$ava_result = mysql_query("SELECT * FROM users WHERE id='". $_userid ."'");

			$row = mysql_fetch_array($ava_result);

			$current_ava_address = $row['avatar'];

			$current_ava_alt = $row['avatar_alt'];

			$current_ava = "<img src='$current_ava_address' border='0' alt='$current_ava_alt' title='$current_ava_alt'>";

			

			$template->set_template ("current_ava", $current_ava);

			$template->set_template ("current_ava_alt", $current_ava_alt);

			if($row['ava_uploaded']=="0"){

				$template->set_template ("current_ava_address", $current_ava_address);

			} else {

				$template->set_template ("current_ava_address", "");

			}


			$template->end_loop ("home", "");

			$template->end_loop ("edit_pro", "");

			$template->end_loop ("change_pass", "");

			$template->end_loop ("pass_changed", "");

			$template->end_loop ("change_email", "");

			$template->end_loop ("email_notice", "");

			$template->end_loop ("edit_sig", "");

			$template->end_loop ("edit_avatar", $edit_ava_str);

			if($_SETTING['upload_avatars'] == 1){

				$template->end_loop ("upload_avatar", $upload_ava_str);

			} else {

				$template->end_loop ("upload_avatar", "");

			}

			$template->end_loop ("error", "");






		}

	

	} else {

		$template->end_loop ("home", $home_str);

		$template->end_loop ("edit_pro", "");

		$template->end_loop ("change_pass", "");

		$template->end_loop ("pass_changed", "");

		$template->end_loop ("change_email", "");

		$template->end_loop ("email_notice", "");

		$template->end_loop ("edit_sig", "");

		$template->end_loop ("edit_avatar", "");

		$template->end_loop ("upload_avatar", "");

		$template->end_loop ("error", "");

	}

} else {

	$error_str = $template->get_loop ("error");



	$template->set_template ("error_message", "Please login before you proceed!");



	$template->end_loop ("home", "");

	$template->end_loop ("edit_pro", "");

	$template->end_loop ("change_pass", "");

	$template->end_loop ("pass_changed", "");

	$template->end_loop ("change_email", "");

	$template->end_loop ("email_notice", "");

	$template->end_loop ("edit_sig", "");

	$template->end_loop ("edit_avatar", "");

	$template->end_loop ("upload_avatar", "");

	$template->end_loop ("error", $error_str);

}



$online_str = $template->get_loop ("user_online");

$online_today_str = $template->get_loop ("online_today");



$final_online = NULL;

$final_online_today = NULL;



$a_result = mysql_query("SELECT * FROM online WHERE isonline='1'");

$total_count = mysql_num_rows($a_result);



$b_result = mysql_query("SELECT * FROM online WHERE guest='0' AND isonline='1'");

$member_count = mysql_num_rows($b_result);



$c_result = mysql_query("SELECT * FROM online WHERE guest='1' AND isonline='1'");

$guest_count = mysql_num_rows($c_result);



$new_result = mysql_query("select * from users ORDER BY id DESC LIMIT 1");

$row = mysql_fetch_array($new_result);

$new_id = $row['id'];

$new_name = $row['username'];



$topiccount_result = mysql_query("SELECT * FROM topics");

$topiccount = mysql_num_rows($topiccount_result);

$repliescount_result = mysql_query("SELECT * FROM replies");

$repliescount = mysql_num_rows($repliescount_result);

$totalpostcount = $topiccount + $repliescount;



if($member_count<=1){

	$onlinenow_count = null;

} else {

	$onlinenow_count = TRUE;

}



$d_result = mysql_query("SELECT * FROM online WHERE guest='0' AND isonline='1'");

$num = mysql_num_rows ($d_result);

$current = 1;



while($row = mysql_fetch_array($d_result)){

	$o_id = $row['id'];

	$o_user = $row['username'];

	$online_posting = $row['posting'];



	$e_result = mysql_query("SELECT * FROM users WHERE id='". $o_id ."'");

	$row = mysql_fetch_array($e_result);

	$o_level = $row['level'];



	if(($onlinetoday_count<=1) || ($i==$onlinetoday_count-1)){

		$onlinenow_count = null;

		$i = null;

	} else {

		$onlinenow_count = TRUE;

		$i = $i+1;

	}



	$mini_template = new MiniTemplate ();



	$mini_template->template_html = $online_str;



	$mini_template->set_template ("online_num", $onlinenow_count);

	$mini_template->set_template ("online_id", $o_id);

	$mini_template->set_template ("online_user", $o_user);

	$mini_template->set_template ("online_level", $o_level);

	$mini_template->set_template ("online_posting", $online_posting);

	$mini_template->set_template ("spacer", (($current < $num) ? "," : ""));



	$final_online .= $mini_template->return_html ();



	$current++;



}



$template->end_loop ("user_online", $final_online);



$membercount_result = mysql_query("SELECT * FROM users");

$membercount = mysql_num_rows($membercount_result);



$onlinetoday_count_result = mysql_query("SELECT * FROM online WHERE guest='0' ORDER BY timestamp DESC");

$onlinetoday_count = $num = mysql_num_rows($onlinetoday_count_result);

$i = 0;

$current = 1;



while($row = mysql_fetch_array($onlinetoday_count_result)){

	$o_id = $row['id'];

	$o_user = $row['username'];

	$online_posting = $row['posting'];



	$e_result = mysql_query("SELECT * FROM users WHERE id='". $o_id ."'");

	$row = mysql_fetch_array($e_result);

	$o_level = $row['level'];





if(($onlinetoday_count<=1) || ($i==$onlinetoday_count-1)){

	$onlinenow_count = null;

	$i = null;

} else {

	$onlinenow_count = TRUE;

	$i = $i+1;

}



	$mini_template = new MiniTemplate ();



	$mini_template->template_html = $online_today_str;



	$mini_template->set_template ("online_num", $onlinenow_count);

	$mini_template->set_template ("online_id", $o_id);

	$mini_template->set_template ("online_user", $o_user);

	$mini_template->set_template ("online_level", $o_level);

	$mini_template->set_template ("online_posting", $online_posting);

	$mini_template->set_template ("spacer", (($current < $num) ? "," : ""));



	$final_online_today .= $mini_template->return_html ();



	$current++;



}



$template->end_loop ("online_today", $final_online_today);



$template->set_template ("total_active_users", number_format($total_count));

$template->set_template ("total_active_guests", number_format($guest_count));

$template->set_template ("total_active_members", number_format($member_count));

$template->set_template ("total_post_count", number_format($totalpostcount));

$template->set_template ("total_member_count", number_format($membercount));

$template->set_template ("newest_user_id", "$new_id");

$template->set_template ("newest_user", "$new_name");

$template->set_template ("online_today_count", number_format($onlinetoday_count));



$mtime = microtime();

$mtime = explode(" ",$mtime);

$mtime = $mtime[1] + $mtime[0];

$endtime = $mtime;

$totaltime = ($endtime - $starttime);

$totaltime = number_format($totaltime,3);





$load = @exec('uptime');

preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$load,$avgs);





$template->set_template ("ekinboard_version", $_version);

$template->set_template ("server_load", "[ Server Load: $avgs[1] ]");

$template->set_template ("execution_time", "[ Script Execution time: $totaltime ]");



echo $template->end_page ();

//var_dump (get_defined_vars ());

?>