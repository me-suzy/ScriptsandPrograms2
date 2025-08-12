<?php

/***************************************************************************

 t_menu.php
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

include_once ("rootdatapath.php");

includeLanguageFiles('admin','adminmenu');


$GLOBALS["usergroup"] = bVerifyLogin();

force_page_refresh();
adminheader();
if (isset($_GET["RefreshMenu"])) {
	?>
	<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback" onload="top.mainbody.left.location='menu.php';">
	<?php
} else {
	?>
	<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback">
	<?php
}

$fdesc = $_GET["groupname"];
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3">
	<tr>
		<td align=center>
			<table border="0" width="100%" cellspacing="3" cellpadding="3">
				<tr class="headercontent">
					<td align="center" class="header">
						<b><?php echo $GLOBALS["tsTitle"]; ?></b>
					</td>
				</tr>
				<tr class="tablecontent">
					<td>
						<?php echo '<br /><b>'.$GLOBALS["tg".$fdesc].'</b>:<br />'.$GLOBALS["tgd".$fdesc].'<br />&nbsp;'; ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html><?php


function bVerifyLogin()
{
	global $EZ_SESSION_VARS;

	$bUsergroup = '';
	if (($EZ_SESSION_VARS["LoginCookie"] != '') && ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
		$strQuery	= "SELECT * FROM ".$GLOBALS["eztbAuthors"]." a, ".$GLOBALS["eztbUsergroups"]." u WHERE a.login='".$EZ_SESSION_VARS["LoginCookie"]."' and a.userpassword='".$EZ_SESSION_VARS["PasswordCookie"]."'";
		$result		= dbRetrieve($strQuery,true,0,0);
		$rs			= dbFetch($result);
		if ($rs["login"] == $EZ_SESSION_VARS["LoginCookie"]) { $bUsergroup = $rs["usergroup"]; }
		dbFreeResult($result);
	}
	return $bUsergroup;
} // function bVerifyLogin()

?>
