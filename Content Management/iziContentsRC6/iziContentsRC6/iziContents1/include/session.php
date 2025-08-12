<?php

/***************************************************************************

 session.php
 ------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

require_once("includeSec.php");
//	Retrieve the session maximum lifetime (found in php.ini)
//	$lifetime = get_cfg_var("session.gc_maxlifetime");
$lifetime = 3600;		// 1800 = 1/2 hour should be more than enough, as it's based around an activity timer

//	Session management functions
//
//	Read the session data from the database
function db_session_read()
{
	GLOBAL $EZ_SESSION_VARS;

	$retval = False;
	db_session_garbage_collect();
	$sqlQuery = "SELECT sessvalue FROM ".$GLOBALS["eztbSessions"]." WHERE SID = '".$GLOBALS["ezSID"]."'";
	$result = dbRetrieve($sqlQuery,true,0,0);
	$rdata = dbFetch($result);
	$sdata = $rdata["sessvalue"];
	dbFreeResult($result);
	if ($sdata != '') {
		$EZ_SESSION_VARS = unserialize($sdata);
		$retval = True;
} else {
		$EZ_SESSION_VARS["LoginCookie"] = '';
		$EZ_SESSION_VARS["PasswordCookie"] = '';
		$EZ_SESSION_VARS["UserID"] = 0;
		$EZ_SESSION_VARS["UserName"] = '';
		$EZ_SESSION_VARS["UserGroup"] = '';
		db_session_write();
		include_once ($GLOBALS["rootdp"]."include/functions.php");
		?><script language=javascript>top.location='index.php';</script><?php
		exit;
	}

	return $retval;
} // function db_session_read()


//	Write the session data to the database.
//	If the SID already exists, then the existing data will be updated.
function db_session_write()
{
	GLOBAL $EZ_SESSION_VARS, $lifetime;

	if (isset($EZ_SESSION_VARS)) {
		$slist = serialize($EZ_SESSION_VARS);
		$expiration = time() + $lifetime;
		$sqlQuery = "INSERT INTO ".$GLOBALS["eztbSessions"]." VALUES('".$GLOBALS["ezSID"]."', '".$expiration."', '".$slist."')";
		$result = dbExecute($sqlQuery,false);
		if (!$result) {
			$sqlQuery = "UPDATE ".$GLOBALS["eztbSessions"]." SET expiration='".$expiration."', sessvalue='".$slist."' WHERE SID='".$GLOBALS["ezSID"]."'";
			$result = dbExecute($sqlQuery,true);
		}
		dbCommit();
	}
} // function db_session_write()


//	Garbage collection.
//	Delete all sessions that have expired (excluding your own).
//	This doesn't mean that your session won't be deleted by somebody else if you let it expire.
function db_session_garbage_collect()
{
	GLOBAL $lifetime;

	$lifespan = time() - $lifetime;
	if ($GLOBALS["ezSID"] != '') {
		$sqlQuery = "DELETE FROM ".$GLOBALS["eztbSessions"]." WHERE expiration < '".$lifespan."' AND SID != '".$GLOBALS["ezSID"]."'";
	} else {
		$sqlQuery = "DELETE FROM ".$GLOBALS["eztbSessions"]." WHERE expiration < '".$lifespan."'";
	}
	$result = dbExecute($sqlQuery,false);
	dbCommit();
} // function db_session_garbage_collect()




//	Establish a connection to the database.
@db_connect($GLOBALS["ezContentsDBServer"],$GLOBALS["ezContentsDBName"],$GLOBALS["ezContentsDBLogin"],$GLOBALS["ezContentsDBPassword"]);
$GLOBALS["dbAccesses"]  = 0;
$GLOBALS["dbTotalTime"] = 0;


//	Wipe $GLOBALS["ezSID"] to prevent hackers trying to hijack a session
$GLOBALS["ezSID"] = '';
if (isset($_COOKIE["ezSID"])) { $GLOBALS["ezSID"] = $_COOKIE["ezSID"];
} else {
	//	If we've come from a form page, the session ID might be in $_POST rather than $_GET
	if (isset($_POST["ezSID"])) { $GLOBALS["ezSID"] = $_POST["ezSID"];
	} else { if (isset($_GET["ezSID"])) { $GLOBALS["ezSID"] = $_GET["ezSID"]; } }
}

//	The Session cookie will be set after our first visit to the page;
//		but if this is our first visit this session, it won't yet be set.
//		We can use this to set the flag $stats_firstvisit, which is used
//		to ensure that we only collect visitor statistics once.
$stats_firstvisit = true;
if ($GLOBALS["ezSID"] != '') { $stats_firstvisit = false; }


//	Start session management
$readsession = True;
if ($GLOBALS["ezSID"] != '') {
	//	If we have a current session established, read the session vars
	$readsession = db_session_read();
	setcookie ("ezSID", $GLOBALS["ezSID"], 0, '/');  /* expire when user closes the browser */
}
if (($GLOBALS["ezSID"] == '') || (!$readsession)){
	//	If not, we create a session.
	//		Start by generating a unique session id

     // Start modification by Comic 
// Skip sessionid for Google, slurp, fast, msnbot, zyborg, alexa and altavista 
if  ((strstr(strtolower($_SERVER['HTTP_USER_AGENT']) ,'googlebot')) || 
         (strstr(strtolower($_SERVER['HTTP_USER_AGENT']) ,'slurp')) || 
      (strstr(strtolower($_SERVER['HTTP_USER_AGENT']) ,'fast')) || 
         (strstr(strtolower($_SERVER['HTTP_USER_AGENT']) ,'msnbot')) || 
         (strstr(strtolower($_SERVER['HTTP_USER_AGENT']) ,'zyborg')) || 
         (strstr(strtolower($_SERVER['HTTP_USER_AGENT']) ,'ia_archiver')) || 
         (strstr(strtolower($_SERVER['HTTP_USER_AGENT']) ,'scooter'))) 
   $GLOBALS["ezSID"] = ""; 
else 
// End modification by Comic

	$GLOBALS["ezSID"] = md5(uniqid(rand(),1));
	//	Initialise our session variables
	//		and create a database entry for the session
	$EZ_SESSION_VARS["LoginCookie"] = '';
	$EZ_SESSION_VARS["PasswordCookie"] = '';
	$EZ_SESSION_VARS["noframesbrowser"] = 0;
	$EZ_SESSION_VARS["UserID"] = 0;
	$EZ_SESSION_VARS["UserName"] = '';
	$EZ_SESSION_VARS["UserGroup"] = '';
	$EZ_SESSION_VARS["Language"] = '';
	$EZ_SESSION_VARS["Country"] = '';
	$EZ_SESSION_VARS["Site"] = '';
	$EZ_SESSION_VARS["Theme"] = '';
	$EZ_SESSION_VARS["Browser"] = '';
	$EZ_SESSION_VARS["WYSIWYG"] = '';
	db_session_write();
	setcookie ("ezSID", $GLOBALS["ezSID"], 0, '/');  /* expire when user closes the browser */
}


$requesturi = $_SERVER["REQUEST_URI"];
$uri = explode('?',$requesturi);
$GLOBALS["REQUEST_URI"] = $uri[0];


if ($_GET["Site"] != '') { $EZ_SESSION_VARS["Site"] = $_GET["Site"]; }

//  If we're set to a specific site in multi-site mode ($EZ_SESSION_VARS["Site"] logs this)
//		then we read in the site-specific configuration at this stage.
if (isset($EZ_SESSION_VARS["Site"])) {

	if ($EZ_SESSION_VARS["Site"] != '') {
		//  First see if the site exists.
		$strQuery = "SELECT sitecode FROM ".$GLOBALS["eztbSites"]." WHERE sitecode='".$EZ_SESSION_VARS["Site"]."'";
		//  If we're accessing from the front end, the site must also be enabled.
		if ($GLOBALS["rootdp"] == '') { $strQuery .= " AND siteenabled='1'"; }
		$sresult = dbRetrieve($strQuery,true,0,0);
		$sRecCount = dbRowsReturned($sresult);
		dbFreeResult($sresult);

		if ($sRecCount > 0) {
			//  If the site exists (and is enabled for front-end users), read in the site-specific
			//		configuration script.
			$fname = $GLOBALS["rootdp"].$GLOBALS["sites_home"]."config.".$EZ_SESSION_VARS["Site"].".php";
			if (file_exists($fname) == true) {
				include_once($fname);
			}
		} else {
			//  Otherwise reset the session variable
			$EZ_SESSION_VARS["Site"] = '';
			db_session_write();
		}
	} else {
		include_once($GLOBALS["rootdp"].'include/config.php');
	}
}

// ##### Theme - @broxx
// Theme laut Topgroup ermitteln
if (isset($_POST["topgroupname"])) { $_GET["topgroupname"] = $_POST["topgroupname"]; }
if ($_GET["topgroupname"] != "") {
    if ($EZ_SESSION_VARS["Language"] != "") { $lang = $EZ_SESSION_VARS["Language"]; }
    else { $lang = "de"; }
    $strQuery = "SELECT topgroupname,language,toptheme FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname = '".$_GET["topgroupname"]."'";
    $themeresult = dbRetrieve($strQuery,true,0,0);
    while ($themedata = dbFetch($themeresult)) {
        $EZ_SESSION_VARS["Theme"] = $themedata["toptheme"];
    }
    dbFreeResult($themeresult);
}
db_session_write();
// #### End: Theme - @broxx


//  If we're set to a specific theme in multi-theme mode ($EZ_SESSION_VARS["Theme"] logs this)
//		then we read in the theme-specific configuration at this stage.
if ($EZ_SESSION_VARS["Theme"] != '') {
	//  First see if the theme exists.
	$strQuery = "SELECT themecode FROM ".$GLOBALS["eztbThemes"]." WHERE themecode='".$EZ_SESSION_VARS["Theme"]."'";
	//  If we're accessing from the front end, the theme must also be enabled.
	if ($GLOBALS["rootdp"] == '') { $strQuery .= " AND themeenabled='1'"; }
	$sresult = dbRetrieve($strQuery,true,0,0);
	$sRecCount = dbRowsReturned($sresult);
	dbFreeResult($sresult);

	if ($sRecCount > 0) {
		//  If the theme exists (and is enabled for front-end users), read in the theme-specific
		//		configuration script.
		$fname = $GLOBALS["rootdp"].$GLOBALS["themes_home"]."config.".$EZ_SESSION_VARS["Theme"].".php";
		if (file_exists($fname) == true) {
			include_once($fname);
		}
	} else {
		//  Otherwise reset the session variable
		$EZ_SESSION_VARS["Theme"] = '';
		db_session_write();
	}
}


if ($EZ_SESSION_VARS["WYSIWYG"] == '') {
	//	Determine whether we should implement HTMLArea
	if (($EZ_SESSION_VARS["Browser"] == 'Microsoft Internet Explorer') && ($EZ_SESSION_VARS["BrowserVersion"] >= '5.5' ) &&
	   (($EZ_SESSION_VARS["Platform"] == 'Windows') || ($EZ_SESSION_VARS["Platform"] == 'Win32'))) {
		$EZ_SESSION_VARS["WYSIWYG"] = 'Y';
		$EZ_SESSION_VARS["WYSIWYG_Version"] = '2';
	}
	if (($EZ_SESSION_VARS["Browser"] == 'Gecko') && ($EZ_SESSION_VARS["BrowserVersion"] >= '20030210' )) {
		$EZ_SESSION_VARS["WYSIWYG"] = 'Y';
		$EZ_SESSION_VARS["WYSIWYG_Version"] = '3 Beta';
	}
	db_session_write();
}

?>
