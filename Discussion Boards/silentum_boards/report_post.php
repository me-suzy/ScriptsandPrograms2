<?
	/*
	Silentum Boards v1.4.3
	report_post.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");
	require_once("text.php");

	$topic_data = get_topic_data($board,$thread);
	$post_data = get_post_data($board,$thread,$post);

	if($user_logged_in != 1) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\tAccess Denied");
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	include("board_bottom.php");
	exit;
	}

	if($user_data['karma'] < "1" && $user_data['status'] != 1 && $user_data['status'] != 2) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Not_Enough_Karma']['0']);
	echo get_message('Not_Enough_Karma','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}

	if($user_data['status'] == "4") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\tAccess Denied");
	echo get_message('Banned','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}

	if($user_data['status'] == "6") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\tAccess Denied");
	echo get_message('Suspended','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}

	else {

	if(!myfile_exists("boards/$board.$thread.txt")) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Topic_Does_Not_Exist'][0]);
	echo get_message('Topic_Does_Not_Exist','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}

	if(get_user_name($post_data[1]) == "Deleted") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\t".$txt['Navigation']['Post_Does_Not_Exist'][0]);
	echo get_message('Post_Does_Not_Exist','<br /><br />'.sprintf($txt['Links']['Topic'],"<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">",'</a>').'<br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}

	if($report != 1 && $report != 2) {

	if($config['enable_censor'] == 1) $topicname = censor(get_thread_name($board,$thread)); else $topicname = get_thread_name($board,$thread);

	if($post_data[8] == "1" || $post_data[8] == "yes") $post_data[3] = basic_html($post_data[3]);

	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">".$topicname."</a>\tReport Post");
?>

<form action="index.php?page=report_post&amp;report=1&amp;board=<?=$board?>&amp;thread=<?=$thread?>&amp;post=<?=$post?>" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">Report Post</span></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left; width: 50%"><span class="normal">By <strong><?=get_user_name($post_data[1])?></strong> on <?=makedate($post_data[2])?></span></td>
		<td class="two" style="text-align: right; width: 50%"><span class="normal"><strong>Post #<?=str_pad($post_data[0],4,"0",str_pad_left)?></strong></span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left"><span class="normal"><? if($config['enable_censor'] == 1) echo censor($post_data[3]); else echo $post_data[3]?><br /><br /></span></td>
	</tr>
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">Which section of the Terms of Service does this post violate?</span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left"><span class="normal">
		<input checked="checked" name="reason" type="radio" value="Flaming" /> <strong>Flaming</strong> (Sexually explicit, racial or general hate comments directed towards another user.)<br />
		<input name="reason" type="radio" value="Censor Bypassing" /> <strong>Censor Bypass</strong> (Changing letters to bypass a censored word.)<br />
		<input name="reason" type="radio" value="Inciting" /> <strong>Inciting</strong> (Posts which incite another user to violate the board rules, or are made to intentionally annoy another user.)<br />
		<input name="reason" type="radio" value="Disruptive" /> <strong>Disruptive</strong> (Posts which disrupt a user's screen, browser, or computer.)<br />
		<input name="reason" type="radio" value="Illegal/Inappropriate" /> <strong>Illegal/Inappropriate</strong> (Posts which offend users, encourage or show use of illegal activities, or use sexually explicit terms.)<br />
		<input name="reason" type="radio" value="Off Topic" /> <strong>Off Topic</strong> (Posts which do not fit the board description.)<br />
		<input name="reason" type="radio" value="Spamming/Flooding" /> <strong>Spamming/Flooding</strong> (3 or more of the same post in one topic or advertisements.)<br />
		<input name="reason" type="radio" value="Exploit" /> <strong>Exploit</strong> (Altering code or using features to do something that was unintended in the board design.)<br />
		<input name="reason" type="radio" value="Other" /> <strong>Other</strong>: 
		<input class="textbox" maxlength="35" name="other" size="35" type="text" value="" /><br /><br /><strong>Only report messages which violate the Terms of Service.<br /><br />Please note that abuse of this feature may result in a suspension or banning.</strong></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Report Post" /></td>
	</tr>
</table>
</object>
</form>
<?
	include("board_bottom.php");
	}

	if($report == 1) {
	$queue_id = myfile("objects/id_queue.txt");
	$new_id = $queue_id[0]+1;
	$towrite = "$new_id\t$thread\t$board\t$post\t$post_data[1]\t$user_data[id]\t$reason\t$other\t\n";
	myfwrite("objects/queue.txt",$towrite,"a");
	myfwrite("objects/id_queue.txt",$new_id,"w");
	header("Location: index.php?page=report_post&report=2&board=$board&thread=$thread&post=$post");
	}
	}

	if($report == 2) {
	$logging = explode(',',$config['record_options']);
	if(in_array(11,$logging)) {
	record("10","%1: Post Reported [IP: %2]");
	}
	if($config['enable_censor'] == 1) $topicname = censor(get_thread_name($board,$thread)); else $topicname = get_thread_name($board,$thread);
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".get_board_name($board)."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">".$topicname."</a>\tPost Reported");
	echo get_message('Post_Reported','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	}
?>