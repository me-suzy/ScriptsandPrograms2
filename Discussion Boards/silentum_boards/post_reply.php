<?
	/*
	Silentum Boards v1.4.3
	post_reply.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	if(!isset($thread)) $thread = $thread;

	$right = 0;

	if(!$board_data = get_board_data($board)) { echo navigation($txt['Navigation']['Error']['0']);
	echo get_message('Error','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	elseif(!$topic_data = get_topic_data($board,$thread)) { echo navigation($txt['Navigation']['Error']['0']);
	echo get_message('Error','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	elseif($user_logged_in != 1) {
	if($board_data['rights'][6] != 1) {
	echo navigation("<a href=\"index.php?method=board&amp;zboard=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\tAccess Denied");
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	elseif($topic_data['status'] != "1" && $topic_data['status'] != "open" && $user_data['status'] != 1 && $user_data['status'] != 2) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\t".$txt['Navigation']['Topic_Locked'][0]);
	echo get_message('Topic_Locked','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>'));
	}
	else $right = 1;
	}
	else {
	if($user_data['status'] == "4") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\tAccess Denied");
	echo get_message('Banned','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['status'] == "6") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\tAccess Denied");
	echo get_message('Suspended','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['status'] == "7") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\tAccess Denied");
	echo get_message('Closed','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['karma'] <= "0" && $user_data['posts'] >= "10" && $user_data['status'] != "1" && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Exceeded_Post_Limit'][0]);
	echo get_message('Exceeded_Post_Limit','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['karma'] <= "4" && $user_data['posts'] >= "30" && $user_data['status'] != "1" && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Exceeded_Post_Limit'][0]);
	echo get_message('Exceeded_Post_Limit','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['karma'] <= "14" && $user_data['posts'] >= "60" && $user_data['status'] != "1" && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Exceeded_Post_Limit'][0]);
	echo get_message('Exceeded_Post_Limit','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	elseif(check_right($board,2) != 1 && $user_data['status'] != 2) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\t".$txt['Navigation']['Restricted'][0]);
	echo get_message('Restricted','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	elseif($topic_data['status'] != "1" && $topic_data['status'] != "open" && $user_data['status'] != 1 && $user_data['status'] != 2) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\t".$txt['Navigation']['Topic_Locked'][0]);
	echo get_message('Topic_Locked','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>'));
	}
	else $right = 1;
	}

	if($right == 1) {
	$displaypage = 1;
	$error = "";
	$nli_name = mutate(trim($nli_name));
	$post = mutate(trim(mysslashes($post)));

	if(isset($preview)) {
	$post = demutate(trim(mysslashes($post)));
	}

	if($method == "save" && !isset($preview)) {
	if($user_logged_in != 1 && $nli_name == "" && $config['guests_must_enter_a_name'] == 1) $error = "You must enter a name.";
	elseif($user_logged_in != 1 && strlen(trim($nli_name)) < 4) $error = "Your name must be at least 4 characters in length.";
	elseif($user_logged_in != 1 && strlen($nli_name) > 20) $error = "Your name is too many characters.";
	elseif($user_logged_in != 1 && !preg_match("/^[A-Z0-9_ ]+$/i",$nli_name)) $error = "Your name can only contain alphanumeric characters, underscores, and spaces.";
	elseif($post == "") $error = "Your post cannot be blank.";
	elseif(strlen($post) > 2056) $error = "Your post is too many characters. The maximum allotment of characters is 2056.";
	else {
	$displaypage = 0;
	$new_id = $topic_data['lpost_id']+1;
	$date = mydate();

	if($user_logged_in == 1) $user_info = $user_id;
	else {
	if($nli_name == "") $nli_name = "Guest";
	$user_info = "0$nli_name";
	}

	$post = nlbr($post);
	$towrite = "$new_id\t$user_info\t$date\t$post\t$REMOTE_ADDR\t1\t$post_icon\t$smilies\t$use_basic_html\t\t\t\t\r\n";
	myfwrite("boards/$board.$thread.txt",$towrite,"a");

	rank_topic($board,$thread); increase_post_amount($board); increase_user_posts($user_id); update_last_post($board,$date,$user_info,$thread,$post_icon); update_topic_time($board,$thread);

	if($board_data['rights'][4] == 1) {
	update_last_posts($board,$thread,$user_info,$date);
	}

	$logging = explode(',',$config['record_options']);
	if(in_array(4,$logging)) {
	record("4","%1: Reply Posted ($board,$thread) [IP: %2]");
	}
	header("Location: index.php?method=topic&board=$board&thread=$thread");
	exit;
	}
	}

	if($displaypage == 1) {

	$topic_file = myfile("boards/$board.$thread.txt");
	$answer_creator = get_post_data($board,$thread,$quote);

	if($quote != "") $quote = "&lt;quote&gt;&lt;i&gt;By ".get_user_name($answer_creator['1'])."&lt;/i&gt; --

".censor(get_post($board,$thread,$quote))."&lt;/quote&gt;\r\n\n";
?>

<script type="text/javascript">
<!--
function setsmile(Indication) {
document.contribution.post.value = document.contribution.post.value + Indication;
}
-->
</script><?
	if($config['enable_censor'] == 1) $topic_data[title] = censor($topic_data[title]);
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[title]</a>\tPost Reply");
	if(isset($preview)) {
	$preview_post = nlbr(trim(mutate($post)));
	if($user_data['signature'] != "") $signature = "<span class=\"normal\"><br />---<br />".basic_html_profile($user_data[signature])."</span>"; else $signature = "";
	if($smilies == 1) $preview_post = make_smilies($preview_post);
	if($use_basic_html == 1 && $board_data['basic_html'] == 1) $preview_post = basic_html($preview_post);
?>
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" style="text-align: left"><span class="heading">Preview Reply</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal"><? if($config['enable_censor'] == 1) echo censor($preview_post.$signature); else echo $preview_post.$signature?></span></td>
	</tr>
</table>
</object><br /><?
	}
	if(!$preview || $smilies == 1) $checked['smilies'] = " checked=\"checked\"";
	if(!$preview || $use_basic_html == 1) $checked['basic_html'] = " checked=\"checked\"";
?>

<form action="index.php?page=post_reply&amp;method=save" method="post" name="contribution">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4" style="text-align: left"><span class="heading">Post Reply</span></td>
	</tr>
<?
	if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"4\" style=\"text-align: left\"><span class=\"heading\">Error: $error</span></td>
	</tr>
";
	if($user_logged_in != 1) {
?>
	<tr>
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><strong>Your Name</strong></span></td>
	 	<td class="one" colspan="3" style="text-align: left; width: 80%"><input class="textbox" maxlength="20" name="nli_name" size="20" type="text" value="<?=trim($nli_name)?>" /></td>
	</tr>
<?
	}
?>

	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Post Icon</strong></span></td>
	 	<td class="one" colspan="3" style="text-align: left; width: 80%"><? $post_icon_file = myfile("objects/post_icons.txt"); $post_icon_file_size = sizeof($post_icon_file);
		for($i = 0; $i < $post_icon_file_size; $i++) {
		$act_post_icon = myexplode($post_icon_file[$i]);
		if($i == 0) $checked[post_icon] = " checked=\"checked\"";
		elseif($i+1 == $post_icon) $checked[post_icon] = " checked=\"checked\""; else $checked[post_icon] = "";
		echo "<input$checked[post_icon] name=\"post_icon\" type=\"radio\" value=\"".$act_post_icon[0]."\" /> <img alt=\"Post Icon\" class=\"icon\" src=\"".$act_post_icon[1]."\" title=\"Post Icon\" /> ";
		if(($i + 1) % 7 == 0) echo "<br />";
		} ?></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Post</strong><br /><br /><strong>Smilies</strong><br /><br /><? $smilie_file = myfile("objects/smilies.txt"); $smilie_file_size = sizeof($smilie_file);
		for($i = 0; $i < $smilie_file_size; $i++) {
		$act_smilie = myexplode($smilie_file[$i]);
		echo "<a class=\"indent\" href=\"javascript:setsmile('%20$act_smilie[1]%20')\" onmouseover=\"status='Smilie';return true\"><img alt=\"$act_smilie[1]\" class=\"icon\" src=\"".trim($act_smilie[2])."\" title=\"$act_smilie[1]\" /></a> ";
		if(($i + 1) % 4 == 0) echo "<br />";
		} ?><br /></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%"><textarea class="textbox" cols="80" name="post" rows="10" tabindex="1"><?=$quote?><?=demutate(trim(mysslashes($post)));?></textarea></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Options</strong></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%"><span class="normal"><?
		if($board_data['basic_html'] == 1) echo "<input$checked[basic_html] name=\"use_basic_html\" tabindex=\"2\" type=\"checkbox\" value=\"1\" /> Enable <acronym title=\"&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;\">Basic HTML</acronym><br />";
		?><input<?=$checked['smilies']?> name="smilies" tabindex="3" type="checkbox" value="1" /> Enable Smilies <input name="thread" type="hidden" value="<?=$thread?>" /> <input name="board" type="hidden" value="<?=$board?>" /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" name="preview" tabindex="4" type="submit" value="Preview Reply" /> <input class="button" tabindex="5" type="submit" value="Post Reply" /></td>
	</tr>
</table>
</object>
</form>
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">10 Newest Posts (Signatures Hidden)</span></td>
	</tr>
<?
	$topic_file = myfile("boards/$board.$thread.txt");
	$temp_size = sizeof($topic_file);

	if($temp_size < 11) $end_post = 0;
	else $end_post = $temp_size - 11;

	$current_contribution = myexplode($topic_file[$i]);

	for($i = $temp_size-1; $i > $end_post; $i--) {
	$act_post = myexplode($topic_file[$i]);

	if($act_post[7] == 1 || $act_post[7] == "yes") $act_post[3] = make_smilies($act_post[3]);
	if(($act_post[8] == 1 || $act_post[8] == "yes") && $board_data['basic_html'] == 1) $act_post[3] = basic_html($act_post[3]);
	if($config['enable_censor'] == 1) $act_post[3] = censor($act_post[3]);

	$answer_creator2 = get_user_data($act_post[1]);
?>
	<tr>
		<td class="two" style="text-align: left; width: 50%" valign="middle"><span class="normal"><img align="left" src="<? if($act_post[6] == "" || get_post_icon_address($act_post[6]) == "") echo "images/post_icons/icon_1.png"; else echo get_post_icon_address($act_post[6]); ?>" />&nbsp;By <? if($act_post[1] != "0".get_user_name($act_post[1])."") echo "<a href=\"index.php?page=profile&amp;id=".$act_post[1]."\">" ?><strong><?=get_user_name($act_post[1])?></strong><? if($act_post[1] != "0".get_user_name($act_post[1])."") echo "</a>" ?> on <?=makedate($act_post[2])?></span></td>
		<td class="two" style="text-align: right; width: 50%" valign="middle"><span class="normal"><strong><a id="post<?=str_pad($act_post[0],4,"0",str_pad_left)?>"></a><a href="#post<?=str_pad($act_post[0],4,"0",str_pad_left)?>">Post #<?=str_pad($act_post[0],4,"0",str_pad_left)?></a></strong></span></td>
	</tr>
	<tr>
		<td class="one" colspan="2" style="text-align: left" valign="top"><span class="normal"><?=$act_post[3]?><br /><br /></span></td>
	</tr>				 
<?
	}
	echo "</table>
</object><br />
";
	}
	}
?>