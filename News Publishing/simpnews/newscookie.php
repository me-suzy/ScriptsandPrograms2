<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$path_simpnews=dirname(__FILE__);
require_once($path_simpnews.'/config.php');
require_once($path_simpnews.'/functions.php');
// settings
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("SimpNews not set up.");
$lastvisitdays=$myrow["lastvisitdays"];
$lastvisitsessiontime=$myrow["lastvisitsessiontime"];
$actdate = date("Y-m-d H:i:00");
$cookieexpire=time()+($lastvisitdays*24*60*60);
$cookiedate="";
if($new_global_handling)
	if(isset($_COOKIE[$cookiename]))
		$cookiedata=$_COOKIE[$cookiename];
else
	if(isset($_COOKIE[$cookiename]))
		$cookiedata=$_COOKIE[$cookiename];
if(isset($cookiedata))
{
	if(sn_array_key_exists($cookiedata,"lastvisit"))
		$cookiedate=$cookiedata["lastvisit"];
}
else
	$cookiedate="";
if($cookiedate && (strpos($cookiedate,"-")>0))
{
	list($mydate,$mytime)=explode(" ",$cookiedate);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	$lastvisitdate=mktime($hour,$min,0,$month,$day,$year);
	if((time()-$lastvisitdate)>($lastvisitsessiontime*60))
		setcookie($cookiename."[lastvisit]",$actdate,$cookieexpire,$url_simpnews,$cookiedomain,$cookiesecure);
}
else
	setcookie($cookiename."[lastvisit]",$actdate,$cookieexpire,$url_simpnews,$cookiedomain,$cookiesecure);
?>