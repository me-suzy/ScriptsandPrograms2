<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

	if($Config_makelogs == "1")
      $csr->MakeAdminLogs( $uid, "Logged out", "1");

	  $ucook->Logout();
 	  $result_logout = queryDB( "UPDATE $tbl_userinfo SET sessiontime='0' WHERE uid='$uid'" );

        $usr->HeaderOut();
	  $errMsg = "<b>$strLoginError5, <a href=login.php>$strLogin</a></b>\n";
	  $usr->errMessage( $errMsg, '', 'tick', '70' );

        $usr->FooterOut();

        exit;
?>
