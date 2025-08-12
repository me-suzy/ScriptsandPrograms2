<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<?php

if($_POST[post]) {
							
		//--POSTING NEW TOPIC
		if($_GET[type] == "topic") {
			$topicname = $_POST[topicname];
            $body = $_POST[body];
            if($topicname == "") {
				echo "<p align='center' style='background-color:#fff; color:#000; padding:10px;'>Error: Topic name was left blank. <a href='javascript:history.back()'>Back...</a></p>";
			} elseif(strlen($topicname) > 40) {
				echo "<p align='center' style='background-color:#fff; color:#000; padding:10px;'>Error: Topic name must have 40 or less characters. <a href='javascript:history.back()'>Back...</a></p>";
			} elseif($body == "") {
				echo "<p align='center' style='background-color:#fff; color:#000; padding:10px;'>Error: You didn't type anything in your post. <a href='javascript:history.back()'>Back...</a></p>";
			} else {
				$t_i = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts order by posts_topic desc limit 1"));
				$next_topic = $t_i[posts_topic] + 1;
				$body = nl2br($body);
				$currdate_time = time();
				$topicname = strip_tags($topicname);
				$topicname = trim($topicname);
				if (!$current_user) { $uid = $_POST[guestname]; } else { $uid = $current_user[users_id]; }
				@mysql_query("insert into $_CON[prefix]posts (posts_main,posts_topic,posts_name,posts_body,posts_starter,posts_posted,posts_forum) values('yes','$next_topic','$topicname','$body','$uid','$currdate_time','$_GET[f]')");
				$topic_post_id = @mysql_fetch_array(@mysql_query("select posts_id from $_CON[prefix]posts where posts_main = 'yes' and posts_topic = '$next_topic'"));
				@mysql_query("update $_CON[prefix]posts set posts_topic_lastpost = '$topic_post_id[0]' where posts_main = 'yes' and posts_topic = '$next_topic'");
				echo redirect("index.php?&amp;act=showforum&amp;f=$_GET[f]");
			}

		} elseif($_GET[type] == "reply") {

			//--POSTING REPLY
			$body = $_POST[body];
            if($body == "") {
				echo "<p align='center' style='background-color:#fff; color:#333; padding:10px;'>Error: The post was left blank. <a href='javascript:history.back()'>Back...</a></p>";
			} else {
				$topic_details = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_forum = '$_GET[f]' and posts_topic = '$_GET[t]' and posts_main = 'yes'"));
				$body = nl2br($body);
				$currdate_time = time();
				if (!$current_user) { $uid = $_POST[guestname]; } else { $uid = $current_user[users_id]; }
				@mysql_query("insert into $_CON[prefix]posts (posts_main,posts_topic,posts_body,posts_starter,posts_posted,posts_forum) values('no','$topic_details[posts_topic]','$body','$uid','$currdate_time','$_GET[f]')");
				$post_id = @mysql_fetch_array(@mysql_query("select posts_id from $_CON[prefix]posts where posts_body = '$body' and posts_posted = '$currdate_time'"));
				mysql_query("update $_CON[prefix]posts set posts_topic_lastpost = '$post_id[0]' where posts_main = 'yes' and posts_topic = '$topic_details[posts_topic]'");
				echo redirect("index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$topic_details[posts_topic]");
			}
		} elseif ($_GET[type] == "edit") {
			$body = $_POST[body];
			$topicname = $_POST[topicname];
			$body = nl2br($body);
			@mysql_query("update $_CON[prefix]posts set posts_body = '$body' where posts_id = '$_GET[p]'");
			@mysql_query("update $_CON[prefix]posts set posts_name = '$topicname' where posts_id = '$_GET[p]' and posts_main = 'yes'");
			echo redirect("index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]");
		}
	


} else { //We didn't submit anything, so let's display the posting form

	if ($_POST[preview]) {
		
		$parsed_body = nl2br($_POST[body]);
		$parsed_body = BBCodeParser($parsed_body);
		$parsed_body = stripslashes($parsed_body);

		echo "<div class='catheader' style='padding-left:5px; padding-top:5px; padding-bottom:5px;'>
				&nbsp;<strong>Preview</strong>
			</div>
			<div class='msg_content'>
				$parsed_body
			</div><br />";

	}

			$forum_post_perm = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]forums where forums_id = '$_GET[f]'"));
			if ((($forum_post_perm[forums_p_topic] > $ulvl) and ($_GET[type] == "topic")) or (($forum_post_perm[forums_p_reply] > $ulvl) and ($_GET[type] == "reply")) or (($_GET[type] == "edit") and ($ulvl < 2))) { //now that's a mouthful
                    echo "<p align='center' style='background-color:#fff; color:#000; padding:10px;'>Forum access permissions prevent you from doing this.</p>";
                } else {
                    if($_GET[f] == "") {
                        echo "<p align='center' style='background-color:#fff; color:#000; padding:10px;'>No forum selected.</p>";
						echo redirect("index.php", 1);
                    } else {
							if ($_GET[type] == "topic") {
							echo "<form method='post' action='index.php?act=post&amp;type=topic&amp;f=$_GET[f]'>";
							$type_txt = "Posting New Topic";
							} elseif ($_GET[type] == "reply") {
							echo "<form method='post' action='index.php?&amp;act=post&amp;type=reply&amp;f=$_GET[f]&amp;t=$_GET[t]'>";
							$type_txt = "Posting Reply";
							}
							elseif ($_GET[type] == "edit") {
							echo "<form method='post' action='index.php?act=post&amp;type=edit&amp;p=$_GET[p]&amp;f=$_GET[f]&amp;t=$_GET[t]'>";
							$type_txt = "Editing Post";
							$edited_post = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_id = '$_GET[p]'"));
							$edited_post[posts_body] = strip_tags($edited_post[posts_body]);
							}

							if($_GET[q]) {
								$quoted_post = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_id = '$_GET[q]'"));
								$quoted_post[posts_posted] = get_date($quoted_post[posts_posted],$_MAIN[date_format],"no");
								$quoted_post[posts_body] = strip_tags($quoted_post[posts_body]);
								$quoted_post_starter = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_id = '$quoted_post[posts_starter]'"));
							}
							
							echo "<div class='catheader' style='padding-left:5px; padding-top:5px; padding-bottom:5px;'>
									&nbsp;<strong>$type_txt</strong>
								</div>";
							
							echo "<div class='posting_table' style='text-align:center;'>";

							echo	"<span style='float:right;'>";
									include("functions/global/bbcode_table.php");
							echo	"</span>";

							if (!$current_user) {
							echo "<p align='center'>
									<em>You are posting as a guest.</em><br /><br />
									&nbsp; <strong>Name:</strong> <input type='text' name='guestname' size='20' class='input' value='$_POST[guestname]' />
								</p>";
							}

							if (($_GET[type] == "topic") or (($edited_post[posts_main] == "yes") and ($current_user[users_level] > 2))) {
							echo "<p align='center'>
									&nbsp; <strong>Topic Name:</strong> <input type='text' name='topicname' size='20' class='input'";
								if ($_POST[topicname]) { 
									$topicname = stripslashes($_POST[topicname]);
									echo "value=\"$topicname\""; }
								if ($edited_post[posts_main] == "yes") { 
									$topicname = stripslashes($edited_post[posts_name]);
									echo "value=\"$topicname\""; }
								echo " />
								</p>";
							}							
							
							echo	"<strong>Post Body:</strong><br /><textarea name='body' style='width:600px; height:200px;' cols='40' rows='40'>";
									if($_POST[preview]) { echo stripslashes($_POST[body]); }
									if($quoted_post) { echo "[quote=$quoted_post_starter[users_username] on $quoted_post[posts_posted]]$quoted_post[posts_body][/quote]"; }
									if(($edited_post) and (!$_POST[preview])) { echo "$edited_post[posts_body]"; }
									echo "</textarea>
								</div>
							<div class='posting_table' style='text-align:center;'>
									&nbsp; <input type='submit' name='post' value='Post' class='form_button' />
									&nbsp; <input type='submit' name='preview' value='Preview' class='form_button' />
								</div>
							</form>";
					}
                }
            echo "
    <br />";
}
?>