<?php



include ("config.php");

include ("updateonline.php");



$page_name = "Login";

updateonline('0','0','0', $_userid, $_username, $page_name);



function sendmail($reciever,$username,$template,$main_location,$main_email,$organization,$password){



	$result = mysql_query("SELECT * FROM users WHERE username='". $username ."'");

	$row = mysql_fetch_array($result);

	$id = $row['id'];

	$activation = $row['activation_code'];



    $date_month = date(m);

    $date_year = date(Y);

    $date_day = date(d);

    $time_hour = date(H);

    $time_min = date(i);



    $Date = "$date_day/$date_month/$date_year - $time_hour:$time_min";



    $subject = "Login Information!";



    $headers = "From: $organization <$main_email>\n";

    $headers .= "Reply-To: $main_email\n";

    $headers .= "Organization: $organization\n";

    $headers .= "Content-Type: text/html";



    $design = "

<HTML>

<HEAD>

<TITLE>Login Information</TITLE>

<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">

<link rel=\"stylesheet\" type=\"text/css\" href=\"". $main_location ."/templates/". $template ."/style.css\">

</HEAD>

<body>

Dear $username,

<p>

The login information as requested is listed below:<br>

Username: $username <br>

Password: $password <br>

<br>

$organization Staff

<p>

-----------------------------------------------------------

<p>

Please do not reply to this e-mail. Mail sent to this address cannot be answered.<p>

<a href=http://www.ekinboard.com target=_blank>EKINboard</a> v1 © 2005 <a href=http://www.ekindesigns.com target=_blank>EKINdesigns</a>

</BODY>

</HTML>";



    mail($reciever, $subject, $design, $headers);



}



function sendmail2($reciever,$user_id,$username,$template,$main_location,$main_email,$organization,$code){



	$result = mysql_query("SELECT * FROM users WHERE username='". $username ."'");

	$row = mysql_fetch_array($result);

	$id = $row['id'];

	$activation = $row['activation_code'];



    $date_month = date(m);

    $date_year = date(Y);

    $date_day = date(d);

    $time_hour = date(H);

    $time_min = date(i);



    $Date = "$date_day/$date_month/$date_year - $time_hour:$time_min";



    $subject = "Password Reset!";



    $headers = "From: $organization <$main_email>\n";

    $headers .= "Reply-To: $main_email\n";

    $headers .= "Organization: $organization\n";

    $headers .= "Content-Type: text/html";



    $design = "

<HTML>

<HEAD>

<TITLE>Password Reset</TITLE>

<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">

<link rel=\"stylesheet\" type=\"text/css\" href=\"". $main_location ."/templates/". $template ."/style.css\">

</HEAD>

<body>

Dear $username,

<p>

You have requested a password reset from ". $organization .". Please click the link below to finish resetting your password.

<p>

<a href=". $main_location ."/login.php?d=forgot&id=". $user_id ."&code=". $code .">Reset Your Password</a>

<p>

If you did not request this password reset, just do not click the link and no change will come to your account.

<p>

$organization Staff

<p>

-----------------------------------------------------------

<p>

Please do not reply to this e-mail. Mail sent to this address cannot be answered.<p>

<a href=http://www.ekinboard.com target=_blank>EKINboard</a> v1 © 2005 <a href=http://www.ekindesigns.com target=_blank>EKINdesigns</a>

</BODY>

</HTML>";



    mail($reciever, $subject, $design, $headers);



}



$new_mail = NULL;

$username_error = NULL;

$username_error_message = NULL;

$password_error = NULL;

$password_error_message = NULL;

$submitted_user = NULL;

$onlinetoday_count = NULL;



if(!isset($_GET['d'])){

	$_GET['d'] = NULL;

}

$from_url = getenv('HTTP_REFERER');



if(($_GET['d']=="login") || ($_GET['d']=="logout") || ($_GET['d']=="forgot")){

	if($_GET['d']=="login"){

		$submitted_user = $_POST['username'];

		$submitted_pass = $_POST['password'];

		if($_POST['from_url'] != NULL){
			$from_url = $_POST['from_url'];
		}

		if(($submitted_user!=NULL) && ($submitted_pass!=NULL)){

			$password = md5($submitted_pass);



			$l_result = mysql_query("select * from users WHERE username='". $_POST[username] ."'");



	 		$l_count = mysql_num_rows($l_result);



			if($l_count>0){

				$username_error = 0;



				$row = mysql_fetch_array($l_result);

				$f_password = $row['password'];

				$f_username = $row['username'];



				if($f_password==$password){

					$password_error = 0;



					$f_activated = $row['activated'];

					$f_banned = $row['banned'];

					if($f_activated==1){

						$date = date("Y-m-j");

						$u_result = mysql_query("UPDATE users SET lastlogin='". $date ."' WHERE username='". $submitted_user ."'");



						$username_error = 0;



						if ($_POST['cookie'] == '1'){

							setcookie("username", $submitted_user, time()+604800);

							setcookie("password", $password, time()+604800);

						}



						$_SESSION['username'] = $f_username;

						$_SESSION['password'] = $f_password;



						$delete_results = mysql_query("DELETE FROM online WHERE ip='". $REMOTE_ADDR ."'");



						header("Location: $from_url");

					} else {

						if($f_activated==0){

							$username_error = 1;

							$username_error_message = "Not Activated!";

						}

					}

				} else {

					$password_error = 1;

					$password_error_message = "Password is incorrect!";

				}

			} else {

				$username_error = 1;

				$username_error_message = "Username does not exist!";

			}

		} else {

			if($submitted_user==NULL){

				$username_error = 1;

				$username_error_message = "Username is missing!";

			}

			if($submitted_pass==NULL){

				$password_error = 1;

				$password_error_message = "Password is missing!";

			}

		}

	} else if($_GET['d']=="logout"){

		$_SESSION['username'] = FALSE;

		$_SESSION['password'] = FALSE;

		session_destroy();



		setcookie("username", '', time() - 604800);

		setcookie("password", '', time() - 604800);

		header("Location: ". getenv(HTTP_REFERER));

	} else if($_GET['d']=="forgot"){

		if (isset($_POST[submit])) {

			if ($_POST[username] == NULL) {

				$username_error=1;

				$username_error_message="Please enter your username.";

			} else {

				$username_result=mysql_query("SELECT * FROM `users` WHERE `username`='$_POST[username]' AND `activated`!='0'");

				$count=mysql_num_rows($username_result);

				if ($count == 0) {

					$username_error=1;

					$username_error_message="User does not exist.";

				} else {

					$username_row=mysql_fetch_array($username_result);

					$code=md5(time() . $username_row[username] . $username_row[email]);

					$result=mysql_query("UPDATE `users` SET `activation_code`='$code' WHERE `username`='$_POST[username]'");

					sendmail2($username_row[email],$username_row[id],$username_row[username],$user[theme],$_SETTING[main_location],$_SETTING[main_email],$_SETTING[organization],$code);

					$password_sent2=TRUE;

				}

			}

		} else if ($_GET[code] != NULL) {

			$user_result=mysql_query("SELECT * FROM `users` WHERE `id`='$_GET[id]' AND `activation_code`='$_GET[code]' AND `activated`!='0'");

			$count=mysql_num_rows($user_result);

			if ($count == 0) {

				header("Location: login.php?d=forgot");

			} else {

				$user_row=mysql_fetch_array($user_result);

				$password='';

				$alphanumeric="abcdefghijklmnopqrstuvwxyz1234567890";

				for ($i=0;$i<8;$i++) {

					$password.=$alphanumeric[rand(0,strlen($alphanumeric)-1)];

				}

				$result=mysql_query("UPDATE `users` SET `activation_code`='', `password`='". md5($password) ."' WHERE `id`='$_GET[id]'");

				sendmail($user_row[email],$user_row[username],$user[theme],$_SETTING[main_location],$_SETTING[main_email],$_SETTING[organization],$password);

				$password_sent=TRUE;

			}

		} else {

			$username_error=0;

			$username_error_message='';

		}







/*		if(($_POST[email] == NULL) && (isset($_POST[submit]))){

			$email_error = 1;

			$email_error_message = "Please enter your email address!";

		} else {



			$l_result = mysql_query("select * from users WHERE email='". $_POST[email] ."'");

	 		$l_count = mysql_num_rows($l_result);



			if($l_count>0){

				$password_sent = TRUE;

			} else {

				$password_sent = FALSE;



				$email_error = 1;

				$email_error_message = "Email address is not found!";

			}

		}*/

	}

}

	$mtime = microtime();

	$mtime = explode(" ",$mtime);

	$mtime = $mtime[1] + $mtime[0];

	$starttime = $mtime;



	include ("class/template.class.php");

	include ("class/db.class.php");

	include ("class/mini_template.class.php");



	$template = new Template ();

	$db = new db ($db_host, $db_user, $db_pass, $db_name);



	$template->add_file ("header.tpl");

	$template->add_file ("login.tpl");



	$template->set_template ("template", $user["theme"]);

$template->set_template ("page_title", $_SETTING['organization']);



include ("ad.php");

if ($_banned == TRUE) { // check to see if the user was banned
	$notice_str = $template->get_loop ("notice");

	$template->end_loop ("notice", $notice_str);
	$template->set_template ("notice_message", "Your account has been banned for:<p>". pmcode('[redtable]'. $_banned_reason. '[/redtable]'));
} else {
	$template->end_loop ("notice", "");
}

	if($_userid != null){



		$logged_in = 1;

		$user_id = $_userid;

		$user_name = $_username;

		$user_level = $_userlevel;

		$check_mail = mysql_query("SELECT * FROM inbox WHERE reciever_id='". $_userid ."' AND message_read='0'");

		$new_mail = mysql_num_rows($check_mail);



		$template->set_template ("new_messages", $new_mail);

		$template->set_template ("user_name", $user_name);

		$template->set_template ("user_id", $user_id);



	} else {

		$logged_in = 0;

	}



	$mini_menu_guest = $template->get_loop ("guest_mini_menu");

	$mini_menu_registered = $template->get_loop ("registered_mini_menu");

	$mini_menu_admin = $template->get_loop ("admin_mini_menu");

	$mini_menu_mod = $template->get_loop ("mod_mini_menu");



	if ($logged_in == 0) { // guest

		$template->end_loop ("guest_mini_menu", $mini_menu_guest);

	} else {

		$template->end_loop ("guest_mini_menu", "");

	}



	if ($logged_in == 1) { // registered user

		$template->end_loop ("registered_mini_menu", $mini_menu_registered);

	} else {

		$template->end_loop ("registered_mini_menu", "");

	}



	if ($_userlevel == 3) { // admin

		$template->end_loop ("admin_mini_menu", $mini_menu_admin);

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



		$n_result = mysql_query("select * from forums WHERE news='1' AND hidden='0' ORDER BY id DESC LIMIT 1");



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

	//////////////////////////////////////////////////////////////////////////////////////



	$logged_in_notice = $template->get_loop ("logged_in");

	$login_form = $template->get_loop ("login");

	$forgot_sent_notice = $template->get_loop ("forgot_sent");

	$forgot_sent_notice2 = $template->get_loop ("forgot_sent2");

	$forgot_form = $template->get_loop ("forgot");



if($_GET[d] == "forgot"){

	if($_userid == NULL){

		$mini_template = new MiniTemplate ();



		$template->set_template ("username_error", $username_error);

		$template->set_template ("username_error_message", $username_error_message);



		if($password_sent == TRUE){

			$template->end_loop ("forgot", "");

			$template->end_loop ("forgot_sent", $forgot_sent_notice);

			$template->end_loop ("forgot_sent2", "");

		} else if ($password_sent2 == TRUE) {

			$template->end_loop ("forgot", "");

			$template->end_loop ("forgot_sent", "");

			$template->end_loop ("forgot_sent2", $forgot_sent_notice2);

		} else {

			$template->end_loop ("forgot", $forgot_form);

			$template->end_loop ("forgot_sent", "");

			$template->end_loop ("forgot_sent2", "");

		}

		$template->end_loop ("login", "");

		$template->end_loop ("logged_in", "");

	} else {

		$template->end_loop ("login", "");

		$template->end_loop ("forgot", "");

		$template->end_loop ("forgot_sent", "");

		$template->end_loop ("forgot_sent2", "");

		$template->end_loop ("logged_in", $logged_in_notice);

		$template->end_loop ("error_login", "");

	}

} else {

	if($_userid == NULL){

		$mini_template = new MiniTemplate ();



		$mini_template->template_html = $login_form;

		$mini_template->set_template ("username_error", $username_error);

		$mini_template->set_template ("username_error_message", $username_error_message);

		$mini_template->set_template ("password_error", $password_error);

		$mini_template->set_template ("password_error_message", $password_error_message);

		$mini_template->set_template ("submitted_user", $submitted_user);

		$mini_template->set_template ("from_url", $from_url);



		$template->end_loop ("forgot", "");

		$template->end_loop ("forgot_sent", "");

		$template->end_loop ("forgot_sent2", "");

		$template->end_loop ("login", $mini_template->return_html ());

		$template->end_loop ("logged_in", "");

	} else {

		$template->end_loop ("login", "");

		$template->end_loop ("forgot", "");

		$template->end_loop ("forgot_sent", "");

		$template->end_loop ("forgot_sent2", "");

		$template->end_loop ("logged_in", $logged_in_notice);

		$template->end_loop ("error_login", "");

	}



}

	//////////////////////////////////////////////////////////////////////////////////////









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