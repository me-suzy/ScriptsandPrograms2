<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
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