<?php

$user["theme"] = "test";

include ("config.php");
include ("updateonline.php");

$page_name = "Edit Post";

// Need to make sure the user has access to view this page

	if($_GET[act] == "er"){
		$reply_check = mysql_query("SELECT * FROM replies WHERE id='". $_GET[id] ."'");

		$row = mysql_fetch_array($reply_check);

		$top_t_id = $row[tid];

		$post_poster = $row[poster];

		$check_count = mysql_num_rows($reply_check);


		$top_t_result = mysql_query("select * from topics where id='". $top_t_id ."'");

		$row = mysql_fetch_array($top_t_result);

		$top_f_id = $row['fid'];

		$top_t_title = $row['title'];

	} else if($_GET[act] == "et"){
		$topic_check = mysql_query("select * from topics WHERE id='". $_GET[id] ."'");

		$row = mysql_fetch_array($topic_check);

		$top_f_id = $row['fid'];

		$top_t_title = $row['title'];

		$post_poster = $row[poster];

		$check_count = mysql_num_rows($topic_check);
	}

	$top_f_result = mysql_query("select * from forums where id='". $top_f_id ."'");

	$row = mysql_fetch_array($top_f_result);

	$top_c_id = $row['cid'];

	$top_f_sub = $row['subforum'];


	if($top_f_sub == 1){

		$top_f_result = mysql_query("select * from forums where id='". $top_c_id ."'");

		$row = mysql_fetch_array($top_f_result);

		$top_c_id = $row['cid'];
	}

	$top_f_name = $row['name'];


	$top_c_result = mysql_query("select * from categories where id='". $top_c_id ."'");

	$row = mysql_fetch_array($top_c_result);

	$top_c_name = $row['name'];

	$cat_access_level = $row['level_limit'];


if((isset($_userid)) && ($_userlevel == 1)){
	$is_moderator = FALSE;

	$forum_mod_sql = mysql_query("SELECT * FROM users WHERE id='$_userid'");
	$mods_row = mysql_fetch_array($forum_mod_sql);


	$mod_forums = $mods_row['forum_mod'];

	$mod_forums = explode(",", $mod_forums);

	foreach($mod_forums as $fm_id){

		if($fm_id == $top_f_id){

			$is_moderator = TRUE;

		}

	}

}

//////////////////////////

updateonline('0','0','1', $_userid, $_username, $page_name);

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
	$template->add_file ("edit.tpl");

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
$error_str = $template->get_loop ("error");
$edittopic_str = $template->get_loop ("edittopic");
$editreply_str = $template->get_loop ("editreply");

if($check_count != 0){
	if(($post_poster == $_username) || ($_userlevel >= 2) || ($is_moderator == TRUE)){
		if(($_GET[act] == "er") && ($_GET[id] != NULL)){
			$q = "SELECT * FROM replies WHERE id='". $_GET[id] ."'";
			$res = mysql_query($q);

			$check = mysql_num_rows($res);
			if(($_GET[d]=='post') && ($check != 0)){
			    if ($_POST['message'] != NULL){
					$q = "UPDATE replies SET message='". $_POST[message] ."' WHERE id='". $_GET[id] ."'";
					$res = mysql_query($q);

					$r_q = "SELECT * FROM replies WHERE id='". $_GET[id] ."'";
					$r_res = mysql_query($r_q);
					$r_fetch = mysql_fetch_assoc($r_res);
					$r_id = $r_fetch[tid];

			        $how_many = mysql_query("SELECT * FROM replies WHERE tid='". $r_id ."'");
	                $count = mysql_num_rows($how_many);
	                $limit = 10;
	                $has_to_be = ($count/$limit);
	                $i=0;
	                while ($i<$has_to_be){
	                	$i++;
	                }

					header("Location: viewtopic.php?id=$r_id&page=$i&#$_GET[id]");
			    } else if($check == 0){
					$template->end_loop ("editreply", "");
					$template->end_loop ("edittopic", "");
					$template->end_loop ("error", $error_str);

					$template->set_template ("error_message", "There has been an error when trying to complete your request!");
				} else {
					$message_error = 1;
					$message_error_message = "The message has been left blank!";

					$template->end_loop ("editreply", $editreply_str);
					$template->end_loop ("edittopic", "");
					$template->end_loop ("error", "");

					$template->set_template ("id", $_GET[id]);
					$template->set_template ("message_error", $message_error);
					$template->set_template ("message_error_message", $message_error_message);
					$template->set_template ("reply_message", $_POST[message]);

			    }
		    } else {
				$q = "SELECT * FROM replies WHERE id='". $_GET[id] ."'";
				$res = mysql_query($q);
				$fetch = mysql_fetch_assoc($res);

				$_message = $fetch[message];

				$template->end_loop ("editreply", $editreply_str);
				$template->end_loop ("edittopic", "");
				$template->end_loop ("error", "");

				$template->set_template ("id", $_GET[id]);
				$template->set_template ("message_error", $message_error);
				$template->set_template ("message_error_message", $message_error_message);
				$template->set_template ("reply_message", $_message);
			}

		} else if(($_GET[act] == "et") && ($_GET[id] != NULL)){
			if($_GET[d]=='post'){
				$q = "UPDATE topics SET title='". $_POST[title] ."', description='". $_POST[description] ."', message='". $_POST[message] ."' WHERE id='". $_GET[id] ."'";
				$res = mysql_query($q);

				header("Location: viewtopic.php?id=$_GET[id]");
			} else {
				$q = "SELECT * FROM topics WHERE id='$_GET[id]'";
				$res = mysql_query($q);
				$fetch = mysql_fetch_assoc($res);

				$_topic = $fetch[title];
				$_description = $fetch[description];
				$_message = $fetch[message];

				$template->end_loop ("editreply", "");
				$template->end_loop ("edittopic", $edittopic_str);
				$template->end_loop ("error", "");

				$template->set_template ("id", $_GET[id]);
				$template->set_template ("title_error", $title_error);
				$template->set_template ("title_error_message", $title_error_message);
				$template->set_template ("message_error", $title_error);
				$template->set_template ("message_error_message", $title_error_message);
				$template->set_template ("topic_title", $_topic);
				$template->set_template ("topic_description", $_description);
				$template->set_template ("topic_message", $_message);
			}
		} else {
				$template->end_loop ("editreply", "");
				$template->end_loop ("edittopic", "");
				$template->end_loop ("error", $error_str);

				$template->set_template ("error_message", "There has been an error when trying to complete your request!");
		}
	} else {
		$template->end_loop ("editreply", "");
		$template->end_loop ("edittopic", "");
		$template->end_loop ("error", $error_str);
		$template->set_template ("error_message", "You do not have proper access to view this page.");
	}
} else {
	$template->end_loop ("editreply", "");
	$template->end_loop ("edittopic", "");
	$template->end_loop ("error", $error_str);
	$template->set_template ("error_message", "This post does not exist!");
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