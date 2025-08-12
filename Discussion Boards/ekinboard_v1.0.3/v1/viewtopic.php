<?PHP
include "config.php";

include "updateonline.php";
$page_name = "Viewing Topic";
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime;

// Need to make sure the user has access to view this page

	$top_t_result = mysql_query("select * from topics where id='". $_GET[id] ."'");

	$row = mysql_fetch_array($top_t_result);

	$top_f_id = $row['fid'];

	$top_t_title = $row['title'];


	$top_f_result = mysql_query("select * from forums where id='$top_f_id'");

	$row = mysql_fetch_array($top_f_result);

	$top_c_id = $row['cid'];

	$top_f_sub = $row['subforum'];

	$top_f_rlevel = $row['restricted_level'];

	if($top_f_sub == 1){

		$top_f_result = mysql_query("select * from forums where id='$top_c_id'");

		$row = mysql_fetch_array($top_f_result);

		$top_c_id = $row['cid'];

	}


	$top_f_name = $row['name'];


	$top_c_result = mysql_query("select * from categories where id='$top_c_id'");

	$row = mysql_fetch_array($top_c_result);

	$top_c_name = $row['name'];

	$cat_access_level = $row['level_limit'];

	updateonline($_GET['id'],$top_f_id,'0',$_userid,$_username,$page_name);

//////////////////////////



$new_mail = NULL;

$onlinetoday_count = NULL;

$tmp_num = NULL;

$final_page_number_str = NULL;

$onlinetoday_count = NULL;

$final_pages_str = NULL;

$a = NULL;

$b = NULL;

$current_page = NULL;



if(!isset($_GET['delete'])){

	$_GET['delete'] = NULL;

}



if(isset($_userid)){

	if(!isset($_GET['stick'])){

		$_GET['stick'] = NULL;

	}

	if(!isset($_GET['lock'])){

		$_GET['lock'] = NULL;

	}

	if(!isset($_POST['poll_vote'])){

		$_POST['poll_vote'] = NULL;

	}

}





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

if(($_userlevel >= 2) || ($is_moderator == TRUE) || ($cat_access_level <= $_userlevel)){



	if(($_GET['stick'] == "0") && ($_GET['id'] != NULL)){



		$stick_check_result = mysql_query("select * from topics where id='". $_GET[id] ."' AND sticky='0'");



		$stick_check = mysql_num_rows($stick_check_result);



		if($stick_check != 0){



			$stick_topic = mysql_query("UPDATE topics SET sticky='1' WHERE id='". $_GET[id] ."'");



		}



	} else if(($_GET['stick'] == "1") && ($_GET['id'] != NULL)){



		$stick_check_result = mysql_query("select * from topics where id='". $_GET[id] ."' AND sticky='1'");



		$stick_check = mysql_num_rows($stick_check_result);



		if($stick_check != 0){



			$stick_topic = mysql_query("UPDATE topics SET sticky='0' WHERE id='". $_GET[id] ."'");



		}



	}

	if(($_GET['lock'] == "0") && ($_GET['id'] != NULL)){



		$lock_check_result = mysql_query("select * from topics where id='". $_GET[id] ."' AND locked='0'");



		$lock_check = mysql_num_rows($lock_check_result);



		if($lock_check != 0){



			$lock_topic = mysql_query("UPDATE topics SET locked='1' WHERE id='". $_GET[id] ."'");



		}



	} else if(($_GET['lock'] == "1") && ($_GET['id'] != NULL)){



		$lock_check_result = mysql_query("select * from topics where id='". $_GET[id] ."' AND locked='1'");



		$lock_check = mysql_num_rows($lock_check_result);



		if($lock_check != 0){



			$lock_topic = mysql_query("UPDATE topics SET locked='0' WHERE id='". $_GET[id] ."'");



		}



	} else if(($_GET['delete'] == "topic") && ($_GET[id] != NULL)){



		$delete = TRUE;

	} else if(($_GET['delete'] == "reply") && ($_GET[id] != NULL)){



		$delete = TRUE;

	}

}



if(($_userid != NULL) && ($_POST['poll_vote'] != NULL)){



	$poll_check_result = mysql_query("select * from topics where id='". $_GET[id] ."' AND poll='1'");



	$poll_check = mysql_num_rows($poll_check_result);







	if($poll_check != 0){



		$poll_voted_check_result = mysql_query("select * from poll_votes where pid='". $_GET[id] ."' AND voter='$_userid'");



		$poll_voted_check = mysql_num_rows($poll_voted_check_result);







		if($poll_voted_check == 0){



			$result=MYSQL_QUERY("INSERT INTO `poll_votes` (`pid` , `choice_id` , `voter`)".



			"VALUES ('". $_GET[id] ."', '". $_POST[poll_vote] ."', '$_userid')");
		}
	}
}
include ("class/template.class.php");
include ("class/mini_template.class.php");
$template = new Template ();

$template->add_file ("header.tpl");
$template->add_file ("viewtopic.tpl");

$template->set_template ("template", $user["theme"]);

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
	$check_mail = mysql_query("SELECT * FROM inbox WHERE reciever_id='$_userid' AND message_read='0'");
	$new_mail = mysql_num_rows($check_mail);

	$template->set_template ("new_messages", $new_mail);
} else {
	$logged_in = 0;
}
$mini_menu_guest = $template->get_loop ("guest_mini_menu");



$mini_menu_registered = $template->get_loop ("registered_mini_menu");



$mini_menu_admin = $template->get_loop ("admin_mini_menu");



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



if($_userlevel >= $cat_access_level){

	$template->set_template ("topic_title", $top_t_title);



	$template->set_template ("topic_id", $_GET['id']);



	$template->set_template ("forum_id", $top_f_id);



	$template->set_template ("forum_name", $top_f_name);



	$template->set_template ("site_title", $_SETTING['organization']);



	$template->set_template ("page_title", $_SETTING['organization'] ." - ". $top_t_title);



	$template->set_template ("cat_name", $top_c_name);



	$template->set_template ("cat_id", $top_c_id);



	$error_str = $template->get_loop ("error");

} else {

	$template->set_template ("topic_title", "");



	$template->set_template ("topic_id", "");



	$template->set_template ("forum_id", "");



	$template->set_template ("forum_name", "");



	$template->set_template ("site_title", $_SETTING['organization']);



	$template->set_template ("page_title", $_SETTING['organization']);



	$template->set_template ("cat_name", "");



	$template->set_template ("cat_id", "");



	$error_str = $template->get_loop ("error");

}



if(($_GET[act] == "move") && (!empty($_GET[id])) && (($_userlevel >= 2) || ($is_moderator == TRUE))){



$template->end_loop ("delete", "");

	$template->end_loop ("pages", "");

	$template->end_loop ("topic", "");

	$template->end_loop ("replies", "");

	$template->end_loop ("member_buttons", "");



	if(isset($_POST[move])){

		$topic_check = mysql_query("SELECT * FROM topics WHERE id='". $_GET[id] ."'");

		$topic_check = mysql_num_rows($topic_check);



		if($topic_check > 0){

			$template->end_loop ("error", "");



			echo $_POST[fid];

			$topic_move = mysql_query("UPDATE topics SET fid='". $_POST[fid] ."' WHERE id='". $_GET[id] ."'");



			header("Location: viewtopic.php?id=$_GET[id]");

		}

	} else {

		$topic_check = mysql_query("SELECT * FROM topics WHERE id='". $_GET[id] ."'");

		$topic_check = mysql_num_rows($topic_check);



		if($topic_check > 0){

			$template->end_loop ("error", "");

			$move_str = $template->get_loop ("move");

			$template->end_loop ("move", $move_str);



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



					$dropdown_list = "<optgroup label=\"$dd_cat_name\">";



					if($_userlevel >= '2'){



						$dropdown_forum_list = mysql_query("SELECT * FROM forums WHERE subforum='0' AND hidden='0' AND cid='$dd_cat_id' ORDER BY id ASC");



						while($dd_f_row = mysql_fetch_array($dropdown_forum_list)){



							$dd_name = $dd_f_row['name'];

							$dd_id = $dd_f_row['id'];



							$dropdown_list .= "<option value='$dd_id'> - $dd_name</option>";



							// SUBFORUM CHECK

							$sub_dd_check = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='$dd_id' AND hidden='0' ORDER BY id ASC");



							$sub_dd_count = mysql_num_rows($sub_dd_check);



							if($sub_dd_count!=='0'){



								while($sub_dd_row = mysql_fetch_array($sub_dd_check)){



									$sub_dd_id = $sub_dd_row['id'];

									$sub_dd_name = $sub_dd_row['name'];



									$dropdown_list .= "<option value='$sub_dd_id'> &nbsp;- $sub_dd_name</option>";



								}



							}



						}



						$mini_template = new MiniTemplate ();



						$mini_template->template_html = $dropdown_list_str;



						$mini_template->set_template ("dropdown_list", $dropdown_list);



						$final_dropdown .= $mini_template->return_html ();



					}







				} else {



						$dropdown_list = "<optgroup label=\"$dd_cat_name\">";



						$dropdown_forum_list = mysql_query("SELECT * FROM forums WHERE subforum='0' AND hidden='0' AND cid='$dd_cat_id' ORDER BY id ASC");



						while($dd_f_row = mysql_fetch_array($dropdown_forum_list)){



							$dd_name = $dd_f_row['name'];

							$dd_id = $dd_f_row['id'];



							$dropdown_list .= "<option value='$dd_id'> - $dd_name</option>";



							// SUBFORUM CHECK

							$sub_dd_check = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='$dd_id' AND hidden='0' ORDER BY id ASC");



							$sub_dd_count = mysql_num_rows($sub_dd_check);



							if($sub_dd_count!=='0'){



								while($sub_dd_row = mysql_fetch_array($sub_dd_check)){



									$sub_dd_id = $sub_dd_row['id'];

									$sub_dd_name = $sub_dd_row['name'];



									$dropdown_list .= "<option value='$sub_dd_id'> &nbsp;- $sub_dd_name</option>";



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

			} else {

$template->end_loop ("move", "");

	$template->end_loop ("error", $error_str);

$template->set_template ("error_message", "This topic does not exist");

}

}

} else {

$template->end_loop ("move", "");



if($_userlevel >= $cat_access_level){



	$stick_topic_str = $template->get_loop ("stick_topic");



	$lock_topic_str = $template->get_loop ("lock_topic");



	$edit_topic_str = $template->get_loop ("edit_topic");



	$move_topic_str = $template->get_loop ("move_topic");



	$delete_topic_str = $template->get_loop ("delete_topic");



	$quote_topic_str = $template->get_loop ("quote_topic");



	$reply_ava_str = $template->get_loop ("reply_ava");





if(($_username != NULL) && ($top_f_rlevel <= $_userlevel)){



	$t_result = mysql_query("select * from topics where id='". $_GET[id] ."'");



	$row = mysql_fetch_array($t_result);



	$topic_poster = $row['poster'];



	$topic_sticky = $row['sticky'];



	$topic_locked = $row['locked'];



	if (($topic_poster == $_username) || ($_userlevel >= 2) || ($is_moderator == TRUE)){



		$template->end_loop ("edit_topic", $edit_topic_str);



		if(($_userlevel >= 2) || ($is_moderator == TRUE)){



			$template->end_loop ("move_topic", $move_topic_str);



			$template->end_loop ("delete_topic", $delete_topic_str);



			$template->end_loop ("stick_topic", $stick_topic_str);



			$template->end_loop ("lock_topic", $lock_topic_str);



			$template->set_template ("sticky", $topic_sticky);



			$template->set_template ("locked", $topic_locked);



		} else {



			$template->end_loop ("move_topic", "");



			$template->end_loop ("delete_topic", "");



			$template->end_loop ("stick_topic", "");



			$template->end_loop ("lock_topic", "");



		}



	} else {



		$template->end_loop ("move_topic", "");



		$template->end_loop ("edit_topic", "");



		$template->end_loop ("delete_topic", "");



		$template->end_loop ("stick_topic", "");



		$template->end_loop ("lock_topic", "");





	}


	$template->end_loop ("quote_topic", $quote_topic_str);



} else {



	$template->end_loop ("move_topic", "");



	$template->end_loop ("stick_topic", "");



	$template->end_loop ("lock_topic", "");



	$template->end_loop ("edit_topic", "");



	$template->end_loop ("delete_topic", "");



	$template->end_loop ("quote_topic", "");



}









$topic_str = $template->get_loop ("topic");
$error_str = $template->get_loop ("error");



if(($_GET['delete'] == "topic") || ($_GET['delete'] == "reply")){

$delete_str = $template->get_loop ("delete");



$template->end_loop ("member_buttons", "");

$template->end_loop ("topic", "");

$template->end_loop ("poll", "");

$template->end_loop ("pages", "");

$template->end_loop ("replies", "");



if($_GET[delete] == "reply"){

	$r_result = mysql_query("select * from replies where id='". $_GET[id] ."'");

	$row = mysql_fetch_array($r_result);

	$tid = $row['tid'];

	$poster = $row['poster'];

} else if($_GET[delete] == "topic"){

	$t_result = mysql_query("select * from topics where id='". $_GET[id] ."'");

	$row = mysql_fetch_array($t_result);

	$fid = $row['fid'];

	$poster = $row['poster'];

}



if(($_userlevel >= 2) || ($is_moderator == TRUE)){

	if($_GET[sure] == "yes"){

		if($_GET[delete] == "reply"){

			$d_result = mysql_query("DELETE FROM replies WHERE id='". $_GET[id] ."'");



			header("Location: viewtopic.php?id=". $tid);

		} else if($_GET[delete] == "topic"){
			$t_result = mysql_query("select attch_name from topics where id='". $_GET[id] ."'");
			$row = mysql_fetch_array($t_result);

			if($row[0] != NULL){
				unlink($row[0]);
			}

			$d_result = mysql_query("DELETE FROM replies WHERE tid='". $_GET[id] ."'");

			$d_result = mysql_query("DELETE FROM topics WHERE id='". $_GET[id] ."'");



			header("Location: viewforum.php?id=". $fid);

		}

	} else {

		if($_GET[delete] == "reply"){

			$template->end_loop ("delete", $delete_str);



			$template->set_template ("id", $_GET[id]);

			$template->set_template ("delete_type", "reply");

		} else if($_GET[delete] == "topic"){

			$template->end_loop ("delete", $delete_str);



			$template->set_template ("id", $_GET[id]);

			$template->set_template ("delete_type", "topic");

		}

	}



$template->end_loop ("error", "");

} else {



$template->end_loop ("delete", "");

$template->end_loop ("error", $error_str);

}

} else {



$final_topic_html = NULL;



$page_result = mysql_query("select * from topics where id='". $_GET[id] ."'");



$numrows = mysql_num_rows($page_result);



if($numrows!=0){



	$read_result=mysql_query("SELECT * FROM `read` WHERE user_id='$_userid' AND topic_id='". $_GET[id] ."'");



	$read_num=mysql_num_rows($read_result);





	if($read_num==0){



		@mysql_query("INSERT INTO `read` VALUES ('". $_GET[id] ."', '$_userid')");



	}







	$t_result = mysql_query("select * from topics where id='". $_GET[id] ."'");



	$t_count = mysql_num_rows($t_result);



	$row=mysql_fetch_array($t_result);







	$t_id = $row['id'];



	$t_poll = $row['poll'];



	$p_question = $row['poll_question'];



	$t_title = $row['title'];



	$t_description = $row['description'];



	$t_message = ekincode($row['message'],$user['theme']);



	$t_poster = $row['poster'];



	$t_date = $row['date'];



	$t_date = date("l, F jS, Y", strtotime($t_date, "\n"));



	$t_views = $row['views'];



	$t_views = $t_views + 1;

	$attch_name = $row['attch_name'];
	$attch_size = round($row['attch_size']/1024, 1);
	$attch_type = $row['attch_type'];


	$update_result = mysql_query("UPDATE topics SET views='$t_views' WHERE id='". $_GET[id] ."'");



		$q1 = mysql_query("SELECT * FROM replies WHERE poster='". $t_poster ."'");



		$q2 = mysql_query("SELECT * FROM topics WHERE poster='". $t_poster ."'");



    $poster_posts = mysql_num_rows($q1) + mysql_num_rows($q2);



	$t_locked = $row['locked'];



	$t_result = mysql_query("select * from replies where tid='$t_id'");
	$r_count = mysql_num_rows($t_result);
	$getuser_result = mysql_query("SELECT * FROM users WHERE username='". $t_poster ."'");

	$row = mysql_fetch_array($getuser_result);

	if($row['display_name'] != NULL){
		$poster_display_name = $row['display_name'];
	} else {
		$poster_display_name = $t_poster;
	}

	$poster_level = $row['level'];
	$poster_id = $row['id'];
	$poster_sig = ekincode($row['sig'],$user['theme']);
	if($row['title'] != NULL){		$member_title = "<br>". $row['title'];	} else {		$member_title = NULL;	}
	$member_joined = date("M jS, Y", strtotime($row["signup_date"], "\n"));
	$poster_aim = $row['aim'];
	$poster_msn = $row['msn'];
	$poster_yahoo = $row['yahoo'];
	$poster_icq = $row['icq'];
	$poster_www = $row['website_url'];
	$poster_avatar = $row['avatar'];

	$total_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $poster_id ."'");	$total_votescount = mysql_num_rows($total_votescount_result);
	$bad_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $poster_id ."' AND value='bad'");	$bad_votescount = mysql_num_rows($bad_votescount_result);
	$good_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $poster_id ."' AND value='good'");	$good_votescount = mysql_num_rows($good_votescount_result);
	$check_voted_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $poster_id ."' AND id_from='". $_userid ."'");	$check_voted = mysql_num_rows($check_voted_result);
	if($total_votescount != 0){		$good_votes = $good_votescount / $total_votescount * 3;		$good_votes = round($good_votes, 0);		$bad_votes = $bad_votescount / $total_votescount * -3;		$bad_votes = round($bad_votes, 0);				$rounded_votenum = $bad_votes + $good_votes;				if((isset($_userid)) && ($_userid != $poster_id)){			if($check_voted != 0){				$vote_img = "<br><img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )''>";			} else {				$vote_img = "<br><a href=profile.php?id=$poster_id&d=rate&value=0><img src=templates/". $user[theme] ."/images/member_rating_delete.gif border=0 alt='Not Helpful'></a><img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )'><a href=profile.php?id=$poster_id&d=rate&value=1><img src=templates/". $user[theme] ."/images/member_rating_add.gif border=0 alt='Helpful'></a>";			}		} else {			$vote_img = "<br><img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )'>";		}	} else {		if((isset($_userid)) && ($_userid != $poster_id)){			$vote_img = "<br><a href=profile.php?id=$poster_id&d=rate&value=0><img src=templates/". $user[theme] ."/images/member_rating_delete.gif border=0 alt='Not Helpful'></a><img src='templates/". $user[theme] ."/images/vote_null.gif' alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )' border=0><a href=profile.php?id=$poster_id&d=rate&value=1><img src=templates/". $user[theme] ."/images/member_rating_add.gif border=0 alt='Helpful'></a>";		} else {			$vote_img = "<br><img src='templates/". $user[theme] ."/images/vote_null.gif' alt='' border=0>";		}	}
	$poster_avatar_alt = $row['avatar_alt'];

	$poster_avatar = str_replace(" ", "%20", $poster_avatar);
	if($poster_avatar != null){

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
		$poster_avatar = "<img src='$poster_avatar' border='0' height='$ava_height' width='$ava_width' title='$poster_avatar_alt' alt='$poster_avatar_alt'><p>";

		$ava_height = NULL;
		$ava_width = NULL;
	} else {
		$poster_avatar == NULL;
	}
	switch ($poster_level) {
		case 0:
		   $poster_level = "Test Account";
		   break;
		case 1:
		   $poster_level = "Member";
   			break;
		case 2:
		   $poster_level = "Moderator";
		   break;
		case 3:
		   $poster_level = "Administrator";
		   break;
	}
	$mini_template = new MiniTemplate ();
	$mini_template->template_html = $topic_str;
	$sig_str = $template->get_loop ("topic_sig", $topic_str);

	if ($poster_sig != NULL) {
		$poster_sig = str_replace ("<{sig}>", $poster_sig, $sig_str);
	} else {
		$poster_sig = "";
	}
	$mini_template->template_html = $template->end_loop ("topic_sig", $poster_sig, $topic_str);
	$mini_template->set_template ("id", $t_id);
	$mini_template->set_template ("display_name", $poster_display_name);
	$mini_template->set_template ("title", $t_title);
	$mini_template->set_template ("description", $t_description);
	$mini_template->set_template ("message", $t_message);
	$mini_template->set_template ("date", $t_date);
	$mini_template->set_template ("poster", $t_poster);
	$mini_template->set_template ("posts", $poster_posts);
	$mini_template->set_template ("level", $poster_level);
	$mini_template->set_template ("poster_id", $poster_id);
	$mini_template->set_template ("locked", $t_locked);
	$mini_template->set_template ("member_title", $member_title);
	$mini_template->set_template ("user_voting", $vote_img);
	$mini_template->set_template ("member_number", number_format($poster_id));
	$mini_template->set_template ("member_joined", $member_joined);
	$mini_template->set_template ("poster_www", $poster_www);
	$mini_template->set_template ("poster_aim", $poster_aim);
	$mini_template->set_template ("poster_msn", $poster_msn);
	$mini_template->set_template ("poster_yahoo", $poster_yahoo);
	$mini_template->set_template ("poster_icq", $poster_icq);
	$mini_template->set_template ("poster_avatar", $poster_avatar);

	$final_topic_html .= $mini_template->return_html ();
	$member_buttons_str = $template->get_loop ("member_buttons");
	$locked_str = $template->get_loop ("locked");
	$post_reply_str = $template->get_loop ("post_reply");



	$new_topic_str = $template->get_loop ("new_topic");



	$new_poll_str = $template->get_loop ("new_poll");

	if (($_username != NULL) && ($top_f_rlevel <= $_userlevel)) {



		$template->end_loop ("member_buttons", $member_buttons_str);



		$template->end_loop ("new_topic", $new_topic_str);



		$template->end_loop ("new_poll", $new_poll_str);
		if($t_locked == 1){

			if(($_userlevel >= 2) || ($is_moderator == TRUE)){

				$template->end_loop ("post_reply", $post_reply_str);

			} else {

				$template->end_loop ("post_reply", "");

			}

				$template->end_loop ("locked", $locked_str);

		} else{



			$template->end_loop ("post_reply", $post_reply_str);



			$template->end_loop ("locked", "" );



		}



	} else {



		$template->end_loop ("member_buttons", "");



	}







} else {

$template->end_loop ("member_buttons", "");

$template->end_loop ("error", $error_str);



$template->set_template ("error_message", "This topic does not exist.  Please return to the previous page and try again.");

}


$template->end_loop ("topic", $final_topic_html);


/////////////////////////////////////////
if($attch_name != NULL){
$topic_attch_str = $template->get_loop ("topic_attch");

	$mini_template = new MiniTemplate ();
	$mini_template->template_html = $topic_attch_str;

	$mini_template->set_template ("attch_name", $attch_name);
	$mini_template->set_template ("attch_size", $attch_size);
	$mini_template->set_template ("attch_type", $attch_type);

	$final_attch_html .= $mini_template->return_html ();
	$template->end_loop ("topic_attch", $final_attch_html);
} else {
	$template->end_loop ("topic_attch", "");
}
/////////////////////////////////////////



$poll_str = $template->get_loop ("poll");







$evenchoice_count = 0;







if($t_poll == '1'){



	$template->set_template ("poll_question", $p_question);







	$poll_vote_str = $template->get_loop ("poll_vote");



	$poll_results_str = $template->get_loop ("poll_results");







	$template->end_loop ("poll", $poll_str);







	$poll_voted_check_result = mysql_query("select * from poll_votes where pid='". $_GET[id] ."' AND voter='". $_userid. "'");



	$poll_voted_check = mysql_num_rows($poll_voted_check_result);







	if(($_username != NULL) && ($poll_voted_check == 0)){



			if($_GET[poll] == "results"){



				$poll_choice_str = $template->get_loop ("poll_results_choice");







				$p_result = mysql_query("select * from poll_choices where pid='". $_GET[id] ."' ORDER BY id");



				while($poll_row=mysql_fetch_array($p_result)){







					if(!($evenchoice_count % 2) == TRUE){



						$evenchoice = 1;



					} else {



						$evenchoice = 2;



					}







					$evenchoice_count++;







					$poll_choice_value = $poll_row['id'];



					$poll_choice = $poll_row['choice'];







					$c_total_result = mysql_query("select * from poll_votes where pid='". $_GET[id] ."'");



					$c_total_count = mysql_num_rows($c_total_result);







					$c_result = mysql_query("select * from poll_votes where choice_id='$poll_choice_value'");



					$c_count = mysql_num_rows($c_result);







					if(($c_total_count!=0) && ($c_count!=0)){



						$count_percentage = $c_count / $c_total_count * 100;



					} else {



						$count_percentage = 0;



					}







					$poll_bar_width = round($count_percentage, 0) * 2;







					$poll_choice_percent = round($count_percentage, 2);







				 	$mini_template = new MiniTemplate ();







					$mini_template->template_html = $poll_choice_str;







					$mini_template->set_template ("poll_choice", $poll_choice);



					$mini_template->set_template ("poll_choice_value", $poll_choice_value);



					$mini_template->set_template ("poll_choice_votes", $c_count);



					$mini_template->set_template ("poll_bar_width", $poll_bar_width);



					$mini_template->set_template ("poll_choice_percent", $poll_choice_percent);



					$mini_template->set_template ("evenchoice", $evenchoice);







					$final_poll_html .= $mini_template->return_html ();







					$current++;



				}







				$template->end_loop ("poll_results", $template->end_loop ("poll_results_choice", $final_poll_html, $poll_results_str));



				$template->end_loop ("poll_vote", "");



			} else {



				$poll_choice_str = $template->get_loop ("poll_vote_choice");







				$p_result = mysql_query("select * from poll_choices where pid='". $_GET[id] ."' ORDER BY id");



				while($poll_row=mysql_fetch_array($p_result)){







					if(!($evenchoice_count % 2) == TRUE){



						$evenchoice = 1;



					} else {



						$evenchoice = 2;



					}







					$evenchoice_count++;







					$poll_choice_value = $poll_row['id'];



					$poll_choice = $poll_row['choice'];







					$c_result = mysql_query("select * from poll_votes where choice_id='$poll_choice_value'");



					$c_count = mysql_num_rows($c_result);







				 	$mini_template = new MiniTemplate ();







					$mini_template->template_html = $poll_choice_str;







					$mini_template->set_template ("poll_choice", $poll_choice);



					$mini_template->set_template ("poll_choice_value", $poll_choice_value);



					$mini_template->set_template ("poll_choice_votes", $c_count);



					$mini_template->set_template ("evenchoice", $evenchoice);







					$final_poll_html .= $mini_template->return_html ();







					$current++;



				}







				$template->end_loop ("poll_vote", $template->end_loop ("poll_vote_choice", $final_poll_html, $poll_vote_str));



				$template->end_loop ("poll_results", "");



			}



	} else {



		$poll_choice_str = $template->get_loop ("poll_results_choice");







		$p_result = mysql_query("select * from poll_choices where pid='". $_GET[id] ."' ORDER BY id");



		while($poll_row=mysql_fetch_array($p_result)){







					if(!($evenchoice_count % 2) == TRUE){



						$evenchoice = 1;



					} else {



						$evenchoice = 2;



					}







					$evenchoice_count++;







					$poll_choice_value = $poll_row['id'];



					$poll_choice = $poll_row['choice'];







					$c_total_result = mysql_query("select * from poll_votes where pid='" .$_GET[id] ."'");



					$c_total_count = mysql_num_rows($c_total_result);







					$c_result = mysql_query("select * from poll_votes where choice_id='$poll_choice_value'");



					$c_count = mysql_num_rows($c_result);







					if(($c_total_count!=0) && ($c_count!=0)){



						$count_percentage = $c_count / $c_total_count * 100;



					} else {



						$count_percentage = 0;



					}







					$poll_bar_width = round($count_percentage, 0) * 2;







					$poll_choice_percent = round($count_percentage, 2);







				 	$mini_template = new MiniTemplate ();







					$mini_template->template_html = $poll_choice_str;







					$mini_template->set_template ("poll_choice", $poll_choice);



					$mini_template->set_template ("poll_choice_value", $poll_choice_value);



					$mini_template->set_template ("poll_choice_votes", $c_count);



					$mini_template->set_template ("poll_bar_width", $poll_bar_width);



					$mini_template->set_template ("poll_choice_percent", $poll_choice_percent);



					$mini_template->set_template ("evenchoice", $evenchoice);







					$final_poll_html .= $mini_template->return_html ();







					$current++;



			if($poll_voted_check != 0){



				$poll_notice = "( You have already voted! )";



			}



		}







		$template->set_template ("poll_notice", $poll_notice);







		$template->end_loop ("poll_results", $template->end_loop ("poll_results_choice", $final_poll_html, $poll_results_str));



		$template->end_loop ("poll_vote", "");



	}



} else {



	$template->end_loop ("poll", "");



}







$page_result = mysql_query("select * from replies where tid='". $_GET[id] ."'");



$numrows = mysql_num_rows($page_result);







$replies_str = $template->get_loop ("replies");



$reply_str = $template->get_loop ("reply");



$final_replies_html = NULL;







$display_num = 10;



$limitnum = 2;







$query_rows = "SELECT * FROM replies where tid='". $_GET[id] ."'";



$result_rows = @mysql_query ($query_rows);



$num_rows = @mysql_num_rows ($result_rows);







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



			$mini_template->set_template ("id", $_GET['id']);







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







			$mini_template->set_template ("id", $_GET['id']);



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







if($numrows==0){



	$template->end_loop ("replies", "");



} else {







	$r_result = mysql_query("select * from replies where tid='". $_GET[id] ."' ORDER BY datesort LIMIT $db_page_num, $display_num");



	$r_count = mysql_num_rows($r_result);



	while($row=mysql_fetch_array($r_result)){



	$r_id = $row['id'];



	$r_message = ekincode($row['message'],$user['theme']);



	$r_poster = $row['poster'];



	$r_date = $row['date'];



	$r_date = date("l, F jS, Y", strtotime($r_date, "\n"));





	$tmp_num = $tmp_num + 1;



	$post_number = $page_page_num - 1;



	$post_number = $display_num * $post_number;



	$post_number = $post_number + $tmp_num;





	$getuser_result = mysql_query("select * from users where username='". $r_poster ."'");



	$row = mysql_fetch_array($getuser_result);



	$poster_id = $row['id'];
	$total_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $poster_id ."'");	$total_votescount = mysql_num_rows($total_votescount_result);
	$bad_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $poster_id ."' AND value='bad'");	$bad_votescount = mysql_num_rows($bad_votescount_result);
	$good_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $poster_id ."' AND value='good'");	$good_votescount = mysql_num_rows($good_votescount_result);
	$check_voted_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $poster_id ."' AND id_from='". $_userid ."'");	$check_voted = mysql_num_rows($check_voted_result);
	if($total_votescount != 0){		$good_votes = $good_votescount / $total_votescount * 3;		$good_votes = round($good_votes, 0);		$bad_votes = $bad_votescount / $total_votescount * -3;		$bad_votes = round($bad_votes, 0);				$rounded_votenum = $bad_votes + $good_votes;				if((isset($_userid)) && ($_userid != $poster_id)){			if($check_voted != 0){				$vote_img = "<br><img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )''>";			} else {				$vote_img = "<br><a href=profile.php?id=$poster_id&d=rate&value=0><img src=templates/". $user[theme] ."/images/member_rating_delete.gif border=0 alt='Not Helpful'></a><img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )'><a href=profile.php?id=$poster_id&d=rate&value=1><img src=templates/". $user[theme] ."/images/member_rating_add.gif border=0 alt='Helpful'></a>";			}		} else {			$vote_img = "<br><img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )'>";		}	} else {		if((isset($_userid)) && ($_userid != $poster_id)){			$vote_img = "<br><a href=profile.php?id=$poster_id&d=rate&value=0><img src=templates/". $user[theme] ."/images/member_rating_delete.gif border=0 alt='Not Helpful'></a><img src='templates/". $user[theme] ."/images/vote_null.gif' alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )' border=0><a href=profile.php?id=$poster_id&d=rate&value=1><img src=templates/". $user[theme] ."/images/member_rating_add.gif border=0 alt='Helpful'></a>";		} else {			$vote_img = "<br><img src='templates/". $user[theme] ."/images/vote_null.gif' alt='' border=0>";		}	}
	if($row['display_name'] != NULL){
		$poster_display_name = $row['display_name'];
	} else {
		$poster_display_name = $r_poster;
	}


	$poster_joined = $row['signup_date'];



	$poster_joined = date("M jS, Y", strtotime($poster_joined, "\n"));



	$poster_www = $row['website_url'];



	$poster_aim = $row['aim'];



	$poster_msn = $row['msn'];



	$poster_yahoo = $row['yahoo'];



	$poster_icq = $row['icq'];



	$poster_sig = ekincode($row['sig'],$user['theme']);



	$poster_ava = $row['avatar'];



	$poster_level = $row['level'];
	if($row['title'] != NULL){		$member_title = "<br>". $row['title'];	} else {		$member_title = NULL;	}
		$q1 = mysql_query("SELECT * FROM replies WHERE poster='". $r_poster ."'");		$q2 = mysql_query("SELECT * FROM topics WHERE poster='". $r_poster ."'");
	$poster_posts = mysql_num_rows($q1) + mysql_num_rows($q2);
	$poster_avatar = $row['avatar'];

	$poster_avatar_alt = $row['avatar_alt'];



	$poster_avatar = str_replace(" ", "%20", $poster_avatar);



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

		$poster_avatar = "<img src='$poster_avatar' border='0' height='$ava_height' width='$ava_width' title='$poster_avatar_alt' alt='$poster_avatar_alt'><p>";

	} else {

		$poster_avatar == NULL;

	}



	switch ($poster_level) {



		case 0:



		   $poster_level = "Test Account";



		   break;



		case 1:



		   $poster_level = "Member";



   			break;



		case 2:



		   $poster_level = "Moderator";



		   break;



		case 3:



		   $poster_level = "Administrator";



		   break;



	}



	$sig_str = $template->get_loop ("reply_sig", $reply_str);





	if ($poster_sig != NULL) {



		$poster_sig = str_replace ("<{sig}>", $poster_sig, $sig_str);



	} else {



		$poster_sig = "";



	}

	$mini_template = new template ();



	$mini_template->template_html = $reply_str;



	$mini_template->template_html = $template->end_loop ("reply_sig", $poster_sig, $reply_str);



	$edit_reply_str = $mini_template->get_loop ("edit_reply");



	$delete_reply_str = $mini_template->get_loop ("delete_reply");



	$quote_reply_str = $mini_template->get_loop ("quote_reply");



if(($_username != NULL) && ($top_f_rlevel <= $_userlevel)){



	$mini_template->end_loop ("quote_reply", $quote_reply_str);



	if (($r_poster == $_username) || ($_userlevel >= 2) || ($is_moderator == TRUE)){



		$mini_template->end_loop ("edit_reply", $edit_reply_str);



		if(($_userlevel >= 2) || ($is_moderator == TRUE)){



			$mini_template->end_loop ("delete_reply", $delete_reply_str);



		} else {



			$mini_template->end_loop ("delete_reply", "");



		}



	} else {



		$mini_template->end_loop ("edit_reply", "");



		$mini_template->end_loop ("delete_reply", "");



	}



} else {



	$mini_template->end_loop ("edit_reply", "");



	$mini_template->end_loop ("delete_reply", "");



	$mini_template->end_loop ("quote_reply", "");



}



	$mini_template->set_template ("id", $r_id);



	$mini_template->set_template ("message", $r_message);



	$mini_template->set_template ("date", $r_date);


	$mini_template->set_template ("display_name", $poster_display_name);


	$mini_template->set_template ("poster", $r_poster);



	$mini_template->set_template ("posts", $poster_posts);



	$mini_template->set_template ("level", $poster_level);



	$mini_template->set_template ("poster_id", $poster_id);



	$mini_template->set_template ("locked", $t_locked);



	$mini_template->set_template ("member_title", $member_title);

	$mini_template->set_template ("user_voting", $vote_img);

	$mini_template->set_template ("member_number", number_format($poster_id));



	$mini_template->set_template ("member_joined", $poster_joined);



	$mini_template->set_template ("poster_www", $poster_www);



	$mini_template->set_template ("poster_aim", $poster_aim);



	$mini_template->set_template ("poster_msn", $poster_msn);



	$mini_template->set_template ("poster_yahoo", $poster_yahoo);



	$mini_template->set_template ("poster_icq", $poster_icq);



	$mini_template->set_template ("poster_avatar", $poster_avatar);



	$mini_template->set_template ("post_num", $post_number);



	$mini_template->set_template ("post_page", $page_page_num);



	$final_replies_html .= $mini_template->end_page ();



}







$template->end_loop ("replies", $template->end_loop ("reply", $final_replies_html, $replies_str));



}

$template->end_loop ("error", "");

$template->end_loop ("delete", "");

}

} else {

$template->end_loop ("delete", "");

$template->end_loop ("pages", "");

$template->end_loop ("topic", "");

$template->end_loop ("replies", "");

$template->end_loop ("member_buttons", "");

$template->end_loop ("error", $error_str);



$template->set_template ("error_message", "This topic does not exist.  Please return to the previous page and try again.");

}

}

$online_str = $template->get_loop ("user_online");



$online_today_str = $template->get_loop ("online_today");







$final_online = NULL;



$final_online_today = NULL;







$a_result = mysql_query("SELECT * FROM online WHERE viewtopic='". $_GET[id] ."' AND isonline='1'");



$total_count = mysql_num_rows($a_result);







$b_result = mysql_query("SELECT * FROM online WHERE viewtopic='". $_GET[id] ."' AND guest='0' AND isonline='1'");



$member_count = mysql_num_rows($b_result);







$c_result = mysql_query("SELECT * FROM online WHERE viewtopic='". $_GET[id] ."' AND guest='1' AND isonline='1'");



$guest_count = mysql_num_rows($c_result);





if($member_count<=1){



	$onlinenow_count = null;



} else {



	$onlinenow_count = TRUE;



}







$d_result = mysql_query("SELECT * FROM online WHERE viewtopic='". $_GET[id] ."' AND guest='0' AND isonline='1'");
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
	$mini_template->set_template ("online_level", $o_level);
	$mini_template->set_template ("online_posting", $online_posting);
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
