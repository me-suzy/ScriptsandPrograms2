<?php

/******************************************************
 * CjOverkill version 2.0.1
 * © Kaloyan Olegov Georgiev
 * http://www.icefire.org/
 * spam@icefire.org
 * 
 * Please read the lisence before you start editing this script.
 * 
********************************************************/

$loginpcheck="^[[:alnum:]]{1,}$"; // Login and password pattern. Edit if you know what you do
$maxlogin=250; // Maximum login lenght. Edit if you know what you do
$maxpasswd=250; // maximum password lenght. Edit if you know what you do
$cookietime=3600; // Time before auto logout (in seconds). Increase this one if you want.

$cookie = explode("|", $_COOKIE["cjoverkill_admin"]);

$stime=localtime();
$thishour=$stime[2];

if ($cookie[1]!=''){
    if (!eregi($loginpcheck,$cookie[1]) || !eregi($loginpcheck,$cookie[2]) || strlen($cookie[1])>$maxlogin || strlen($cookie[2])>$maxpasswd){
	if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
	    $proxy=$_SERVER["REMOTE_ADDR"];
	    $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else {
	    $ip=$_SERVER["REMOTE_ADDR"];
	     $proxy="";
	}
	$what="possible SQL injection or brute force attempt exploiting admin cookie";
	@mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
	  VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
	  print_error(mysql_error());
	print_error("Your cookie does not match the security criteria<BR>
		      Are you trying to hack me duhdah?
		      ");
    }
    elseif (eregi($loginpcheck,$cookie[1]) && eregi($loginpcheck,$cookie[2]) && strlen($cookie[1])<=$maxlogin && strlen($cookie[2])<=$maxpasswd){
	$cookie2=$cookie[2];
	$logon=@mysql_query("SELECT login, passwd, PASSWORD('$cookie2') AS passwd2 FROM cjoverkill_settings") OR 
	  print_error(mysql_error());
	$cj_row=@mysql_fetch_array($logon);
	extract($cj_row);
	if ($cookie[0] < time()-$cookietime) {
//	    echo ("1");
	    go_out();
	}
	elseif ($login!=$cookie[1] || $passwd!=$passwd2) {
	    if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
		$proxy=$_SERVER["REMOTE_ADDR"];
		$ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
	    }
	    else {
		$ip=$_SERVER["REMOTE_ADDR"];
		$proxy="";
	    }
	    $what="Admin cookie does not match login and password. Possible brute force attempt";
	    @mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
	      VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
	      print_error(mysql_error());
	    cjoverkill_disconnect();
	    print_error("Your cookie does not match the security criteria<BR>
			  Are you trying to hack me duhdah?
			  ");
	}
	else {
//	    echo ("3");
//	    go_out();
	}
    }
/*    else {
	if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
	    $proxy=$_SERVER["REMOTE_ADDR"];
	    $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else {
	    $ip=$_SERVER["REMOTE_ADDR"];
	     $proxy="";
	}
	$what="possible SQL injection attempt exploiting admin cookie";
	@mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
                      VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
	  print_error(mysql_error());
	print_error("Login and password do not match the security criteria<BR>
		      Are you trying to hack me duhdah?
		      ");
    }*/
}
else {
    if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
	$proxy=$_SERVER["REMOTE_ADDR"];
	$ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    else {
	$ip=$_SERVER["REMOTE_ADDR"];
	$proxy="";
    }
    $what="Admin area access attempt without authentication";
    @mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
                  VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
      print_error(mysql_error());
    print_error("You appear to have no cookie<BR>
		  Are you trying to hack me duhdah?
		  ");
}

function go_out(){
    setcookie("cjoverkill_admin", "", time());
    header("Location: index.php");
    exit;
}
	

?>
