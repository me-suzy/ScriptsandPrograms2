<?php

include ("config.php");
include ("updateonline.php");

$page_name = "Forum Index";
updateonline('0','0','0', $_userid, $_username, $page_name);

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

$new_mail = NULL;
$recent_topic_id = NULL;
$recent_datesort = NULL;
$onlinetoday_count = NULL;
$final_html_cats = NULL;

include ("class/template.class.php");
include ("class/db.class.php");
include ("class/mini_template.class.php");

$template = new Template ();
$db = new db ($db_host, $db_user, $db_pass, $db_name);
	
$template->add_file ("header.tpl");
$template->add_file ("index.tpl");

$template->set_template ("template", $user["theme"]);
$template->set_template ("page_title", $_SETTING['organization']);
$template->set_template ("ekinboard_version", $_version);
$template->set_template ("from_url", getenv(HTTP_REFERER));

if ($_banned == TRUE) { // check to see if the user was banned
	$notice_str = $template->get_loop ("notice");

	$template->end_loop ("notice", $notice_str);
	$template->set_template ("notice_message", "Your account has been banned for:<p>". pmcode('[redtable]'. $_banned_reason. '[/redtable]'));
} else {
	$template->end_loop ("notice", "");
}

if(isset($_userid)){

	$logged_in = 1;
	$check_mail = mysql_query("SELECT * FROM inbox WHERE reciever_id='". escape_string($_userid) ."' AND message_read='0'");
	$new_mail = mysql_num_rows($check_mail);

	$template->set_template ("new_messages", $new_mail);

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
	$template->set_template ("user_name", $_username);
	$template->set_template ("user_id", $_userid);
	$template->end_loop ("registered_mini_menu", $mini_menu_registered);
} else {
	$template->end_loop ("registered_mini_menu", "");
}



if ($_userlevel == 3) { // admin

	$template->end_loop ("admin_mini_menu", $mini_menu_admin);

} else {

	$template->end_loop ("admin_mini_menu", "");

}



include ("ad.php");



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
	$n_result = mysql_query("SELECT * FROM topics WHERE fid='". escape_string($nidi) ."'");

	if (mysql_num_rows($n_result) > 0){

		$n_result = mysql_query("select * from forums WHERE news='1' AND hidden='0' ORDER BY id DESC LIMIT 1");

		$n_row = mysql_fetch_array($n_result);

		$n_fid = $n_row['id'];

	

		$n_result = mysql_query("select * from topics WHERE fid='". escape_string($n_fid) ."' ORDER BY id DESC LIMIT 1");

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



$cat_str = $template->get_loop ("category");

$forum_str = $template->get_loop ("forums");



$forum_moderator_str = $template->get_loop ("forum_moderators");

$mod_list_str = $template->get_loop ("mod_list");



$subforum_list_str = $template->get_loop ("forum_subforum_list");

$subforum_list_html = $template->get_loop ("subforum_list");



if (isset ($_GET["cid"])) { // only show selected category



	$sql_cat_select = $_GET["cid"];



	$sql = "SELECT * FROM `categories` WHERE (`id`=$sql_cat_select)";

	$result = @mysql_query ($sql);

	$num = @mysql_num_rows ($result);



	if ($num) { // category id exists

		$c_result = @mysql_query("SELECT * FROM categories WHERE id='". escape_string($_GET[cid]) ."' ORDER BY id ASC"); 

	} else {

		$c_result = @mysql_query("SELECT * FROM categories ORDER BY id ASC"); 

	}



} else {

	$c_result = @mysql_query("SELECT * FROM categories ORDER BY id ASC"); 

}



$c_count = @mysql_num_rows($c_result);

$c_rows = @mysql_num_rows($c_result);



while($c_row = @mysql_fetch_array($c_result)){

	$c_id = $c_row['id']; 

	$c_name = $c_row['name'];

	$c_level = $c_row['level_limit'];



	if(($c_level==1) || ($c_level <= $_userlevel)){

		$f_result = mysql_query("SELECT * FROM forums WHERE cid='". escape_string($c_id) ."' AND subforum='0' AND hidden='0'");



		$final_html_forums = NULL;

		$evenforum_count = NULL;



		while($f_row = mysql_fetch_array($f_result)){

			$f_id = $f_row['id'];

			$f_name = $f_row['name'];

			$f_description = $f_row['description'];



			$t_result = mysql_query("SELECT * FROM topics WHERE fid='". escape_string($f_id) ."'"); 

			$t_count = mysql_num_rows($t_result);





			$read_t_num = 0;



				$read_result_a = mysql_query("SELECT * FROM topics WHERE fid='". escape_string($f_id) ."'");

				while($read_row = mysql_fetch_array($read_result_a)){

					$read_t_id = $read_row['id'];

					$read_result = mysql_query("SELECT * FROM `read` WHERE user_id='". escape_string($_userid) ."' AND topic_id='". escape_string($read_t_id) ."'");

					$read_num = mysql_num_rows($read_result);

					$read_t_num = $read_t_num + $read_num;

				}

				if ($_userid != NULL) {

					if ($read_t_num < $t_count) {

						$read_forum = '1';

					} else {

						$read_forum = '0';	

					}

				} else {

					$read_forum = '1';

				}



			$recent_result = mysql_query("SELECT * FROM topics WHERE fid='". escape_string($f_id) ."' ORDER BY datesort DESC LIMIT 1");

			$topic_count_result = mysql_num_rows($recent_result);



			$reply_count_result = mysql_query("SELECT * FROM replies WHERE tid='". escape_string($recent_topic_id) ."'");

			$reply_count = mysql_num_rows($reply_count_result);



				$topic_row = mysql_fetch_array($recent_result);

				$recent_topic_id = $topic_row['id'];

				$recent_name = $topic_row['title'];

				$recent_topic_over = $recent_name;

				if (strlen($recent_name) > 16) {

					$recent_name = substr($recent_name, 0, 16) . "...";

				}



				$recent_reply_result = mysql_query("SELECT * FROM replies WHERE tid='". escape_string($recent_topic_id) ."' ORDER BY datesort DESC LIMIT 1");

				$recent_reply_count = mysql_num_rows($recent_reply_result);



				if($recent_reply_count != 0){

					$reply_row = mysql_fetch_array($recent_reply_result);

					$recent_reply_id = $reply_row['id'];

					$recent_poster = $reply_row['poster'];

					$recent_date = NULL;

					$recent_date = $reply_row['date'];

					$recent_datesort = $reply_row['datesort'];

		

					$recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". escape_string($recent_poster) ."'");

					$userid_row = mysql_fetch_array($recent_userid_result);

					$recent_poster_id = $userid_row['id'];

				} else {

					$recent_date = NULL;

					$recent_poster = $topic_row['poster'];

					$recent_date = $topic_row['date'];

					$recent_datesort = $topic_row['datesort'];



					$recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". escape_string($recent_poster) ."'");

					$userid_row = mysql_fetch_array($recent_userid_result);

					$recent_poster_id = $userid_row['id'];

				}



				$sub_forum_result = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='". escape_string($f_id) ."' ORDER BY id ASC");

				$subforum_count = mysql_num_rows($sub_forum_result);



				if($subforum_count != 0){



					while($subforum_row = mysql_fetch_array($sub_forum_result)){



						$sub_id = $subforum_row['id'];



						$subforum_names = $subforum_row['name'];

					



						$new_recent_result = mysql_query("SELECT * FROM topics WHERE fid='". escape_string($sub_id) ."' ORDER BY datesort DESC LIMIT 1");

						$new_topic_count_result = mysql_num_rows($new_recent_result);





						if ($new_topic_count_result != 0) {

							$new_topic_row = mysql_fetch_array($new_recent_result);

							$new_recent_topic_id = $new_topic_row['id'];

							$new_recent_name = $new_topic_row['title'];



							if (strlen($new_recent_name) > 16) {

								$new_recent_name = substr($new_recent_name, 0, 16) . "...";

							}



							$new_recent_reply_result = mysql_query("SELECT * FROM replies WHERE tid='". escape_string($new_recent_topic_id) ."' ORDER BY datesort DESC LIMIT 1");

							$new_recent_reply_count = mysql_num_rows($new_recent_reply_result);



							if($new_recent_reply_count != 0){



								$new_reply_row = mysql_fetch_array($new_recent_reply_result);

								$new_recent_reply_id = $new_reply_row['id'];

								$new_recent_poster = $new_reply_row['poster'];

								$new_recent_date = null;

								$new_recent_date = $new_reply_row['date'];

								$new_recent_datesort = $new_reply_row['datesort'];



								if($new_recent_datesort > $recent_datesort){



									$recent_topic_id = $new_recent_topic_id;

									$recent_name = $new_recent_name;



									$recent_reply_id = $new_recent_reply_id;

									$recent_poster = $new_recent_poster;

									$recent_date = $new_recent_date;

									$recent_datesort = $new_recent_datesort;



									$new_recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". escape_string($recent_poster) ."'");

									$new_userid_row = mysql_fetch_array($new_recent_userid_result);

									$recent_poster_id = $new_userid_row['id'];



								}



							} else {

								$new_recent_date = NULL;

								$new_recent_poster = $new_topic_row['poster'];

								$new_recent_date = $new_topic_row['date'];

								$new_recent_datesort = $new_topic_row['datesort'];



								if($new_recent_datesort > $recent_datesort){



									$recent_topic_id = $new_recent_topic_id;

									$recent_name = $new_recent_name;



									$recent_poster = $new_recent_poster;

									$recent_date = $new_recent_date;

									$recent_datesort = $new_recent_datesort;



									$new_recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". escape_string($new_recent_poster) ."'");

									$new_userid_row = mysql_fetch_array($new_recent_userid_result);

									$recent_poster_id = $new_userid_row['id'];



								}



							}



						}



					}



				}



				$recent_date = date("F jS, Y", strtotime($recent_date));



				if(!($evenforum_count % 2) == TRUE){

					$evenforum = 1;

				} else {

					$evenforum = 2;

				}



				// GET REPLY COUTN

				$topic_reply_sql = mysql_query("SELECT * FROM topics WHERE fid='". escape_string($f_id) ."'");



				$reply_count = 0;



				while($trrow = mysql_fetch_assoc($topic_reply_sql)){

					$topic_reply_id = $trrow['id'];



					$reply_count_sql = mysql_query("SELECT * FROM replies WHERE tid='". escape_string($topic_reply_id) ."'");



					$reply_count_num = mysql_num_rows($reply_count_sql);



					$reply_count =  $reply_count + $reply_count_num;



				}



				$final_subforums_html = NULL;

				$subforums_exist = FALSE;



				$sql_get_subforums = @mysql_query ("SELECT `forums`.`id`, `forums`.`name` FROM `forums` WHERE (`forums`.`subforum`='1' && `forums`.`cid`='". escape_string($f_id) ."' && `forums`.`hidden`='0') ORDER BY `forums`.`name` ASC");



				while ($row_subforums_list = @mysql_fetch_assoc ($sql_get_subforums)) {



					$mini_mod_template = new MiniTemplate ();



					$mini_mod_template->template_html = $subforum_list_html;



					$mini_mod_template->set_template ("id", $row_subforums_list['id']);

					$mini_mod_template->set_template ("name", $row_subforums_list['name']);



					$final_subforums_html .= $mini_mod_template->return_html () . ", ";



					$subforums_exist = TRUE;



				}



				if ($subforums_exist) {



					$final_subforums_html = substr ($final_subforums_html, 0, -2);



					$subforum_list_tmp = $template->end_loop ("subforum_list", $final_subforums_html, $subforum_list_str);

					$forum_mod_tmp = $template->end_loop ("forum_subforum_list", $subforum_list_tmp, $forum_str);



				} else {

					$subforum_list_tmp = $template->end_loop ("subforum_list", $final_subforums_html);

					$forum_mod_tmp = $template->end_loop ("forum_subforum_list", "", $forum_str);

				}



				// GET MODERATORS

				$final_html_mod_list = NULL;

				$forum_mod_checker = FALSE;



				$mod_finder_sql = mysql_query("SELECT * FROM users WHERE level='1' ORDER BY id ASC");

				while($modf_row = mysql_fetch_array($mod_finder_sql)){



					$forum_mod_id_list = $modf_row['forum_mod'];

					$forum_mod_list = explode(",", $forum_mod_id_list);



					foreach($forum_mod_list as $fm_id){



						if($fm_id == $f_id){



							$mini_mod_template = new MiniTemplate ();



							$mini_mod_template->template_html = $mod_list_str;



							$mini_mod_template->set_template ("moderator_id", $modf_row['id']);

							$mini_mod_template->set_template ("moderator_name", $modf_row['username']);



							$final_html_mod_list .= $mini_mod_template->return_html () . ", ";



							$forum_mod_checker = TRUE;



						}

					}

				}



				if ($forum_mod_checker) {



					$final_html_mod_list = substr ($final_html_mod_list, 0, -2);



					$mod_list_tmp = $template->end_loop ("mod_list", $final_html_mod_list, $forum_moderator_str);

					$forum_mod_tmp = $template->end_loop ("forum_moderators", $mod_list_tmp, $forum_mod_tmp);



				} else {

				

					$mod_list_tmp = $template->end_loop ("mod_list", $final_html_mod_list);

					$forum_mod_tmp = $template->end_loop ("forum_moderators", "", $forum_mod_tmp);

				}


				// GET VIEWING COUNT

				$viewing_count_sql = mysql_query("SELECT * FROM online WHERE viewforum='". escape_string($f_id) ."'");

				$viewing_count = mysql_num_rows($viewing_count_sql);

				

				$evenforum_count = $evenforum_count + 1;

				

				$mini_template = new MiniTemplate ();



				$mini_template->template_html = $forum_mod_tmp;



				$mini_template->set_template ("id", $f_id);

				$mini_template->set_template ("name", $f_name);

				$mini_template->set_template ("description", $f_description);

				$mini_template->set_template ("topic_count", $t_count);

				$mini_template->set_template ("replies_count", $reply_count);

				$mini_template->set_template ("viewing_count", $viewing_count);

				$mini_template->set_template ("recent_date", $recent_date);

				$mini_template->set_template ("recent_topic", $recent_name);

				$mini_template->set_template ("recent_topic_over", $recent_topic_over);

				$mini_template->set_template ("recent_topic_id", $recent_topic_id);

				$mini_template->set_template ("recent_poster", $recent_poster);

				$mini_template->set_template ("recent_poster_id", $recent_poster_id);

				$mini_template->set_template ("read", $read_forum);

				$mini_template->set_template ("evenforum", $evenforum);

				$final_html_forums .= $mini_template->return_html ();
		}
		$cat_str_tmp = $template->end_loop ("forums", $final_html_forums, $cat_str);

		$mini_template = new MiniTemplate ();
		$mini_template->template_html = $cat_str_tmp;

		$mini_template->set_template ("name", $c_name);

		$mini_template->set_template ("id", $c_id);



		$final_html_cats .= $mini_template->return_html ();



	}



}



$template->end_loop ("category", $final_html_cats);





// FORUM DROPDOWN MENU

$dropdown_list_str = $template->get_loop ("dropdown");



$dropdown_cat_list = mysql_query("SELECT * FROM categories ORDER BY id ASC");



$final_dropdown = NULL;



$dropdown_list = NULL;



while($dd_row = mysql_fetch_array($dropdown_cat_list)){



	$dd_cat_id = $dd_row['id'];

	$dd_cat_name = $dd_row['name'];

	$level_limit = $dd_row['level_limit'];



	if($level_limit >= '2'){



		$dropdown_list = "<option value='index.php?cid=$dd_cat_id'>$dd_cat_name</option>";



		if($_userlevel >= '2'){



			$dropdown_forum_list = mysql_query("SELECT * FROM forums WHERE subforum='0' AND hidden='0' AND cid='". escape_string($dd_cat_id) ."' ORDER BY id ASC");



			while($dd_f_row = mysql_fetch_array($dropdown_forum_list)){



				$dd_name = $dd_f_row['name'];

				$dd_id = $dd_f_row['id'];



				$dropdown_list .= "<option value='viewforum.php?id=$dd_id'> - $dd_name</option>";



				// SUBFORUM CHECK

				$sub_dd_check = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='". escape_string($dd_id) ."' AND hidden='0' ORDER BY id ASC");



				$sub_dd_count = mysql_num_rows($sub_dd_check);



				if($sub_dd_count!=='0'){



					while($sub_dd_row = mysql_fetch_array($sub_dd_check)){



						$sub_dd_id = $sub_dd_row['id'];

						$sub_dd_name = $sub_dd_row['name'];



						$dropdown_list .= "<option value='viewforum.php?id=$sub_dd_id'> &nbsp;- $sub_dd_name</option>";



					}



				}



			}



				$mini_template = new MiniTemplate ();



				$mini_template->template_html = $dropdown_list_str;



				$mini_template->set_template ("dropdown_list", $dropdown_list);



				$final_dropdown .= $mini_template->return_html ();



		}







	} else {



			$dropdown_list = "<option value='index.php?cid=$dd_cat_id'>$dd_cat_name</option>";



			$dropdown_forum_list = mysql_query("SELECT * FROM forums WHERE subforum='0' AND hidden='0' AND cid='". escape_string($dd_cat_id) ."' ORDER BY id ASC");



			while($dd_f_row = mysql_fetch_array($dropdown_forum_list)){



				$dd_name = $dd_f_row['name'];

				$dd_id = $dd_f_row['id'];



				$dropdown_list .= "<option value='viewforum.php?id=$dd_id'> - $dd_name</option>";



				// SUBFORUM CHECK

				$sub_dd_check = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='". escape_string($dd_id) ."' AND hidden='0' ORDER BY id ASC");



				$sub_dd_count = mysql_num_rows($sub_dd_check);



				if($sub_dd_count!=='0'){



					while($sub_dd_row = mysql_fetch_array($sub_dd_check)){



						$sub_dd_id = $sub_dd_row['id'];

						$sub_dd_name = $sub_dd_row['name'];



						$dropdown_list .= "<option value='viewforum.php?id=$sub_dd_id'> &nbsp;- $sub_dd_name</option>";



					}



				}



			}



				$mini_template = new MiniTemplate ();



				$mini_template->template_html = $dropdown_list_str;



				$mini_template->set_template ("dropdown_list", $dropdown_list);



				$final_dropdown .= $mini_template->return_html ();



			}



	}





$template->end_loop ("dropdown", $final_dropdown);





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



	$e_result = mysql_query("SELECT * FROM users WHERE id='". escape_string($o_id) ."'");

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



	$e_result = mysql_query("SELECT * FROM users WHERE id='". escape_string($o_id) ."'");

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



$template->set_template ("server_load", "[ Server Load: $avgs[1] ]");

$template->set_template ("execution_time", "[ Script Execution time: $totaltime ]");



echo $template->end_page ();

?>

