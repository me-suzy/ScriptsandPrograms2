<?
	/*
	Silentum Boards v1.4.3
	post_poll.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	$right = 0;

	if(!$board_data = get_board_data($board)) { echo navigation($txt['Navigation']['Error']['0']);
	echo get_message('Error','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	elseif($user_logged_in != 1) {
	if($board_data['rights'][7] == 1 || $user_data['status'] == "2") $right = 1;
	else {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\tAccess Denied");
	echo get_message('Not_Logged_In','<br /><br />'.sprintf($txt['Links']['Register_Or_Login'],"<a href=\"index.php?page=register\">",'</a>',"<a href=\"index.php?page=login\">",'</a>'));
	}
	}
	else {
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
	if($user_data['status'] == "7") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\tAccess Denied");
	echo get_message('Closed','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['karma'] < "0") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Not_Enough_Karma'][0]);
	echo get_message('Not_Enough_Karma','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['karma'] <= "0" && $user_data['posts'] >= "10" && $user_data['status'] != "1" && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Exceeded_Post_Limit'][0]);
	echo get_message('Exceeded_Post_Limit','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['karma'] <= "4" && $user_data['posts'] >= "30" && $user_data['status'] != "1" && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Exceeded_Post_Limit'][0]);
	echo get_message('Exceeded_Post_Limit','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	if($user_data['karma'] <= "14" && $user_data['posts'] >= "60" && $user_data['status'] != "1" && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Exceeded_Post_Limit'][0]);
	echo get_message('Exceeded_Post_Limit','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	elseif(check_right($board,3) != "1" && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Restricted'][0]);
	echo get_message('Restricted','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	include("board_bottom.php");
	exit;
	}
	else $right = 1;
	}

	if($right == 1) {
	$max_poll_choices = 15;
	switch($method) {
	default:
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\tPost Poll");
?>

<form action="index.php?page=post_poll&amp;method=step2&amp;board=<?=$board?>" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" style="text-align: left" colspan="2"><span class="heading">Post Poll</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><strong>Number of Choices</strong></span></td>
		<td class="one" style="text-align: left; width: 80%"><select class="textbox" name="choices"><option value="2">2</option> <? for($i = 3; $i <= $max_poll_choices; $i++) { echo "<option value=\"$i\">$i</option>"; } ?>
		</select></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><strong>Allowed to Vote</strong></span></td>
		<td class="one" style="text-align: left; width: 80%"><select class="textbox" name="poll_type"><option value="1">Registered Users and Guests</option><option selected="selected" value="2">Registered Users Only</option></select></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Continue" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;

	case 'step2':
	$choices = round($choices);
	if($choices < 2 || $choices > $max_poll_choices) {
	header("Location: index.php?page=post_poll&amp;method=newpoll&amp;board=$board");
	}
	else {

	$displaypage = 1;
	$error = "";
	$nli_name = mutate(trim($nli_name));
	$post = mutate(trim(mysslashes($post)));
	$title = mutate(trim(mysslashes($title)));
	$title = str_replace("         ", " ", trim($title));
	$title = str_replace("       ", " ", trim($title));
	$title = str_replace("     ", " ", trim($title));
	$title = str_replace("   ", " ", trim($title));
	$title = str_replace("  ", " ", trim($title));
	$title = str_replace("­", "", trim($title));

	if(isset($preview)) {
	$post = demutate(trim(mysslashes($post)));
	}

	if(isset($save)) {

	array_walk($poll_choice,'array_mutate');
	reset($poll_choice);

	if(!isset($preview)) {
	while($act_value = each($poll_choice)) {
	if($act_value[1] == "") unset($poll_choice[$act_value[0]]);
	}
	reset($poll_choice);
	$choice_number = sizeof($poll_choice);
	if($user_logged_in != 1 && $nli_name == "" && $config['guests_must_enter_a_name'] == 1) $error = "You must enter a name.";
	elseif($user_logged_in != 1 && strlen(trim($nli_name)) < 4) $error = "Your name must be at least 4 characters in length.";
	elseif($user_logged_in != 1 && strlen($nli_name) > 20) $error = "Your name is too many characters.";
	elseif($user_logged_in != 1 && !preg_match("/^[A-Z0-9_ ]+$/i",$nli_name)) $error = "Your name can only contain alphanumeric characters, underscores, and spaces.";
	elseif($title == "") $error = "You must enter a poll question.";
	elseif(strlen(trim($title)) < 6) $error = "Your poll question must be at least 6 characters in length.";
	elseif(strlen($title) > 80) $error = "Your poll question is too many characters.";
	elseif($choice_number < 2 || $choice_number > $max_poll_choices) $error = "You must give at least 2 possible choices.";
	elseif($post == "") $error = "Your post cannot be blank.";
	elseif(strlen($post) > 2056) $error = "Your post is too many characters. The maximum allotment of characters is 2056.";
	else {
	$displaypage = 0;
	$x = 1;
	$date = mydate();
	if($user_logged_in == 1) $user_info = $user_id;
	else {
	if($nli_name == "") $nli_name = "Guest";
	$user_info = "0$nli_name";
	}
	if($poll_type != 2 && $poll_type != 1) $poll_type = 1;

	$new_poll_id = myfile('boards/polls/polls.txt'); $new_poll_id = $new_poll_id[0]+1;
	$new_thread = myfile("boards/$board.id.topics.txt"); $new_thread = $new_thread[0]+1;
	
	$post = nlbr($post);
	$towrite = "1\t$title\t$user_info\t$post_icon\t\t".time()."\t0\t$new_poll_id\t\t\t\t\t\t\n"."1\t$user_info\t$date\t$post\t$REMOTE_ADDR\t1\t$post_icon\t$smilies\t$use_basic_html\t\t\t\t\n";
	myfwrite("boards/$board.topics.txt","$new_thread\n","a"); myfwrite("boards/$board.$new_thread.txt",$towrite,"w"); myfwrite("boards/$board.id.topics.txt",$new_thread,"w");
	increase_topic_amount($board);
	increase_post_amount($board);
	increase_user_posts($user_id); update_last_post($board,$date,$user_info,$new_thread,$post_icon);

	$towrite = "$poll_type\t$user_info\t$date\t$title\t0\t$board,$new_thread\t\t\t\t\t\t\n";
	while($act_value = each($poll_choice)) {
	$towrite .= "$x\t$act_value[1]\t0\t\t\t\t\n";
	$x++;
	}
	myfwrite("boards/polls/$new_poll_id.1.txt",$towrite,'w');
	myfwrite("boards/polls/$new_poll_id.2.txt",'','w');
	myfwrite('boards/polls/polls.txt',$new_poll_id,'w');

	if($board_data['rights'][4] == 1) {
	update_last_posts($board,$new_thread,$user_info,$date);
	}

	$logging = explode(',',$config['record_options']);
	if(in_array(4,$logging)) {
	record("4","%1: Poll Posted ($board,$new_thread) [IP: %2]");
	}
	header("Location: index.php?method=topic&board=$board&thread=$new_thread");
	exit;
	}
	}
	}
	if($displaypage == 1) {
?>

<script type="text/javascript">
<!--
function setsmile(Indication) {
document.contribution.post.value = document.contribution.post.value + Indication;
}
-->
</script><?
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\tPost Poll");
	if(isset($preview)) {
	$preview_post = nlbr($post);
	if($user_data['signature'] != "") $signature = "<span class=\"normal\"><br />---<br />".basic_html_profile($user_data['signature'])."</span>"; else $signature = "";
	if($smilies == 1) $preview_post = make_smilies($preview_post);
	if($use_basic_html == 1 && $board_data['basic_html'] == 1) $preview_post = basic_html($preview_post);
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" style="text-align: left"><span class="heading">Preview Poll</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left"><span class="normal"><? if($config['enable_censor'] == 1) echo censor($preview_post.$signature); else echo $preview_post.$signature?></span></td>
	</tr>
</table>
</object><br /><?
	}
	if(!isset($preview) || $smilies == 1) $checked['smilies'] = " checked=\"checked\"";
	if(!isset($preview) || $use_basic_html == 1) $checked['basic_html'] = " checked=\"checked\"";
?>

<form action="index.php?page=post_poll&amp;board=<?=$board?>&amp;method=step2" method="post" name="contribution"><input name="poll_type" type="hidden" value="<?=$poll_type?>" /><input name="choices" type="hidden" value="<?=$choices?>" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">Post Poll</span></td>
	</tr>
<?
	if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"2\"><span class=\"heading\">Error: $error</span></td>
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
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><strong>Poll Question</strong></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%"><input class="textbox" maxlength="80" name="title" size="80" tabindex="1" type="text" value="<?=trim($title)?>" /></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4" style="text-align: left"><span class="heading">Choices</span></td>
	</tr>
<?
	for($i = 0; $i < $choices; $i++) {
	$i2 = $i+1;
	echo "	<tr>
		<td class=\"one\" style=\"text-align: left\"><span class=\"normal\"><strong>Option $i2</strong></span></td>
		<td class=\"one\" colspan=\"3\" style=\"text-align: left; width: 80%\"><input class=\"textbox\" maxlength=\"80\" name=\"poll_choice[$i]\" size=\"80\" tabindex=\"2\" type=\"text\" value=\"".trim($poll_choice[$i])."\" /></td>
	</tr>
";
	}
?>
	<tr>
		<td class="heading2" colspan="4" style="text-align: left"><span class="heading">Post</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Post Icon</strong></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%" valign="top"><? $post_icon_file = myfile("objects/post_icons.txt"); $post_icon_file_size = sizeof($post_icon_file);
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
		<td class="one" colspan="3" style="text-align: left; width: 80%"><textarea class="textbox" cols="80" name="post" rows="10" tabindex="3"><?=$post?></textarea></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Options</strong></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%"><span class="normal"><?
		if($board_data['basic_html'] == 1) echo "<input$checked[basic_html] name=\"use_basic_html\" tabindex=\"4\" type=\"checkbox\" value=\"1\" /> Enable <acronym title=\"&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;\">Basic HTML</acronym><br />"; ?><input<?=$checked['smilies']?> name="smilies" tabindex="5" type="checkbox" value="1" /> Enable Smilies <input name="save" type="hidden" value="yes" /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" name="preview" tabindex="7" type="submit" value="Preview Poll" /> <input class="button" tabindex="8" type="submit" value="Post Poll" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	}
	break;
	}
	}
?>