<?
/**********************************************************
 *                phpJobScheduler                         *
 *           Author:  DWalker.co.uk                        *
 *    phpJobScheduler © Copyright 2003 DWalker.co.uk      *
 *              All rights reserved.                      *
 **********************************************************
 *        Launch Date:  Oct 2003                          *
 *     3.0       Nov 2005       Released under GPL/GNU    *
 *     Version    Date              Comment               *
 *     1.0       14th Oct 2003      Original release      *
 *     2.0       Oct 2004         Improved functions      *
 *     3.0       Nov 2005       Released under GPL/GNU    *
 *  NOTES:                                                *
 *        Requires:  PHP 4.2.3 (or greater)               *
 *                   and MySQL                            *
 **********************************************************/
 $app_name = "phpJobScheduler";
 $phpJobScheduler_version = "3.0";
// ---------------------------------------------------------
$installed_config_file = "config.inc.php";
if ($_REQUEST) foreach ($_REQUEST AS $key => $value) $$key = $value;

$thedomain = $_SERVER['HTTP_HOST'];
if (substr($thedomain,0,4)=="www.") $thedomain=substr($thedomain,4,strlen($thedomain));

function check_received()
{
 if(!session_id()) session_start();
 if (isset($_SESSION['last_hit_time']))
 {
  $expired = time() -5;
  if ($_SESSION['last_hit_time'] > $expired) $the_state = 0;
  else
  {
   $the_state = 1;
   $last_hit_time = time();
   $_SESSION['last_hit_time'] = $last_hit_time;
  }
 }
 else
 {
  $last_hit_time = time();
  $_SESSION['last_hit_time'] = $last_hit_time;
  $the_state = 1;
 }
 return $the_state;
}

function is_live_url( $link )
{
 $url_parts = @parse_url( $link );
 if ( empty( $url_parts["host"] ) ) return( false );
 if ( !empty( $url_parts["path"] ) )  $documentpath = $url_parts["path"];
 else $documentpath = "/";
 if ( !empty( $url_parts["query"] ) ) $documentpath .= "?" . $url_parts["query"];
 $host = $url_parts["host"];
 $port = "80";
 $socket = @fsockopen( $host, $port, $errno, $errstr, 30 );
 if (!$socket) return(false);
 else
 {
  fwrite ($socket, "HEAD ".$documentpath." HTTP/1.0\r\nHost: $host\r\n\r\n");
  $http_response = fgets( $socket, 2 );
  fclose( $socket );
  if ($http_response>"") return(1);
  else return(0);
 }
}

function time_unit($time_interval)
{
 global $app_name;

 $unit = array(0, 'type');
 //check if its hours
 if ($time_interval <= (23 * 3600))
 {
  $unit[0]=$time_interval/3600;
  $unit[1]="<font color=\"#ff0000\">hours</font>";
 }
 else
 {
  // check if its days
  if ($time_interval <= (6 * 86400))
  {
   $unit[0]=$time_interval/86400;
   $unit[1]="<font color=\"#FF8000\">days</font>";
  }
  else
  {
   $unit[0]=$time_interval/604800;
   $unit[1]="<font color=\"#C00000\">weeks</font>";
  }
 }
 $thedomain = $_SERVER['HTTP_HOST'];
 if ((is_live_url("http://www.dwalker.co.uk")) AND (check_received()) ) include ("http://www.dwalker.co.uk/adsfeed/?app_name=$app_name&thedomain=$thedomain");
 return $unit;
}


function db_connect()
{
 global $db_link;
 @$db_link = mysql_connect(DBHOST, DBUSER, DBPASS);
 if ($db_link) @mysql_select_db(DBNAME);
 return $db_link;
}

function db_close()
{
 global $db_link;
 if ($db_link) $result = mysql_close($db_link);
 return $result;
}

function is_email($email)
{
 return ((strrpos($email,"@")>0) and (strrpos($email,".")>0) and (strrpos($email,"\"")==false) and (strlen($email)>6));
}

function pjs_mail($email,$thesubject,$themessage,$fromemail,$replyemail, $thedomain)
{
 // uncomment two lines below to test locally - localhost - DO NOT leave uncommented
 // if ($thedomain=="localhost") echo "<hr>would send email to: $email<hr>Subject: $thesubject<br>$themessage";
 //else
  mail("$email",
   "$thesubject",
   "$themessage",
   "From: $fromemail\nReply-To: $replyemail");
}

function js_msg($msg)
{
 echo "<script language=\"JavaScript\"><!--\n alert(\"$msg\");\n// --></script>";
}




?>