<?PHP
include "config.php";
include "updateonline.php";

$page_name = "Mailbox";
updateonline('0','0','0', $_userid, $_username, $page_name);

	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$starttime = $mtime;

include ("class/template.class.php");
include ("class/mini_template.class.php");

$template = new Template ();

$template->add_file ("header.tpl");
$template->add_file ("mailbox.tpl");

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

	if($_GET['act'] == "delete"){
		if($_GET['folder'] == "inbox"){
			if($_POST['message'] != NULL){
				$ids_to_delete = implode(",", $_POST['message']);

				$query = "UPDATE inbox SET reciever_delete='1' WHERE id IN (". $ids_to_delete .") AND reciever_id='". $_userid ."'";
				mysql_query($query) or die("Delete query failed: " . mysql_error());
			} else if($_POST['inbox_message_id'] != NULL){
				$message_id = $_POST['inbox_message_id'];

				$query = "UPDATE inbox SET reciever_delete='1' WHERE id='". $message_id ."' AND reciever_id='". $_userid ."'";
				mysql_query($query) or die("Delete query failed: " . mysql_error());
			}
		}
	}

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

$template->end_loop ("new_message", "");

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

$compose_str = $template->get_loop ("compose");
$message_sent_str = $template->get_loop ("message_sent");
$inbox_str = $template->get_loop ("inbox");
$sent_str = $template->get_loop ("sent");
$error_str = $template->get_loop ("error");


if ($logged_in == 1) {
if($_GET[act] == "compose"){
		$m_reciever = $_POST['message_to'];
		$m_reciever = htmlspecialchars($m_reciever);
		$m_subject = $_POST['message_subject'];
		$m_subject = htmlspecialchars($m_subject);
		$m_message = $_POST['message_text'];
		$m_message = htmlspecialchars($m_message);
		$m_sender = $_username;

	if($_GET[send] == "true"){
		if(($m_reciever != null) && ($m_subject!=null) && ($m_message!=null)){
	        if (strtolower($_POST[message_to]) != strtolower($_username)){
				$m_date = date("Y-m-d");
				$m_time = date("g:i:s a");
				$m_datetime = $m_date ." ". $m_time;

				$get_result = mysql_query("select * from users where username='". $m_reciever ."'");

				$member_check = @mysql_num_rows($get_result);

				if($member_check!=0){
					$row = mysql_fetch_array($get_result);
					$get_id = $row['id'];

					$result=MYSQL_QUERY("INSERT INTO inbox (sender,sender_id,reciever_id,subject,message,date,datesort)".
					"VALUES ('". $_username ."', '". $_userid ."', '". $get_id ."', '". $m_subject ."', '". $m_message ."', '". $m_date ."', '". $m_datetime ."')");

					$m_id = mysql_insert_id();

					header("Location: mailbox.php?act=compose&send=done&id=$m_id");
				} else {
					$to_error = 1;
					$to_error_message = "The user you have selected does not exist!";
				}
			} else {
				$to_error = 1;
				$to_error_message = "You can not send messages to yourself!";
			}
		} else {
			if($_POST[message_to] == NULL){
				$to_error = 1;
				$to_error_message = "Please enter the recipient!";
			}
			if($_POST[message_subject] == NULL){
				$subject_error = 1;
				$subject_error_message = "You need to have a subject!";
			}
			if($_POST[message_text] == NULL){
				$message_error = 1;
				$message_error_message = "Please enter your message!";
			}
		}

		$template->set_template ("to_error", $to_error);
		$template->set_template ("to_error_message", $to_error_message);
		$template->set_template ("submitted_to", $_POST[message_to]);
		$template->set_template ("subject_error", $subject_error);
		$template->set_template ("subject_error_message", $subject_error_message);
		$template->set_template ("submitted_subject", $_POST[message_subject]);
		$template->set_template ("message_error", $message_error);
		$template->set_template ("message_error_message", $message_error_message);
		$template->set_template ("submitted_message", $_POST[message_text]);

		$template->end_loop ("compose", $compose_str);
		$template->end_loop ("inbox", "");
		$template->end_loop ("sent", "");
		$template->end_loop ("read_inbox", "");
		$template->end_loop ("read_sent", "");
		$template->end_loop ("message_sent", "");
		$template->end_loop ("pages", "");
		$template->end_loop ("error", "");
	} else if($_GET[send] == "done"){
				$get_result = mysql_query("select * from inbox where id='". $_GET[id] ."' AND sender_id='". $_userid ."'");
				$message_check = @mysql_num_rows($get_result);

				if($message_check!=0){
					$row = mysql_fetch_array($get_result);
					$get_id = $row['reciever_id'];

					$get_result = mysql_query("select * from users where id='". $get_id ."'");
					$row = mysql_fetch_array($get_result);
					$get_reciever = $row['username'];

					$template->set_template ("reciever_id", $get_id);
					$template->set_template ("reciever", $get_reciever);

					$template->end_loop ("compose", "");
					$template->end_loop ("inbox", "");
					$template->end_loop ("sent", "");
					$template->end_loop ("read_inbox", "");
					$template->end_loop ("read_sent", "");
					$template->end_loop ("message_sent", $message_sent_str);
					$template->end_loop ("pages", "");
					$template->end_loop ("error", "");
				} else {
					$template->set_template ("error_message", "The message you have selected does not exist!");

					$template->end_loop ("compose", "");
					$template->end_loop ("inbox", "");
					$template->end_loop ("sent", "");
					$template->end_loop ("read_inbox", "");
					$template->end_loop ("read_sent", "");
					$template->end_loop ("message_sent", "");
					$template->end_loop ("pages", "");
					$template->end_loop ("error", $error_str);
				}
	} else {
			if($_GET[to] != NULL){
				$m_reciever = $_GET[to];
			}
			if(($_GET[d] == "reply") && ($_GET[id] != NULL)){

				$m_result = mysql_query("select * from inbox where id='". $_GET[id] ."' AND reciever_id='". $_userid ."'");
				$message_check = mysql_num_rows($m_result);

				if($message_check != 0){
					$row=mysql_fetch_array($m_result);

					$m_reciever = $row[sender];
					$m_subject = "RE: ". $row[subject];
					$m_message = "[QUOTE=". $row[sender] ."]". $row[message] ."[/QUOTE]\n\n";
				}
			}
		$template->set_template ("to_error", $to_error);
		$template->set_template ("to_error_message", $to_error_message);
		$template->set_template ("submitted_to", $m_reciever);
		$template->set_template ("subject_error", $subject_error);
		$template->set_template ("subject_error_message", $subject_error_message);
		$template->set_template ("submitted_subject", $m_subject);
		$template->set_template ("message_error", $message_error);
		$template->set_template ("message_error_message", $message_error_message);
		$template->set_template ("submitted_message", $m_message);

		$template->end_loop ("compose", $compose_str);
		$template->end_loop ("inbox", "");
		$template->end_loop ("sent", "");
		$template->end_loop ("read_inbox", "");
		$template->end_loop ("read_sent", "");
		$template->end_loop ("message_sent", "");
		$template->end_loop ("pages", "");
		$template->end_loop ("error", "");
	}
} else {
	if($_GET[folder]=="sent"){

		$read_sent_str = $template->get_loop ("read_sent");

		if(($_GET[act] == "read") && ($_GET[folder] == "sent") && ($_GET[id] != NULL)){

			$m_result = mysql_query("select * from inbox where id='". $_GET[id] ."' AND sender_id='". $_userid ."' AND sender_delete='0'");
			$message_check = mysql_num_rows($m_result);

			if($message_check != 0){
				$row=mysql_fetch_array($m_result);

				$template->end_loop ("sent", "");
				$template->end_loop ("inbox", "");
				$template->end_loop ("compose", "");
				$template->end_loop ("message_sent", "");
				$template->end_loop ("read_inbox", "");
				$template->end_loop ("pages", "");
				$template->end_loop ("error", "");

				$message_id = $row['id'];
				$message_reciever_id = $row['reciever_id'];
				$message_title = $row['subject'];
				$message_date = date("l, F jS, Y", strtotime($row['date'], "\n"));
				$message_text = ekincode($row['message'], $user[theme]);

				$u_result = mysql_query("select * from users where id='". $message_reciever_id ."'");
				$u_row=mysql_fetch_array($u_result);

				$message_reciever = $u_row['username'];

			 	$mini_template = new MiniTemplate ();

				$mini_template->template_html = $read_sent_str;

				$mini_template->set_template ("message_id", $message_id);
				$mini_template->set_template ("message_reciever_id", $message_reciever_id);
				$mini_template->set_template ("message_reciever", $message_reciever);
				$mini_template->set_template ("message_title", $message_title);
				$mini_template->set_template ("message_date", $message_date);
				$mini_template->set_template ("message_text", $message_text);

				$final_message_html .= $mini_template->return_html ();

				$template->end_loop ("read_sent", $final_message_html);
			} else {
				$template->set_template ("error_message", "The message you have selected does not exist!");

				$template->end_loop ("sent", "");
				$template->end_loop ("inbox", "");
				$template->end_loop ("compose", "");
				$template->end_loop ("read_inbox", "");
				$template->end_loop ("read_sent", "");
				$template->end_loop ("message_sent", "");
				$template->end_loop ("pages", "");
				$template->end_loop ("error", $error_str);
			}
		} else {
			$sent_message_str = $template->get_loop ("sent_message");

			$evenmessage_count = 0;

			$template->set_template ("folder", $_GET[folder]);
			$template->set_template ("id", $_GET[id]);

			$page_result = mysql_query("select * from inbox where sender_id='". $_userid ."' AND sender_delete='0' ORDER BY datesort DESC");
			$numrows = mysql_num_rows($page_result);

			$replies_str = $template->get_loop ("replies");
			$reply_str = $template->get_loop ("reply");
			$final_replies_html = NULL;

			$display_num = 20;
			$limitnum = 2;

			$query_rows = "select * from inbox where sender_id='". $_userid ."' AND sender_delete='0' ORDER BY datesort DESC";
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

			$m_result = mysql_query("select * from inbox where sender_id='". $_userid ."' AND sender_delete='0' ORDER BY datesort DESC LIMIT ". $db_page_num .", ". $display_num ."");
			while($row=mysql_fetch_array($m_result)){
				if(!($evenmessage_count % 2) == TRUE){
					$evenmessage = 1;
				} else {
					$evenmessage = 2;
				}

				$evenmessage_count++;

				$message_id = $row['id'];
				$message_reciever_id = $row['reciever_id'];
				$message_title = $row['subject'];
				$message_date = date("l, F jS, Y", strtotime($row['date'], "\n"));
				$message_read = $row['message_read'];

				$u_result = mysql_query("select * from users where id='". $message_reciever_id ."'");
				$u_row=mysql_fetch_array($u_result);

				$message_reciever = $u_row['username'];

			 	$mini_template = new MiniTemplate ();

				$mini_template->template_html = $sent_message_str;

				$mini_template->set_template ("message_id", $message_id);
				$mini_template->set_template ("message_reciever_id", $message_reciever_id);
				$mini_template->set_template ("message_reciever", $message_reciever);
				$mini_template->set_template ("message_title", $message_title);
				$mini_template->set_template ("message_date", $message_date);
				$mini_template->set_template ("message_read", $message_read);
				$mini_template->set_template ("evenmessage", $evenmessage);

				$final_message_html .= $mini_template->return_html ();
				$current++;
			}
			$template->end_loop ("sent", $template->end_loop ("sent_message", $final_message_html, $sent_str));
			$template->end_loop ("inbox", "");
			$template->end_loop ("read_inbox", "");
			$template->end_loop ("read_sent", "");
			$template->end_loop ("compose", "");
			$template->end_loop ("message_sent", "");
			$template->end_loop ("error", "");
		}
	} else {

		$read_inbox_str = $template->get_loop ("read_inbox");

		if(($_GET[act] == "read") && ($_GET[folder] == "inbox") && ($_GET[id] != NULL)){

			$m_result = mysql_query("select * from inbox where id='". $_GET[id] ."' AND reciever_id='". $_userid ."'");
			$message_check = mysql_num_rows($m_result);

			if($message_check != 0){
				$template->end_loop ("sent", "");
				$template->end_loop ("inbox", "");
				$template->end_loop ("compose", "");
				$template->end_loop ("read_sent", "");
				$template->end_loop ("message_sent", "");
				$template->end_loop ("pages", "");
				$template->end_loop ("error", "");

				$mark_read = mysql_query("UPDATE inbox SET message_read='1' WHERE reciever_id='". $_userid ."' AND id='". $_GET[id] ."'");

				$row=mysql_fetch_array($m_result);

				$message_id = $row['id'];
				$message_sender_id = $row['sender_id'];
				$message_sender = $row['sender'];
				$message_title = $row['subject'];
				$message_date = date("l, F jS, Y", strtotime($row['date'], "\n"));
				$message_text = ekincode($row['message'], $user[theme]);

			 	$mini_template = new MiniTemplate ();

				$mini_template->template_html = $read_inbox_str;

				$mini_template->set_template ("message_id", $message_id);
				$mini_template->set_template ("message_sender_id", $message_sender_id);
				$mini_template->set_template ("message_sender", $message_sender);
				$mini_template->set_template ("message_title", $message_title);
				$mini_template->set_template ("message_date", $message_date);
				$mini_template->set_template ("message_text", $message_text);

				$final_message_html .= $mini_template->return_html ();

				$template->end_loop ("read_inbox", $final_message_html);
			} else {
				$template->set_template ("error_message", "The message you have selected does not exist!");

				$template->end_loop ("sent", "");
				$template->end_loop ("inbox", "");
				$template->end_loop ("compose", "");
				$template->end_loop ("read_inbox", "");
				$template->end_loop ("read_sent", "");
				$template->end_loop ("message_sent", "");
				$template->end_loop ("pages", "");
				$template->end_loop ("error", $error_str);
			}
		} else {

			$inbox_message_str = $template->get_loop ("inbox_message");

			$evenmessage_count = 0;

			$template->set_template ("folder", $_GET[folder]);
			$template->set_template ("id", $_GET[id]);

			$page_result = mysql_query("select * from inbox where reciever_id='". $_userid ."' AND reciever_delete='0' ORDER BY datesort DESC");
			$numrows = mysql_num_rows($page_result);

			$replies_str = $template->get_loop ("replies");
			$reply_str = $template->get_loop ("reply");
			$final_replies_html = NULL;

			$display_num = 20;
			$limitnum = 2;

			$query_rows = "select * from inbox where reciever_id='". $_userid ."' AND reciever_delete='0' ORDER BY datesort DESC";
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

			$m_result = mysql_query("select * from inbox where reciever_id='". $_userid ."' AND reciever_delete='0' ORDER BY datesort DESC LIMIT ". $db_page_num .", ". $display_num ."");
			while($row=mysql_fetch_array($m_result)){
				if(!($evenmessage_count % 2) == TRUE){
					$evenmessage = 1;
				} else {
					$evenmessage = 2;
				}

				$evenmessage_count++;

				$message_id = $row['id'];
				$message_sender_id = $row['sender_id'];
				$message_sender = $row['sender'];
				$message_title = $row['subject'];
				$message_date = date("l, F jS, Y", strtotime($row['date'], "\n"));
				$message_read = $row['message_read'];

			 	$mini_template = new MiniTemplate ();

				$mini_template->template_html = $inbox_message_str;

				$mini_template->set_template ("message_id", $message_id);
				$mini_template->set_template ("message_sender_id", $message_sender_id);
				$mini_template->set_template ("message_sender", $message_sender);
				$mini_template->set_template ("message_title", $message_title);
				$mini_template->set_template ("message_date", $message_date);
				$mini_template->set_template ("message_read", $message_read);
				$mini_template->set_template ("evenmessage", $evenmessage);

				$final_message_html .= $mini_template->return_html ();
				$current++;
			}
			$template->end_loop ("inbox", $template->end_loop ("inbox_message", $final_message_html, $inbox_str));
			$template->end_loop ("sent", "");
			$template->end_loop ("read_inbox", "");
			$template->end_loop ("read_sent", "");
			$template->end_loop ("compose", "");
			$template->end_loop ("message_sent", "");
			$template->end_loop ("error", "");
		}
	}
}
} else {
			$template->set_template ("error_message", "You will need to login in order to view this feature!");
			$template->end_loop ("pages", "");
			$template->end_loop ("inbox", "");
			$template->end_loop ("sent", "");
			$template->end_loop ("read_inbox", "");
			$template->end_loop ("read_sent", "");
			$template->end_loop ("compose", "");
			$template->end_loop ("message_sent", "");
			$template->end_loop ("error", $error_str);
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