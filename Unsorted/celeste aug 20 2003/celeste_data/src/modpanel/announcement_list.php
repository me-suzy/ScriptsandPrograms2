<?php
if (!getpermission('announce')) mod_exception('Permission denied.');
mod_header("Browse Announcement - Moderator Control Panel");

if (isset($_POST['toDel'])) {
  if (is_array($_POST['toDel']) && (count($_POST['toDel'])>0) ) {
    $query = 'delete from celeste_announcement where '.( $gppermission['admin'] ? '' : ' forumid=\''.$forumid.'\' and ' ).' announcementid in(';
    foreach($_POST['toDel'] as $i) $query.= $i.',';
    $query = substr($query,0,-1).')';
    $DB->update($query);
    mod_success_redirect_in('Announcement(s) has been deleted.', 'fid='.$forumid.'&prog=announce::list' );
  }
}

$anns = $DB->query("SELECT announcementid,title,userid,username,startdate,enddate FROM celeste_announcement where forumid='$forumid' OR forumid=0 AND enddate>'$celeste->timestamp' order by announcementid DESC");

$content = '';

while($dataRow=&$anns->fetch()) {
  $content .= '<tr><td><a href=\'modpanel.php?fid='.$forumid.'&prog=announce::edit&aid='.$dataRow['announcementid'].'\'>'.$dataRow['title'].'</a></td><td>'.
	          getTime($dataRow['startdate'],'date').'</td><td>'.
	          $dataRow['username'].'</td><td>'.
	          getTime($dataRow['enddate'],'date').'</td><td>'.
              '<input type=checkbox name=toDel[] value='.$dataRow['announcementid'].'></td></tr>';
}
?>
<form method=post>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=5>
You are now browsing announcments in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br>
<hr size=1 width=100%>
</td>
<td>
</td>
</tr>
<tr><td> Announcement Title :</td> <td>Posted : </td> <td>By : </td> <td> Expire Date: </td><td>Del</td></tr>
<tr><td colspan=5><hr size=1></td></tr>
<?=$content?>
<tr><td colspan=5><hr size=1></td></tr>
<tr><td colspan=5><input type=submit value=" Delete " name=submit></td></tr>

</table>
</form>

<?

mod_footer();

function getNextMonth() {
  return getTime(time() + 3600 * 24 * 30, 'Y-m-d');
}
?>