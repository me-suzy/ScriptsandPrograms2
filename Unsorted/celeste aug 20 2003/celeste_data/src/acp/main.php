<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

if($thisprog == 'logout') {
  $session->destroy();
  acp_redirect($_SERVER['PHP_SELF']);
}

if(isset($_GET['main']) && $_GET['main'] != 'prog=main&' && $_GET['main'] != 'prog=header&' && $_GET['main'] != 'prog=menu&') {
  $mainpage = $_SERVER['PHP_SELF'].$_GET['main'];
} else {
  $mainpage = $_SERVER['PHP_SELF'].'?prog=welcome';
}

?>
<html>
<head>
<title>Celeste Admin Control Panel</title>
</head>

	<frameset cols="200,*" bordercolor="#0099cc" border="0" frameborder="0" style="background-color:#0099cc">
		<frame src="<?=$_SERVER['PHP_SELF']?>?prog=menu" name="sidebar" scrolling="auto" marginheight="0" marginwidth="0" noresize>	   
		<frameset rows="*" bordercolor="#0099cc" border="0" frameborder="0" style="background-color:#0099cc">   
			<frame src="<?=$mainpage?>" name="main" scrolling="auto" noresize>
		</frameset>
	</frameset>

</html>

