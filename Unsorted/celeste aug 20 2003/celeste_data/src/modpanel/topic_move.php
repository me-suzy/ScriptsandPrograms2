<?php
if (!getpermission('movetopic')) mod_exception('Permission denied.');

mod_header("Move Topic - Moderator Control Panel");

$errormsg = '';

if (!empty($_POST['submit'])) {

  $conditions = 0;
  $query = '';
  if (!empty($_POST['topic'])) {
	$conditions ++;
    $keys = explode(' ', $_POST['topic']);
	foreach( $keys as $key ) $query .= ' and topic like \'%'.$key.'%\' ';	
  }

  if (!empty($_POST['username'])) {
	$conditions ++;
    $query .= ' and poster= \''.slashesencode($_POST['username']).'\' ';
  }

  if (!empty($_POST['userid'])) {
     $conditions ++;
     $query .= ' and posterid= \''.slashesencode($_POST['userid']).'\' ';
  }

  if (!empty($_POST['elite'])) {
     $conditions ++;
     $query .= ' and elite= \''.($_POST['elite']=='1' ? '0' : '1' ).'\' ';
  }
  if (!empty($_POST['sticky'])) {
     $conditions ++;
     $query .= ' and displayorder= \''.($_POST['sticky']=='1' ? '1' : $_POST['priority'] ).'\' ';
  }

  if (!empty($_POST['last1'])) {
     $conditions ++;
     $query .= ' and lastupdate >= \''.getTimeStamp($_POST['last1']).'\' ';
  }

  if (!empty($_POST['last2'])) {
     $conditions ++;
     $query .= ' and lastupdate <= \''.getTimeStamp($_POST['last2']).'\' ';
  }

  if ($conditions>0 && !empty($_POST['confirm'])) {
     $ids =& $DB->query('select topicid from celeste_topic where forumid=\''.$forumid.'\' '.$query);
	 $idlist = '';
	 while($ids->next_record()) $idlist .= $ids->get('topicid').',';
     if (strlen($idlist)>0) {
		 $idlist = substr($idlist, 0, -1);
         $posts = $DB->result('select count(*) from celeste_post where topicid in ('.$idlist.')');
     } else $posts = 0;

	 $query = 'Update celeste_topic set forumid=\''.$_POST['toID'].'\' where forumid=\''.$forumid.'\'  '.$query;
	 $DB->update($query);
	 $num = $DB->affected_rows();

	 $DB->update('update celeste_forum set topics=topics-'.$num.', posts=posts-'.$posts.' where forumid='.$forumid);
	 $DB->update('update celeste_forum set topics=topics+'.$num.', posts=posts+'.$posts.' where forumid="'.$_POST['toID'].'"');

    $lastTopicId = $DB->result('select topicid from celeste_topic where forumid=\''.$forumid.'\' and displayorder>0 order by lastupdate desc');
    if (!$lastTopicId) $DB->update('UPDATE celeste_forum SET lasttopicid=\'\',lasttopic=\'\',lastposter=\'\',lastpost=\'\' WHERE forumid=\''.$forumid.'\'');
    else {
      $lastTopic = $DB->result('select topic from celeste_topic where topicid='.$lastTopicId);
      $lastPost = $DB->result('select username,posttime from celeste_post where topicid='.$lastTopicId.' order by postid DESC ');
      $DB->update('UPDATE celeste_forum SET lasttopicid=\''.$lastTopicId.
       '\',lasttopic=\''.slashesEncode($lastTopic, 1).'\',lastposter=\''.$lastPost['username'].'\',lastpost=\''.$lastPost['posttime'].'\' WHERE forumid=\''.$forumid.'\'');

    }


     mod_success_redirect_in('Topic(s) has been moved.', 'fid='.$forumid.'&prog=topic::list' );
   }

}

?>
<form method=post>
<input type=hidden name=action value='option'>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2>
You are now managing topic in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br><br>
Topic to move : <br>
<font color=red><?=$errormsg?></font>
<hr size=1>
</td>
<td>
</td>
</tr>
<tr><td> Keywords in topics : ( seperated by " " )</td>
<td><input type=text name=topic size=50 maxlength=80 <?php if (!empty($_POST['topic'])) echo 'value="'.$_POST['topic'].'"'; ?>></td></tr>

<tr><td> Topic Starter : </td>
<td><input type=text name=username size=20 maxlength=30 <?php if (!empty($_POST['username'])) echo 'value="'.$_POST['username'].'"'; ?>></td></tr>

<tr><td> Starter UserID : </td>
<td><input type=text name=userid size=20 maxlength=30 <?php if (!empty($_POST['userid'])) echo 'value="'.$_POST['userid'].'"'; ?>></td></tr>

<tr><td> Elite Topic : </td>
<td><select name='elite'><option value='0'>Any</option><option value='0'>No</option><option value='1'>Yes</option></select></td></tr>

<tr><td> Locked : </td>
<td><select name='locked'><option value='0'>Any</option><option value='0'>No</option><option value='1'>Yes</option></select></td></tr>

<tr><td> Sticky : </td>
<td><select name='sticky'><option value='0'>Any</option><option value='0'>No</option><option value='1'>Yes</option></select> Position Priority 
<select name='priority'>
<option value=2>Normal</option>
<option value=3>High</option>
<option value=4>Very High</option>
<option value=5>Highest</option>
</td></tr>

<tr><td> Topic Last Updated : (yyyy-mm-dd)</td>
<td> Between <input type=text name=last1 > and <input type=text name=last2 ></td></tr>

</table>

<br>

<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2> Move Topic :
<hr size=1>
</td></tr>
<tr><td> Move To : </td><td><select name='toID'><?=getModList()?></select></td>
<tr><td> Move? </td><td><input type=checkbox name=confirm value=1> &nbsp; &nbsp; <input type=submit name=submit value=" Move "></td>
</table>
</form>


<?

mod_footer();

function getTimeStamp ( $date ) {
  list($year, $month, $day) = explode('-', $date);
  return mktime ( 0,0,0, $month, $day, $year);
}