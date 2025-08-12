<?
	/*
	Silentum Boards v1.4.3
	mod_post.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	if(!$topic_file = myfile("boards/$board.$thread.txt")) die("There was an error while loading the post data.");
	else {

	$topic_data = myexplode($topic_file[0]);
	if($config['enable_censor'] == 1) $topic_data[1] = censor($topic_data[1]);
	$post_data = get_post_data($board,$thread,$post_id);
	$board_data = get_board_data($board);

	if($user_logged_in != 1 || $user_data['status'] != 1 && $user_data['status'] != 2) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">$board_data[name]</a>\t".$txt['Navigation']['Restricted'][0]);
	echo get_message('Restricted','<br /><br />'.sprintf($txt['Links']['Topic'],"<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">",'</a>').'<br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}

	else {

	$save = "";

	switch($method) {
	default:
	if($post_data['signature'] == 1) $checked['sig'] = " checked=\"checked\"";
	if($post_data['smilies'] == 1) $checked['smilies'] = " checked=\"checked\"";
	if($post_data['basic_html'] == 1 && $board_data['basic_html']) $checked['basic_html'] = " checked=\"checked\"";
?>

<script type="text/javascript">
<!--
function setsmile(Indication) {
document.contribution.post.value = document.contribution.post.value + Indication;
}
-->
</script><? echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".$board_data['name']."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tEdit Post")?>

<form action="index.php?page=mod_post&amp;thread=<?=$thread?>&amp;post_id=<?=$post_id?>&amp;board=<?=$board?>" method="post" name="contribution"><input name="method" type="hidden" value="update" /><input name="queue_id" type="hidden" value="<?=$queue_id?>" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Edit Post</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%" valign="top"><span class="normal"><strong>Post</strong><br /><br /><strong>Smilies</strong><br /><br /><? $sm_file = myfile("objects/smilies.txt"); $sm_file_size = sizeof($sm_file);
		for($i = 0; $i < $sm_file_size; $i++) {
		$act_sm = myexplode($sm_file[$i]);
		echo "<a class=\"indent\" href=\"javascript:setsmile('%20$act_sm[1]%20')\" onmouseover=\"status='Smilie';return true\"><img alt=\"$act_sm[1]\" class=\"icon\" src=\"".trim($act_sm[2])."\" title=\"$act_sm[1]\" /></a> ";
		if(($i + 1) % 4 == 0) echo "<br />";
		} ?></span></td>
		<td class="one" style="width: 80%"><textarea class="textbox" cols="60" name="post" rows="10"><?=brnl($post_data[post])?></textarea></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%" valign="top"><span class="normal"><strong>Options</strong></span></td>
		<td class="one" style="width: 80%"><span class="normal"><? if($board_data['basic_html'] == 1) echo "<input".$checked['basic_html']." name=\"use_basic_html\" type=\"checkbox\" value=\"1\" /> Enable <acronym title=\"&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;\">Basic HTML</acronym><br />"; ?><input<?=$checked['smilies']?> name="smilies" type="checkbox" value="1" /> Enable Smilies<br /><input<?=$checked['sig']?> name="show_signature" type="checkbox" value="1" /> Show Signature</span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Edit Post" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;

	case "update":
	$datetime = date("Y-F-d / h:i:sa");
	$post = nlbr(trim(mutate($post)));
	for($i = 1; $i < sizeof($topic_file); $i++) {
	$act_post = myexplode( $topic_file[$i]);
	if($act_post[0] == $post_id) {
	$act_post[3] = "".$post."<br /><br /><span class=\"normal\"><em>Edited ".$datetime." by ".$user_data['nick']."</em></span>";
	$act_post[5] = $show_signature;
	$act_post[7] = $smilies;
	$act_post[8] = $use_basic_html;
	$topic_file[$i] = myimplode($act_post);
	$save = 1;
	break;
	}
	}
	if($save == 1) {
	myfwrite("boards/$board.$thread.txt",$topic_file,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("5","%1: Post Edited ($board,$thread,$post_id) [IP: %2]");
	}
	$queue = myfile("objects/queue.txt");
	for($i = 0; $i < sizeof($queue); $i++) {
	$act_queue = myexplode($queue[$i]);
	if($queue_id == $act_queue[0]) {
	$deletequeue = 1; $queue[$i] = ""; break;
	}
	}
	if($deletequeue == 1) {
	myfwrite("objects/queue.txt",$queue,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("8","%1: Queue Item Deleted [IP: %2]");
	}
	}
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".$board_data['name']."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tPost Edited");
	echo get_message('Post_Edited','<br /><br />'.sprintf($txt['Links']['Queue'],"<a href=\"index.php?page=queue\">",'</a>').'<br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	break;

	case "remove":
	if($remove != "yes") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".$board_data['name']."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tRemove Post");
?>

<form action="index.php?page=mod_post&amp;method=remove&amp;board=<?=$board?>&amp;thread=<?=$thread?>&amp;post_id=<?=$post_id?>" method="post"><input name="remove" type="hidden" value="yes" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2"><span class="heading">Remove Post</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center"><span class="normal"><br />Do you really want to remove this post?<br /><br /><strong>Note: This action is irreversible and the post will be removed from the entire database. Only do this under <ins>extreme</ins> circumstances.</strong><br /><br /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Remove Post" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	else {
	$topic_file_size = sizeof($topic_file);
	for($i = 1; $i < $topic_file_size; $i++) {
	$currentr_post = myexplode( $topic_file[$i]);
	if($post_id == $currentr_post[0]) {
	$topic_file[$i] = "";
	$save = 1;
	break;
	}
	}
	if($save == 1) {
	myfwrite("boards/$board.$thread.txt",$topic_file,"w");
	decrease_post_amount($board,1);
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("5","%1: Post Removed ($board,$thread,$post_id) [IP: %2]");
	}

	if($topic_file_size == 2) {
	$topic_deleted = 1;

	if($topic_data[7] != "") {
	unlink("boards/polls/$topic_data[7].1.txt");
	unlink("boards/polls/$topic_data[7].2.txt");
	}

	unlink("boards/$board.$thread.txt");
	$topics = myfile("boards/$board.topics.txt");
	for($i = 0; $i < sizeof($topics); $i++) {
	if($thread == killnl($topics[$i])) {
	$topics[$i] = "";
	$save = "yes";
	break;
	}
	}
	if($save == "yes") {
	myfwrite("boards/$board.topics.txt",$topics,"w");
	decrease_topic_amount($board);
	}
	else echo "An error has occurred.";
	}
	$queue = myfile("objects/queue.txt");
	for($i = 0; $i < sizeof($queue); $i++) {
	$act_queue = myexplode($queue[$i]);
	if($queue_id == $act_queue[0]) {
	$deletequeue = 1; $queue[$i] = ""; break;
	}
	}
	if($deletequeue == 1) {
	myfwrite("objects/queue.txt",$queue,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("8","%1: Queue Item Deleted [IP: %2]");
	}
	}
	if($topic_deleted == 1) {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".$board_data['name']."</a>\tPost Removed");
	echo get_message('Post_Removed','<br /><br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	else {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".$board_data['name']."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tPost Removed");
	echo get_message('Post_Removed','<br /><br />'.sprintf($txt['Links']['Topic'],"<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread&amp;z=last\">",'</a>').'<br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}

	}
	else echo "An error has occurred.";
	}
	break;

	case "delete":
	if($delete != "yes") {
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".$board_data['name']."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tDelete Post");
?>

<form action="index.php?page=mod_post&amp;method=delete&amp;board=<?=$board?>&amp;thread=<?=$thread?>&amp;post_id=<?=$post_id?>" method="post" name="contribution"><input name="method" type="hidden" value="update2" /><input name="queue_id" type="hidden" value="<?=$queue_id?>" />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1"><span class="heading">Delete Post</span></td>
	</tr>
	<tr>
		<td class="one" style="text-align: center" valign="top"><span class="normal"><br /><input name="deleted" type="hidden" value="<?=htmlspecialchars(get_post($board,$thread,$post_id))?>" /><input name="post" type="hidden" value="-- This post has been deleted by a Moderator or an Administrator --" /><strong>After deleting this post...</strong><br /><br /><select class="textbox" name="karmayes"><option value="1">Subtract 1 Karma</option><option value="0">Do Not Subtract 1 Karma</option></select><br /><br /><strong>and...</strong><br /><br /><select class="textbox" name="suspendyes"><option value="0">Do Not Suspend User</option><option value="1">Suspend User</option></select><br /><br /></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" type="submit" value="Delete Post" /></td>
	</tr>
</table>
</object>
</form>
<?
	break;
	}

	case "update2":
	$post = nlbr(trim(mutate($post)));
	for($i = 1; $i < sizeof($topic_file); $i++) {
	$act_post = myexplode( $topic_file[$i]);
	if($act_post[0] == $post_id) {
	$act_post[3] = $post;
	$act_post[5] = $show_signature;
	$act_post[7] = $smilies;
	$act_post[8] = $use_basic_html;
	$deleted = killnl($deleted);
	$topic_file[$i] = myimplode($act_post);
	$savedelete = 1;
	break;
	}
	}
	if($savedelete == 1) {
	myfwrite("boards/$board.$thread.txt",$topic_file,"w");
	if($karmayes == "1" && $suspendyes != "1") {
	decrease_karma($act_post[1]);
	$karmaloss = "1";
	}
	$timesent = date("Y-F-d / h:i:sa");
	$moderations = myfile("members/".$act_post[1].".moderations.txt");
	$new_id = sizeof($moderations)+1;
	$towrite = "$new_id\t$timesent\t$board\t$thread\t$karmaloss\t$deleted\t\n";
	myfwrite("members/".$act_post[1].".moderations.txt",$towrite,"a");
	$reference = trim(mutate($reference)); $notebox = nlbr(trim(mutate($notebox)));
	$new_id2 = myfile("members/$act_post[1].notebox.txt"); $new_id2 = myexplode($new_id2[sizeof($new_id2)-1]); $new_id2 = $new_id2[0]+1;
	$reference = "You have received a moderation";
	$notebox = "One of your posts has been deleted for violating the Terms of Service. Please review your <a href=\"index.php?page=moderations\">moderations</a> and re-read the Terms of Service if necessary.";
	$towrite2 = "$new_id2\t$reference\t$notebox\t$note_box_id\t$timesent\t1\t1\t1\t\r\n";
	myfwrite("members/$act_post[1].notebox.txt",$towrite2,"a");
	$queue = myfile("objects/queue.txt");
	for($i = 0; $i < sizeof($queue); $i++) {
	$act_queue = myexplode($queue[$i]);
	if($queue_id == $act_queue[0]) {
	$deletequeue = 1; $queue[$i] = ""; break;
	}
	}
	if($suspendyes == "1") {
	suspend_user($act_post[1]);
	}
	
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: User ID ".$act_post[1]." Suspended [IP: %2]");
	}
	}
	if($deletequeue == 1) {
	myfwrite("objects/queue.txt",$queue,"w");
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("8","%1: Queue Item Deleted [IP: %2]");
	}
	}
	$logging = explode(',',$config['record_options']);
	if(in_array(5,$logging)) {
	record("5","%1: Post Deleted ($board,$thread,$post_id) [IP: %2]");
	}
	echo navigation("<a href=\"index.php?method=board&amp;board=$board\">".$board_data['name']."</a>\t<a href=\"index.php?method=topic&amp;board=$board&amp;thread=$thread\">$topic_data[1]</a>\tPost Deleted");
	echo get_message('Post_Deleted','<br /><br />'.sprintf($txt['Links']['Queue'],"<a href=\"index.php?page=queue\">",'</a>').'<br />'.sprintf($txt['Links']['Topic_Index'],"<a href=\"index.php?method=board&amp;board=$board\">",'</a>').'<br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>'));
	}
	}
	}
?>