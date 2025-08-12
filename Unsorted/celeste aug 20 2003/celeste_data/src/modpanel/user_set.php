<?php
if (!getpermission('setpermission')) mod_exception('Permission denied.');

mod_header("Set User Permission - Moderator Control Panel");


if (empty($_GET['uid']) || !isInt($_GET['uid'])) mod_exception_in("Invalid User ID");

if (($ugtoset=$DB->result('select usergroupid from celeste_user where userid=\''.$_GET['uid'].'\''))<=3 && $usergroupid>=$ugtoset && $usergroupid!=1)
mod_exception_in("Permission Denied");

if (!empty($_POST['submit'])) {

  if ($DB->result("select count(*) from celeste_permission where forumid=".$forumid." and userid=".$_GET['uid'])) {
    $DB->update("update celeste_permission set
    allowview='".($_POST['allowview'] ? 1 : 0)."',
    allowcreatetopic='".($_POST['allowcreatetopic'] ? 1 : 0)."',
    allowreply='".($_POST['allowreply'] ? 1 : 0)."',
    allowcreatepoll='".($_POST['allowcreatepoll'] ? 1 : 0)."',
    allowvote='".($_POST['allowvote'] ? 1 : 0)."',
    allowupload='".($_POST['allowupload'] ? 1 : 0)."',
    allowcetag='".($_POST['allowcetag'] ? 1 : 0)."',
    allowimage='".($_POST['allowimage'] ? 1 : 0)."',
    allowhtml='".($_POST['allowhtml'] ? 1 : 0)."',
    allowsmiles='".($_POST['allowsmiles'] ? 1 : 0)."'
    where forumid=".$forumid." and usergroupid=0 and userid=".$_GET['uid']);

  } else {
    $DB->update("insert into celeste_permission set
    allowview='".($_POST['allowview'] ? 1 : 0)."',
    allowcreatetopic='".($_POST['allowcreatetopic'] ? 1 : 0)."',
    allowreply='".($_POST['allowreply'] ? 1 : 0)."',
    allowcreatepoll='".($_POST['allowcreatepoll'] ? 1 : 0)."',
    allowvote='".($_POST['allowvote'] ? 1 : 0)."',
    allowupload='".($_POST['allowupload'] ? 1 : 0)."',
    allowcetag='".($_POST['allowcetag'] ? 1 : 0)."',
    allowimage='".($_POST['allowimage'] ? 1 : 0)."',
    allowhtml='".($_POST['allowhtml'] ? 1 : 0)."',
    allowsmiles='".($_POST['allowsmiles'] ? 1 : 0)."',
    forumid=".$forumid.",usergroupid=0,userid=".$_GET['uid']);
  }
  mod_success_redirect_in('Permission(s) has been set.', 'fid='.$forumid.'&prog=user::permission' );
}

$username =& $DB->result('select username from celeste_user where userid=\''.$_GET['uid'].'\'');

$oldresult =& $DB->result('select * from celeste_permission where forumid='.$forumid.' and userid=\''.$_GET['uid'].'\'');

?>
<br>

<form method=post>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2>
You are now managing user in :
<br><br><b> Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br><br>
Set User Permission :  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; [ <a href="modpanel.php?fid=<?=$forumid?>&prog=user::view&uid=<?=$userinfo['userid']?>"><b><?=$username?></b></a> ]<br>
<hr size=1>
</td>
<td>
</td>
</tr>

<tr><td width=50%> User ID : </td>
<td width=50%>
<?=$_GET['uid']?></td></tr>

<tr><td width=50%> User Name : </td>
<td width=50%>
<?=$username?></td></tr>

<tr>
<td>can view this forum?</td>
<td>
<select name=allowview>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowview'])) echo 'selected'; ?>>yes</option>
</select>
</td>
</tr>

<tr>
<td>can add new topic?</td>
<td>
<select name=allowcreatetopic>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowcreatetopic'])) echo 'selected'; ?>>yes</option>
</select>
</td>
</tr>

<tr>
<td>can reply topic?</td>
<td><select name=allowreply>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowreply'])) echo 'selected'; ?>>yes</option>
</select></td>
</tr>


<tr>
<td>can add new poll?</td>
<td><select name=allowcreatepoll>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowcreatepoll'])) echo 'selected'; ?>>yes</option>
</select></td>
</tr>

<tr>
<td>can vote in poll?</td>
<td><select name=allowvote>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowvote'])) echo 'selected'; ?>>yes</option>
</select></td>
</tr>

<tr>
<td>can upload in post?</td>
<td><select name=allowupload>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowupload'])) echo 'selected'; ?>>yes</option>
</select></td>
</tr>

<tr>
<td>can use Celeste Tag in post?</td>
<td><select name=allowcetag>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowcetag'])) echo 'selected'; ?>>yes</option>
</select></td>
</tr>


<tr>
<td>can use Image Tag in post?</td>
<td><select name=allowimage>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowimage'])) echo 'selected'; ?>>yes</option>
</select></td>
</tr>


<tr>
<td>can use HTML in post?</td>
<td><select name=allowhtml>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowhtml'])) echo 'selected'; ?>>yes</option>
</select></td>
</tr>


<tr>
<td>can use Smiles Tag in post?</td>
<td><select name=allowsmiles>
<option value=0>no</option>
<option value=1 <? if (!empty($oldresult['allowsmiles'])) echo 'selected'; ?>>yes</option>
</select></td>
</tr>


</table>

<br>

<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td> Set  :
<hr size=1>
</td></tr>
<tr><td><input type=submit name=submit value=" Set "></td>
</table>
</form>

<?php


mod_footer();
