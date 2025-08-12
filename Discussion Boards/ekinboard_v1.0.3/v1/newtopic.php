<?php

include ("config.php");

include ("updateonline.php");

$page_name = "Post Topic";

	$mtime = microtime();


	$mtime = explode(" ",$mtime);


	$mtime = $mtime[1] + $mtime[0];


	$starttime = $mtime;


// Need to make sure the user has access to view this page


	$top_f_result = mysql_query("select * from forums where id='". $_GET[id] ."'");

	$row = mysql_fetch_array($top_f_result);

	$top_c_id = $row['cid'];

	$top_f_sub = $row['subforum'];

	$top_f_name = $row['name'];

	$top_f_hidden = $row['hidden'];

	$top_f_protected = $row['protected'];

	$top_f_news = $row['news'];

	$top_f_rlevel = $row['restricted_level'];



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


//////////////////////////

	include ("class/template.class.php");

	include ("class/db.class.php");

	include ("class/mini_template.class.php");


	$template = new Template ();

	$db = new db ($db_host, $db_user, $db_pass, $db_name);


	$template->add_file ("header.tpl");



	$template->add_file ("newtopic.tpl");

	$template->set_template ("template", $user["theme"]);

	$template->set_template ("forum_id", ($cat_access_level > $_userlevel) ? '' : $_GET['id']);

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



	$n_result = mysql_query("SELECT * FROM topics WHERE fid='$nidi'");



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


	$template->set_template ("forum_name", ($cat_access_level > $_userlevel) ? '' : $top_f_name);







	$top_c_result = mysql_query("select * from categories where id='". $top_c_id ."'");



	$row = mysql_fetch_array($top_c_result);



	$top_c_name = $row['name'];







	$template->set_template ("category_name", ($cat_access_level > $_userlevel) ? '' : $top_c_name);



	$template->set_template ("category_id", ($cat_access_level > $_userlevel) ? '' : $top_c_id);







updateonline('0',$_GET['id'],'1', $_userid, $_username, $page_name);







	//////////////////////////////////////////////////////////////////////////////////////



$error_str = $template->get_loop ("error");



$reply_str = $template->get_loop ("newtopic");
$attch_str = $template->get_loop ("attch");






$forum_check = mysql_query("SELECT * FROM forums WHERE id='". $_GET[id] ."'");



$forum_check_count = mysql_num_rows($forum_check);







if($forum_check_count != 0){



	if($_username == NULL){



		$template->end_loop ("newtopic", "");


		$mini_template=new MiniTemplate;



		$mini_template->template_html=$error_str;



		$mini_template->set_template ("error_message", "Please login first!");



		$template->end_loop ("error", $mini_template->return_html ());



	} else {



		if (($cat_access_level > $_userlevel) || ($top_f_rlevel > $_userlevel)) { // They cannot access the page



			$template->end_loop ("newtopic", "");



			$mini_template=new MiniTemplate;



			$mini_template->template_html=$error_str;

			if($top_f_rlevel > $_userlevel){
				$error_message = "You do not  have sufficient access to create a new topic in this forum.";
			} else if($cat_access_level > $_userlevel){
				$error_message = "The topic you have selected does not exist!";
			}

			$mini_template->set_template ("error_message", $error_message);



			$template->end_loop ("error", $mini_template->return_html ());



            } else {



		$checkresult = mysql_query("select * from forums where id='". $_GET[id] ."'");



		$checkcount = mysql_num_rows($checkresult);







		if($checkcount == 0){



			$template->end_loop ("newtopic", "");

			$mini_template=new MiniTemplate;



			$mini_template->template_html=$error_str;



			$mini_template->set_template ("error_message", "The forum you have selected does not exist!");


			$template->end_loop("error", $mini_template->return_html ());



		} else {
			$iserror = FALSE;


			if($_GET['d']=="post"){

				$submitted_title = $_POST['topic_title'];

				$submitted_description = $_POST['topic_description'];

				$submitted_message = $_POST['message'];

				//$r_message = htmlspecialchars($r_message);


	            $t_poster = $_username;



				if(($submitted_title == "") || ($submitted_message == "") || ($attch_error == 1)){

					if($submitted_title == ""){

						$title_error = 1;

						$title_error_message = "The topic title has been left blank!";
						$iserror = TRUE;
	
					} else {
						$message_error = 0;
					}

					if($submitted_message == ""){

						$message_error = 1;
						$message_error_message = "The message has been left blank!";
						$iserror = TRUE;

					} else {

						$message_error = 0;

					}

				} else {
					if(($_SETTING['allow_attch'] == 1) && ($_FILES['attachment']['name'] != NULL)){
						$max_size = $_SETTING['attch_max_size'];  // IMAGE SIZE - 500kb
						$uploaddir = "uploaded/attachments"; // DIRECTORY CHMOD TO 0777
						$allowed_ext = $_SETTING['attch_exts'];  // EXTENSIONS ALLOWED
	
						$extension = pathinfo($_FILES['attachment']['name']);
						$extension = $extension[extension];
						$allowed_paths = explode(", ", $allowed_ext);
	
						for($i = 0; $i < count($allowed_paths); $i++) {
							if ($allowed_paths[$i] == "$extension") {
							$ok = "1";
							}
						}

						if ($ok == "1"){
							if($_FILES['attachment']['size'] > $max_size){
								$attch_error = 1;
								$attch_error_message = "The file your are trying to upload is too big!";
								$iserror = TRUE;
							} else {

							// The Upload Part
							if(is_uploaded_file($_FILES['attachment']['tmp_name']))	{
	
								move_uploaded_file($_FILES['attachment']['tmp_name'],$uploaddir.'/tmp/'.$_FILES['attachment']['name']);
	
								// GET FILE NAME
								list($filename, $extinfo) = explode(".", $_FILES['attachment']['name']);
	
								// RENAMING FILES TO USERS USERNAME
								$old="" . $uploaddir . "/tmp/".$_FILES['attachment']['name'];
								$new="" . $uploaddir . "/tmp/" . $_FILES['attachment']['name'] . "";
								$newdir="" . $uploaddir . "/" . $_FILES['attachment']['name'] . "";
	
								if(!@copy($old, $newdir)){
									unlink($old);
									$attch_error = 1;
			
									$attch_error_message = "Error uploading attachment!";	
									$iserror = TRUE;
								}
	
								unlink($old);
	
								$modlink = $newfilename;
								$f_type = '0';
	
							} }
	
						} else {
							$attch_error = 1;
	
							$attch_error_message = "Incorrect file type!";
							$iserror = TRUE;
						}
					}

				}

					if($iserror != TRUE){
					
										$t_date = date("Y-m-d");					
										$t_time = date("H:i:s a");					
										$t_datetime = $t_date ." ". $t_time;
					
					
					
					
										$result=MYSQL_QUERY("INSERT INTO topics (fid,title,description,message,poster,date,last_post,datesort)".
					
										"VALUES ('". $_GET[id] ."', '". $submitted_title ."', '". $submitted_description ."', '". $submitted_message ."', '". $t_poster ."', '". $t_date ."', '". $t_date ."', '". $t_datetime ."')");
					
					
										$t_id = mysql_insert_id();
					
										if(($_SETTING['allow_attch'] == 1) && ($_FILES['attachment']['name'] != NULL)){
											$newfilename= "" . $filename . "_" . $t_id . "." . $extension . "";
											$old = "" . $uploaddir . "/" . $_FILES['attachment']['name'];
											$new = "" . $uploaddir . "/" . $newfilename . "";
											rename($old,$new);

											$result=MYSQL_QUERY("UPDATE topics SET attch_name='$newfilename', attch_size='". $_FILES['attachment']['size'] ."', attch_type='$extension' WHERE id='$t_id'");
										}
				
										header("Location: viewtopic.php?id=$t_id");
					} else {
										$mini_template=new template;
					
					
					
										$mini_template->template_html=$reply_str;
					
					
					
										$mini_template->set_template ("id", $_GET['id']);
					
					
					
										$mini_template->set_template ("submitted_title", $submitted_title);
					
					
					
										$mini_template->set_template ("submitted_description", $submitted_description);
					
					
					
										$mini_template->set_template ("submitted_message", $submitted_message);
					

										$mini_template->set_template ("title_error", $title_error);
					
					
					
										$mini_template->set_template ("title_error_message", $title_error_message);
					
					
					
										$mini_template->set_template ("description_error", $description_error);
					
					
					
										$mini_template->set_template ("description_error_message", $description_error_message);
					
					
					
										$mini_template->set_template ("message_error", $message_error);
					
					
					
										$mini_template->set_template ("message_error_message", $message_error_message);
					
					
			
					
										$last_reply = $mini_template->end_page ();
					
					
					
										$template->end_loop("newtopic", $last_reply);
					
										if($_SETTING['allow_attch'] == 1){
											echo $attach_str;
											$template->end_loop ("attch", $attch_str);
											$template->set_template ("attch_error", $attch_error);
											$template->set_template ("attch_error_message", $attch_error_message);
										} else {
											$template->end_loop ("attch", "");
										}
					
										$template->end_loop ("error", "");
					}


			} else {







				$mini_template=new template;



				$mini_template->template_html=$reply_str;







				$mini_template->set_template ("id", $_GET['id']);



				$mini_template->set_template ("submitted_title", $submitted_title);



				$mini_template->set_template ("submitted_description", $submitted_description);



				$mini_template->set_template ("submitted_message", $submitted_message);

				$mini_template->set_template ("submitted_attch", $submitted_attch);

				$mini_template->set_template ("title_error", $title_error);

				$mini_template->set_template ("attch_error", $attch_error);

				$mini_template->set_template ("attch_error_message", $attch_error_message);

				$mini_template->set_template ("title_error_message", $title_error_message);



				$mini_template->set_template ("description_error", $description_error);



				$mini_template->set_template ("description_error_message", $description_error_message);



				$mini_template->set_template ("message_error", $message_error);



				$mini_template->set_template ("message_error_message", $message_error_message);







				$last_reply = $mini_template->end_page ();







				$template->end_loop("newtopic", $last_reply);

										if($_SETTING['allow_attch'] == 1){
											$template->end_loop ("attch", $attch_str);
											$template->set_template ("attch_error", $attch_error);
				
											$template->set_template ("attch_error_message", $attch_error_message);
										} else {
											$template->end_loop ("attch", "");
										}

				$template->end_loop ("error", "");



			}



		}



		}



	}



} else {



	$template->end_loop ("newtopic", "");



	$mini_template=new MiniTemplate;



	$mini_template->template_html=$error_str;



	$mini_template->set_template ("error_message", "The forum you have selected does not exist!");



	$template->end_loop ("error", $mini_template->return_html ());



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