<?
	/*
	Silentum Boards v1.4.3
	administrator_user.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");
	administrator();

	if($user_logged_in != 1 || $user_data['id'] != 1) {
	$logging = explode(',',$config['record_options']);
	if(in_array(2,$logging)) {
	record("2","%1: Control Panel Access Attempt [IP: %2]");
	}
	header("Location: index.php");
	exit;
	}
	else {

	switch($method) {

	default:
	$displaypage = 1;
	if(!$user_profile = myfile("members/$id.txt")) {
	header("Location: administrator_user.php");
	exit;
	}
	if(isset($edit)) {
	$displaypage = 0;
	$user_profile[0] = mutate($name)."\n";
	$user_profile[1] = mutate($id)."\n";
	$user_profile[2] = mutate($pw)."\n";
	$user_profile[4] = mutate($status)."\n";
	$user_profile[5] = mutate($posts)."\n";
	$user_profile[6] = mutate($regdat)."\n";
	$user_profile[8] = nlbr(mutate($signature))."\n";
	$user_profile[9] = mutate($aim)."\n";
	$user_profile[12] = mutate($msn)."\n";
	$user_profile[13] = mutate($yahoo)."\n";
	$user_profile[14] = mutate($possiblekarma)."\n";
	$user_profile[15] = mutate($stylesheet)."\n";
	$user_profile[16] = mutate($karma)."\n";
	$user_profile[17] = nlbr(mutate($quote))."\n";
	$user_profile[18] = mutate($icq)."\n";
	$user_profile[20] = mutate($publicemail)."\n";
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: User Edited User ID ".killnl($user_profile[1])." - ".killnl($user_profile[0])." [IP: %2]");
	}
	myfwrite("members/$id.txt",$user_profile,"w");
	include("board_top.php"); echo navigation("<a href=\"index.php?page=profile&amp;id=$id\">View Profile - ".get_user_name($id)."</a>\t".$txt['Navigation']['User_Edited'][0]);
	echo get_message('User_Edited','<br /><br />'.sprintf($txt['Links']['Board_Index'],"<a href=\"index.php\">",'</a>').'<br />View <a href="index.php?page=profile&id='.$id.'">'.get_user_name($id).'\'s Profile</a>');
	}
	}
	}
	if($displaypage == 1) {
	include("board_top.php");
	echo navigation("<a href=\"index.php?page=profile&amp;id=$id\">View Profile - ".get_user_name($id)."</a>\tEdit User");
	if(!isset($name)) $name = killnl($user_profile[0]); else $name = mutate($name);
	if(!isset($id)) $id = killnl($user_profile[1]); else $id = mutate($id);
	if(!isset($pw)) $pw = killnl($user_profile[2]); else $pw = mutate($pw);
	if(!isset($status)) $status = killnl($user_profile[4]); else $status = mutate($status);
	if(!isset($posts)) $posts = killnl($user_profile[5]); else $posts = mutate($posts);
	if(!isset($regdat)) $regdat = killnl($user_profile[6]); else $regdat = mutate($regdat);
	if(!isset($signature)) $signature = killnl($user_profile[8]); else $signature = mutate($signature);
	if(!isset($aim)) $aim = killnl($user_profile[9]); else $aim = mutate($aim);
	if(!isset($msn)) $msn = killnl($user_profile[12]); else $msn = mutate($msn);
	if(!isset($yahoo)) $yahoo = killnl($user_profile[13]); else $yahoo = mutate($yahoo);
	if(!isset($possiblekarma)) $possiblekarma = killnl($user_profile[14]); else $possiblekarma = mutate($possiblekarma);
	if(!isset($stylesheet)) $stylesheet = killnl($user_profile[15]); else $stylesheet = mutate($stylesheet);
	if(!isset($karma)) $karma = killnl($user_profile[16]); else $karma = mutate($karma);
	if(!isset($quote)) $quote = killnl($user_profile[17]); else $quote = mutate($quote);
	if(!isset($icq)) $icq = killnl($user_profile[18]); else $icq = mutate($icq);
	if(!isset($publicemail)) $publicemail = killnl($user_profile[20]); else $publicemail = mutate($publicemail);
?>

<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
cntfield.value = maxlimit - field.value.length;
}
</script>
<form action="administrator_user.php?method=edit&amp;edit=yes" method="post" name="hostedit">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="4"><span class="heading">Edit User</span></td>
	</tr>
	<tr>
		<td class="heading2" colspan="4"><span class="heading">User Information</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%" valign="middle"><span class="normal"><strong>User ID</strong></span></td>
		<td class="one" style="width: 30%" valign="top"><span class="normal"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"7\" name=\"id\" size=\"6\" tabindex=\"1\" type=\"text\" value=\"$id\" />" ?></span></td>
		<td class="one" style="width: 20%" valign="middle"><span class="normal"><strong>E-mail Address</strong></span></td>
		<td class="one" style="width: 30%" valign="top"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"100\" name=\"publicemail\" size=\"30\" tabindex=\"10\" type=\"text\" value=\"$publicemail\" />" ?></td>
	</tr>
	<tr>
		<td class="two" valign="middle"><span class="normal"><strong>User Name</strong></span></td>
		<td class="two" valign="top"><span class="normal"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"20\" name=\"name\" tabindex=\"2\" type=\"text\" value=\"$name\" />" ?></span></td>
		<td class="two" valign="middle"><span class="normal"><strong>Registration Date</strong></span></td>
		<td class="two" valign="middle"><span class="normal"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"24\" name=\"regdat\" tabindex=\"4\" type=\"text\" value=\"$regdat\" />" ?></span></td>
	</tr>
	<tr>
		<td class="one" valign="middle"><span class="normal"><strong>User Status</strong></span></td>
		<td class="one" valign="top"><span class="normal"><? if($status == "1") $statusnew = "Administrator"; ?><? if($status == "2") $statusnew = "Moderator"; ?><? if($status == "3") $statusnew = "User"; ?><? if($status == "4") $statusnew = "Banned"; ?><? if($status == "5") $statusnew = "Deleted"; ?><? if($status == "6") $statusnew = "Suspended"; ?><? if($status == "7") $statusnew = "Closed"; ?><? if($id != 0) echo "<select class=\"textbox\" name=\"status\" tabindex=\"3\"><option value=\"".trim($user_profile[4])."\">$statusnew - Current</option><option value=\"1\">Administrator</option><option value=\"2\">Moderator</option><option value=\"3\">User</option><option value=\"7\">Closed</option><option value=\"6\">Suspended</option><option value=\"4\">Banned</option><option value=\"5\">Deleted</option></select>" ?></span></td>
		<td class="one" valign="middle"><span class="normal"><strong>Password (Encrypted)</strong></span></td>
		<td class="one" valign="top"><span class="normal"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"35\" name=\"pw\" tabindex=\"5\" type=\"text\" value=\"$pw\" />" ?></span></td>
	</tr>
	<tr>
		<td class="two" valign="middle"><span class="normal"><strong>Karma/Possible Karma/Posts</strong></span></td>
		<td class="two" valign="top"><span class="normal"><? if($user_data['id'] != 0) echo "<input class=\"textbox\" maxlength=\"10\" name=\"karma\" size=\"3\" tabindex=\"7\" type=\"text\" value=\"$karma\" /> <strong>/</strong> <input class=\"textbox\" maxlength=\"10\" name=\"possiblekarma\" size=\"3\" tabindex=\"7\" type=\"text\" value=\"$possiblekarma\" /> <strong>/</strong> <input class=\"textbox\" maxlength=\"10\" name=\"posts\" size=\"3\" tabindex=\"7\" type=\"text\" value=\"$posts\" />" ?></span></td>
		<td class="two" valign="middle"><span class="normal"><strong>Stylesheet</strong></span></td>
		<td class="two" valign="middle"><span class="normal"><select class="textbox" name="stylesheet" tabindex="7"><?
	$dir = "stylesheets/";
	$handle = @opendir($dir);
	while ($file = @readdir ($handle))
	{
	if(eregi("^\.{1,2}$",$file))
	{
	continue;
	}

	if(!is_dir($dir.$file))

	{
	if($stylesheet == $dir.$file) echo '<option selected="selected" value="'.$dir.$file.'">'.substr($file, 0, -4).'</option>';
	elseif(substr($file, 0, 5) == "board" or substr($file, 0, 3) == "cat") echo "";
	else echo '<option value="'.$dir.$file.'">'.substr($file, 0, -4).'</option>';
	}
	}
	@closedir($handle);
?>
</select></span> <span class="normal"><a href="javascript:void(0)" onclick="window.open('stylesheets/previewer/previewer.php','Stylesheet Previewer','height=350, width=550,scrollbars=no')">Previewer</a></span></td>
	</tr>
	<tr>
		<td class="one" rowspan="3" style="width: 20%" valign="top"><span class="normal"><strong>Signature</strong><br /><acronym title="&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;">Basic HTML</acronym> <strong>Enabled</strong><br /><strong>5 Line Breaks/350 Chars</strong> Max<br /><input class="textbox" maxlength="3" name="remLen1" readonly="readonly" size="4" style="text-align: center" type="text" value="350" /> characters left</span></td>
		<td class="one" rowspan="3" style="width: 30%" valign="top"><textarea class="textbox" cols="35" name="signature" rows="6" onKeyDown="textCounter(document.hostedit.signature,document.hostedit.remLen1,350)" onKeyUp="textCounter(document.hostedit.signature,document.hostedit.remLen1,350)" tabindex="14"><?=brnl(killnl($user_profile[8]))?></textarea></td>
		<td class="one" rowspan="3" style="width: 20%" valign="top"><span class="normal"><strong>Quote</strong><br /><acronym title="&lt;b&gt;bold&lt;/b&gt; &lt;i&gt;italic&lt;/i&gt; &lt;u&gt;underline&lt;/u&gt; &lt;s&gt;strikeout&lt;/s&gt;">Basic HTML</acronym> <strong>Enabled</strong><br /><strong>10 Line Breaks/700 Chars</strong> Max<br /><input class="textbox" maxlength="3" name="Infor1" readonly="readonly" size="4" style="text-align: center" type="text" value="700" /> characters left</span></td>
		<td class="one" rowspan="3" style="width: 30%" valign="top"><textarea class="textbox" cols="35" rows="6" name="quote" onKeyDown="textCounter(document.hostedit.quote,document.hostedit.Infor1,700)" onKeyUp="textCounter(document.hostedit.quote,document.hostedit.Infor1,700)" tabindex="15"><?=brnl(killnl($user_profile[17]))?></textarea></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading2" colspan="4" style="text-align: left"><span class="heading">Instant Message Information</span></td>
	</tr>
	<tr>
		<td class="one" style="width: 20%" valign="middle"><span class="normal"><strong>AIM Handle</strong></span></td>
		<td class="one" style="width: 30%" valign="top"><span class="normal"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"16\" name=\"aim\" tabindex=\"11\" type=\"text\" value=\"$aim\" />" ?></span></td>
		<td class="one" style="width: 20%" valign="middle"><span class="normal"><strong>MSN Handle</strong></span></td>
		<td class="one" style="width: 30%" valign="top"><span class="normal"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"100\" name=\"msn\" tabindex=\"12\" type=\"text\" value=\"$msn\" />" ?></span></td>
	</tr>
	<tr>
		<td class="two" valign="middle"><span class="normal"><strong>ICQ Handle</strong></span></td>
		<td class="two" valign="top"><span class="normal"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"16\" name=\"icq\" tabindex=\"11\" type=\"text\" value=\"$icq\" />" ?></span></td>
		<td class="two" valign="middle"><span class="normal"><strong>Yahoo! Handle</strong></span></td>
		<td class="two" valign="top"><span class="normal"><? if($id != 0) echo "<input class=\"textbox\" maxlength=\"20\" name=\"yahoo\" tabindex=\"13\" type=\"text\" value=\"$yahoo\" />" ?></span></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" tabindex="16" type="submit" value="Edit User" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	include("board_bottom.php");
	break;
?>