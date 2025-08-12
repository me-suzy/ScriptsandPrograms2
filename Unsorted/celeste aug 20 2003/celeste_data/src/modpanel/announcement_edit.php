<?php

if (!getpermission('announce')) mod_exception('Permission denied.');

if (empty($_POST['submit']) && empty($_GET['aid'])) mod_exception('Permission denied.');

if (!$gppermission['admin'] && $DB->result('select forumid from celeste_announcement where announcementid=\''.$_GET['aid'].'\'')==0) mod_exception('Permission denied.');

mod_header("Edit Announcement - Moderator Control Panel");

$errormsg = '<br>';

if (!empty($_POST['submit'])) {
  // save change to announcement
  $checked = true;

  if (empty($_POST['announce_title'])) {
    $checked=false;
    $errormsg .= "Please fill in announcement title<br>";
  }

  if (empty($_POST['announce_content'])) {
    $checked=false;
    $errormsg .= "Please fill in announcement title<br>";
  }

  if (!preg_match("/[0-9]{4}\\-[0-9]{1,2}\\-[0-9]{1,2}/", $_POST['expire_date'])) {
    $checked=false;
    $errormsg .= "Please fill in your expire date in correct format (yyyy-mm-dd)<br>";
  }

  if (empty($_POST['expire_date'])) {
    $_POST['expire_date'] = getNextMonth();
  }

  if ($checked) {
    $_POST['announce_title'] =& nl2br( _removeHTML( slashesEncode( $_POST['announce_title'])));
    $_POST['announce_content'] =& nl2br( _removeHTML( slashesEncode( $_POST['announce_content'])));
    list($y, $m, $d) = explode('-', $_POST['expire_date']);
	$enddate = mktime (0,0,0,$m,$d,$y);
	$DB->update('update celeste_announcement set forumid=\''.$forumid.'\',title=\''.$_POST['announce_title'].'\',username=\''.$user->username.'\',userid=\''.$userid.'\',startdate=\''.$celeste->timestamp.'\',enddate=\''.$enddate.'\',content=\''.$_POST['announce_content'].'\' where announcementid=\''.$_POST['announce_id'].'\'');

	mod_success_redirect_in('Your change has been saved.', 'fid='.$forumid.'&prog=announce::list' );
  }
}

if (!isset($_POST['submit'])) {

  $data = $DB->result('select title,content from celeste_announcement where announcementid=\''.$_GET['aid'].'\'');
  $_POST['announce_title']=&$data['title'];
  $_POST['announce_content']=&$data['content'];
}



?>
<form method=post>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2>
You are now editing announcment in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br>
You can use HTML and Celeste Tag.<br>
<font color=red><?=$errormsg?></font>
<hr size=1>
</td>
<td>
</td>
</tr>
<tr><td> Announcement Title :</td>
<td><input type=text name=announce_title size=50 maxlength=80 <? if (!empty($_POST['announce_title'])) echo 'value="'.$_POST['announce_title'].'"'; ?>></td></tr>
<tr><td valign=top> Announcement Content :</td>
<td> <Textarea name=announce_content cols=50 rows=20><? if (!empty($_POST['announce_content'])) echo $_POST['announce_content']; ?></textarea></td></tr>
<tr><td>Expire Date (yyyy-mm-dd) :</td>
<td><input type=text name=expire_date size=50 value="<?=getNextMonth()?>"></td></tr>
<td colspan=2><hr size=1>
<input type=submit name=submit value=" Save "> &nbsp; <input type=reset name=reset value=" Reset "></td></tr>
</table>
<input type=hidden value="<?php isset($_GET['aid']) ? print($_GET['aid']):print($_POST['announce_id']); ?>" name=announce_id>
</form>

<?

mod_footer();

function getNextMonth() {
  global $celeste;
  return date('Y-m-d',$celeste->timestamp + 3600 * 24 * 30);
}
?>