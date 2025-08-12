<?php
/**
 * check permission
 */
if (!getpermission('edittopic'))
  mod_exception('Permission denied.');

if (empty($_POST['submit']) && empty($_GET['tid'])) mod_exception('Permission denied.');
if (empty($topic->properties['pollid'])) mod_exception('Invalid Access.');

mod_header("Poll Options - Moderator Control Panel");

$errormsg = '';

if ( !empty($_POST['submit']) ) {
  // update
  $optionlist = explode("\n", $_POST['options']);
  $optioncount = count($optionlist);
  if($optioncount<2 || $optioncount>SET_MAX_POLL_OPTIONS) {
    $errormsg='Invalid Poll Options';
  }
  if ($errormsg=='') {
    if (trim($_POST['topic'])) {
      $DB->update('update celeste_topic set topic=\''.nl2br( _removeHTML( slashesEncode( $_POST['topic']))).'\' where topicid=\''.$topicid.'\'');
    }
    $newDate = mktime(0,0,0, $_POST['timeout_month'], $_POST['timeout_day'], $_POST['timeout_year']) ;
    $DB->update('update celeste_poll set multichoice=\''. (empty($_POST['multichoice']) ? '0' : '1') .
     '\',locked=\''. (empty($_POST['locked']) ? '0' : '1' ) . '\', timeout=\''. $newDate. '\'');

    $options = '';
    $rs = $DB->query("SELECT * FROM celeste_vote WHERE pollid=".$topic->properties['pollid']);
    while($d = $rs->fetch()) {
      $options .= $d['optiontitle']."\n";
    }

    if (trim(str_replace("\r\n", "\n", $_POST['options']))!=trim($options)) {
      // update options
      $DB->update('delete from celeste_vote where pollid=\''. $topic->properties['pollid'].'\''); 

      $query = 'insert INTO celeste_vote (optionid,pollid,optiontitle,votecount) values';
      $pollid = $topic->properties['pollid'];
      for($i = 0; $i<$optioncount; $i++) {
        if($optiontitle = trim($optionlist[$i])) 
          $query.='(\''.$DB->nextid('vote').'\','.$pollid.',\''.$optiontitle.'\',0),';
      }
      $DB->update(substr($query,0,-1));
      $empty= '';
      writetofile(DATA_PATH.'/poll/'.$topic->properties['pollid'].'.poll.php', $empty, 'w');
    }

     mod_success_redirect_in('Poll options updated.', 'fid='.$forumid.'&prog=topic::list' );

  }
}

$poll =& $DB->result('select voters,votecount,multichoice,timeout,locked FROM celeste_poll where pollid='.$topic->properties['pollid']);

list($d, $m, $y) = explode(',', date("j,n,Y", $poll['timeout']));

?>

<form method=post>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2>
You are now editing poll options in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br><br>
Poll Options : <br>
<font color=red><?=$errormsg?></font>
<hr size=1>
</td>
<td>
</td>
</tr>
<tr><td> Poll Topic : </td>
<td><input type=text name=topic size=50 maxlength=80 <?php if (!empty($_POST['topic'])) echo 'value="'.$_POST['topic'].'"'; else echo 'value="'.$topic->getProperty('topic').'"'; ?>></td></tr>

<tr><td colspan=2>Currently we have <b><?=$poll['voters']?></b> voters with totally <b><?=$poll['votecount']?></b> votes in this poll.</td></tr>

<tr><td> Available By : <br>  (mm dd, yyyy)</td>
<td>
<input class=subject maxlength=2 name=timeout_month size=3 value="<?=$m?>">
<input class=subject maxlength=2 name=timeout_day size=3 value="<?=$d?>">, <input class=subject maxlength=4 name=timeout_year size=5 value="<?=$y?>">

</td></tr>

<tr><td> Locked : </td>
<td><select name='locked'><option value='0'>No</option><option <?php if ($poll['locked']) echo 'selected'; ?> value='1'>Yes</option></select></td></tr>

<tr><td> Multi Answer : </td>
<td><select name='multi'><option value='0'>No</option><option <?php if ($poll['multichoice']) echo 'selected'; ?> value='1'>Yes</option></select></td></tr>

<tr><td> Poll options :<br>
Each line will be considered as a poll option. ( maximum <?=SET_MAX_POLL_OPTIONS?> options )<br>
<font color=red>Warning: All Voted Data will be reset if changed</font>
</td><td>
<textarea cols=60 name=options rows=5 wrap=VIRTUAL><?php
$options = '';
$rs = $DB->query("SELECT * FROM celeste_vote WHERE pollid=".$topic->properties['pollid']);
while($d = $rs->fetch()) {
    $options .= $d['optiontitle']."\n";
  }
print(substr($options, 0, -1));
 ?></textarea>
</td></tr>

<td colspan=2><hr size=1>
<input type=submit name=submit value=" Save "> &nbsp; <input type=reset name=reset value=" Reset "></td></tr>
</table>

</form>
<br>
<?php

mod_footer();

?>