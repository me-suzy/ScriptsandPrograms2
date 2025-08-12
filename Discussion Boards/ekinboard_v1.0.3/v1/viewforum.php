<?PHP
include "config.php";

include "updateonline.php";

$page_name = "Viewing Forum";

updateonline('0',$_GET['id'],'0', $_userid, $_username, $page_name);



	$mtime = microtime();

	$mtime = explode(" ",$mtime);

	$mtime = $mtime[1] + $mtime[0];

	$starttime = $mtime;



		$top_cat_result = mysql_query("select * from categories where id='$top_cid_id'");

		$crow = mysql_fetch_array($top_cat_result);

		$top_cat_name = $crow['name'];

		$cat_access_level = $crow['level_limit'];



$new_mail = NULL;

$run_rows1 = NULL;

$onlinetoday_count = NULL;



if(($_GET['act'] == "markread") && ($_userid != NULL)){

	$forum_read_sql = mysql_query("SELECT * FROM topics WHERE fid='".$_GET['id']."'");



	while($row = mysql_fetch_array($forum_read_sql)){

				$read_result = mysql_query("SELECT * FROM `read` WHERE user_id='$_userid' AND topic_id='$row[id]'");

				$read_num = mysql_num_rows($read_result);



		if($read_num == 0){

				$result = mysql_query("INSERT INTO `read` VALUES ('$row[id]', '$_userid')");



		}

	}

} else if(($_GET['act'] == "markunread") && ($_userid != NULL)){

	$forum_unread_sql = mysql_query("SELECT * FROM topics WHERE fid='". $_GET['id'] ."'");



	while($row = mysql_fetch_array($forum_unread_sql)){

				$delete_result = mysql_query("DELETE FROM `read` WHERE user_id='$_userid' AND topic_id='$row[id]'");

	}

}



if((isset($_userid)) && ($_userlevel == 1)){

	$forum_mod_sql = mysql_query("SELECT * FROM users WHERE id='$_userid'");



	$mods_row = mysql_fetch_array($forum_mod_sql);



	$mod_forums = $mods_row['forum_mod'];

	$mod_forums = explode(",", $mod_forums);

	foreach($mod_forums as $fm_id){

		if($fm_id == $_GET['id']){

			$is_moderator = TRUE;

		}

	}

}



include ("class/template.class.php");

include ("class/mini_template.class.php");



$template = new Template ();



$template->add_file ("header.tpl");

$template->add_file ("viewforum.tpl");



$template->set_template ("template", $user['theme']);

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

$template->set_template ("forum_id", $_GET['id']);



if($_userid != null){



	$logged_in = 1;

	$check_mail = mysql_query("SELECT * FROM inbox WHERE reciever_id='$_userid' AND message_read='0'");

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



	$n_result = mysql_query("SELECT * FROM topics WHERE fid='$nidi'");



	if (mysql_num_rows($n_result) > 0){



		$n_result = mysql_query("select * from forums WHERE news='1' AND hidden='0' ORDER BY id DESC LIMIT 1");



		$n_row = mysql_fetch_array($n_result);



		$n_fid = $n_row['id'];







		$n_result = mysql_query("select * from topics WHERE fid='$n_fid' ORDER BY id DESC LIMIT 1");



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

	$top_f_result = mysql_query("select * from forums where id='". $_GET[id] ."'");

	$row = mysql_fetch_array($top_f_result);

	$top_f_subforum = $row['subforum'];

	$top_c_id = $row['cid'];

	$top_f_name = $row['name'];

	$top_f_hidden = $row['hidden'];

	$top_f_protected = $row['protected'];

	$top_f_news = $row['news'];

	$top_f_rlevel = $row['restricted_level'];
	$template->set_template ("forum_name", $top_f_name);



	if($top_f_subforum !== '0'){



		$top_c_result = mysql_query("select * from forums where id='$top_c_id'");

		$row = mysql_fetch_array($top_c_result);

		$top_c_name = $row['name'];

		$top_cid_id = $row['cid'];



		$template->set_template ("subforum_cat_link", "<a href='viewforum.php?id=$top_c_id'>$top_c_name</a> - ");



		$template->set_template ("category_name", $top_cat_name);

		$template->set_template ("category_id", $top_cid_id);



	} else {



		$top_c_result = mysql_query("select * from categories where id='$top_c_id'");

		$row = mysql_fetch_array($top_c_result);

		$top_c_name = $row['name'];

		$cat_access_level = $row['level_limit'];



		$template->set_template ("category_name", $top_c_name);

		$template->set_template ("category_id", $top_c_id);

		$template->set_template ("subforum_cat_link", "");



	}



	$error_str = $template->get_loop ("error");



if($_userlevel >= $cat_access_level){

	$search_topic_str = $template->get_loop ("search_topics");



	$sub_forum_str = $template->get_loop ("sub_forums");

	$forum_str = $template->get_loop ("forums");



	$final_html_forums = NULL;

	$evenforum_count = NULL;



	$forum_check_result = @mysql_query("SELECT * FROM forums WHERE id='". $_GET[id] ."' AND hidden='0'");

	$forum_check = @mysql_num_rows($forum_check_result);



if($forum_check != 0){

	$f_result = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='". $_GET[id] ."' AND hidden='0'");

	$f_num = mysql_num_rows ($f_result);



	if ($f_num) {

		while($f_row = mysql_fetch_array($f_result)){


			$f_id = $f_row['id'];

			$f_name = $f_row['name'];

			$f_description = $f_row['description'];



			$t_result = mysql_query("SELECT * FROM topics WHERE fid='". $f_id ."'");

			$t_count = mysql_num_rows($t_result);





			$read_t_num = 0;



				$read_result_a = mysql_query("SELECT * FROM topics WHERE fid='". $f_id ."'");

				while($read_row = mysql_fetch_array($read_result_a)){

					$read_t_id = $read_row['id'];

					$read_result = mysql_query("SELECT * FROM `read` WHERE user_id='". $_userid ."' AND topic_id='". $read_t_id ."'");

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



			$recent_result = mysql_query("SELECT * FROM topics WHERE fid='". $f_id ."' ORDER BY datesort DESC LIMIT 1");

			$topic_count_result = mysql_num_rows($recent_result);



			$reply_count_result = mysql_query("SELECT * FROM replies WHERE tid='". $recent_topic_id ."'");

			$reply_count = mysql_num_rows($reply_count_result);



				$topic_row = mysql_fetch_array($recent_result);

				$recent_topic_id = $topic_row['id'];

				$recent_name = $topic_row['title'];

				$recent_topic_over = $recent_name;

				if (strlen($recent_name) > 16) {

					$recent_name = substr($recent_name, 0, 16) . "...";

				}



				$recent_reply_result = mysql_query("SELECT * FROM replies WHERE tid='". $recent_topic_id ."' ORDER BY datesort DESC LIMIT 1");

				$recent_reply_count = mysql_num_rows($recent_reply_result);



				if($recent_reply_count != 0){

					$reply_row = mysql_fetch_array($recent_reply_result);

					$recent_reply_id = $reply_row['id'];

					$recent_poster = $reply_row['poster'];

					$recent_date = NULL;

					$recent_date = $reply_row['date'];

					$recent_datesort = $reply_row['datesort'];



					$recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". $recent_poster ."'");

					$userid_row = mysql_fetch_array($recent_userid_result);

					$recent_poster_id = $userid_row['id'];

				} else {

					$recent_date = NULL;

					$recent_poster = $topic_row['poster'];

					$recent_date = $topic_row['date'];

					$recent_datesort = $topic_row['datesort'];



					$recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". $recent_poster ."'");

					$userid_row = mysql_fetch_array($recent_userid_result);

					$recent_poster_id = $userid_row['id'];

				}



				$sub_forum_result = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='". $f_id ."' ORDER BY id ASC");

				$subforum_count = mysql_num_rows($sub_forum_result);



				if($subforum_count != 0){



					while($subforum_row = mysql_fetch_array($sub_forum_result)){



						$sub_id = $subforum_row['id'];



						$subforum_names = $subforum_row['name'];





						$new_recent_result = mysql_query("SELECT * FROM topics WHERE fid='". $sub_id ."' ORDER BY datesort DESC LIMIT 1");

						$new_topic_count_result = mysql_num_rows($new_recent_result);





						if ($new_topic_count_result != 0) {

							$new_topic_row = mysql_fetch_array($new_recent_result);

							$new_recent_topic_id = $new_topic_row['id'];

							$new_recent_name = $new_topic_row['title'];



							if (strlen($new_recent_name) > 16) {

								$new_recent_name = substr($new_recent_name, 0, 16) . "...";

							}



							$new_recent_reply_result = mysql_query("SELECT * FROM replies WHERE tid='". $new_recent_topic_id ."' ORDER BY datesort DESC LIMIT 1");

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



									$new_recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". $recent_poster ."'");

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



									$new_recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". $new_recent_poster ."'");

									$new_userid_row = mysql_fetch_array($new_recent_userid_result);

									$recent_poster_id = $new_userid_row['id'];



								}



							}



						}



					}



				}



				if($recent_date != NULL){
					$recent_date = date("F jS, Y", strtotime($recent_date));
				}



				if(!($evenforum_count % 2) == TRUE){

					$evenforum = 1;

				} else {

					$evenforum = 2;

				}



				// GET REPLY COUTN

				$topic_reply_sql = mysql_query("SELECT * FROM topics WHERE fid='". $f_id ."'");



				$reply_count = 0;



				while($trrow = mysql_fetch_assoc($topic_reply_sql)){

					$topic_reply_id = $trrow['id'];



					$reply_count_sql = mysql_query("SELECT * FROM replies WHERE tid='". $topic_reply_id ."'");



					$reply_count_num = mysql_num_rows($reply_count_sql);



					$reply_count =  $reply_count + $reply_count_num;



				}



				$final_subforums_html = NULL;

				$subforums_exist = FALSE;



				$sql_get_subforums = @mysql_query ("SELECT `forums`.`id`, `forums`.`name` FROM `forums` WHERE (`forums`.`subforum`='1' && `forums`.`cid`='". $f_id ."' && `forums`.`hidden`='0') ORDER BY `forums`.`name` ASC");



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

				$viewing_count_sql = mysql_query("SELECT * FROM online WHERE viewforum='". $f_id ."'");

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



		$forums_end_html = $template->end_loop ("forums", $final_html_forums, $sub_forum_str);



		$mini_template = new MiniTemplate ();



		$mini_template->template_html = $forums_end_html;



		$mini_template->set_template ("name", $c_name);



		$forums_end_html = $mini_template->return_html ();



		$template->end_loop ("sub_forums", $forums_end_html);



	} else {

		$template->end_loop ("sub_forums", "");

	}



	if($_userid != NULL){

		if($top_f_rlevel <= $_userlevel){

			$forum_access = TRUE;

		}

	}



	$page_result = mysql_query("select * from topics where fid='". $_GET[id] ."' and sticky='1' ORDER BY datesort DESC");

	$numrows = mysql_num_rows($page_result);



	$pinned_topic_str = $template->get_loop ("pinned");

	$pinned_topics_str = $template->get_loop ("pinned_topics");



	$final_pinned_html = NULL;

	$evenforum_count = 0; // should not be null, needs to be 0 because it is a number



	$query_rows1 = "SELECT * FROM topics where fid='". $_GET[id] ."' and sticky='1'";

	$query_rows2 = "select * from topics where fid='". $_GET[id] ."' and sticky='0'";

	$result_rows1 = @mysql_query ($query_rows1);

	$result_rows2 = @mysql_query ($query_rows2);

	$num_rows1 = @mysql_num_rows ($result_rows1);

	$run_rows2 = @mysql_num_rows ($result_rows2);

	$num_rows = $run_rows1 + $run_rows2;



	$display_num = 20;

	$limitnum = 2;



	if ($num_rows > $display_num) {

		$num_pages = ceil ($num_rows / $display_num);

	} else {

		$num_pages = 1;

	}



	if (isset($_GET['page'])) {

		$page_page_num = $_GET['page'];

		$db_page_num = ($_GET['page'] - 1) * $display_num;

	} else {

		$db_page_num = 0;

		$page_page_num = 1;

	}



	$page_num_str = NULL;



	if ($num_rows1 > $display_num) {

		$pinned_limit = "$db_page_num, $display_num";

		$regular_limit = "0, 0";

	} elseif (!$num_rows1) {

		$pinned_limit = "0, 0";

		$regular_limit = "$db_page_num, $display_num";

	} elseif (($num_rows1 - $db_page_num) <= 0) {

		$pinned_limit = "0, 0";

		$regular_limit = "$db_page_num, $display_num";

	} else {

		$pinned_limit = "0, $num_rows1";

		$regular_limit = "$db_page_num, " . ($display_num - $num_rows1);

	}



	$page_num_str = NULL;



	if ($num_pages > 1) {

		$pages_str = $template->get_loop ("pages");

		$mini_template = new MiniTemplate ();

		$mini_template->template_html = $pages_str;



		$mini_template->set_template ("total_pages", $num_pages);



		$final_pages_str .= $mini_template->return_html ();

		$template->end_loop ("pages", $final_pages_str);



		$current_page = ($db_page_num / $display_num) + 1;



		$page_number_str = $template->get_loop ("page_number");



		for ($i = 1; $i <= $num_pages; $i++) {

			if ($i==$current_page) {

				$mini_template = new MiniTemplate ();

				$mini_template->template_html = $page_number_str;

				$mini_template->set_template ("id", $_GET[id]);



				$mini_template->set_template ("page_num", $i);

				$mini_template->set_template ("current_page", 2);



				$final_page_number_str .= $mini_template->return_html ();



			} else if(($i < $current_page - $limitnum) || ($i > $current_page + $limitnum)){

				if(($i < $current_page - $limitnum) && ($a == NULL)){

					$first_page_str = $template->get_loop ("first_page");



					$mini_template = new MiniTemplate ();

					$mini_template->template_html = $first_page_str;



					$mini_template->set_template ("id", $_GET[id]);

					$mini_template->set_template ("page_num", 1);



					$final_first_page_str .= $mini_template->return_html ();

					$template->end_loop ("first_page", $final_first_page_str);



					$a = 1;

				} else if(($i > $current_page + $limitnum) && ($b == NULL)){

					$last_page_str = $template->get_loop ("last_page");



					$mini_template = new MiniTemplate ();

					$mini_template->template_html = $last_page_str;



					$mini_template->set_template ("id", $_GET[id]);

					$mini_template->set_template ("page_num", $num_pages);



					$final_last_page_str .= $mini_template->return_html ();

					$template->end_loop ("last_page", $final_last_page_str);

					$b = 1;

				}

			} else {

				$mini_template = new MiniTemplate ();

				$mini_template->template_html = $page_number_str;



				$mini_template->set_template ("id", $_GET[id]);

				$mini_template->set_template ("page_num", $i);

				$mini_template->set_template ("current_page", 1);



				$final_page_number_str .= $mini_template->return_html ();

			}

		}

		$template->end_loop ("page_number", $final_page_number_str);

		if($a==NULL){

			$template->end_loop ("first_page", "");

		}

		if($b==NULL){

			$template->end_loop ("last_page", "");

		}

	} else {

		$template->end_loop ("pages", "");

	}





$template->set_template ("page_num_link", (($page_num_str != NULL) ? $page_num_str : ""));



	if($numrows!=0){

		$f_result = mysql_query("select * from topics where fid='". $_GET[id] ."' and sticky='1' ORDER BY datesort DESC LIMIT $pinned_limit");

		$f_count = mysql_num_rows($f_result);

		while($row=mysql_fetch_array($f_result)){



			$t_id = $row['id'];

			$t_poll = $row['poll'];

			$t_title = $row['title'];

			$t_description = $row['description'];

			$t_poster = $row['poster'];

			$t_date = $row['date'];

			$t_views = number_format($row['views']);

			$t_locked = $row['locked'];

			$t_result = mysql_query("select * from replies where tid='$t_id'");

			$r_count = number_format(mysql_num_rows($t_result));



			$getuser_result = mysql_query("select * from users where username='". $t_poster ."'");

			$row = mysql_fetch_array($getuser_result);

			$poster_id = $row['id'];



			if($r_count==0){

				$recent_date = '';

				$recent_poster = '---';

			} else {

				$recent_reply_result = mysql_query("SELECT * FROM replies WHERE tid='$t_id' ORDER BY datesort DESC LIMIT 1");

				$recent_reply_count = mysql_num_rows($recent_reply_result);



				$reply_row = mysql_fetch_array($recent_reply_result);

				$recent_reply_id = $reply_row['id'];

				$recent_poster = $reply_row['poster'];

				$recent_date = null;

				$recent_date = $reply_row['date'];

				$recent_date = date("F jS, Y", strtotime($recent_date));



				$recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". $recent_poster ."'");

				$userid_row = mysql_fetch_array($recent_userid_result);

				$recent_poster_id = $userid_row['id'];

			}



			$read_num = 0;



			$read_result=@mysql_query("SELECT * FROM `read` WHERE user_id='$_userid' AND topic_id='$t_id'");

			$read_num=mysql_num_rows($read_result);



			$viewing_result = mysql_query("SELECT * FROM online WHERE viewtopic='$t_id' AND isonline='1'");

			$viewing_count = mysql_num_rows($viewing_result);



			if ($_userid) {

				if ($read_num == 0) {

					$read_forum = '1';

				} else {

					$read_forum = '0';

				}

			} else {

				$read_forum = '1';

			}



			if(!($evenforum_count % 2) == TRUE){

				$evenforum = 1;

			} else {

				$evenforum = 2;

			}



			$evenforum_count = $evenforum_count + 1;



			$mini_template = new MiniTemplate ();



			$mini_template->template_html = $pinned_topics_str;



			$mini_template->set_template ("id", $t_id);

			$mini_template->set_template ("poll", $t_poll);

			$mini_template->set_template ("title", $t_title);

			$mini_template->set_template ("description", $t_description);

			$mini_template->set_template ("poster", $t_poster);

			$mini_template->set_template ("poster_id", $poster_id);

			$mini_template->set_template ("views", $t_views);

			$mini_template->set_template ("viewing_count", $viewing_count);

			$mini_template->set_template ("replies", $r_count);

			$mini_template->set_template ("locked", $t_locked);

			$mini_template->set_template ("recent_poster_id", $recent_poster_id);

			$mini_template->set_template ("recent_poster", $recent_poster);

			$mini_template->set_template ("recent_date", $recent_date);

			$mini_template->set_template ("read", $read_forum);

			$mini_template->set_template ("evenforum", $evenforum);



			$final_pinned_html .= $mini_template->return_html ();



		}

	}



	if ($f_count) {

		$template->end_loop ("pinned", $template->end_loop ("pinned_topics", $final_pinned_html, $pinned_topic_str));

	} else {

		$template->end_loop ("pinned", "");

	}



	$topic_str = $template->get_loop ("topics");



	$final_topic_html = NULL;



	$f_result = mysql_query("select * from topics where fid='". $_GET[id] ."' and sticky='0' ORDER BY datesort DESC LIMIT $regular_limit");

	$numrows = mysql_num_rows($f_result);



	if($numrows != 0){

		$member_buttons_str = $template->get_loop ("member_buttons");

		if (($logged_in==1) && ($top_f_rlevel <= $_userlevel)) {
			$template->end_loop ("member_buttons", $member_buttons_str);
		} else {
			$template->end_loop ("member_buttons", "");
		}

		$f_count = mysql_num_rows($f_result);

		while($row=mysql_fetch_array($f_result)){



			$t_id = $row['id'];

			$t_poll = $row['poll'];

			$t_title = $row['title'];

			$t_description = $row['description'];

			$t_poster = $row['poster'];

			$t_date = $row['date'];

			$t_views = number_format($row['views']);

			$t_locked = $row['locked'];

			$t_result = mysql_query("select * from replies where tid='$t_id'");

			$r_count = number_format(mysql_num_rows($t_result));



			$getuser_result = mysql_query("select * from users where username='". $t_poster ."'");

			$row = mysql_fetch_array($getuser_result);

			$poster_id = $row['id'];



			if($r_count==0){

				$recent_date = '';

				$recent_poster_link = '---';

			} else {

				$recent_reply_result = mysql_query("SELECT * FROM replies WHERE tid='$t_id' ORDER BY datesort DESC LIMIT 1");

				$recent_reply_count = mysql_num_rows($recent_reply_result);



				$reply_row = mysql_fetch_array($recent_reply_result);

				$recent_poster = $reply_row['poster'];



				$recent_userid_result = mysql_query("SELECT * FROM users WHERE username='". $recent_poster ."'");

				$userid_row = mysql_fetch_array($recent_userid_result);

				$recent_poster_id = $userid_row['id'];



				$recent_poster_link = "<a href=\"profile.php?id={$recent_poster_id}\">{$reply_row['poster']}</a>";

				$recent_date = null;

				$recent_date = $reply_row['date'];

				$recent_date = date("F jS, Y", strtotime($recent_date));

			}



			$read_num = 0;



			$read_result=@mysql_query("SELECT * FROM `read` WHERE user_id='$_userid' AND topic_id='$t_id'");

			$read_num=mysql_num_rows($read_result);



			$viewing_result = mysql_query("SELECT * FROM online WHERE viewtopic='$t_id'");

			$viewing_count = mysql_num_rows($viewing_result);



			if ($_userid) {

				if ($read_num == 0) {

					$read_forum = '1';

				} else {

					$read_forum = '0';

				}

			} else {

				$read_forum = '1';

			}



			if(!($evenforum_count % 2) == TRUE){

			$evenforum = 1;

			} else {

				$evenforum = 2;

			}



			$evenforum_count = $evenforum_count + 1;



			$mini_template = new MiniTemplate ();



			$mini_template->template_html = $topic_str;



			$mini_template->set_template ("id", $t_id);

			$mini_template->set_template ("poll", $t_poll);

			$mini_template->set_template ("title", $t_title);

			$mini_template->set_template ("description", $t_description);

			$mini_template->set_template ("poster", $t_poster);

			$mini_template->set_template ("poster_id", $poster_id);

			$mini_template->set_template ("views", $t_views);

			$mini_template->set_template ("viewing_count", $viewing_count);

			$mini_template->set_template ("replies", $r_count);

			$mini_template->set_template ("locked", $t_locked);

			$mini_template->set_template ("recent_poster_link", $recent_poster_link); // instead of having poster name and id, lets just make it so that the name and id get into a link string, if not, a --- string

			$mini_template->set_template ("recent_date", $recent_date);

			$mini_template->set_template ("read", $read_forum);

			$mini_template->set_template ("evenforum", $evenforum);



			$final_topic_html .= $mini_template->return_html ();



		}



	} else {

		$notopics_str = $template->get_loop ("notopics");

		$member_buttons_str = $template->get_loop ("member_buttons");



			if ($logged_in==1) {

				$template->end_loop ("member_buttons", $member_buttons_str);

			} else {

				$template->end_loop ("member_buttons", "");

			}

		$template->end_loop ("topics", "");

		$template->end_loop ("notopics", $notopics_str);

		$template->end_loop ("search_topics", "");

		$template->end_loop ("error", "");

	}





	/*

	// LIMIT PEOPLE TO CERTAIN FORUMS

	$get_cat_rlevel = mysql_query("SELECT level_limit FROM categories WHERE id='$top_c_id'");

	$cat_rlevel_info = mysql_fetch_row($get_cat_rlevel);

	if($cat_rlevel_info[0] <= $_userlevel){



		$template->end_loop ("topics", "");

		$template->end_loop ("notopics", "");

		$template->end_loop ("search_topics", "");

		$template->end_loop ("error", $error_str);



		$template->set_template ("error_message", "This forum does not exist.  Please return to the previous page and try again.");



	}

	*/



	if ($f_count) {

		$template->end_loop ("topics", $final_topic_html);

		$template->end_loop ("notopics", "");

	} else {

		$template->end_loop ("notopics", $notopics_str);

		$template->end_loop ("topics", "");

	}



	$template->end_loop ("search_topics", $search_topic_str);

	$template->end_loop ("error", "");



	if(!($evenforum_count % 2) == TRUE){

		$evensearch = 1;

	} else {

		$evensearch = 2;

	}



	$template->set_template ("evensearch", $evensearch);



} else {

	$template->end_loop ("topics", "");

	$template->end_loop ("notopics", "");

	$template->end_loop ("pages", "");

	$template->end_loop ("sub_forums", "");

	$template->end_loop ("pinned", "");

	$template->end_loop ("member_buttons", "");

	$template->end_loop ("search_topics", "");

	$template->end_loop ("error", $error_str);



	$template->set_template ("category_name", "");

	$template->set_template ("subforum_cat_link", "");

	$template->set_template ("forum_name", "");

	$template->set_template ("error_message", "This forum does not exist.  Please return to the previous page and try again.");

}

} else {

	$template->end_loop ("topics", "");

	$template->end_loop ("notopics", "");

	$template->end_loop ("pages", "");

	$template->end_loop ("sub_forums", "");

	$template->end_loop ("pinned", "");

	$template->end_loop ("member_buttons", "");

	$template->end_loop ("search_topics", "");

	$template->end_loop ("error", $error_str);



	$template->set_template ("category_name", "");

	$template->set_template ("subforum_cat_link", "");

	$template->set_template ("forum_name", "");

	$template->set_template ("error_message", "This forum does not exist.  Please return to the previous page and try again.");

}

$online_str = $template->get_loop ("user_online");



$final_online = NULL;

$final_online_today = NULL;



$a_result = mysql_query("SELECT * FROM online WHERE viewforum='". $_GET[id] ."' AND isonline='1'");

$total_count = mysql_num_rows($a_result);



$b_result = mysql_query("SELECT * FROM online WHERE viewforum='". $_GET[id] ."' AND guest='0' AND isonline='1'");

$member_count = mysql_num_rows($b_result);



$c_result = mysql_query("SELECT * FROM online WHERE viewforum='". $_GET[id] ."' AND guest='1' AND isonline='1'");

$guest_count = mysql_num_rows($c_result);



if($member_count<=1){

	$onlinenow_count = NULL;

} else {

	$onlinenow_count = TRUE;

}



$d_result = mysql_query("SELECT * FROM online WHERE viewforum='". $_GET[id] ."' AND guest='0' AND isonline='1'");

$num = mysql_num_rows ($d_result);

$current = 1;



while($row = mysql_fetch_array($d_result)){

	$o_id = $row['id'];

	$o_user = $row['username'];

	$online_posting = $row['posting'];



	$e_result = mysql_query("SELECT * FROM users WHERE id='$o_id'");

	$row = mysql_fetch_array($e_result);

	$o_level = $row['level'];



	if(($onlinetoday_count<=1) || ($i==$onlinetoday_count-1)){

		$onlinenow_count = null;

		$i = null;

	} else {

		$onlinenow_count = TRUE;

		$i = $i+1;

	}

	if($is_moderator == TRUE){

		$o_level = 2;

	}



	$mini_template = new MiniTemplate ();



	$mini_template->template_html = $online_str;



	$mini_template->set_template ("online_num", $onlinenow_count);

	$mini_template->set_template ("online_id", $o_id);

	$mini_template->set_template ("online_user", $o_user);

	$mini_template->set_template ("online_posting", $online_posting);

	$mini_template->set_template ("online_level", $o_level);

	$mini_template->set_template ("spacer", (($current < $num) ? "," : ""));



	$final_online .= $mini_template->return_html ();



	$current++;



}



$template->end_loop ("user_online", $final_online);



$template->set_template ("total_active_users", number_format($total_count));

$template->set_template ("total_active_guests", number_format($guest_count));

$template->set_template ("total_active_members", number_format($member_count));

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