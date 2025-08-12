<?
	/*
	Silentum Boards v1.4.3
	post_topic.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	$right = 0;

	if(!$board_data = get_board_data($board)) { echo navigation($txt['Navigation']['Error']['0']);
	echo get_message('Error','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	elseif($user_logged_in != 1) {
	if($board_data['rights'][5] == 1 || $user_data['status'] == "2") $right = 1;
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
	elseif(check_right($board,1) != 1 && $user_data['status'] != "2") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Restricted'][0]);
	echo get_message('Restricted','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	else $right = 1;
	}

	if($right == 1) {
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

	if($save == "yes" && !$preview) {
	if($user_logged_in != 1 && $nli_name == "" && $config['guests_must_enter_a_name'] == 1) $error = "You must enter a name.";
	elseif($user_logged_in != 1 && strlen(trim($nli_name)) < 4) $error = "Your name must be at least 4 characters in length.";
	elseif($user_logged_in != 1 && strlen($nli_name) > 20) $error = "Your name is too many characters.";
	elseif($user_logged_in != 1 && !preg_match("/^[A-Z0-9_ ]+$/i",$nli_name)) $error = "Your name can only contain alphanumeric characters, underscores, and spaces.";
	elseif($title == "") $error = "You must enter a topic title.";
	elseif(strlen(trim($title)) < 6) $error = "Your topic title must be at least 6 characters in length.";
	elseif(strlen($title) > 80) $error = "Your topic title is too many characters.";
	elseif($post == "") $error = "Your post cannot be blank.";
	elseif(strlen($post) > 2056) $error = "Your post is too many characters. The maximum allotment of characters is 2056.";
	else {
	$displaypage = 0;
	$new_id = myfile("boards/$board.id.topics.txt"); $new_id = $new_id[0]+1;
	$post = nlbr($post);
	$date = mydate();

	if($user_logged_in == 1) $user_info = $user_id;
	else {
	if($nli_name == "") $nli_name = "Guest";
	$user_info = "0$nli_name";
	}

	$towrite = "1\t$title\t$user_info\t$post_icon\t\t".time()."\t0\t\t\t\t\t\t\t\n"."1\t$user_info\t$date\t$post\t$REMOTE_ADDR\t1\t$post_icon\t$smilies\t$use_basic_html\t\t\t\t\r\n";
	myfwrite("boards/$board.topics.txt","$new_id\r\n","a"); myfwrite("boards/$board.$new_id.txt",$towrite,"w"); myfwrite("boards/$board.id.topics.txt",$new_id,"w");
	increase_topic_amount($board);
	increase_post_amount($board);
	increase_user_posts($user_id); update_last_post($board,$date,$user_info,$new_id,$post_icon);
	if($board_data['rights'][4] == 1) {
	update_last_posts($board,$new_id,$user_info,$date);
	}

	$logging = explode(',',$config['record_options']);
	if(in_array(4,$logging)) {
	record("4","%1: Topic Posted ($board,$new_id) [IP: %2]");
	}
	header("Location: index.php?method=topic&board=$board&thread=$new_id");
	exit;
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
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\tPost Topic");
	if($preview) {
	$preview_post = nlbr($post);
	if($user_data['signature'] != "") $signature = "<span class=\"normal\"><br />---<br />".basic_html_profile($user_data[signature])."</span>"; else $signature = "";
	if($smilies == 1) $preview_post = make_smilies($preview_post);
	if($use_basic_html == 1 && $board_data[basic_html] == 1) $preview_post = basic_html($preview_post);
?>

<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" style="text-align: left"><span class="heading">Preview Topic</span></td>
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

<form action="index.php?page=post_topic" method="post" name="contribution">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4" style="text-align: left"><span class="heading">Post Topic</span></td>
	</tr>
<?
	if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"4\"><span class=\"heading\">Error: $error</span></td>
	</tr>
";
	if($user_logged_in != 1) {
?>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Your Name</strong></span></td>
	 	<td class="one" colspan="3" style="text-align: left; width: 80%"><input class="textbox" maxlength="20" name="nli_name" size="20" type="text" value="<?=trim($nli_name)?>" /></td>
	</tr>
<?
	}
?>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Post Icon</strong></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%" valign="top"><?$post_icon_file = myfile("objects/post_icons.txt"); $post_icon_file_size = sizeof($post_icon_file);
		for($i = 0; $i < $post_icon_file_size; $i++) {
		$act_post_icon = myexplode($post_icon_file[$i]);
		if($i == 0) $checked[post_icon] = " checked=\"checked\"";
		elseif($i+1 == $post_icon) $checked[post_icon] = " checked=\"checked\""; else $checked[post_icon] = "";
		echo "<input$checked[post_icon] name=\"post_icon\" type=\"radio\" value=\"".$act_post_icon[0]."\" /> <img alt=\"Post Icon\" class=\"icon\" src=\"".$act_post_icon[1]."\" title=\"Post Icon\" /> ";
		if(($i + 1) % 7 == 0) echo "<br />";
		} ?></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="middle"><span class="normal"><strong>Topic Title</strong></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%"><input class="textbox" maxlength="80" name="title" size="80" tabindex="1" type="text" value="<?=demutate(trim($title))?>" /></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left" valign="top"><span class="normal"><strong>Post</strong><br /><br /><strong>Smilies</strong><br /><br /><? $smilie_file = myfile("objects/smilies.txt"); $smilie_file_size = sizeof($smilie_file);
		for($i = 0; $i < $smilie_file_size; $i++) {
		$act_smilie = myexplode($smilie_file[$i]);
		echo "<a class=\"indent\" href=\"javascript:setsmile('%20$act_smilie[1]%20')\" onmouseover=\"status='Smilie';return true\"><img alt=\"$act_smilie[1]\" class=\"icon\" src=\"".trim($act_smilie[2])."\" title=\"$act_smilie[1]\" /></a> ";
		if(($i + 1) % 4 == 0) echo "<br />";
		} ?><br /></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%"><textarea class="textbox" cols="80" name="post" rows="10" tabindex="2"><?=$post?></textarea></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%" valign="top"><span class="normal"><strong>Options</strong></span></td>
		<td class="one" colspan="3" style="text-align: left; width: 80%"><span class="normal"><?
		if($board_data['basic_html'] == 1) echo "<input$checked[basic_html] name=\"use_basic_html\" tabindex=\"3\" type=\"checkbox\" value=\"1\" /> Enable <acronym title=\"&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;\">Basic HTML</acronym><br />";
		?><input<?=$checked[smilies]?> name="smilies" tabindex="4" type="checkbox" value="1" /> Enable Smilies <input name="save" type="hidden" value="yes" /> <input name="board" type="hidden" value="<?=$board?>" /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" name="preview" tabindex="6" type="submit" value="Preview Topic" /> <input class="button" tabindex="7" type="submit" value="Post Topic" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	}
?>