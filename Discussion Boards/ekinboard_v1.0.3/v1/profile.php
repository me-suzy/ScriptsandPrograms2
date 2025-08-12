<?php



include ("config.php");

include ("updateonline.php");



$page_name = "Viewing Profile";

updateonline('0','0','0', $_userid, $_username, $page_name);



	$mtime = microtime();

	$mtime = explode(" ",$mtime);

	$mtime = $mtime[1] + $mtime[0];

	$starttime = $mtime;

if(($_GET[d] == "rate") && (isset($_GET[value])) && ($_userid != NULL) && ($_userid != $_GET[id])){
	$check_voted_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $_GET[id] ."' AND id_from='". $_userid ."'");
	$check_voted = mysql_num_rows($check_voted_result);

	if($check_voted == 0){
		if($_GET[value] == '0'){
			$from_url = getenv('HTTP_REFERER');

			$vote_result = mysql_query("INSERT INTO `votes` (`type`,`value`,`id_from`,`id_to`) VALUES ('member', 'bad', '$_userid', '$_GET[id]')");

			header("Location: $from_url");
		} else if($_GET[value] == '1'){
			$from_url = getenv('HTTP_REFERER');

			$vote_result = mysql_query("INSERT INTO `votes` (`type`,`value`,`id_from`,`id_to`) VALUES ('member', 'good', '$_userid', '$_GET[id]')");

			header("Location: $from_url");
		}
	}
}

	include ("class/template.class.php");

	include ("class/mini_template.class.php");



	$template = new Template ();



	$template->add_file ("header.tpl");

	$template->add_file ("profile.tpl");



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

	if($_userid != null){



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



$profile_result = mysql_query("select * from users WHERE id='$_GET[id]'");

$user_check = @mysql_num_rows($profile_result);



if($user_check==0){

	$template->end_loop ("main_profile", "");

	$template->end_loop ("sig_profile", "");

	$template->end_loop ("avatar_profile", "");

	$error = 1;



	$error_type = "Error";

	$error_message = "User does not exist!";

} else {



	$main_profile_str = $template->get_loop ("main_profile");

	$profile_sig_str = $template->get_loop ("sig_profile");

	$profile_avatar_str = $template->get_loop ("avatar_profile");



	$row = mysql_fetch_array($profile_result);



	$q1 = mysql_query("SELECT * FROM replies WHERE poster='". $row[username] ."'");

	$q2 = mysql_query("SELECT * FROM topics WHERE poster='". $row[username] ."'");

    $profile_total_posts = mysql_num_rows($q1) + mysql_num_rows($q2);



	$topiccount_result = mysql_query("SELECT * FROM topics");

	$topiccount = mysql_num_rows($topiccount_result);

	$repliescount_result = mysql_query("SELECT * FROM replies");

	$repliescount = mysql_num_rows($repliescount_result);

	$total_post_count = $topiccount + $repliescount;


	$profile_post_percentage = $profile_total_posts / $total_post_count * 100;

	$profile_post_percentage = round($profile_post_percentage, 2);


	$online_status = '';

	$online_q = mysql_query("SELECT * FROM online WHERE id='". $_GET[id] ."' AND isonline='1'");

	$num_online = mysql_num_rows($online_q);



	if ($num_online > 0){

		$profile_status = 'Online';

		$profile_online = 1;

	} else {

		$profile_status = 'Offline';

		$profile_online = 0;

	}

	$total_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $_GET[id] ."'");
	$total_votescount = mysql_num_rows($total_votescount_result);

	$bad_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $_GET[id] ."' AND value='bad'");
	$bad_votescount = mysql_num_rows($bad_votescount_result);

	$good_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $_GET[id] ."' AND value='good'");
	$good_votescount = mysql_num_rows($good_votescount_result);

	$check_voted_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $_GET[id] ."' AND id_from='". $_userid ."'");
	$check_voted = mysql_num_rows($check_voted_result);

	if($total_votescount != 0){
		$good_votes = $good_votescount / $total_votescount * 3;
		$good_votes = round($good_votes, 0);
		$bad_votes = $bad_votescount / $total_votescount * -3;
		$bad_votes = round($bad_votes, 0);
		
		$rounded_votenum = $bad_votes + $good_votes;
		
		if((isset($_userid)) && ($_userid != $_GET[id])){
			if($check_voted != 0){
				$vote_img = "<img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )''>";
			} else {
				$vote_img = "<a href=profile.php?id=$_GET[id]&d=rate&value=0><img src=templates/". $user[theme] ."/images/member_rating_delete.gif border=0 alt='Not Helpful'></a><img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )'><a href=profile.php?id=$_GET[id]&d=rate&value=1><img src=templates/". $user[theme] ."/images/member_rating_add.gif border=0 alt='Helpful'></a>";
			}
		} else {
			$vote_img = "<img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )'>";
		}
	} else {
		if((isset($_userid)) && ($_userid != $_GET[id])){
			$vote_img = "<a href=profile.php?id=$_GET[id]&d=rate&value=0><img src=templates/". $user[theme] ."/images/member_rating_delete.gif border=0 alt='Not Helpful'></a><img src='templates/". $user[theme] ."/images/vote_null.gif' alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )' border=0><a href=profile.php?id=$_GET[id]&d=rate&value=1><img src=templates/". $user[theme] ."/images/member_rating_add.gif border=0 alt='Helpful'></a>";
		} else {
			$vote_img = "<img src='templates/". $user[theme] ."/images/vote_null.gif' alt='' border=0>";
		}
	}

		$p_birthday = $row['birthday'];

	if(!$p_birthday){

		$p_birthday = "No Information";

	}



		$p_location = $row['location'];

	if(!$p_location){

		$p_location = "No Information";

	}



		$p_website_url = $row['website_url'];

	if($p_website_url==null){

		$p_website_url = "No Information";

	} else {

		$p_website_url = "<a href='$p_website_url' target=_blank>$p_website_url</a>";

	}

		$p_aim = $row['aim'];

	if(!$p_aim){

		$p_aim = "No Information";

	}

		$p_msn = $row['msn'];

	if(!$p_msn){

		$p_msn = "No Information";

	}

		$p_yahoo = $row['yahoo'];

	if(!$p_yahoo){

		$p_yahoo = "No Information";

	}

		$p_icq = $row['icq'];

	if(!$p_icq){

		$p_icq = "No Information";

	}



	$mini_template = new MiniTemplate ();



	$mini_template->template_html = $main_profile_str;

	$mini_template->set_template ("profile_username", $row[username]);



	$mini_template->set_template ("profile_total_posts", number_format($profile_total_posts));

	$mini_template->set_template ("profile_post_percentage", $profile_post_percentage);

	$mini_template->set_template ("profile_last_login", date('l, F jS, Y', strtotime($row[lastlogin], '\n')));

	$mini_template->set_template ("profile_birthday", $p_birthday);

	$mini_template->set_template ("profile_location", $p_location);

	$mini_template->set_template ("profile_online_status", $profile_status);


	$mini_template->set_template ("vote_img", $vote_img);

	$mini_template->set_template ("profile_website", $p_website_url);

	$mini_template->set_template ("profile_aim", $p_aim);

	$mini_template->set_template ("profile_msn", $p_msn);

	$mini_template->set_template ("profile_yim", $p_yahoo);

	$mini_template->set_template ("profile_icq", $p_icq);



	$template->end_loop ("main_profile", $mini_template->return_html ());

	$mini_template->return_html ();



	if($row['sig'] != NULL){



		$profile_signature = ekincode($row[sig],$user[theme]);



		$mini_template = new MiniTemplate ();



		$mini_template->template_html = $profile_sig_str;



		$mini_template->set_template ("profile_username", $row[username]);

		$mini_template->set_template ("profile_signature", $profile_signature);



		$template->end_loop ("sig_profile", $mini_template->return_html ());

		$mini_template->return_html ();

	} else {

		$template->end_loop ("sig_profile", "");

	}

	$poster_avatar = str_replace(" ", "%20", $row[avatar]);



	if($poster_avatar!=null){

		$ava_height = NULL;

		$ava_width = NULL;

		$size = getimagesize($poster_avatar);

		$height = $size[1];

		$width = $size[0];

			if ($height > 100){

				$ava_height = 100;

				$percent = ($size[1] / $ava_height);

				$ava_width = ($size[0] / $percent);

			} else if ($width > 100){

				$ava_width = 100;

				$percent = ($size[0] / $ava_width);

				$ava_height = ($size[1] / $percent);

			} else {

				$ava_height = $height;

				$ava_width = $width;

			}

		$profile_avatar = "<img src='$row[avatar]' border='0' height='$ava_height' width='$ava_width' alt='$row[avatar_alt]' title='$row[avatar_alt]'>";



		$mini_template = new MiniTemplate ();



		$mini_template->template_html = $profile_avatar_str;



		$mini_template->set_template ("profile_username", $row[username]);

		$mini_template->set_template ("profile_avatar", $profile_avatar);



		$template->end_loop ("avatar_profile", $mini_template->return_html ());

		$mini_template->return_html ();

	} else {

		$template->end_loop ("avatar_profile", "");

	}

}



	$error_str = $template->get_loop ("error");



	if($error == 1){

		$mini_template = new MiniTemplate ();



		$mini_template->template_html = $error_str;



		$mini_template->set_template ("error_message", $error_message);



		$template->end_loop ("error", $mini_template->return_html ());

		$mini_template->return_html ();

	} else {

		$template->end_loop ("error", "");

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