<?PHP



include "config.php";

include "updateonline.php";



function str_highlight($text, $needle, $options = null, $highlight = null)

{

    // Default highlighting

    if ($highlight === null) {

        $highlight = '[hilight]\1[/hilight]';

    }



    // Select pattern to use

    if ($options & STR_HIGHLIGHT_SIMPLE) {

        $pattern = '#(%s)#';

        $sl_pattern = '#(%s)#';

    } else {

        $pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';

        $sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';

    }



    // Case sensitivity

    if (!($options & STR_HIGHLIGHT_CASESENS)) {

        $pattern .= 'i';

        $sl_pattern .= 'i';

    }



    $needle = (array) $needle;

    foreach ($needle as $needle_s) {

        $needle_s = preg_quote($needle_s);



        // Escape needle with optional whole word check

        if ($options & STR_HIGHLIGHT_WHOLEWD) {

            $needle_s = '\b' . $needle_s . '\b';

        }



        // Strip links

        if ($options & STR_HIGHLIGHT_STRIPLINKS) {

            $sl_regex = sprintf($sl_pattern, $needle_s);

            $text = preg_replace($sl_regex, '\1', $text);

        }



        $regex = sprintf($pattern, $needle_s);

        $text = preg_replace($regex, $highlight, $text);

    }



    return $text;

}





$page_name = "Searching";

updateonline('0','0','0', $_userid, $_username, $page_name);



	$mtime = microtime();

	$mtime = explode(" ",$mtime);

	$mtime = $mtime[1] + $mtime[0];

	$starttime = $mtime;





include ("class/template.class.php");

include ("class/mini_template.class.php");



$template = new Template ();



$template->add_file ("header.tpl");

$template->add_file ("search.tpl");



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



} else {

	$logged_in = 0;

	$_userlevel = 1;

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



$template->set_template ("search_query", $_GET[query]);



$mini_menu_guest = $template->get_loop ("guest_mini_menu");

$mini_menu_registered = $template->get_loop ("registered_mini_menu");

$mini_menu_admin = $template->get_loop ("admin_mini_menu");





$sql_a = "(SELECT id, datesort, 'topic' AS tablename FROM topics WHERE MATCH(title, message) AGAINST('". $_GET[query] ."'))

			UNION ALL

			(SELECT id, datesort, 'reply' AS tablename FROM replies WHERE MATCH(message) AGAINST('". $_GET[query] ."')) ORDER BY datesort";



$results_a = mysql_query($sql_a) or die(mysql_error());



$num_rows = 0;

$num_topics = 0;

$num_replies = 0;

$i = 0;



$limitnum = 2;

$display_num = 10;



if(isset($_GET[page])){

	$page_page_num = $_GET[page];

} else {

	$page_page_num = 1;

}



$sql_start = $page_page_num - 1;

$sql_start = $sql_start * $display_num;



$sql_end = $sql_start + $display_num;



if(isset($_userlevel)){

	$ul = $_userlevel;

} else {

	$ul = 1;

}



$result_str = $template->get_loop ("result");



        while($r = MySQL_fetch_array($results_a)) {

			if($r[2] == "topic"){

				$sql_b = "SELECT * FROM topics WHERE id='". $r[0] ."'";

				$results_b = mysql_query($sql_b) or die(mysql_error());



				$rr = MySQL_fetch_array($results_b);



				if(eregi("f_", $_GET[in])){

					$tmp_fid = str_replace("f_", "", $_GET[in]);

				} else {

					$tmp_fid = "all";

				}



				if(($tmp_fid == $rr[fid]) || ($tmp_fid == "all")){

					$sql_c = "SELECT * FROM forums WHERE id='". $rr[fid] ."'";

					$results_c = mysql_query($sql_c) or die(mysql_error());



					$rrr = MySQL_fetch_array($results_c);



					if(eregi("c_", $_GET[in])){

						$tmp_cid = str_replace("c_", "", $_GET[in]);

					} else {

						$tmp_cid = "all";

					}



					if(($tmp_cid == $rrr[cid]) || ($tmp_cid == "all")){

						$sql_e = "SELECT * FROM categories WHERE id='". $rrr[cid] ."'";

						$results_e = mysql_query($sql_e) or die(mysql_error());



						$rrrrr = MySQL_fetch_array($results_e);



						if(($rrrrr[level_limit]<=$ul) || ($is_moderator == TRUE)){

							$i = $i + 1;

							$num_rows = $num_rows + 1;

							$num_topics = $num_topics + 1;

							if(($i>=$sql_start) && ($i<=$sql_end)){



								$getuser_result = mysql_query("select * from users where username='". $rr[poster] ."'");



								$row = mysql_fetch_array($getuser_result);



								$poster_id = $row['id'];



								$poster_joined = $row['signup_date'];



								$poster_joined = date("M jS, Y", strtotime($poster_joined, "\n"));



								$poster_www = $row['website_url'];



								$poster_aim = $row['aim'];



								$poster_msn = $row['msn'];



								$poster_yahoo = $row['yahoo'];



								$poster_icq = $row['icq'];



								$poster_ava = $row['avatar'];



								$poster_level = $row['level'];



								$member_title = $row['title'];



									$q1 = mysql_query("SELECT * FROM replies WHERE poster='". $rr[poster] ."'");



									$q2 = mysql_query("SELECT * FROM topics WHERE poster='". $rr[poster] ."'");



								$poster_posts = mysql_num_rows($q1) + mysql_num_rows($q2);







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

									$hilight_start = "<b>";

									$hilight_end = "</b>";



									$tmp_message = str_highlight($rr[message],$_GET[query]);



									$mini_template = new MiniTemplate ();

									$mini_template->template_html = $result_str;



									$mini_template->set_template ("title", $rr[title]);



									$mini_template->set_template ("topic_id", $rr[id]);



									$mini_template->set_template ("message", ekincode($tmp_message, $user["theme"]));



									$mini_template->set_template ("date", date("l, F jS, Y", strtotime($rr[date], "\n")));



									$mini_template->set_template ("poster", $rr[poster]);



									$mini_template->set_template ("posts", $poster_posts);



									$mini_template->set_template ("level", $poster_level);



									$mini_template->set_template ("poster_id", $poster_id);



									$mini_template->set_template ("locked", $t_locked);



									$mini_template->set_template ("member_title", $member_title);



									$mini_template->set_template ("member_number", number_format($poster_id));



									$mini_template->set_template ("member_joined", $poster_joined);



									$mini_template->set_template ("poster_www", $poster_www);



									$mini_template->set_template ("poster_aim", $poster_aim);



									$mini_template->set_template ("poster_msn", $poster_msn);



									$mini_template->set_template ("poster_yahoo", $poster_yahoo);



									$mini_template->set_template ("poster_icq", $poster_icq);



									$mini_template->set_template ("post_num", $post_number);



									$mini_template->set_template ("post_page", $current_page);



									$final_result_html .= $mini_template->return_html ();



							}

						}

					}

				}

			} else if($r[2] == "reply"){

				$sql_b = "SELECT * FROM replies WHERE id='". $r[0] ."'";

				$results_b = mysql_query($sql_b) or die(mysql_error());



				$rr = MySQL_fetch_array($results_b);



				$sql_c = "SELECT * FROM topics WHERE id='". $rr[tid] ."'";

				$results_c = mysql_query($sql_c) or die(mysql_error());



				$rrr = MySQL_fetch_array($results_c);



				if(eregi("f_", $_GET[in])){

					$tmp_fid = str_replace("f_", "", $_GET[in]);

				} else {

					$tmp_fid = "all";

				}



				if(($tmp_fid == $rrr[fid]) || ($tmp_fid == "all")){

					$sql_d = "SELECT * FROM forums WHERE id='". $rrr[fid] ."'";

					$results_d = mysql_query($sql_d) or die(mysql_error());



					$rrrr = MySQL_fetch_array($results_d);



					if(eregi("c_", $_GET[in])){

						$tmp_cid = str_replace("c_", "", $_GET[in]);

					} else {

						$tmp_cid = "all";

					}



					if(($tmp_cid == $rrrr[cid]) || ($tmp_cid == "all")){

						$sql_e = "SELECT * FROM categories WHERE id='". $rrrr[cid] ."'";

						$results_e = mysql_query($sql_e) or die(mysql_error());



						if($rrrrr[level_limit]<=$ul){

							$i = $i + 1;

							$num_rows = $num_rows + 1;

							$num_replies = $num_replies + 1;

							if(($i>=$sql_start) && ($i<=$sql_end)){

								$getuser_result = mysql_query("select * from users where username='". $rr[poster] ."'");



								$row = mysql_fetch_array($getuser_result);



								$poster_id = $row['id'];



								$poster_joined = $row['signup_date'];



								$poster_joined = date("M jS, Y", strtotime($poster_joined, "\n"));



								$poster_www = $row['website_url'];



								$poster_aim = $row['aim'];



								$poster_msn = $row['msn'];



								$poster_yahoo = $row['yahoo'];



								$poster_icq = $row['icq'];



								$poster_ava = $row['avatar'];



								$poster_level = $row['level'];



								$member_title = $row['title'];



									$q1 = mysql_query("SELECT * FROM replies WHERE poster='". $rr[poster] ."'");



									$q2 = mysql_query("SELECT * FROM topics WHERE poster='". $rr[poster] ."'");



								$poster_posts = mysql_num_rows($q1) + mysql_num_rows($q2);







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

									$hilight_start = "<b>";

									$hilight_end = "</b>";



									$tmp_message = str_highlight($rr[message],$_GET[query]);





									$mini_template = new MiniTemplate ();

									$mini_template->template_html = $result_str;



									$mini_template->set_template ("title", $rrr[title]);



									$mini_template->set_template ("topic_id", $rrr[id]);



									$mini_template->set_template ("message", ekincode($tmp_message, $user["theme"]));



									$mini_template->set_template ("date", date("l, F jS, Y", strtotime($rr[date], "\n")));



									$mini_template->set_template ("poster", $rr[poster]);



									$mini_template->set_template ("posts", $poster_posts);



									$mini_template->set_template ("level", $poster_level);



									$mini_template->set_template ("poster_id", $poster_id);



									$mini_template->set_template ("locked", $t_locked);



									$mini_template->set_template ("member_title", $member_title);



									$mini_template->set_template ("member_number", number_format($poster_id));



									$mini_template->set_template ("member_joined", $poster_joined);



									$mini_template->set_template ("poster_www", $poster_www);



									$mini_template->set_template ("poster_aim", $poster_aim);



									$mini_template->set_template ("poster_msn", $poster_msn);



									$mini_template->set_template ("poster_yahoo", $poster_yahoo);



									$mini_template->set_template ("poster_icq", $poster_icq);



									$mini_template->set_template ("post_num", $post_number);



									$mini_template->set_template ("post_page", $current_page);



									$final_result_html .= $mini_template->return_html ();

							}

						}

					}

				}

			}

        }



$template->end_loop ("result", $final_result_html);



$result_stats_str = $template->get_loop ("result_stats");



if($num_rows > 0){

	$template->end_loop ("result_stats", $result_stats_str);



	$template->set_template ("total_results", $num_rows);

	$template->set_template ("topic_results", $num_topics);

	$template->set_template ("reply_results", $num_replies);

} else {

	$template->end_loop ("result_stats", "");

}



if ($num_rows > $display_num) {



	$num_pages = ceil ($num_rows / $display_num);



} else {



	$num_pages = 1;



}





$page_num_str = NULL;







if ($num_pages > 1) {







	$pages_str = $template->get_loop ("pages");



	$mini_template = new MiniTemplate ();



	$mini_template->template_html = $pages_str;







	$mini_template->set_template ("total_pages", $num_pages);







	$final_pages_str .= $mini_template->return_html ();



	$template->end_loop ("pages", $final_pages_str);







	$current_page = ($sql_start / $display_num) + 1;







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





$dropdown_list_str = $template->get_loop ("dropdown");



$dropdown_cat_list = mysql_query("SELECT * FROM categories ORDER BY id ASC");



$final_dropdown = NULL;



$dropdown_list = NULL;



while($dd_row = mysql_fetch_array($dropdown_cat_list)){



	$dd_cat_id = $dd_row['id'];

	$dd_cat_name = $dd_row['name'];

	$level_limit = $dd_row['level_limit'];



	if($level_limit >= '2'){



		$dropdown_list = "<option value='c_$dd_cat_id'>&nbsp;&nbsp;-&nbsp;&nbsp;$dd_cat_name</option>";



		if($_userlevel >= '2'){



			$dropdown_forum_list = mysql_query("SELECT * FROM forums WHERE subforum='0' AND hidden='0' AND cid='". $dd_cat_id ."' ORDER BY id ASC");



			while($dd_f_row = mysql_fetch_array($dropdown_forum_list)){



				$dd_name = $dd_f_row['name'];

				$dd_id = $dd_f_row['id'];



				$tmp_fid = str_replace("f_", "", $_GET[in]);



				if($dd_id == $tmp_fid){

					$selected = " SELECTED";

				}



				$dropdown_list .= "<option value='f_$dd_id'$selected>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;$dd_name</option>";



				// SUBFORUM CHECK

				$sub_dd_check = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='". $dd_id ."' AND hidden='0' ORDER BY id ASC");



				$sub_dd_count = mysql_num_rows($sub_dd_check);



				if($sub_dd_count!=='0'){



					while($sub_dd_row = mysql_fetch_array($sub_dd_check)){



						$sub_dd_id = $sub_dd_row['id'];

						$sub_dd_name = $sub_dd_row['name'];



						$tmp_fid = str_replace("f_", "", $_GET[in]);



						if($dd_id == $tmp_fid){

							$selected = " SELECTED";

						} else {

							$selected = NULL;

						}



						$dropdown_list .= "<option value='f_$sub_dd_id'$selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;$sub_dd_name</option>";



					}



				}



			}



				$mini_template = new MiniTemplate ();



				$mini_template->template_html = $dropdown_list_str;



				$mini_template->set_template ("dropdown_list", $dropdown_list);



				$final_dropdown .= $mini_template->return_html ();



		}







	} else {

			$tmp_cid = str_replace("c_", "", $_GET[in]);



			if($dd_cat_id == $tmp_cid){

				$selected = " SELECTED";

			} else {

				$selected = NULL;

			}



			$dropdown_list = "<option value='c_$dd_cat_id'$selected>&nbsp;&nbsp;-&nbsp;&nbsp;$dd_cat_name</option>";



			$dropdown_forum_list = mysql_query("SELECT * FROM forums WHERE subforum='0' AND hidden='0' AND cid='". $dd_cat_id ."' ORDER BY id ASC");



			while($dd_f_row = mysql_fetch_array($dropdown_forum_list)){



				$dd_name = $dd_f_row['name'];

				$dd_id = $dd_f_row['id'];





				$tmp_fid = str_replace("f_", "", $_GET[in]);



				if($dd_id == $tmp_fid){

					$selected = " SELECTED";

				} else {

					$selected = NULL;

				}



				$dropdown_list .= "<option value='f_$dd_id'$selected>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;$dd_name</option>";



				// SUBFORUM CHECK

				$sub_dd_check = mysql_query("SELECT * FROM forums WHERE subforum='1' AND cid='". $dd_id ."' AND hidden='0' ORDER BY id ASC");



				$sub_dd_count = mysql_num_rows($sub_dd_check);



				if($sub_dd_count!=='0'){



					while($sub_dd_row = mysql_fetch_array($sub_dd_check)){



						$sub_dd_id = $sub_dd_row['id'];

						$sub_dd_name = $sub_dd_row['name'];



						$tmp_fid = str_replace("f_", "", $_GET[in]);



						if($sub_dd_id == $tmp_fid){

							$selected = " SELECTED";

						} else {

							$selected = NULL;

						}



						$dropdown_list .= "<option value='f_$sub_dd_id'$selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;$sub_dd_name</option>";



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