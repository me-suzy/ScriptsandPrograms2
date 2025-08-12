<?php

/***************************************************************************

 access.php
 -----------
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

function VerifyLogin()
{
	global $EZ_SESSION_VARS;

	$strQuery = "SELECT login FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."' AND userpassword='".$EZ_SESSION_VARS["PasswordCookie"]."'";
	$result = dbRetrieve($strQuery,false,0,0);
	$rs = dbFetch($result);
	if ($rs["login"] == $EZ_SESSION_VARS["LoginCookie"]) {
		dbFreeResult($result);
		return true;
	}
	dbFreeResult($result);
	Header("Location: ".BuildLink($GLOBALS["rootdp"].'login.php'));
} // function VerifyLogin()


function VerifyAdminLogin() {
	global $EZ_SESSION_VARS;

	$valid = False;
	if (VerifyLogin()) {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbPrivileges"]." WHERE usergroupname='".$EZ_SESSION_VARS["UserGroup"]."' AND functionname='".$GLOBALS["form"]."'";
		$result = dbRetrieve($strQuery,false,0,0);
		$rs = dbFetch($result);
		$valid = '';
		if ($rs["accessview"] == "Y") { $valid = 'V'; $GLOBALS["canview"] = True;
		} else { $GLOBALS["canview"] = False; }
		if ($rs["accessedit"] == "Y") { $valid .= 'E'; $GLOBALS["canedit"] = True;
		} else { $GLOBALS["canedit"] = False; }
		if ($rs["accessadd"] == "Y") { $valid .= 'A'; $GLOBALS["canadd"] = True;
		} else { $GLOBALS["canadd"] = False; }
		if ($rs["accessdelete"] == "Y") { $valid .= 'D'; $GLOBALS["candelete"] = True;
		} else { $GLOBALS["candelete"] = False; }
		if ($rs["accesstranslate"] == "Y") { $valid .= 'T'; $GLOBALS["cantranslate"] = True;
		} else { $GLOBALS["cantranslate"] = False; }
		dbFreeResult($result);
		if ($valid == '') {
			Header("Location: ".BuildLink('adminlogin.php'));
		}
	}
	return $valid;
}


function VerifyAdminLogin2()
{
	VerifyAdminLogin();
	if ($GLOBALS["canedit"] == False) {
		if ($GLOBALS["canview"] == False) {
			Header("Location: ".BuildLink('adminlogin.php'));
		} else {
			$GLOBALS["fieldstatus"] = ' disabled';
		}
	} else {
		$GLOBALS["fieldstatus"] = '';
		$GLOBALS["specialedit"] = True;
	}
} // function VerifyAdminLogin2()


function VerifyAdminLogin3($keyname)
{
	global $_GET, $_POST;

	VerifyAdminLogin();
	// Now test the returned access values
	if ($GLOBALS["canedit"] == False) {
		if ($GLOBALS["canadd"] == False) {
			if (($GLOBALS["canview"] == False) && ($GLOBALS["cantranslate"] == False)) {
				Header("Location: ".BuildLink('adminlogin.php'));
			} else {
				$GLOBALS["fieldstatus"] = ' disabled';
			}
		} else {
			if (($_GET[$keyname] != '') || ($_POST[$keyname] != '')) {
				$GLOBALS["fieldstatus"] = ' disabled';
			} else {
				$GLOBALS["specialedit"] = True;
			}
		}
	} else {
		$GLOBALS["specialedit"] = True;
	}
} // function VerifyAdminLogin3()

?>
