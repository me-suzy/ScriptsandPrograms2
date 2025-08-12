<?php
/**
 * Project Source File
 * Celeste V2003
 * Jun 28, 2003
 * Celeste Dev Team - Lvxing / Xinshi
 *
 * Copyright (C) 2003 CelesteSoft.com. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

/**
 * Global Functions in Celeste
 */

function getParam($param, $priority = 'P') {
  global $_GET, $_POST;
  $return = '';
  if('P' == $priority) {
    !empty($_GET[$param])  && $return = $_GET[$param];
    !empty($_POST[$param]) && $return = $_POST[$param];
  } else {
    !empty($_POST[$param]) && $return = $_POST[$param];
    !empty($_GET[$param])  && $return = $_GET[$param];
  }
  $return = str_replace('\'', '"', $return);
  $return = str_replace('\\"', '"', $return);
  return str_replace("'", '"', $return);;
}


/**
 * checks if an email address is valid
 * @param email address to be checked
 */
function isEmail($email) {
  if(!$email)return false;
  if(preg_match("/^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,3}$/i",$email))return 1;
  return false;
}

/**
 * checks if a password string is valid
 * @param password string to be checked
 */
function isPassword($password) {
  return preg_match("/^[0-9a-z!#$%&()+\\-.\\[\\]\\/\\\\@?{}|:;]+$/i", $password);
}

/**
 * checks if a username is valid
 * @param username to be checked
 */
function isUsername($username) {
  return preg_match("/^[^'\,\"%*\n\r\t?<>\\/\\\\ ]+$/", $username);
}

/**
 * checks if a variable is in int type
 * @param variable to be checked
 */
function isInt($int) {
  return (String)(int)$int === (String)$int;
  //return preg_match('/^\d+$/', $int);
}

/**
 * checks if a file with $filename is an image file
 * @param filename
 */
function isImage($filename) {
  switch(strtolower(substr(strrchr($filename, '.'), 1))) {
    case 'gif':case 'jpg':case 'png':case 'bmp':
      return true;
    default:
      return false;
  }
}

function redirect($pra='') {
  die('<!-- celeste redirect -->'.
      '<script>this.location = \'index.php?'.slashesEncode($pra, 1).'\';</script>');
}

function verifyUser($username, $password) {
  global $DB;
  $username = slashesencode($username);
  $u = $DB->result("select userid, password From celeste_users Where username='$username'");

  return ( $u['password'] != md5($password)) ? False : $u['userid'];
}

function comparePassword($password, $password2) {
  return ($password == substr(md5($password2), 0, strlen($password)));
}

/***
 * time and date functions
 */
function getTime($timestamp, $format = 'time') {
  global $celeste;
  return ($format == 'time') ?
          date(SET_TIME_FORMAT, $timestamp) :
          date(SET_DATE_FORMAT, $timestamp);
}

/**
 */
function readfromfile($name) {
  $fp = fopen($name, 'r');
  $c =& fread($fp, filesize($name));
  fclose($fp);
  return $c;
}

function writetofile($name, &$content, $method='w') {
  $fp = fopen($name, $method);
  flock($fp, 2);
  fwrite($fp, $content);
  flock($fp, 3);
  fclose($fp);
}

function slashesEncode($string, $force = 0) {
  global $celeste;
  return ($force || !$celeste->autoQuotes) ? addslashes($string) : $string;
}

function slashesDecode($string, $force = 0) {
  global $celeste;
  return ($force || $celeste->autoQuotes) ? stripslashes($string) : $string;
}

/**
 * generates a random password
 */
function makePassword($length = 10) {
  return substr(md5(uniqid(rand(),1)),0,$length);
}

/**
 * ban ip
 */
function banIp() {
  global $celeste;

  /***
   * load the banned ipaddress
   */
  include_once( DATA_PATH.'/settings/banned_ip.inc.php' );

  foreach( $banned_ip as $ip ) {
    if( $ip && strpos($celeste->ipaddress, $ip)===0) {
      celeste_exception_handle( 'permission_denied', 0 );
    }
  }
}

/**
 * file based solution
 * alternative shared memory based solution
 */
function cacheExists($cacheName) {
  return file_exists( DATA_PATH . '/cache/' . $cacheName . '.tmp' );
}

function storeCache($cacheName, &$cacheContent, $serialize=0) {
  if($serialize)
    writetofile(DATA_PATH . '/cache/' . $cacheName . '.tmp', serialize($cacheContent));
  else
    writetofile(DATA_PATH . '/cache/' . $cacheName . '.tmp', $cacheContent);
}

function getCache($cacheName, $unserialize = 0) {
  $cacheContent =& readfromfile(DATA_PATH . '/cache/' . $cacheName . '.tmp' );
  return ($unserialize ? unserialize($cacheContent) : $cacheContent);
}

function getPages($param, $totalPage, $amplitude=5) {
  global $t, $page, $root;

  if ($totalPage<=1) {
    $root->set('page', $t->getString('only_one_page'));
  } else {
  	$param.='&page=';
    $pageTemp =& $t->get('page');
    $cp =& $t->get('current_page');
    $cp->set('page', $page);
    $cp->set('url', $param.$page);
    $cp->parse();
    $end = min( $page + $amplitude, $totalPage );
    for ($i=max(1, $page-$amplitude); $i<=$end; $i++) {
      $pageTemp->set('url', $param.$i);
      if ($i==$page) $pageTemp->final.=$cp->final;
      else {
      	$pageTemp->set('page', $i);
        $pageTemp->parse(true);
      }
    }
    $multipage = $t->get('multi_page');
    $multipage->set('url', $param);
    $multipage->set('pages', $pageTemp->final);
  
    $root->set('page', $multipage->parse());
  }

}

function makeForumSelection($mode = 0, $des_name = 'forumlist', $t_element = 'topic_search_forum') {
  global $t, $DB;
  global $root;

  $avaForums = getAvailableForums();
  $cacheName = 'FS_'.$t_element.'_'.$avaForums.'_'.$mode;
  if(cacheExists($cacheName)) {
    $root->set($des_name, getCache($cacheName));
    return;
  }

  $tree = array();
  $rs =& $DB->query('Select title,forumid,parentid,path from celeste_forum f where forumid in ('.$avaForums.') order by f.parentid ASC,f.displayorder DESC,forumid ASC');
  while($dataRow = &$rs->fetch()) {
    $tree[(string)$dataRow['forumid']] =& $dataRow;
    if (empty($tree[(string)$dataRow['parentid']]['child'])) $tree[(string)$dataRow['parentid']]['child'] = array(); 
    $tree[(string)$dataRow['parentid']]['child'][(string)$dataRow['forumid']] =& $tree[(string)$dataRow['forumid']];
  }
  $rs->free();

  $f =& $t->get($t_element);
  $f->set('title', (0==$mode ? 'All Categories' : ' '));
  $f->set('forumid', 0);
  $f->parse(true);
  _makeForumSelection(&$tree[0]['child'], &$f);
  $root->set($des_name, $f->final);

  storeCache($cacheName, $f->final);
}

function _makeForumSelection(&$tree, &$t_handle) {
  foreach( $tree as $element ) {
    if (empty($element['path']))
      $t_handle->set('title', '&nbsp;&nbsp;[ '.$element['title'].' ]');
    else $t_handle->set('title', str_repeat('&nbsp;&nbsp;&nbsp;', substr_count($element['path'], ',')+2).'&#149; '.$element['title']);
    $t_handle->set('forumid', $element['forumid']);
    $t_handle->parse(true);
    if (isset($element['child']) && count($element['child'])>0) _makeForumSelection( $element['child'], $t_handle );
  }
}

function getNewPMs() {
  global $DB, $userid, $thisprog;

  if((($thisprog=='forum::list' && SET_PM_AUTO_CHECK) || SET_PM_AUTO_CHECK>1) && substr($thisprog, 0, 7)!='ucp::pm') {
    /**
     * check new private messages
     */
    return $DB->result("select count(*) From celeste_pmessage WHERE recieverid='$userid' AND haveread=0 AND box='in'");
  }

  return 0;
}


function sessionExists() {
  return ( $_COOKIE['CES'] || $_GET['CES'] );
}

/**
 * imports core class
 * @param class name
 */
function import($ClassName) {
  if ($ClassName)  include_once(DATA_PATH.'/src/core/com/celeste/class.'.$ClassName.'.php');
}

function celeste_exception_handle($exceptionType, $exceptionLevel=1) {
  if(!defined('CE_INDEX')) return 0;
  import('exception');
  new exception($exceptionType, $exceptionLevel);
}

function celeste_success_redirect( $successType, $redirectUrl = '') {
  import('success');
  new success($successType, $redirectUrl);
}

function _removeHTML(&$str) {
  return str_replace('&amp;', '&', htmlspecialchars(str_replace('{', '{ ',$str)));
}

function _replaceCensored(&$string) {
  global $CensoredWords, $ReplaceToWords;
  
  include( DATA_PATH.'/settings/censoredword.inc.php' );

  if (!$CensoredWords || !count($CensoredWords)) return $string;
  else {
    $ceWO1 = array();
    $ceWD1 = array();
    $ceWO2 = array();
    $ceWD2 = array();
    foreach($CensoredWords as $key => $origin) {
      if(substr($origin, 0, 1)=='"' && substr($origin, -1)=='"') {
        $ceWO2[] = substr($origin, 1, -1);
        $ceWD2[] = &$ReplaceToWords[$key];
      } else {
        $ceWO1[] = '/'.str_replace(' ', '(\s|<br>|<br\s?\/>)*', preg_quote($origin, '/')).'/i';
        $ceWD1[] = &$ReplaceToWords[$key];
      }
    }
    $string = str_replace($ceWO2, $ceWD2, $string);
    return    preg_replace($ceWO1, $ceWD1, $string);
  }
}

function getTitle(&$utitle, &$p, &$gid ) {
  global $titleSetting, $groupLevelImage;

  if (!empty($utitle) && isset($groupLevelImage[$gid]))
  	return array($utitle, '<img src=\''.$groupLevelImage[$gid].'\' border=0>');

  foreach($titleSetting as $level)
  	if ($p>= $level['post']) {
  	  $t=$level['title'];
  	  $i=$level['image'];
  	} else break;

  return array( ($utitle ? $utitle : $t ) , '<img src=\''.( isset($groupLevelImage[$gid]) ? $groupLevelImage[$gid] : $i ).'\' border=0>');
}

/**
 * gets the available forum id list
 */
function getAvailableForums() {
  global $userid, $celeste, $usergroupid, $DB;

  if (!$celeste->usergroup['allowview']) {
    return '0';
  }

  $AVAForumIDs = '';
  if($celeste->isSU())
  {
    $rs =& $DB->query('SELECT DISTINCT forumid FROM celeste_forum WHERE active=1');
  }
  else
  {
    $rs =& $DB->query(
        'SELECT DISTINCT celeste_forum.forumid FROM celeste_forum 
        LEFT JOIN celeste_permission P ON (P.forumid=celeste_forum.forumid AND (P.userid='.$userid.' OR P.usergroupid='.$usergroupid.'))
        WHERE active=1 AND ( P.allowview=1 OR (celeste_forum.allowview=1 AND P.allowview IS NULL) )');
  }

  while ($rs->next_record()) $AVAForumIDs .= $rs->get('forumid').',';
  $rs->free();
  $AVAForumIDs =& substr($AVAForumIDs,0,-1);
  return $AVAForumIDs;
}

function getOnlineList($fid = 0) {
  global $t, $DB, $userNo, $celeste;
  $online = '';
  // informal usage | execution time concern
  $width = ceil(100 / SET_ONLINE_PER_LINE ) . '%';
  $gp1 =& str_replace('{width}', $width, $t->getString('online_group1'));
  $gp2 =& str_replace('{width}', $width, $t->getString('online_group2'));
  $gp3 =& str_replace('{width}', $width, $t->getString('online_group3'));
  $gp4 =& str_replace('{width}', $width, $t->getString('online_othergroup'));
  $ln =& $t->getString('online_linebreak');
  	
  $rs =& $DB->query('Select usergroupid,userid, username from celeste_useronline where showme=1 AND lastvisit>'.$celeste->onlinetime
  . ($fid ? ' AND lastforumid=\''.$fid.'\'' : '' ));
  $i = 0;
  while($rs->next_record()){
    $i ++; $userNo++;
    if ($rs->get('usergroupid')<4) $name = 'gp'.$rs->get('usergroupid');
    else $name = 'gp4';
 
    $online .= str_replace('{userid}', $rs->get('userid'), str_replace('{username}', $rs->get('username'), $$name));
    if ($i % SET_ONLINE_PER_LINE==0) {
    	$online .= $ln;
    }
  }
  $rs->free();
  $userNo = $i;
  if ($i % SET_ONLINE_PER_LINE == 0)
  $online =& substr( $online, 0, 0 - strlen($ln));
  return $online;
}


function getUsernameByID($userid) {
  global $DB;
  if(!is_array($userid)) {
    return $DB->result("select username from celeste_user where userid='$userid'");
  } else {
    $users = array();
    $userlist = join("', '", $userid);
    $rs = $DB->query("select userid, username from celeste_user where userid IN ('$userlist')");
    while($user =& $rs->fetch()) {
      $users[$user['userid']] = $user['username'];
    }
    return $users;
  }
}

?>