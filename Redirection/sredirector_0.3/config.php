<?PHP
/*
Seraph Redirector
Author: Ryan Ong <Snobord787@msn.com>
Copyright (c): 2003 Ryan Ong, all rights reserved
Version: 0.3
Site: sredirector.sourceforge.net
Updated: 10/30/03
 * This Script is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License (GPL)
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/*------------------------------------------------
URL config
------------------------------------------------*/
$host		= "www.google.com";	// do not add slash at end and do not add http://
$protocal	= "http://";		// This can be either ftp:// or http:// or https://

/*------------------------------------------------
Site Relay Config
Site Relay will send Post form, cookie and file Vars 
IF fsockopen works
------------------------------------------------*/
$relay		= 1;			// Relays page from remote server.
$timeout	= 30;			// How long you allow the page to Load.


$title		= "Home Page";		// This is the pages title for Frame set and Redirection(if $rtime > 0)
/*------------------------------------------------
Redirect Config
Redirect will foward cookies.

Only get forms vars will be fowarded. Insert
method="get"
in your <form> tag.
------------------------------------------------*/
$redirect	= 0;			// True for Redirect
$rtime		= 5;			// This is the time it takes to redirect if using Redirect (in seconds)

/*------------------------------------------------
Frameset Config
Frameset will foward cookies.

Only get forms vars will be fowarded. Insert 
method="get"
in your <form> tag.
------------------------------------------------*/
$frameset	= 0;			// True to use Frameset

/*------------------------------------------------
Redirection Template.
------------------------------------------------*/
function template(){
	global $title,$rtime,$host,$protocal,$path;
	$path=$protocal.$url.$path;
/*------------------------------------------------
EDIT BETWEEN
return <<<SKIN
AND
SKIN;
------------------------------------------------*/
return <<<SKIN
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<div align="center" style="font-family: Verdana;font-size: 12px;"><br /><br />You will be redirected <a href="$path">$path</a> in $rtime seconds</div>
</body>
</html>
SKIN;
}
?>