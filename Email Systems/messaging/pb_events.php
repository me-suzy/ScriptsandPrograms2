<?php

//define ("PB_CRYPT_LINKS" , "1");

function DoEvents($this) {
	global $_CONF , $_TSM;

	if($_SESSION["minibase"]["raw"]["user_level"] == 1) {
		$_CONF["forms"]["adminpath"] = $_CONF["forms"]["userpath"];
	}

	switch ($_GET["sub"]) {
		default:
			if ($_GET["redirect"]) {
				header("Location:" . urldecode($_GET["redirect"]));
				exit;
			}
			
		break;


	}
}

?>