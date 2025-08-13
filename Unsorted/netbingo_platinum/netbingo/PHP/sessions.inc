<?php
// Database connection parameters - set in config.inc
$conID=mysql_pconnect ($host,$dbusername,$dbpassword);
mysql_select_db($dbname,$conID);

// Define the Session class

class Session
{

// Define the properties:

var $sqlhost;
var $sqluser;
var $sqlpass;
var $sqldb;
var $linkid;
var $seshid;
var $sessdata;
var $err;
var $err_no;
var $expire_time; 
// length of time until expiration in seconds

var $userid;

function Session($localSeshID, $localUserID=0, $localError=0)
{
$this->seshid=$localSeshID;
$this->userid=$localUserID;

$this->sqlhost=$GLOBALS['host'];
$this->sqluser=$GLOBALS['dbusername'];
$this->sqlpass=$GLOBALS['dbpassword'];
$this->sqldb=$GLOBALS['dbname'];
$this->expire_time=$GLOBALS['session_expire'];

// connect to MySQL
$this->linkid=mysql_connect($this->sqlhost,$this->sqluser,$this->sqlpass);

// verify connection made
if (!$this->linkid) // was not able to connect
{
$localError=mysql_error();
$this->err_no=101;
return;
}

// select database
$result=mysql_select_db($this->sqldb,$this->linkid);
if (!$result) // unable to select db
{
$localError=mysql_error();
$this->err_no=102;
return;
}

$this->CleanUp(); // remove expired sessions

// check to see if verifying session or creating session
if(!$this->seshid) // seshid 0 so creating
{
$current=time();
$random=$this->userid . $current;
$this->seshid=md5($random);
$query="insert into sessions values('$this->seshid','$this->userid',$current)";
$result=mysql_query($query);
if (!$result)
{
$localError=mysql_error();
$this->err_no=104;
return;
}
// finished session create return to script

$this->err_no=0;
return;
}
// not new session, verify
$result=mysql_query("SELECT * FROM sessions
WHERE seshid='$this->seshid'");
if (!$result) // select failed
{
$localError=mysql_error();
$this->err_no=103;
return;
}
// verify valid data returned
$numrows=mysql_num_rows($result);
if (!$numrows) // no rows returned, seshid not valid
{
$this->err="Session id not valid";
$this->err_no=201;
return;
}
//get session data
$this->sessdata=mysql_fetch_array($result);
$not_expired=$this->VerifyTime();
if (!$not_expired) // too much time has passed since last access
{
$error="Session has expired";
$this->err_no=202;
return;
}
$this->ResetTime(); // reset lastused to current
$this->CleanUp(); // remove expired sessions
$this->err_no=0;
}

function VerifyTime()
{
$current=time(); // get current UNIX timestamp

// check if the session has expired
if ($this->sessdata[lastused]+$this->expire_time<$current) 
{
return 0;
}
return 1;
}

function ResetTime()
{
$current=time(); // get current UNIX timestamp
$query="update sessions ";
$query.="set lastused=$current ";
$query.="where seshid='$this->seshid'";
$result=mysql_query($query);
}

function CleanUp()
{
$current=time();
$still_valid=$current - $this->expire_time;
//$still_valid=$current - 60;
$query="delete from sessions where lastused<'$still_valid'";
$result=mysql_query($query);
}

} // end of class definition
?>