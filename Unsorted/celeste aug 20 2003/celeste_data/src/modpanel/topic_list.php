<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */
if (!getpermission('edittopic') && !getpermission('deltopic')) mod_exception('Permission denied.');

//if (!isInt($_GET['fid']))
//{
//  mod_exception('Permission denied.');
//}

if (isset($_POST['toMan'])) {

  if (is_array($_POST['toMan']) && (count($_POST['toMan'])>0) ) {

    // get all topic ids 
    $ids = '';
    foreach($_POST['toMan'] as $i) $ids.= $i.',';
    $ids = substr($ids, 0, -1);
    $toDelList =& $DB->query('select topicid from celeste_topic where forumid=\''.$forumid.'\' and topicid in ('.$ids.')');
    $newids ='';
    while($toDelList->next_record()) $newids.= $toDelList->get('topicid').',';
    $newids = & substr($newids, 0, -1);

    
    if (!empty($_POST['delete'])) {

      // delete these topics
      $query = 'delete from celeste_topic where topicid in('.$newids.')';
      $DB->update($query);
      $topicDeleted = $DB->affected_rows();
      $query = 'delete from celeste_post where topicid in ('.$newids.')';
      $DB->update($query);
      $postsDeleted = $DB->affected_rows();
      $DB->update('update celeste_forum set topics=topics-'.$topicDeleted.', posts=posts-'.$postsDeleted.' where forumid=\''.$forumid.'\'');
      $DB->update('update celeste_foruminfo set total_topic=total_topic-'.$topicDeleted.', total_post=total_post-'.$postsDeleted);

      $lastTopicId = $DB->result('select topicid from celeste_topic where forumid=\''.$forumid.'\' and displayorder>0 order by lastupdate desc');
      if (!$lastTopicId) $DB->update('UPDATE celeste_forum SET lasttopicid=\'\',lasttopic=\'\',lastposter=\'\',lastpost=\'\' WHERE forumid=\''.$forumid.'\'');
      else {
        $lastTopic = $DB->result('select topic from celeste_topic where topicid='.$lastTopicId);
        $lastPost = $DB->result('select username,posttime from celeste_post where topicid='.$lastTopicId.' order by postid DESC ');
        $DB->update('UPDATE celeste_forum SET lasttopicid=\''.$lastTopicId.
       '\',lasttopic=\''.slashesEncode($lastTopic, 1).'\',lastposter=\''.$lastPost['username'].'\',lastpost=\''.$lastPost['posttime'].'\' WHERE forumid=\''.$forumid.'\'');

      }
     mod_header("Browse Topics - Moderator Control Panel");
     mod_success_redirect_in('Topic(s) has been deleted.', 'fid='.$forumid.'&prog=topic::list' );

   }elseif (!empty($_POST['lock'])) {
     // lock these topics

      $DB->update( 'update celeste_topic set locked=1 where topicid in('.$newids.')' );
      mod_header("Browse Topics - Moderator Control Panel");
      mod_success_redirect_in('Topic(s) has been locked.', 'fid='.$forumid.'&prog=topic::list' );

   }elseif (!empty($_POST['unlock'])) {
     // unlock these topics

      $DB->update( 'update celeste_topic set locked=0 where topicid in('.$newids.')' );

      mod_header("Browse Topics - Moderator Control Panel");
      mod_success_redirect_in('Topic(s) has been unlocked.', 'fid='.$forumid.'&prog=topic::list' );

   }elseif (!empty($_POST['stick'])) {
     // stick these topics

      $DB->update( 'update celeste_topic set displayorder=2 where topicid in('.$newids.')' );
      mod_header("Browse Topics - Moderator Control Panel");
      mod_success_redirect_in('Topic(s) has been sticked.', 'fid='.$forumid.'&prog=topic::list' );

   }elseif (!empty($_POST['bump'])) {
     // bump these topics

      $DB->update( 'update celeste_topic set lastupdate=\''.$celeste->timestamp.'\' where topicid in('.$newids.')' );
      mod_header("Browse Topics - Moderator Control Panel");
      mod_success_redirect_in('Topic(s) has been bumped.', 'fid='.$forumid.'&prog=topic::list' );
      

   }  
  }
}

import('topiclist');

$topiclist = new topicList($forumid);
if (isset($_GET['page']))
{
  if ($_GET['page']=='end') $_GET['page']=$topiclist->max_page;
} else $_GET['page']=1;


mod_header("Browse Topics - Moderator Control Panel");
?>
<form method=post>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=7>
You are now browsing topics in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br>
<hr size=1 width=100%>
</td>
<td>
</td>
</tr>
<tr>
          <TD align=center >Topic ( click to manage )</TD>
          <TD align=center noWrap >Author</TD>
          <TD align=center noWrap >Hits</td>
          <TD align=center noWrap >Posts</TD>
          <TD align=center noWrap >Last Post</TD>
          <TD align=center noWrap >By</TD>
          <TD align=center noWrap >&nbsp;</TD>
        </TR>

<tr><td colspan=7><hr size=1></td></tr>

<?

$page = (isset($_GET['page']) ? $_GET['page'] : '1');
$topiclist->setPage( $page );

$rs = $topiclist->getList();

while($dataRow =& $rs->fetch()) {

	$dataRow['lastupdate'] = getTime($dataRow['lastupdate']);
print <<<EOF
<TR bgColor=#FFFFFF> 
  <TD>[<a href="index.php?prog=topic::flat&tid=$dataRow[topicid]" target=_blank><font color="336699">read</font></a>] <a href="modpanel.php?prog=topic::manage&tid=$dataRow[topicid]" class="thread">$dataRow[topic]</a>&nbsp;
  </TD>
  <TD align=center  noWrap>
    <A href="modpanel.php?prog=user::view&uid=$dataRow[posterid]&fid=$forumid">$dataRow[poster]</A>
  </TD>
  <td align=center noWrap >$dataRow[hits]</td>
  <TD align=center noWrap>$dataRow[posts]</TD>
  <TD noWrap >
    <center>&nbsp;$dataRow[lastupdate]&nbsp;</center>
  </TD>
  <TD align=center noWrap >$dataRow[lastupdater]</TD>
  <TD align=center><input type=checkbox name=toMan[] value=$dataRow[topicid]></td>
</TR>
EOF;
}
    $rs->free();
?>
<tr><td colspan=7><hr size=1></td></tr>
<tr><td colspan=2>
<input type=submit value=" Lock " name=lock>
<input type=submit value=" Unlock " name=unlock>
<input type=submit value=" Stick " name=stick>
<input type=submit value=" Bump " name=bump>
<input type=submit value=" Delete " name=delete></td>
<td colspan=5><?=getModPages('fid='.$forumid.'&prog=topic::list', $topiclist->max_page); ?></td>
</tr>

</table>
</form>

<?

mod_footer();
