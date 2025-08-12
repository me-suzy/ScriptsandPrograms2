<?php

mod_header("View User Info - Moderator Control Panel");

if (empty($_GET['uid']) || !isInt($_GET['uid'])) mod_exception_in("Invalid User ID");

$userinfo =& $DB->result('SELECT u.*,g.title gptitle,o.ipaddress FROM celeste_useronline o right join celeste_user u using(userid) ,celeste_usergroup g where u.usergroupid=g.usergroupid and u.userid=\''.$_GET['uid'].'\'');

?>
<br>
<input type=hidden name=action value='option'>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2>
You are now managing user in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br><br>
View User Info :  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; [ <a href="modpanel.php?fid=<?=$forumid?>&prog=user::set&uid=<?=$userinfo['userid']?>">Set Permission In this Forum</a> ]<br>
<hr size=1>
</td>
<td>
</td>
</tr>

<tr><td> User ID : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['userid']?>"></td></tr>

<tr><td> User Name : </td>
<td>
<input readonly type=text  size=40  value="<?=$userinfo['username']?>"></td></tr>

<tr><td> User Group : </td>
<td>
<input readonly type=text  size=40  value="<?=$userinfo['gptitle']?>"></td></tr>

<tr><td> Email : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['email']?>"></td></tr>

<tr><td> Title : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['title']?>"></td></tr>

<tr><td> Homepage : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['homepage']?>"></td></tr>

<tr><td> ICQ : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['icq']?>"></td></tr>

<tr><td> MSN Messenger : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['msn']?>"></td></tr>

<tr><td> AOL Instant Messenger : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['aim']?>"></td></tr>

<tr><td> Yahoo Messenger : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['yahoo']?>"></td></tr>

<tr><td> Date of Birth : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['birth']?>"></td></tr>

<tr><td> Location : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['location']?>"></td></tr>

<tr><td> Posts : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['posts']?>"></td></tr>

<tr><td> Rating Credits : </td>
<td>
<input readonly type=text size=40  value="<?=$userinfo['totalrating']?>"></td></tr>

<tr><td> Last Post Date : </td>
<td>
<input readonly type=text size=40  value="<?=getTime($userinfo['lastpost'])?>"></td></tr>

<tr><td> Join Date :</td><td>
<input readonly type=text size=40  value="<?=$userinfo['joindate']?>"></td>
</tr>

<tr><td> Last IP Address : </td>
<td>
<input readonly type=text size=40 value="<?=$userinfo['ipaddress']?>"></td></tr>

</table>

<br>

<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td> Search :
<hr size=1>
</td></tr>
<tr><td><input type=submit name=submit value=" Search "></td>
</table>
</form>

<?php


mod_footer();

function getTimeStamp ( $date ) {
  list($year, $month, $day) = explode('-', $date);
  return mktime ( 0,0,0, $month, $day, $year);
}