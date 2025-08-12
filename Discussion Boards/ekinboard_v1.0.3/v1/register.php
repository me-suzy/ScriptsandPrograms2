<?php



include ("config.php");

include ("updateonline.php");



$page_name = "Register";

updateonline('0','0','0', $_userid, $_username, $page_name);



    function validEmail($addr)

    {

    	list($local, $domain) = explode("@", $addr);

    	$pattern_local = '^([0-9a-z]*([-|_]?[0-9a-z]+)*)(([-|_]?)\.([-|_]?)[0-9a-z]*([-|_]?[0-9a-z]+)+)*([-|_]?)$';

    	$pattern_domain = '^([0-9a-z]+([-]?[0-9a-z]+)*)(([-]?)\.([-]?)[0-9a-z]*([-]?[0-9a-z]+)+)*\.[a-z]{2,4}$';

    	$match_local = eregi($pattern_local, $local);

    	$match_domain = eregi($pattern_domain, $domain);

    	if ($match_local && $match_domain)

    		return 1;

    	else

    		return 0;

    }



function sendmail($reciever,$username,$template,$main_location,$main_email,$organization){



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



    $subject = "Activate your account!";



    $headers = "From: $organization <$main_email>\n";

    $headers .= "Reply-To: $main_email\n";

    $headers .= "Organization: $organization\n";

    $headers .= "Content-Type: text/html";



    $design = "

<HTML>

<HEAD>

<TITLE>Account Activation</TITLE>

<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">

<link rel=\"stylesheet\" type=\"text/css\" href=\"". $main_location ."/templates/". $template ."/style.css\">

</HEAD>

<body>

Dear $username,

<p>

Congratulations! You have just registered with $organization.

<p>

But you're not done yet!

<p>

In order to complete your registration you must click the link below to confirm your email address.

<p>

<a href=". $main_location ."/register.php?act=activate&id=". $id ."&code=". $activation .">Activate Now!</a>

<p>

Thank you for your interest in $organization!

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

$new_mail = NULL;

$error_message = NULL;

$account_created =NULL;

$onlinetoday_count = NULL;



if(!isset($_GET['email'])){

	$_GET['email'] = NULL;

}

if(!isset($_GET['username'])){

	$_GET['username'] = NULL;

}

if(!isset($_GET['password'])){

	$_GET['password'] = NULL;

}

if(!isset($_GET['confirm'])){

	$_GET['confirm'] = NULL;

}

if(!isset($_GET['email_confirm'])){

	$_GET['email_confirm'] = NULL;

}

if(!isset($_GET['accept'])){

	$_GET['accept'] = NULL;

}



if($_GET['act'] == "activate"){

	if((!$_GET['id']) || (!$_GET['code'])){

		$activation_notice = 1;

		$activation_notice_message = "Some information is missing!";

	} else {

		$result = mysql_query("SELECT * FROM users WHERE id='". $_GET[id] ."' AND activation_code='". $_GET[code] ."'");

		$count = mysql_num_rows($result);



		if($count == 0){

			$activation_notice = 1;

			$activation_notice_message = "The account you are trying to activate does not exist!";

		} else {

			$row = mysql_fetch_array($result);

			$activated = $row['activated'];



			if($activated==1){

				$activation_notice = 1;

				$activation_notice_message = "The account you are trying to activate has already been activated!";

			} else {

				$result = mysql_query("UPDATE users SET activated='1' WHERE id='". $_GET[id] ."'");

				$activation_notice = 0;

				$activation_notice_message = "Thank you!<p>Your account has now been activated!  You may login and experience the benefits of being a registered member.";

			}

		}

	}





} else {

	if(($_GET['email']!=NULL) || ($_GET['username']!=NULL) || ($_GET['password']!=NULL) || ($_GET['confirm']!=NULL) || ($_GET['email_confirm']!=NULL)){

		if($_username == null){

			$email = htmlspecialchars($_GET['email']);

			$email_confirm = htmlspecialchars($_GET['email_confirm']);

			$username = htmlspecialchars($_GET['username']);

			$password = htmlspecialchars($_GET['password']);

			$confirm = htmlspecialchars($_GET['confirm']);







			if((!$email) || (!$username) || (!$password) || (!$confirm) || (!$email_confirm)){

				if((!$email) || (!$email_confirm)){

					$email_error_message = "One or both of the email address fields have been left blank!";

					$email_error = 1;

				} else {

					$email_error = 0;

				}

				if(!$username){

					$username_error_message = "Username is missing!";

					$username_error = 1;

				} else {

					$username_error = 0;

				}

				if((!$password) || (!$confirm)){

					$password_error_message = "One or both of the password fields have been left blank!";

					$password_error = 1;

				} else {

					$password_error = 0;

				}

			} else {

				if(($password != $confirm) || (strlen($password) < 3) || (strlen($password) > 15)){

					if(strlen($password) < 3){

						$password_error_message = "Passwords must be more than 3 characters!";

						$password_error = 1;

					}

					if(strlen($password) > 15){

						$password_error_message = "Passwords must be less than 15 characters!";

						$password_error = 1;

					}

					if($password != $confirm){

						$password_error_message = "Passwords do not match!";

						$password_error = 1;

					}

				} else {

						$password_error = 0;



						$result = mysql_query("select * from users where username='$username'");

						$count1 = mysql_num_rows($result);



						if(!$count1){

							$username_error = 0;



							$result2 = mysql_query("select * from users where email='$email'");

							$count2 = mysql_num_rows($result2);



							if(!$count2){

								$email_check = $email;

								if(($email != $email_confirm) || (!validEmail($email_check))){

									if($email != $email_confirm){

										$email_error_message = "E-mail addresses do not match!";

										$email_error = 1;

									}

									if(!validEmail($email_check)){

										$email_error_message = "Invalid e-mail address!";

										$email_error = 1;

									}

								} else {

									$email_error = 0;

									$password = md5($password);

									$date = date("Y-m-j");

									$activation_code = time();

									$activation_code = md5($activation_code);



									$result = MYSQL_QUERY("INSERT INTO users (first_name,email,username,password,signup_date,website_url,msn,aim,yahoo,icq,activated,sig,activation_code)

											  VALUES ('". $first_name ."', '". $email ."', '". $username ."', '". $password ."', '". $date ."', '". $website ."', '". $msn ."', '". $aim ."', '". $yahoo ."', '". $icq ."', '0', '". $sig ."', '". $activation_code ."')");



									$account_created = TRUE;



									if($_SETTING['acitvate_accounts'] == 1){

										sendmail($email,$username,$user[theme],$_SETTING['main_location'],$_SETTING['main_email'],$_SETTING['organization']);

									}

								}

							} else {

								$email_error_message = "An account has already been set up with your email address!";

								$email_error = 1;

							}

						} else {

							$username_error_message = "Your username has already been chosen!";

							$username_error = 1;

						}

					}

			}

		}

	}

}



	$mtime = microtime();

	$mtime = explode(" ",$mtime);

	$mtime = $mtime[1] + $mtime[0];

	$starttime = $mtime;



	include ("class/template.class.php");

	include ("class/mini_template.class.php");



	$template = new Template ();



	$template->add_file ("header.tpl");

	$template->add_file ("register.tpl");



	$template->set_template ("template", $user["theme"]);

$template->set_template ("page_title", $_SETTING['organization']);
$template->set_template ("from_url", getenv(HTTP_REFERER)); 


include ("ad.php");

if ($_banned == TRUE) { // check to see if the user was banned
	$notice_str = $template->get_loop ("notice");

	$template->end_loop ("notice", $notice_str);
	$template->set_template ("notice_message", "Your account has been banned for:<p>". pmcode('[redtable]'. $_banned_reason. '[/redtable]'));
} else {
	$template->end_loop ("notice", "");
}

	if($_userid!=null){



		$logged_in = 1;

		$check_mail = mysql_query("SELECT * FROM inbox WHERE reciever_id='". $_userid ."' AND message_read='0'");

		$new_mail = mysql_num_rows($check_mail);



		$template->set_template ("new_messages", $new_mail);

		$template->set_template ("user_name", $_username);

		$template->set_template ("user_id", $_userid);



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

	$error = $template->get_loop ("error");



	if($error_message!=NULL){

		$mini_template = new MiniTemplate ();



		$mini_template->template_html = $error;



		$mini_template->set_template ("message", $error_message);

		$template->end_loop ("error", $mini_template->return_html ());

		$mini_template->return_html ();



		$current++;

	} else {

		$template->end_loop ("error", "");

	}



	$already_member_notice = $template->get_loop ("already_member");

	$register_terms = $template->get_loop ("terms");

	$register_form = $template->get_loop ("register");

	$registered_notice = $template->get_loop ("registered");



	if($logged_in==0){

		if ($_GET['act'] == "activate") {

			$template->end_loop ("terms", "");

			$template->end_loop ("register", "");

			$template->end_loop ("registered", "");

			$template->end_loop ("already_member", "");



			$activate_table = $template->get_loop ("activate");



			$template->set_template ("activation_notice", $activation_notice);

			$template->set_template ("activation_notice_message", $activation_notice_message);



			$template->end_loop ("activate", $activate_table);

		} else {

			$template->end_loop ("activate", "");



			if($account_created == TRUE){

				if($_SETTING['acitvate_accounts'] == 1){

					$template->set_template ("registered_notice", "Registration is almost done!");

					$template->set_template ("registered_notice_message", "The administrator asks that every member activates their email address.<p>An email has been sent to the email address you supplied.  Please visit the link inside in order to activate it.<p>Once activation has been completed you will be able to enjoy the benefits of being a registered member!");

				} else {

					$template->set_template ("registered_notice", "Registration completed!");

					$template->set_template ("registered_notice_message", "Thank you!<p>You may login and experience the benefits of being a registered member.");

				}



				$template->end_loop ("registered", $registered_notice);

				$template->end_loop ("terms", "");

				$template->end_loop ("register", "");

				$template->end_loop ("already_member", "");



			} else {

				if($_GET['accept']==0){



					$template->set_template ("registration_terms", ekincode($_SETTING['terms'], $user['theme']));



					$template->end_loop ("terms", $register_terms);

					$template->end_loop ("register", "");

					$template->end_loop ("registered", "");

					$template->end_loop ("already_member", "");

				} else {

					$mini_template = new MiniTemplate ();



					$mini_template->template_html = $register_form;



					$mini_template->set_template ("email_error", $email_error);

					$mini_template->set_template ("email_error_message", $email_error_message);

					$mini_template->set_template ("username_error", $username_error);

					$mini_template->set_template ("username_error_message", $username_error_message);

					$mini_template->set_template ("password_error", $password_error);

					$mini_template->set_template ("password_error_message", $password_error_message);



					$mini_template->set_template ("submitted_username", $_GET['username']);

					$mini_template->set_template ("submitted_password", $_GET['password']);

					$mini_template->set_template ("submitted_password_confirm", $_GET['confirm']);

					$mini_template->set_template ("submitted_email", $_GET['email']);

					$mini_template->set_template ("submitted_email_confirm", $_GET['email_confirm']);



					$final_register .= $mini_template->return_html ();



					$template->end_loop ("register", $final_register);



					$template->end_loop ("terms", "");

					$template->end_loop ("registered", "");

					$template->end_loop ("already_member", "");

				}

			}

		}

	} else {

		$template->end_loop ("error", "");

		$template->end_loop ("terms", "");

		$template->end_loop ("register", "");

		$template->end_loop ("registered", "");

		$template->end_loop ("activate", "");

		$template->end_loop ("already_member", $already_member_notice);

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



		$e_result = mysql_query("SELECT * FROM users WHERE id='". $o_id ."'");

		$user_check_tmp = mysql_num_rows($e_result);



		if($o_id){

			$row = mysql_fetch_array($e_result);

			$o_level = $row['level'];

		} else {

			$o_level = 1;

		}



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