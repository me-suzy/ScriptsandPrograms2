<?php
if (!$topic->properties['pollid']) die();
$content =& file(DATA_PATH.'/poll/'.$topic->properties['pollid'].'.poll.php');

/**
 * fetch poll options
 */
$polloptions = array();
$rs = $DB->query("SELECT optionid, optiontitle FROM celeste_vote WHERE pollid = '".$topic->properties['pollid']."'");
while($option = $rs->fetch()) {
  $polloptions[(string)$option['optionid']] = $option['optiontitle'];
}
$rs->free();
unset($rs);

mod_header("Poll Logs - Moderator Control Panel");
?>
<form method=post>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2>
You are now Viewing Poll Log in :
<br><br><b> Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br>
<hr size=1 width=100%>
</td>
<td>
</td>
</tr>
<tr><td colspan=3> Poll Topic : <?=$topic->getProperty('topic')?></td></tr>
<tr><td colspan=3><hr size=1></td></tr>
<tr><td>User Name</td><td>Time</td><td>Option(s)</td></tr>
<tr><td colspan=3><hr size=1></td></tr>
<?php

if ($content) {
  $first = true;
foreach($content as $line) {
  if ($first) {
    $first = false;
    continue;
  }
  if (strlen(trim($line))==0) continue;
  list($author, $time, $log, $rawopinion) = explode('|', $line );
  if($log) {
    if(strpos($rawopinion, ',')) 
    {
      // multi-opinion
      $opinion = '';
      $rawopinions = explode(',', $rawopinion);
      foreach($rawopinions as $eachop)
        $opinion .= $polloptions[(string)$eachop].' &nbsp; &nbsp; ';
    }
    else
    {
      $opinion = $polloptions[(string)$rawopinion];
    }
  } else {
    $opinion = '<font color=#757575>Hidden</font>';
  }
  echo '<tr><td>'.$author.'</td><td>'. getTime($time) .'</td><td>'.$opinion.'</td></tr>';
} }?>
<tr><td colspan=3><hr size=1></td></tr>
</table>
</form>
<?

mod_footer();

?>