<?php
if (!getpermission('setpermission')) mod_exception('Permission denied.');

mod_header("Browse User Permissions - Moderator Control Panel");

if (!empty($_POST['submit']) && !empty($_POST['confirm']) && isset($_POST['toDel']) && is_array($_POST['toDel']) && (count($_POST['toDel'])>0)) {

    $query = 'delete from celeste_permission where forumid=\''.$forumid.'\' and permissionid in(';
    foreach($_POST['toDel'] as $i) $query.= $i.',';
    $query = substr($query,0,-1).')';

    $DB->update($query);
    mod_success_redirect_in('Permission(s) has been deleted.', 'fid='.$forumid.'&prog=user::permission' );
}

$permissions = $DB->query('select p.*,u.username from celeste_permission p,celeste_user u where  u.userid=p.userid and forumid=\''.$forumid.'\' and p.usergroupid=0 and u.usergroupid>'.$usergroupid);

$content = '';

while($dataRow=&$permissions->fetch()) {
    
  $content .= '<tr align=center><td><a href=\'modpanel.php?fid='.$forumid.'&prog=user::set&uid='.$dataRow['userid'].'\'><img src="images/mod/edit.gif" border=0> '.$dataRow['username'].'</a></td><td><img src="images/mod/check_'.
            ($dataRow['allowview'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowcreatetopic'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowreply'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowcreatepoll'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowvote'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowupload'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowcetag'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowimage'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowhtml'] ? 'y' : 'n').'.gif"></td><td><img src="images/mod/check_'.
            ($dataRow['allowsmiles'] ? 'y' : 'n').'.gif"></td><td>'.
            '<input type=checkbox name=toDel[] value='.$dataRow['permissionid'].'></td></tr>';
}
?>
<form method=post>
<input type=hidden name=action value='option'>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=12>
You are now browsing user permissions in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br><br>
Single User Permissions In this Forum :  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;[ <a href="modpanel.php?fid=<?=$forumid?>&prog=user::search"><img src="images/mod/next.gif" border=0> Find a user and set Permission</a> ]<br>
<hr size=1>
</td></tr>
<tr align=center>
<td>user</td><td>view</td><td>add topic</td><td>reply</td><td>add poll</td><td>vote</td><td>upload</td><td>use CE Tag</td><td>use Image</td><td>use HTML</td><td>use Smiles</td><td><img src="images/mod/delete.gif"></td></tr>
<?=$content?>
</table>
<br>

<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2> Delete Permissions :
<hr size=1>
</td></tr>

<tr><td> Delete? </td><td><input type=checkbox name=confirm value=1> &nbsp; &nbsp; <input type=submit name=submit value=" Delete "></td></tr>
</table>
</form>

<?

mod_footer();
