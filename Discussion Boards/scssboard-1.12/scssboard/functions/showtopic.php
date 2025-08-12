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
if ($_GET[del]) {
	if(!$_GET[confirm]) {
		if(!$_GET[deltopic]) {
			echo "<div class='catheader' style='padding:5px; width:200px; margin-left:auto; margin-right:auto;'>Delete Post?</div>";
			echo "<div class='msg_content' style='text-align:center; padding:5px; width:200px; margin-left:auto; margin-right:auto;'>Confirm deletion of post $_GET[del].<br /><br /><span class='main_button' style='background-color:red;'><a href='index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;del=$_GET[del]&amp;confirm=yes'>OK</a></span>&nbsp;&nbsp;<span class='main_button'><a href='index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]'>Cancel</a></span></center>";
			echo "</div>";
		} else {
			echo "<div class='catheader' style='padding:5px; width:200px; margin-left:auto; margin-right:auto;'>Delete Topic?</div>";
			echo "<div class='msg_content' style='text-align:center; padding:5px; width:200px; margin-left:auto; margin-right:auto;'>Confirm deletion of topic $_GET[del].<br /><br /><span class='main_button'><a href='index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;del=$_GET[del]&amp;deltopic=yes&amp;confirm=yes'>OK</a></span>&nbsp;&nbsp;<span class='main_button'><a href='index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]'>Cancel</a></span></center>";
			echo "</div>";
		}
	} else {
		if (!$_GET[deltopic]) {
			$result = @mysql_query("delete from $_CON[prefix]posts where posts_id = '$_GET[del]'");
			$location_string = "&amp;t=$_GET[t]";
		} else {
			$result = @mysql_query("delete from $_CON[prefix]posts where posts_topic = '$_GET[del]'");
		}

		if ($result == 1) {
			echo redirect("index.php?&amp;act=showforum&amp;f=$_GET[f]$location_string");
		} else {
			echo "Database error. Unable to delete.";
		}
	}

} elseif ($_GET[lock]) {

	@mysql_query("update $_CON[prefix]posts set posts_topic_locked = '$_GET[lock]' where posts_topic = '$_GET[t]' and posts_main = 'yes'");
	echo redirect("index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]");

} else {

@mysql_query("update $_CON[prefix]posts set posts_views = posts_views + 1 where posts_topic = $_GET[t] and posts_forum = $_GET[f] and posts_main = 'yes'");

$forum = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]forums where forums_id = $_GET[f]"));

if ($ulvl < $forum[forums_p_read]) { die("<p align='center'>You are not authorized to view this topic.</p>"); }

$topic_perms = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]posts where posts_topic = '$_GET[t]' and posts_main = 'yes'"));

            $posts_query = @mysql_query("select * from $_CON[prefix]posts where posts_topic = '$_GET[t]' and posts_forum = $_GET[f] and posts_main = 'yes'");
            echo "<p align='right' style='margin-bottom:5px; margin-top:0px;'>";
				if ($forum[forums_p_reply] <= $ulvl) {
					if ($topic_perms[posts_topic_locked] == 1) {
						if ($ulvl < 2) {
							echo "&nbsp; <span class='main_button'><a href='#'>Locked</a></span> &nbsp;";
						} else {
							echo "&nbsp; <span class='main_button'><a href='index.php?&amp;act=post&amp;type=reply&amp;f=$_GET[f]&amp;t=$_GET[t]'>New Reply [Topic Locked]</a></span> &nbsp;";
						}
					} else {
						echo "&nbsp; <span class='main_button'><a href='index.php?&amp;act=post&amp;type=reply&amp;f=$_GET[f]&amp;t=$_GET[t]'>New Reply</a></span> &nbsp;";
					}
				}
				if ($forum[forums_p_topic] <= $ulvl) {
						echo "&nbsp; <span class='main_button'><a href='index.php?&amp;act=post&amp;type=topic&amp;f=$_GET[f]'>New Topic</a></span>";
				}
                 echo "</p>";

		
            while($posts_show = @mysql_fetch_array($posts_query)) {
                $user = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_id = '$posts_show[posts_starter]'"));
				if ($user != 0) {
					$total_posts_main = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_starter = '$user[users_id]'"));
					$registered_user = 1;
				}
				$parsed_body = BBCodeParser($posts_show[posts_body]);
				//$posts_show[posts_posted] = date("F j, Y, g:i a",$posts_show[posts_posted]);
				$posts_show[posts_posted] = get_date($posts_show[posts_posted],$_MAIN[date_format],$_MAIN[use_relative_dates]);
                echo "
<a name='".$posts_show[posts_id]."'></a>

<div class='catheader' style='padding-top:5px; padding-bottom:5px;'>&nbsp; <strong>$posts_show[posts_name]</strong></div>";
if (!$_GET[start]) {
echo "<div class='poster_info'>
	<span class='post_options'>";
	if (($topic_perms[posts_topic_locked] == 1) and ($current_user[users_level] > 1))
		echo "<span class='post_opt_button'><a href='index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;lock=0'>Unlock Topic</a></span>&nbsp;&nbsp;";
	elseif (($topic_perms[posts_topic_locked] == 0) and ($current_user[users_level] > 1))
		echo "<span class='post_opt_button'><a href='index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;lock=1'>Lock Topic</a></span>&nbsp;&nbsp;";
	if ($current_user[users_level] > 1)
		echo "<span class='post_opt_button'><a href='index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;del=$_GET[t]&amp;deltopic=yes'>Delete Topic</a></span>&nbsp;&nbsp;";
	if (($current_user[users_level] > 1) or (($current_user[users_level] > 0) and ($current_user[users_id] == $user[users_id])))
		echo "<span class='post_opt_button'><a href='index.php?&amp;act=post&amp;type=edit&amp;p=$posts_show[posts_id]&amp;f=$_GET[f]&amp;t=$_GET[t]'>Edit</a></span>&nbsp;&nbsp;";
	if ($current_user[users_level] >= $forum[forums_p_reply])
		echo "<span class='post_opt_button'><a href='index.php?&amp;act=post&amp;type=reply&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;q=$posts_show[posts_id]'>Quote</a></span>";

	echo "</span>
	
	<span class='poster_name'>";
		if ($registered_user)
			echo "<a href='?act=profile&amp;u=$user[users_id]'>$user[users_username]</a></span>&nbsp;&nbsp;&nbsp;Posts: $total_posts_main";
		else
			echo "$posts_show[posts_starter] <strong>(Guest)</strong>";
	echo "
</div>
<div class='msg_content'>
	$parsed_body";
	if($user[users_signature]) {
		if ($_MAIN[allow_sig_bbcode] == "yes") { $user[users_signature] = BBCodeParser($user[users_signature]); }				
		echo "<div class='signature_divider'></div>
		<div class='signature'>$user[users_signature]</div>";
	}
echo "
</div>
<div class='msg_date'>
	$posts_show[posts_posted]
</div>
                            ";

			}

							if (!$current_user) {
								$rpp = 15;
							} else {
								$rpp = $current_user[users_rpp];
							}

							if (!$_GET[start])
								$start = 0;
							else
								$start = $_GET[start];
							$end = $start + $rpp; //5 should be dynamic variable of course
							
							$all_replies = @mysql_query("select * from $_CON[prefix]posts where posts_forum = $_GET[f] and posts_topic = $_GET[t] and posts_main = 'no'");
                            $replies_query = @mysql_query("select * from $_CON[prefix]posts where posts_forum = $_GET[f] and posts_topic = $_GET[t] and posts_main = 'no' order by posts_posted asc limit $start,$end");
							$num_replies = @mysql_num_rows($all_replies);

                            while($reply_show = @mysql_fetch_array($replies_query)) {
                                $total_replies_topic = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_forum = $_GET[f] and posts_id = '$topic_show[posts_id]' and posts_main = 'no'"));
                                $user_reply = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_id = '$reply_show[posts_starter]'"));
								$registered_user = 0;
								if($user_reply) {
									$total_posts_reply = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_starter = '$user_reply[users_id]'"));
									$registered_user = 1;
								}
								$parsed_reply = BBCodeParser($reply_show[posts_body]);

								//$reply_show[posts_posted] = date("F j, Y, g:i a",$reply_show[posts_posted]);
								$reply_show[posts_posted] = get_date($reply_show[posts_posted],$_MAIN[date_format],$_MAIN[use_relative_dates]);
                                echo "

<div class='poster_info'>
	<span class='post_options'>";								
	if ($current_user[users_level] > 1)
		echo "<span class='post_opt_button'><a href='index.php?&amp;act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;del=$reply_show[posts_id]'>Delete</a></span>&nbsp;&nbsp;";
	if (($current_user[users_level] > 1) or (($current_user[users_id] == $user_reply[users_id]) and ($current_user)))
		echo "<span class='post_opt_button'><a href='index.php?&amp;act=post&amp;type=edit&amp;p=$reply_show[posts_id]&amp;f=$_GET[f]&amp;t=$_GET[t]'>Edit</a></span>&nbsp;&nbsp;";
	if ($current_user[users_level] >= $forum[forums_p_reply])
		echo "<span class='post_opt_button'><a href='index.php?&amp;act=post&amp;type=reply&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;q=$reply_show[posts_id]'>Quote</a></span>";

	echo "</span>";
	
	echo "<span class='poster_name'>";
	if ($registered_user) {
		if($posts_show[posts_starter] == $reply_show[posts_starter]) {
			echo "~";
		}
	echo "
	<a href='?act=profile&amp;u=$user_reply[users_id]'>$user_reply[users_username]</a></span><a name='$reply_show[posts_id]'>&nbsp;</a>&nbsp;&nbsp;Posts: $total_posts_reply";
	} else {
		echo "$reply_show[posts_starter] <strong>(Guest)</strong>";
	}
	echo "</span>
	</div>
	<div class='msg_content'>
		$parsed_reply";
		if($user_reply[users_signature]) {
			if ($_MAIN[allow_sig_bbcode] == "yes") { $user_reply[users_signature] = BBCodeParser($user_reply[users_signature]); }
		echo "<div class='signature_divider'></div>
		<div class='signature'>$user_reply[users_signature]</div>";
		}
	echo "
	</div>
	<div class='msg_date'>
		$reply_show[posts_posted]
	</div>";
                            }

							if ($num_replies > $rpp) { 
								$curr_page = 1; //Page that we start with is page 1
								$replies_to_go = $num_replies;
								echo "Pages: ";
								while ($replies_to_go > 0) {
									$replies_start = abs($replies_to_go - $num_replies);
									if ($replies_start != $start)
										echo "<a href='?act=showforum&amp;f=$_GET[f]&amp;t=$_GET[t]&amp;start=$replies_start'>$curr_page</a>&nbsp;";
									else
										echo "$curr_page&nbsp;";
									$replies_to_go = $replies_to_go - $rpp;
									$curr_page = $curr_page + 1;
								}
							}

            echo "<p align='right' style='margin-top:0px; margin-bottom:5px;'>";
				if ($forum[forums_p_reply] <= $ulvl) {
					if ($topic_perms[posts_topic_locked] == 1) {
						if ($ulvl < 2) {
							echo "&nbsp; <span class='main_button'><a href='#'>Locked</a></span> &nbsp;";
						} else {
							echo "&nbsp; <span class='main_button'><a href='index.php?&amp;act=post&amp;type=reply&amp;f=$_GET[f]&amp;t=$_GET[t]'>New Reply [Topic Locked]</a></span> &nbsp;";
						}
					} else {
						echo "&nbsp; <span class='main_button'><a href='index.php?&amp;act=post&amp;type=reply&amp;f=$_GET[f]&amp;t=$_GET[t]'>New Reply</a></span> &nbsp;";
					}
				}
				if ($forum[forums_p_topic] <= $ulvl) {
						echo "&nbsp; <span class='main_button'><a href='index.php?&amp;act=post&amp;type=topic&amp;f=$_GET[f]'>New Topic</a></span>";
				}
                 echo "</p>
                <br />";
            }
}
        
?>