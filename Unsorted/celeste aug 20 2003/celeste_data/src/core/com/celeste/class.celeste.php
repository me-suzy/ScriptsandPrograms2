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

class celeste
{

  var $forumid = 0;

  var $username;
  var $userid;
  var $usergroup;
  var $lastvisit = 0;

  var $timer = 'object';

  var $cookie = array();

  var $thisprog = '';
  var $login = false;

  var $timestamp = 0;
  var $onlinetime = 0;
  var $autoQuotes = 1;
  var $db;
  var $ipaddress;


  function celeste()
  {

  	global $DB;
    $this->timestamp = time();
    $this->db =& $DB;

    $this->timer = new celesteTimer();

    $this->autoQuotes = get_magic_quotes_gpc();
    $this->ipaddress  = $_SERVER['REMOTE_ADDR'];
    $this->timestamp  = time() + SET_TIME_ZONE_OFFSET;
    $this->onlinetime = $this->timestamp - SET_ONLINE_DURATION;

  }
  
  function verifyUserById($id, $pwd)
  {
    $u = $this->db->result("select password From celeste_user Where userid='".$id."'");

    return ( $u==$pwd || $u == md5($pwd));

  }
  
  function verifyUserByName($name, $pwd)
  {
  	$temp =& $this->db->result('select password, userid from celeste_user where username=\''.$name.'\'');

  	if ($temp['password']==md5($pwd) || $temp['password']==$pwd) return $temp['userid'];
  	else return 0;
  }

  /**
   * cookie funcs
   *
   */
  function getCookie($cookieVar)
  {
    return isset($_COOKIE[ SET_COOKIE_HEADER . $cookieVar ]) ? $_COOKIE[ SET_COOKIE_HEADER . $cookieVar ] : '' ;
  }

  function setCookie($cookieVar, $cookieValue, $forceTime = -1)
  {
  	
    if( $forceTime < 0 ) $forceTime = SET_COOKIE_LIFETIME;
    if( is_array($cookieValue) ) 
    {
      $cookieValue = serialize($cookieValue);
    }
    if ($forceTime) $forceTime += $this->timestamp;
    setCookie(SET_COOKIE_HEADER . $cookieVar, $cookieValue, $forceTime); //, SET_COOKIE_SCOPEDIR);
  } // end of celeste::setCookie()

  function unsetCookie($cookieVar)
  {
    setCookie(SET_COOKIE_HEADER.$cookieVar);
  }

  function updateLastAction()
  {
  	global $userid, $forumid, $user,$usergroupid;
    if($this->login)
    {
      /*
      $this->db->update('Replace into celeste_useronline set lastvisit='.$this->timestamp.', lastforumid='.
        $forumid.', ipaddress=\''.$this->ipaddress.'\',userid=\''.$userid.'\',username=\''.$user->properties['username'].'\',usergroupid='.$usergroupid);
      */
      $this->db->update('UPDATE celeste_useronline SET lastvisit='.$this->timestamp.', lastforumid='.
        $forumid.', ipaddress=\''.$this->ipaddress.'\',userid=\''.$userid.'\',username=\''.$user->properties['username'].'\',usergroupid='.$usergroupid.' WHERE userid=\''.$userid.'\'');
    }
    else
    {
      $this->db->update("Replace into celeste_guestonline (ipaddress, lastvisit,lastforumid) values('$this->ipaddress', $this->timestamp, '$forumid')");
    }
  }

  function isAdmin()
  {
  	return ($this->usergroup['admin']);
  }

  function isSU()
  {
  	global $usergroupid;
  	return ($this->usergroup['admin']==1 || $usergroupid == 2);
  }
  
  function sendmail( $to, $reply_to, $subject='', &$content, $sender = SET_EMAIL_SENDER, $acp = 0)
  {
    if (SET_ENABLE_EMAIL && !SET_DELAY_SENDMAIL && !$acp)
  	{
      if (!$subject) $subject = substr( $content, 0 , strpos($content, "\n"));
      $extra = "From: \"$sender\" <$reply_to>\n";
      if (mail($to, $subject, $content, $extra)) return true;
      else celeste_exception_handle('email_failed');
  	}
    else
    {
      $mail = "To: $to\n"."From: \"$sender\" <$reply_to>\n".
              "Subject: $subject\n\n".$content."\n\n";
      writetofile( DATA_PATH.'/mailbox/'.uniqid(time().'_').'.txt', $mail );
    }
  }

} // end of class 'celeste'


/**
 * timer class
 *  - to get the process time
 */
class celesteTimer
{

  var $_beginTime = '';
  var $_endTime   = '';

  function celesteTimer()
  {
    $this->_beginTime = $this->_getMicroTime();
  }


  function benchmark()
  {
    global $DB, $celeste, $thisprog;

    $procTime = $this->processTime();
    return '<center> Process Time: ' . $procTime . ' &nbsp; ' .
          'SQL Queries: ' . ($DB->selectQueries + $DB->updateQueries) . '&nbsp;' .
          '(' . $DB->selectQueries . '+' . $DB->updateQueries . ') </center>';
    /***
     * if execution time too long, record this process
     */
    if( $procTime > SET_MAX_EXECUTION_TIME )
    {

      $recordedString = "\n\n--------------------------------------------\n".
                "Program: " . $thisprog . "\n".
                "Process Time: " . getTime($celeste->timestamp) . "\n".
                "Client IP: " . $celeste->ipaddress . "\n".
                "Time Usage: " . $procTime;
      $fp = fopen( DATA_PATH . '/logs/exception_proc.dat', 'a' );
      fwrite($fp, $recordedString);
      fclose($fp);
    }
  }

  /***
   * get the script proccess time
   */
  function processTime()
  {
    $this->_endTime = $this->_getMicroTime();
    return round($this->_endTime - $this->_beginTime, 4);
  }

  /***
   * return a real type - micro time
   */
  function _getMicroTime()
  {
    list($seconds, $microSeconds) = explode(' ', microtime());
    return (real)((float)$seconds + (float)$microSeconds);
  }

} // end of class 'celesteTimer'
