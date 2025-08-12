<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############# //ADMIN PANEL THREADS & POSTS\\ ############# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Mass Pruning";
$permissions = "threads_posts";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO MASS PRUNE THREADS ##### \\
if($_GET['do'] == "threads") {
	if($_POST['massprune_threads']['set_form']) {
		// get timestamps for before and after...
		$before = mktime(0,0,0,$_POST['month1'],$_POST['day1'],$_POST['year1']);
		$after = mktime(0,0,0,$_POST['month2'],$_POST['day2'],$_POST['year2']);

		$searchForumid = $_POST['massprune_threads']['forum'];
		$searchUsername = $_POST['massprune_threads']['threadUsername'];

		if(!empty($searchUsername)) {
			// get userid
			$useridINFO_q = query("SELECT * FROM user_info WHERE username = '".$searchUsername."' LIMIT 1");

			if(!mysql_num_rows($useridINFO_q)) {
				$searchUsername = "";
			} else {
				$useridINFO = mysql_fetch_array($useridINFO_q);

				$query = "AND thread_starter = ".$useridINFO['userid']." ";
			}
		} else {
			$query = "";
		}

		if($searchForumid != -1) {
			$query .= "AND forumid = '".$searchForumid."' ";
		}

		if($_POST['day1']) {
			$query .= "AND date_made < '".$before."' ";
		}

		if($_POST['day2']) {
			$query .= "AND date_made > '".$after."' ";
		}

		if(empty($query)) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// remove first AND
		$query = preg_replace("|^AND|","",$query);

		// select
		$deletedThreads = query("SELECT * FROM threads WHERE ".$query);

		//print("SELECT * FROM threads WHERE ".$query);

		// if rows.. go through and delete threads, posts, polls, and attachments
		if(mysql_num_rows($deletedThreads)) {
			while($threadinfo = mysql_fetch_array($deletedThreads)) {
				// delete posts
				query("DELETE FROM posts WHERE threadid = '".$threadinfo['threadid']."'");
				query("DELETE FROM poll WHERE threadid = '".$threadinfo['threadid']."'");
				query("DELETE FROM poll_options WHERE threadid = '".$threadinfo['threadid']."'");
				query("DELETE FROM attachments WHERE attachmentthread = '".$threadinfo['threadid']."'");
				query("DELETE FROM thread_subscription WHERE threadid = '".$threadinfo['threadid']."'");
				query("DELETE FROM threads WHERE threadid = '".$threadinfo['threadid']."' LIMIT 1");
			}
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have successfully deleted <strong>".mysql_num_rows($deletedThreads)."</strong> threads. You will now be redirected back.&uri=massprune.php?do=threads");
	}

	else if($_POST['massmove_threads']['set_form']) {
		// get timestamps for before and after...
		$before = mktime(0,0,0,$_POST['month1'],$_POST['day1'],$_POST['year1']);
		$after = mktime(0,0,0,$_POST['month2'],$_POST['day2'],$_POST['year2']);

		$searchForumid = $_POST['massmove_threads']['forum_begin'];
		$searchUsername = $_POST['massmove_threads']['threadUsername'];

		if(!empty($searchUsername)) {
			// get userid
			$useridINFO_q = query("SELECT * FROM user_info WHERE username = '".$searchUsername."' LIMIT 1");

			if(!mysql_num_rows($useridINFO_q)) {
				$searchUsername = "";
			} else {
				$useridINFO = mysql_fetch_array($useridINFO_q);

				$query = "AND thread_starter = ".$useridINFO['userid']." ";
			}
		} else {
			$query = "";
		}

		if($searchForumid != -1) {
			$query .= "AND forumid = '".$searchForumid."' ";
		}

		if($_POST['day1']) {
			$query .= "AND date_made < '".$before."' ";
		}

		if($_POST['day2']) {
			$query .= "AND date_made > '".$after."' ";
		}

		if(empty($query)) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// remove first AND
		$query = preg_replace("|^AND|","",$query);

		// select
		$movedThreads = query("SELECT * FROM threads WHERE ".$query);

		//print("SELECT * FROM threads WHERE ".$query);

		// if rows.. go through and delete threads, posts, polls, and attachments
		if(mysql_num_rows($movedThreads)) {
			while($threadinfo = mysql_fetch_array($movedThreads)) {
				// first update thread with new forum...
				query("UPDATE threads SET forumid = '".$massmove_threads['forum_destination']."' WHERE threadid = '".$threadinfo['threadid']."'");

				// update post forum ids
				query("UPDATE posts SET forumid = '".$massmove_threads['forum_destination']."' WHERE threadid = '".$threadinfo['threadid']."'");				
			}
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have successfully moved <strong>".mysql_num_rows($movedThreads)."</strong> threads. You will now be redirected back.&uri=massprune.php?do=threads");
	}


	// do header
	admin_header("wtcBB Admin Panel - Mass Move/Prune - Threads");

	construct_title("Mass Move/Prune Threads");

	construct_table("options","massprune_threads","massprune_submit",1);

	construct_header("Mass Prune Threads",2);


	construct_select_begin(1,"Thread Made Before","","massprune_threads","beforeDate",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month1\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day1\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Thread Made After","","massprune_threads","afterDate",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month2\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(2,1);

	construct_select(1,"Forum","Select here the forum you wish for the search to be in. Select \"All Forums\" for it to be a global search in all forums.","massprune_threads","forum","",0,0,0,2);

	construct_text(2,"Delete threads started only by this user","Leave blank for this search to delete threads started by all users.","massprune_threads","threadUsername","",1);

	construct_footer(2,"massprune_submit");

	construct_table_END(1);


	print("\n\n<br /><br />\n\n");


	construct_table("options","massmove_threads","massmove_submit",1);

	construct_header("Mass Move Threads",2);


	construct_select_begin(1,"Thread Made Before","","massmove_threads","beforeDate",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month1\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day1\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Thread Made After","","massmove_threads","afterDate",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month2\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(2,1);

	construct_select(1,"Start Forum","Select the forum you wish to move posts from.","massmove_threads","forum_begin","",0,0,0,2);

	construct_select(2,"Destination Forum","Select the forum you wish to move posts to.","massmove_threads","forum_destination","",0,0,0,3);

	construct_text(1,"Move threads started only by this user","Leave blank for this search to move threads started by all users.","massmove_threads","threadUsername","",1);

	construct_footer(2,"massmove_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// ##### DO MASS PRUNE POSTS ##### \\
else if($_GET['do'] == "posts") {
	if($_POST['massprune_posts']['set_form']) {
		// get timestamps for before and after...
		$before = mktime(0,0,0,$_POST['month1'],$_POST['day1'],$_POST['year1']);
		$after = mktime(0,0,0,$_POST['month2'],$_POST['day2'],$_POST['year2']);

		$searchForumid = $_POST['massprune_posts']['forum'];
		$searchUsername = $_POST['massprune_posts']['threadUsername'];

		if(!empty($searchUsername)) {
			// get userid
			$useridINFO_q = query("SELECT * FROM user_info WHERE username = '".$searchUsername."' LIMIT 1");

			if(!mysql_num_rows($useridINFO_q)) {
				$searchUsername = "";
			} else {
				$useridINFO = mysql_fetch_array($useridINFO_q);

				$query = "AND userid = ".$useridINFO['userid']." ";
			}
		} else {
			$query = "";
		}

		if($searchForumid != -1) {
			$query .= "AND forumid = '".$searchForumid."' ";
		}

		if($_POST['day1']) {
			$query .= "AND date_posted > '".$before."' ";
		}

		if($_POST['day2']) {
			$query .= "AND date_posted < '".$after."' ";
		}

		if(empty($query)) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// remove first AND
		$query = preg_replace("|^AND|","",$query);

		// select
		$deletedPosts = query("SELECT * FROM posts WHERE ".$query);

		// if rows.. go through and delete threads, posts, polls, and attachments
		if(mysql_num_rows($deletedPosts)) {
			while($postinfo = mysql_fetch_array($deletedPosts)) {
				// delete posts and possibly threads!
				$deletedThread = query("SELECT * FROM threads WHERE first_post = '".$postinfo['postid']."'");

				// if the above has deleted a thread... delete all posts in that thread
				if(mysql_num_rows($deletedThread)) {
					$threadinfo = mysql_fetch_array($deletedThread);
					
					query("DELETE FROM posts WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM poll WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM poll_options WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM attachments WHERE attachmentthread = '".$threadinfo['threadid']."'");
					query("DELETE FROM thread_subscription WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM threads WHERE threadid = '".$threadinfo['threadid']."' LIMIT 1");
				}

				else {
					query("DELETE FROM attachments WHERE attachmentpost = '".$postinfo['postid']."'");
					query("DELETE FROM posts WHERE postid = '".$postinfo['postid']."'");
				}
			}
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have successfully deleted <strong>".mysql_num_rows($deletedPosts)."</strong> posts. You will now be redirected back.&uri=massprune.php?do=posts");
	}

	// do header
	admin_header("wtcBB Admin Panel - Mass Prune - Posts");

	construct_title("Mass Prune Posts");

	construct_table("options","massprune_posts","massprune_submit",1);

	construct_header("Mass Prune Posts",2);


	construct_select_begin(1,"Post Made Before","","massprune_posts","beforeDate",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month1\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day1\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Post Made After","","massprune_posts","afterDate",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month2\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(2,1);

	construct_select(1,"Forum","Select here the forum you wish for the search to be in. Select \"All Forums\" for it to be a global search in all forums.","massprune_posts","forum","",0,0,0,2);

	construct_text(2,"Delete posts made only by this user","Leave blank for this search to delete posts made by all users.","massprune_posts","threadUsername","",1);

	construct_footer(2,"massprune_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>