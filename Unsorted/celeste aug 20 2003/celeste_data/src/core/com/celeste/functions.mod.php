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

function modpanellogin()
{
  global $thisprog, $t, $celeste, $DB, $forumid;

  $times = $DB->result('select count(*) from celeste_log where ipaddress=\''.$celeste->ipaddress.
  '\' AND action=\'mod::login\' AND time>\''.($celeste->timestamp-60*5).'\'');
  if ($times>5) mod_exception('Account Locked! You are not allowed to login any more in 5 mins.');
  if (!empty($_POST['username']))
  {

      if (empty($_POST['aid'])) {
		    mod_exception('Invalid Anti-Spam Code');
      }

      import('Auth');
      $auth = new Auth($_POST['aid']);
      if (!$auth->verify($_POST['AS_Code'])) {
		    mod_exception('Invalid Anti-Spam Code or the Code Session has ended.');
      }

    
    $userid = $celeste->verifyUserByName(slashesencode($_POST['username']), $_POST['password']);

    if ($userid)
    {
      import('user');
      $user = new user($userid, 1);
      $usergroupid = $user->properties['usergroupid'];
      $gppermission =& $DB->result("Select deltopic+edittopic+movetopic+editpost+deletepost+announce+setpermission+admin from celeste_usergroup where usergroupid='$usergroupid'");

      $forumpermission =& $DB->result("Select deltopic+edittopic+movetopic+editpost+deletepost+announce+setpermission canenter from celeste_permission where (userid='$userid' OR usergroupid='$usergroupid') ORDER BY userid DESC, canenter DESC");

      $canEnter = $gppermission || $forumpermission;
    } else $canEnter = 0;

  	if ($userid && $canEnter)
  	{
      $session = new celesteSession('CEMS');
      $session->set('lastip', $celeste->ipaddress);
      $session->set('lastvisit', $celeste->timestamp);
      $session->set('userid', $userid);
      $query = '';

      foreach($_GET as $key=>$val)
      $query .= $key.'='.$val.'&';
  	  mod_success_redirect('You are now logged in.', $query );
  	}
  	else
  	{
  	  $DB->update('insert into celeste_log SET username=\''.slashesencode($_POST['username']).'\',password=\''.
  	  slashesencode($_POST['password']).'\',action=\'mod::login\',time=\''.$celeste->timestamp.'\',ipaddress=\''.
  	  $celeste->ipaddress.'\'');
  	  if (!$userid) mod_exception('Your password is wrong.');
  	  else mod_exception('You are not allowed to enter moderator control panel.');
  	}
  }
  else 
  {
      import('Auth');
      $auth = new Auth();
      $aid = $auth->getAuthId();

      header("Cache-Control: no-cache");
      header("Pragma: no-cache");

?>
<html>
<head>
	<META content="text/html; charset=<?=SET_DEFAULT_CHARSET?>" http-equiv=Content-Type>
	<title>Celeste Moderator CP Login</title>
	<script language="JavaScript" type="text/javascript">
		<!-- 
		// break out of frames
		if (self.parent.frames.length != 0) {
			self.parent.location=document.location;
		}
		//-->
	</script>
<link rel="stylesheet" href="images/mod/modpanel.css" type="text/css" />
</head>

<body bgcolor="#98b2cc" text="#000000" link="#000000" vlink="#000000" alink="#000000">

<form name="loginForm" method="post">
<input type="hidden" name="login" value="true">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<td width="49%"><br></td>
<td width="2%" align=center nowrap>	
    
    
    <font color=white face="verdana,arial,helvetica,sans-serif"><b>Welcome to Moderator Control Panel</b></font><br /> <br />
    <table cellpadding="1" cellspacing="0" border="0" bgcolor="#000000">
	<tr><td>
    <table cellpadding="2" cellspacing="0" border="0" bgcolor="#FFFFFF">
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td align="right" nowrap><font size="-1" face="arial,helvetica,sans-serif">Username &nbsp;</font></td>
		<td><input type="text" name="username" size="15" maxlength="25"></td>
	</tr>
	<tr>
		<td align="right" nowrap><font size="-1" face="arial,helvetica,sans-serif">Password &nbsp;</font></td>
		<td><input type="password" name="password" size="15" maxlength="20"></td>
	</tr>

	<tr><td colspan="2"><img src="images/mod/blank.gif" width="230" height="5" border="0"></td></tr>

<tr>
  <td align="right"><font size="-1" face="arial,helvetica,sans-serif">AS Code &nbsp;</font></td>
<td><img src="index.php?prog=verifyImg&aid=<?=$aid?>&p=1" height=20 width=10><img src="index.php?prog=verifyImg&aid=<?=$aid?>&p=2" height=20 width=10><img src="index.php?prog=verifyImg&aid=<?=$aid?>&p=3" height=20 width=10><img src="index.php?prog=verifyImg&aid=<?=$aid?>&p=4" height=20 width=10><img src="index.php?prog=verifyImg&aid=<?=$aid?>&p=5" height=20 width=10></td>
</tr>
<tr>
  <td align="right"><font size="-1" face="arial,helvetica,sans-serif">Confirm &nbsp;</font></td>
   <td><input type=text name=AS_Code  size="15" maxlength="20"><input type=hidden name=aid value=<?=$aid?>></td>
</tr>

	<tr><td colspan="2"></td></tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="&nbsp Login &nbsp;" name="login_submit">
			<p>
			<font size="-2" face="verdana,arial,helvetica">
			<b><a href="http://www.celestesoft.com" target="_blank">Celeste Version: 2003</a> <?= SET_VERSION ?></b>
			</font>
		</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
    </table>
    </td></tr>
    </table>
</td>
<td width="49%"><br></td>
</table>

</form>

<script language="JavaScript" type="text/javascript">
<!--
	document.loginForm.username.focus();
//-->
</script>

</body>
</html>


<?
  }
  exit;
	
}

function mod_exception($msg)
{
?>
<html>
<head>
<META content="text/html; charset=<?=SET_DEFAULT_CHARSET?>" http-equiv=Content-Type>
<title>Celeste Moderator CP ERROR</title>
<link rel="stylesheet" href="images/mod/modpanel.css" type="text/css" />
</head>

<body bgcolor="#98b2cc" text="#000000" link="#000000" vlink="#000000" alink="#000000">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<td width="49%"><br></td>
<td width="2%" align=center nowrap>	
    <font color=red face="verdana,arial,helvetica,sans-serif"><b>Error!</b></font><br /> <br />
    <table cellpadding="1" cellspacing="0" border="0" bgcolor="#000000" width=400>
	<tr><td>
    <table cellpadding="2" cellspacing="0" border="0" bgcolor="#FFFFFF" width=100%>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td align="center" nowrap><b><font size="-1" face="arial,helvetica,sans-serif"><?=$msg?></font></b><br><br></td>
	</tr>
	<tr><td background="images/mod/blank.gif" height="4"><img src="images/mod/blank.gif" width=5 height="3"></td></tr>
	<tr>
		<td align="center" nowrap><br><a href="javascript:history.go(-1)"><font size="-1" face="arial,helvetica,sans-serif">Click here to go back to check your input or retry later.&nbsp;</font></a></td>
	</tr>
	<tr><td >&nbsp;</td></tr>
    </table>
    </td></tr>
    </table>
</td>
<td width="49%"><br></td>
</table>


</body>
</html>
<?
exit;
}

function mod_exception_in($msg)
{
?>

<table width="100%" height="70%" border="0" cellspacing="0" cellpadding="0">
<td width="49%"><br></td>
<td width="2%" align=center nowrap>	
    <font color=red face="verdana,arial,helvetica,sans-serif"><b>Error!</b></font><br /> <br />
    <table cellpadding="1" cellspacing="0" border="0" bgcolor="#000000" width=400>
	<tr><td>
    <table cellpadding="2" cellspacing="0" border="0" bgcolor="#FFFFFF" width=100%>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td align="center" nowrap><b><font size="-1" face="arial,helvetica,sans-serif"><?=$msg?></font></b><br><br></td>
	</tr>
	<tr><td background="images/mod/blank.gif" height="4"><img src="images/mod/blank.gif" width=5 height="3"></td></tr>
	<tr>
		<td align="center" nowrap><br><a href="javascript:history.go(-1)"><font size="-1" face="arial,helvetica,sans-serif">Click here to go back to check your input or retry later.&nbsp;</font></a></td>
	</tr>
	<tr><td >&nbsp;</td></tr>
    </table>
    </td></tr>
    </table>
</td>
<td width="49%"><br></td>
</table>

<?
  mod_footer();
  exit;
}


function mod_success_redirect($msg, $url )
{

if (strpos($url, '.php') === false ) $url='modpanel.php?'.$url;
?>
<html>
<head>
<link rel="stylesheet" href="images/mod/modpanel.css" type="text/css" />
<META content="text/html; charset=<?=SET_DEFAULT_CHARSET?>" http-equiv=Content-Type>
<title>Celeste Moderator CP</title>
<meta http-equiv="refresh" content="4; url=<?=$url?>">

</head>

<body bgcolor="#98b2cc" text="#000000" link="#000000" vlink="#000000" alink="#000000">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<td width="49%"><br></td>
<td width="2%" align=center nowrap>	

    <font color=white face="verdana,arial,helvetica,sans-serif"><b>Success!</b></font><br /> <br />
    <table cellpadding="1" cellspacing="0" border="0" bgcolor="#000000" width=400>
	<tr><td>
    <table cellpadding="2" cellspacing="0" border="0" bgcolor="#FFFFFF" width=100%>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td align="center" nowrap><b><font size="-1" face="arial,helvetica,sans-serif"><?=$msg?></font></b><br><br></td>
	</tr>
	<tr><td background="images/mod/blank.gif" height="4"><img src="images/mod/blank.gif" width=5 height="3"></td></tr>
	<tr>
		<td align="center" nowrap><br><font size="-1" face="arial,helvetica,sans-serif">Please wait while we transfer you...</font><br><br><a href="<?=$url?>"><font size="-1" face="arial,helvetica,sans-serif">Or click here if you do not wish to wait&nbsp;</font></a></td>
	</tr>
	<tr><td >&nbsp;</td></tr>
    </table>
    </td></tr>
    </table>
</td>
<td width="49%"><br></td>
</table>


</body>
</html>  
<?
}

function mod_success_redirect_in($msg, $url )
{

if (strpos($url, '.php') === false ) $url='modpanel.php?'.$url;
?>
<table width="100%" height="70%" border="0" cellspacing="0" cellpadding="0">
<td width="49%"><br></td>
<td width="2%" align=center nowrap>	

    <font color=black face="verdana,arial,helvetica,sans-serif"><b>Success!</b></font><br /> <br />
    <table cellpadding="1" cellspacing="0" border="0" bgcolor="#000000" width=400>
	<tr><td>
    <table cellpadding="2" cellspacing="0" border="0" bgcolor="#FFFFFF" width=100%>
	<tr><td >&nbsp;</td></tr>
	<tr>
		<td align="center" nowrap><b><font size="-1" face="arial,helvetica,sans-serif"><?=$msg?></font></b><br><br></td>
	</tr>
	<tr><td background="images/mod/blank.gif" height="4"><img src="images/mod/blank.gif" width=5 height="3"></td></tr>
	<tr>
		<td align="center" nowrap><br><font size="-1" face="arial,helvetica,sans-serif">Please wait while we transfer you...</font><br><br><a href="<?=$url?>"><font size="-1" face="arial,helvetica,sans-serif">Or click here if you do not wish to wait&nbsp;</font></a></td>
	</tr>
	<tr><td >&nbsp;</td></tr>
    </table>
    </td></tr>
    </table>
</td>
<td width="49%"><br></td>
</table>
<script language="javascript">
	setTimeout('top.location=\'<?=$url?>\'',3000); 
</script>
<?

	mod_footer();
	exit;
}

function mod_header( $title, $menu = 1)
{
global $forum, $permission, $forumid;
?>
<html>
<head>
<link rel="stylesheet" href="images/mod/modpanel.css" type="text/css" />
<META content="text/html; charset=<?=SET_DEFAULT_CHARSET?>" http-equiv=Content-Type>
<title>Celeste Moderator CP</title>
<body  text="#000000" link="#000000" vlink="#000000" alink="#000000" topmargin=0 leftmargin=0>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<?
if ($menu)
{
?>
<td width=205 height=100% valign=top align=left bgcolor="#FFFFFF" >
<a href="modpanel.php?fid=<?=$forumid?>"><img src='images/mod/cplogo.gif' border=0></a><br>
  <table width=98% cellpadding=0 cellspacing=0 border=0>
    <tr><td height=1 bgcolor=999999><img src="images/mod/left_bg.gi" height=1 width=1 border=0></td></tr>
  </table>

  <table width=98% cellpadding=3 cellspacing=0 border=0 background="images/mod/left_bg.gif">
    <tr><td height=22>&nbsp;</td></tr>
   
    <tr><td height=25 background="images/mod/left_menu_bg.gif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=white><b>Announcements</b></font></td></tr>

    <tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=announce::new&fid=<?=$forumid?>">&#187; Add Announcement</a></td></tr>
    <tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=announce::list&fid=<?=$forumid?>">&#187; Edit / Delete Announcement</a></td></tr>
    <tr><td>&nbsp;<br></td></tr>

    <tr><td height=25 background="images/mod/left_menu_bg.gif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=white><b>Topic Options</b></font></td></tr>
    <tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=topic::list&fid=<?=$forumid?>">&#187; Browse</font></a></td></tr>
    <tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=topic::move&fid=<?=$forumid?>">&#187; Mass Move</font></a></td></tr>
    <tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=topic::delete&fid=<?=$forumid?>">&#187; Mass Prune</font></a></td></tr>
    <tr><td height=20>&nbsp;</td></tr>

    <tr><td height=25 background="images/mod/left_menu_bg.gif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=white><b>User Options</b></font></td></tr>
	<tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=user::search&fid=<?=$forumid?>">&#187; Find User</a></td></tr>
	<tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=user::permission&fid=<?=$forumid?>">&#187; User Permissions</a></td></tr>
	<tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=usergroup::permission&fid=<?=$forumid?>">&#187; Group Permissions</a></td></tr>
    <tr><td height=16>&nbsp;</td></tr>
  <tr><td height=16><hr size=1></td></tr>
  </table>
  <table width=98% cellpadding=3 cellspacing=0 border=0 background="images/mod/left_bg.gif">

    <tr><td height=24>&nbsp;&nbsp;<a href="modpanel.php?prog=logout&fid=<?=$forumid?>">&#187; LOG OUT</a></td></tr>
    <tr><td height=24>&nbsp;&nbsp;<a href="index.php?prog=topic::list&fid=<?=$forumid?>" target=_blank>&#187; Go to forum</a></td></tr>
  <tr><td height=16><hr size=1></td></tr>
	</table>
  <table width=98% cellpadding=3 cellspacing=0 border=0 background="images/mod/left_bg.gif">

    <form method=GET action="modpanel.php">
    <tr><td height=30>&nbsp;&nbsp;<select name=fid class=menuselect><?=getModList()?></select></td></tr>
    <tr><td height=30 align=right>&nbsp;&nbsp;<input type=submit name=submit  class=menuinput value=' Go '>&nbsp;&nbsp;&nbsp;</td></tr>
    <tr><td height=20>&nbsp;<input type=hidden value="topic::list" name="prog"></td></tr>
    </form>

   <tr><td height=20 valign="top">&nbsp;&nbsp;<font class="menuspan">welcome to moderator control panel!</font></td></tr>
   <tr><td height=20 valign="top">&nbsp;&nbsp;</td></tr>
    </td></tr>
</table>
</td>
<? }
?>
<td width="*" valign="top">
<table width=100% cellspacing=0 cellpadding=0 align=center>
<tr><td height=51 align=center>
<font class="pagetitle"><?=$title?></font>
</td></tr>
<tr><td height=1 bgcolor=999999><img src="images/mod/left_bg.gi" height=1 width=1 border=0></td></tr>
</table>
<?   

}

function buildTree(&$tree)
{
  global $f;
  foreach( $tree as $element )
  {
    if (empty($element['path']))
      $title = '[ '.$element['title'].' ]';
    else $title = str_repeat('&nbsp;', substr_count($element['path'], ',')+2).'&#149; '.$element['title'];
      $f .= "<option value='$element[forumid]'>$title</option>";
      if (isset($element['child']) && count($element['child'])>0) buildTree( $element['child'] );
  }
}

function getMForumList() {
  global $f, $DB, $usergroupid, $userid, $celeste;
  $f = '';

  $rs =& $DB->query('Select title,forumid,parentid,path from celeste_forum f where f.active=1 order by f.parentid ASC,f.displayorder DESC,forumid ASC');

  while($dataRow = &$rs->fetch()) {
   $tree[(string)$dataRow['forumid']] =& $dataRow;
    if (empty($tree[(string)$dataRow['parentid']]['child'])) $tree[(string)$dataRow['parentid']]['child'] = array(); 
    $tree[(string)$dataRow['parentid']]['child'][(string)$dataRow['forumid']] =& $tree[(string)$dataRow['forumid']];
  }
  $rs->free();
  buildTree($tree[0]['child']);
  return $f;
}


function getModList()
{
  global $DB, $usergroupid, $userid, $celeste;
  $f = '';

  $tree = array();
  if ($celeste->usergroup['p'])
    return getMForumList();
  else
  $rs =& $DB->query('Select title,f.forumid from celeste_permission p LEFT JOIN celeste_forum f ON 
  (f.forumid=p.forumid AND (p.userid=\''.$userid.'\' OR p.usergroupid=\''.$usergroupid.'\') AND (deltopic+edittopic+movetopic+editpost+deletepost+announce+setpermission>=1)) where f.active=1 order by f.parentid ASC,f.displayorder DESC,forumid ASC');
  while($dataRow = &$rs->fetch()) {
    $f .= '<option value=\''.$dataRow['forumid'].'\'>'.$dataRow['title'].'</option> ';
  }
  $rs->free();
  return $f;
}

function mod_footer($menu = 1) {
?>

</td></tr>
</table>
<table width=100% height=20 cellpadding=0 cellspacing=0 bgcolor=white>
<tr>
<td align=left valign=middle width=50 background="images/mod/left_bg.gif">&nbsp;</td>
<td align=center valign=middle>
<font color=gray>Moderator Control Panel - <a href="http://www.celestesoft.com">Celeste 2003</a></font>
</td>
<td width=50>&nbsp;</td></tr>
</table>
</body>
</html>
<?
}

function getModPages($param, $totalPage, $amplitude=5) {
  global $page;

  if ($totalPage<=1) {
    return ('Only One Page');
  } else {
  	$param.='&page=';
    $pages='<div align=right><font class=smalltext><a href="modpanel.php?'.$param.'1" class=page>|&lt;</a> &nbsp;';
    $end = min( $page + $amplitude, $totalPage );
    for ($i=max(1, $page-$amplitude); $i<=$end; $i++) {

      if ($i==$page) $pages.='&nbsp;<a href="modpanel.php?'.$param.$i.'" class=page>[<b>'.$i.'</b>]</a>&nbsp;';
      else {
         $pages.='&nbsp;<a href="modpanel.php?'.$param.$i.'" class=page>['.$i.']</a>&nbsp;';
      }
    }
    $pages.= '<a href="modpanel.php?'.$param.$totalPage.'" class=page>&gt;|</a> </font>&nbsp; &nbsp;</div>';
	return $pages;
  }

}

function getAllGroups ( ) {
   global $DB;
   $result = '';
   $allgroups = $DB->query('select usergroupid,title from celeste_usergroup');
   while($allgroups->next_record())
     $result .= '<option value="'.$allgroups->get('usergroupid').'">'.$allgroups->get('title').' </option>';
   
   return $result;
}

/**********************************************************
 * celeste_permission override all
 */
function getpermission($task) {
  global $permission;
  global $pname;

  //if(!preg_match('/^'.join('|', $pname).'$/', $task)) return 0;

  if (!in_array($task, $pname)) return 0;

  return isset($permission[$task]) && !empty($permission[$task]);
  /*
  if($forum->permission[$task] == NULL)
  {
    return $celeste->usergroup[$task];
  }
  else
  {
    return $forum->permission[$task];
  }*/

} // end of 'function getpermission($task) {'