<?php
/**
 * check permission
 */
if (!getpermission('edittopic') && !getpermission('movetopic') && !getpermission('deltopic'))
  mod_exception('Permission denied.');

if (empty($_POST['submit']) && empty($_GET['tid'])) mod_exception('Permission denied.');

mod_header("Topic Management - Moderator Control Panel");

$errormsg = '';

if (!empty($_POST['submit'])) {

  if ($_POST['action']=='option') {
    // save change to topic
    if(!getpermission('edittopic')) mod_exception('Permission denied.');

    $DB->update('update celeste_topic set '. 
    (empty($_POST['topic']) ? '' : 'topic=\''.nl2br( _removeHTML( slashesEncode( $_POST['topic']))).'\',').
    (empty($_POST['sticky']) ? 'displayorder=1,' : 'displayorder=\''.$_POST['priority'].'\',').
    (empty($_POST['bump']) ? '' : 'lastupdate=\''.$celeste->timestamp.'\',').
    'elite = \''.$_POST['elite'].'\','.
    'locked = \''.$_POST['locked'].'\' where topicid=\''.$topicid.'\'');

    mod_success_redirect_in('Your change has been saved.', 'fid='.$forumid.'&prog=topic::list' );
 
  } elseif ($_POST['action']=='move') {
    if(!getpermission('movetopic')) mod_exception('Permission denied.');
    $DB->update('update celeste_topic set forumid=\''.$_POST['toId'].'\' where topicid='.$topicid);
    $DB->update('update celeste_forum set topics=topics+1,posts=posts+'.$topic->getProperty('posts').' where forumid=\''.$_POST['toId'].'\'');
    $DB->update('update celeste_forum set topics=topics-1,posts=posts-'.$topic->getProperty('posts').' where forumid=\''.$forumid.'\'');
    mod_success_redirect_in('Topic has been moved.', 'fid='.$forumid.'&prog=topic::list' );

  } elseif ($_POST['action']=='copy') {
    if(!getpermission('movetopic')) mod_exception('Permission denied.');
    $olddata =& $DB->result('select * from celeste_topic where topicid='.$topicid);
    $query = '';
    foreach ($olddata as $key=>$value) if ($key!=='forumid' && $key!=='topicid') $query .= $key.'="'.$value.'",';
    $query.=' forumid='.$_POST['toId'];

    $DB->update('Insert into celeste_topic set '.$query);
    $lastTopicid = & $DB->lastid();

    $DB->update('update celeste_forum set topics=topics+1,posts=posts+'.$topic->getProperty('posts').' where forumid=\''.$_POST['toId'].'\'');


    $posts =& $DB->query('select * from celeste_post where topicid='.$topicid);
    while ($oldata =& $posts->fetch()) {
      $query = '';
      foreach ($oldata as $key=>$value) if ($key!=='topicid' && $key!=='postid') $query .= $key.'="'.$value.'",';
      $query.=' topicid='.$lastTopicid;

      $DB->update('Insert into celeste_post set '.$query);
    }

    mod_success_redirect_in('Topic has been duplicated.', 'fid='.$forumid.'&prog=topic::list' );
  }
}

// lock / unlock
// copy / move
// edit topic
// selective del
// sticky
// vote

?>

<?php if(getpermission('edittopic')) { ?>
<form method=post>
<input type=hidden name=action value='option'>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2>
You are now managing topic in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br><br>
Topic Options : <br>
<font color=red><?=$errormsg?></font>
<hr size=1>
</td>
<td>
</td>
</tr>
<tr><td> Topic : </td>
<td><input type=text name=topic size=50 maxlength=80 <?php if (!empty($_POST['topic'])) echo 'value="'.$_POST['topic'].'"'; else echo 'value="'.$topic->getProperty('topic').'"'; ?>></td></tr>

<tr><td> Elite Topic : </td>
<td><select name='elite'><option value='0'>No</option><option <?php if ($topic->getProperty('elite')) echo 'selected'; ?> value='1'>Yes</option></select></td></tr>

<tr><td> Locked : </td>
<td><select name='locked'><option value='0'>No</option><option <?php if ($topic->getProperty('locked')) echo 'selected'; ?> value='1'>Yes</option></select></td></tr>

<tr><td> Sticky : </td>
<td><select name='sticky'><option value='0'>No</option><option <?php if ($topic->getProperty('displayorder')>=2) echo 'selected'; ?> value='1'>Yes</option></select> Position Priority 
<select name='priority'>
<option value=2 <?php if ($topic->getProperty('displayorder')==2) echo 'selected'; ?>>Normal</option>
<option value=3 <?php if ($topic->getProperty('displayorder')==3) echo 'selected'; ?>>High</option>
<option value=4 <?php if ($topic->getProperty('displayorder')==4) echo 'selected'; ?>>Very High</option>
<option value=5 <?php if ($topic->getProperty('displayorder')==5) echo 'selected'; ?>>Highest</option>
</td></tr>
<tr><td> Bump To Top : </td>
<td><input type=checkbox name='bump' value='1'></td></tr>

<td colspan=2><hr size=1>
<input type=submit name=submit value=" Save "> &nbsp; <input type=reset name=reset value=" Reset "></td></tr>
</table>

</form>
<br>
<?php /** end of 'if(getpermission('edittopic'))' **/ } ?>

<?php
  if ($topic->getProperty('pollid')) {
?>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td> Poll Options :
<hr size=1>
</td></tr>
<tr><td><center>[ <a href='modpanel.php?tid=<?=$topicid?>&prog=poll::edit'>Click Here to Edit Poll Options</a> ] 
[ <a href='modpanel.php?tid=<?=$topicid?>&prog=poll::viewlog'>Click Here To View Vote Log</a> ] </center><br></td>
</table>
<?php
  }
?>


<?php if(getpermission('deltopic')) {?>
<form method=post action='modpanel.php?fid=<?=$forumid?>&prog=topic::list'>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2> Delete Topic :
<hr size=1>
</td></tr>
<tr><td> Delete? </td><td><input type=checkbox name=toMan[] value=<?=$topicid?>> &nbsp; &nbsp; <input type=submit name=delete value=" Delete "></td>
</table>
</form>
<?php 
  /** end of 'if(getpermission('deltopic'))' **/ 
  } ?>
<?php
 if(getpermission('movetopic')) {
?>

<form method=post>
<input type=hidden name=action value='move'>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2> Move Topic :
<hr size=1>
</td></tr>
<tr><td> Move To </td><td><select name=toId><?=getMForumList()?></select> &nbsp; &nbsp; <input type=submit name=submit value=" Move "></td>
</table>
</form>

<form method=post>
<input type=hidden name=action value='copy'>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2> Copy Topic : 
<hr size=1>
</td></tr>
<tr><td> Copy To </td><td><select name=toId><?=getMForumList()?></select> &nbsp; &nbsp; <input type=submit name=submit value=" Copy "></td>

</table>
</form>
<?php /** end of 'if(getpermission('movetopic'))' **/ } ?>

<?

mod_footer();

function getNextMonth() {
  return date('Y-m-d', time() + 3600 * 24 * 30);
}
?>