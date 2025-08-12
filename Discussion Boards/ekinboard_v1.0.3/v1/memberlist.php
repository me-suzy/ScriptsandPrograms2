<?PHP



include "config.php";

include "updateonline.php";



$page_name = "Member List";

updateonline('0','0','0', $_userid, $_username, $page_name);



$final_letter_str = NULL;

$final_pages_str = NULL;

$final_page_number_str = NULL;

$final_last_page_str = NULL;

$onlinetoday_count = NULL;

$a = NULL;

$b = NULL;



if(!isset($_GET['id'])){

	$_GET['id'] = NULL;

}

if(!isset($_GET['s'])){

	$_GET['s'] = NULL;

}

if(!isset($_GET['sort'])){

	$_GET['sort'] = NULL;

}



	$mtime = microtime();

	$mtime = explode(" ",$mtime);

	$mtime = $mtime[1] + $mtime[0];

	$starttime = $mtime;



include ("class/template.class.php");

include ("class/mini_template.class.php");



$template = new Template ();



$template->add_file ("header.tpl");

$template->add_file ("memberlist.tpl");



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

$_sort = $_GET['sort'];



if((strtolower($_sort) == "username") || (strtolower($_sort) == "level") || (strtolower($_sort) == "joined")){

	if(strtolower($_sort) == "username"){

		$_order = "ASC";

	} else if(strtolower($_sort) == "group"){

		$_sort = "level";

		$_order = "DESC";

	} else if(strtolower($_sort) == "joined"){

		$_sort = "signup_date";

		$_order = "ASC";

	}

} else {

	$_sort = "level";

	$_order = "DESC";

}



$letters_str = $template->get_loop ("letters");

$letter_str = $template->get_loop ("letter");



$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');



foreach ($alphabet as $letter) {

	$mini_template = new MiniTemplate ();

	$mini_template->template_html = $letter_str;



	$s = strtoupper($_GET['s']);



	if(strtoupper($letter) == $s){

		$current_letter = 2;

	} else {

		$current_letter = 1;

	}



	$mini_template->set_template ("letter", $letter);

	$mini_template->set_template ("current_letter", $current_letter);



	$final_letter_str .= $mini_template->return_html ();

}

	$template->end_loop ("letters", $letters_str);

	$template->end_loop ("letter", $final_letter_str);



$page_result = mysql_query("SELECT * FROM users WHERE username LIKE '". $s ."%'");

$numrows = mysql_num_rows($page_result);



$replies_str = $template->get_loop ("member_list");

$reply_str = $template->get_loop ("members");

$final_replies_html = NULL;



$display_num = 40;

$limitnum = 2;



$query_rows = "SELECT * FROM users WHERE username LIKE '". $_GET[s] ."%'";

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



				$mini_template->set_template ("id", $_GET['id']);

				$mini_template->set_template ("page_num", 1);



				$final_first_page_str .= $mini_template->return_html ();

				$template->end_loop ("first_page", $final_first_page_str);



				$a = 1;

			} else if(($i > $current_page + $limitnum) && ($b == NULL)){

				$last_page_str = $template->get_loop ("last_page");



				$mini_template = new MiniTemplate ();

				$mini_template->template_html = $last_page_str;



				$mini_template->set_template ("id", $_GET['id']);

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



$evenmember_count = 0;



if($numrows==0){

	$template->end_loop ("member_list", "");

} else {





	$r_result = mysql_query("select * from users WHERE username LIKE '". $_GET[s] ."%' ORDER BY ". $_sort ." ". $_order ." LIMIT ". $db_page_num .", ". $display_num);

	$r_count = mysql_num_rows($r_result);

	while($row=mysql_fetch_array($r_result)){



	$member_id = $row['id'];

	$total_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $member_id ."'");
	$total_votescount = mysql_num_rows($total_votescount_result);

	$bad_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $member_id ."' AND value='bad'");
	$bad_votescount = mysql_num_rows($bad_votescount_result);

	$good_votescount_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $member_id ."' AND value='good'");
	$good_votescount = mysql_num_rows($good_votescount_result);

	$check_voted_result = mysql_query("SELECT * FROM votes WHERE type='member' AND id_to='". $member_id ."' AND id_from='". $_userid ."'");
	$check_voted = mysql_num_rows($check_voted_result);

	if($total_votescount != 0){
		$good_votes = $good_votescount / $total_votescount * 3;
		$good_votes = round($good_votes, 0);
		$bad_votes = $bad_votescount / $total_votescount * -3;
		$bad_votes = round($bad_votes, 0);
		
		$rounded_votenum = $bad_votes + $good_votes;
		
		$vote_img = "<img src=templates/". $user[theme] ."/images/vote_". $rounded_votenum .".gif alt='Total Ratings: ". $total_votescount ." ( ". $bad_votescount ." Not Helpful, ". $good_votescount ." Helpful )''>";
	} else {
		$vote_img = "<img src='templates/". $user[theme] ."/images/vote_null.gif' alt='' border=0>";
	}

	$member_name = $row['username'];

	$member_joined = $row['signup_date'];

	$member_joined = date("M jS, Y", strtotime($member_joined, "\n"));

	$member_www = $row['website_url'];

	$member_aim = $row['aim'];

	$member_msn = $row['msn'];

	$member_yahoo = $row['yahoo'];

	$member_icq = $row['icq'];

	$member_group = $row['level'];

		$q1 = mysql_query("SELECT * FROM replies WHERE poster='". $member_name ."'");

		$q2 = mysql_query("SELECT * FROM topics WHERE poster='". $member_name ."'");

	$member_posts = mysql_num_rows($q1) + mysql_num_rows($q2);



	switch ($member_group) {

		case 0:

		   $member_group = "Test Account";

		   break;

		case 1:

		   $member_group = "Member";

   			break;

		case 2:

		   $member_group = "Moderator";

		   break;

		case 3:

		   $member_group = "Administrator";

		   break;

	}

	if($member_posts<50){

		$member_level = 1;

	} else if($member_posts>=50 && $member_posts<100){

		$member_level = 2;

	} else if($member_posts>=100 && $member_posts<250){

		$member_level = 3;

	} else if($member_posts>=250 && $member_posts<500){

        $member_level = 4;

    } else if($member_posts>=500){

        $member_level = 5;

    }



	if(!($evenmember_count % 2) == TRUE){

		$even_member = 1;

	} else {

		$even_member = 2;

	}



	$evenmember_count = $evenmember_count + 1;



	$mini_template = new MiniTemplate ();



	$mini_template->template_html = $reply_str;


	$mini_template->set_template ("member_id", $member_id);

	$mini_template->set_template ("vote_img", $vote_img);

	$mini_template->set_template ("member_name", $member_name);

	$mini_template->set_template ("member_level", $member_level);

	$mini_template->set_template ("member_group", $member_group);

	$mini_template->set_template ("member_joined", $member_joined);

	$mini_template->set_template ("member_posts", $member_posts);

	$mini_template->set_template ("member_name", $member_name);

	$mini_template->set_template ("member_www", $member_www);

	$mini_template->set_template ("member_aim", $member_aim);

	$mini_template->set_template ("member_msn", $member_msn);

	$mini_template->set_template ("member_yahoo", $member_yahoo);

	$mini_template->set_template ("member_icq", $member_icq);

	$mini_template->set_template ("even_member", $even_member);



	$final_replies_html .= $mini_template->return_html ();



}



$template->end_loop ("member_list", $template->end_loop ("members", $final_replies_html, $replies_str));



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