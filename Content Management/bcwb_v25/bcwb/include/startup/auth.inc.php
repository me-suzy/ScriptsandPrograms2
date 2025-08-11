<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function  authenticate()  	{
	Header("WWW-Authenticate: Basic realm=\"".$lang["Authorization_in_admin_area"]."\"");
	Header("HTTP/1.0 401 Unauthorized");
	echo  "You  must  enter  a  valid  login  ID  and  password  to  access  this  resource\n";
	exit;
}


session_register("auth_session");
$SESSION_ID=session_id();
//if(!isset($rn_cookies))	{ SetCookie("rn_cookies", $SESSION_ID, gmmktime()+(60*60*2*24*364)); $rn_cookies=$SESSION_ID;}
$authorized=false;

if($argv[0]=="logout")
{
	$auth_session=false; $argv=array();
}

if($argv[0]==$admin_subdomain)
{
	if( (!$auth_session AND !$POST_AUTH_DATA) AND !isset($PHP_AUTH_USER)  )
	authenticate();
	else 	
	{
		$auth_session["login"]=$PHP_AUTH_USER;
		$auth_session["passw"]=$PHP_AUTH_PW;
	}
}

if( $auth_session["login"] == $admin_login AND $auth_session["passw"] == $admin_password )
	$authorized = true;
	elseif($argv[0]==$admin_subdomain) authenticate();

?>
