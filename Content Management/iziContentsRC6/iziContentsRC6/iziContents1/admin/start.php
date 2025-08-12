<?php

/***************************************************************************

 start.php
 ----------
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
	<body leftmargin=0 topmargin=10 marginwidth="0" marginheight="10" class="mainback" onload="top.ezc.mainbody.left.location='<?php echo BuildLink('menu.php'); ?>&activegroup=<?php echo $_GET["RefreshMenu"]; ?>';">
	<?php
} else {
	?>
	<body leftmargin=0 topmargin=10 marginwidth="0" marginheight="10" class="mainback">
	<?php
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td align=center class=bg_table>
			<table border="0" width="100%" cellspacing="1" cellpadding="3">
				<tr class="headercontent">
					<td colspan="2" align="center" class="header">
						<b><?php echo $GLOBALS["tsTitle"]; ?></b>
					</td>
				</tr>
				<tr class="tablecontent">
					<td colspan="2">
						<?php
						echo $GLOBALS["tsWelcome"].' <b>'.$UserName.'</b><br />';
						echo $GLOBALS["tsMessage"].'<br /><br />';

						$cgroupname = '';
						$strQuery = "SELECT g.groupname as groupname,f.functionname as functionname FROM ".$GLOBALS["eztbFunctions"]." f, ".$GLOBALS["eztbFunctiongroups"]." g, ".$GLOBALS["eztbPrivileges"]." p WHERE g.groupname=f.groupname AND p.functionname=f.functionname AND p.usergroupname='".$EZ_SESSION_VARS["UserGroup"]."' AND (p.accessview='Y' OR p.accessedit='Y' OR p.accessadd='Y' OR p.accessdelete='Y') ORDER BY g.grouporderid";
						$result = dbRetrieve($strQuery,true,0,0);
						while ($rs = dbFetch($result)) {
							if ($rs["groupname"] != $cgroupname) {
								$fdesc = $rs["groupname"];
								echo '<b>'.$GLOBALS["tg".$fdesc].'</b>: '.$GLOBALS["tgd".$fdesc].'<br />';
							}
							$cgroupname = $rs["groupname"];
						}
						echo '<br />';
						dbFreeResult($result);
						?>
					</td>
				</tr>
				<?php
				$strQuery	= "SELECT scid FROM ".$GLOBALS["eztbSpecialcontents"]." WHERE scvalid='Y'";
				$result		= dbRetrieve($strQuery,true,0,0);
				$rs			= dbFetch($result);
				$lSCIDCount	= dbRowsReturned($result);
				dbFreeResult($result);
				if ($lSCIDCount > 0) {
					?>
					<tr class="headercontent">
						<td colspan="2" align="center" class="header">
							<b><?php echo $GLOBALS["tmSubContent"]; ?></b>
						</td>
					</tr>
					<tr class="tablecontent">
						<td colspan="2">
							<?php
							$strQuery = "SELECT * FROM ".$GLOBALS["eztbSpecialcontents"]." ORDER BY scname";
							$result = dbRetrieve($strQuery,true,0,0);
							while ($rs = dbFetch($result)) {
								$scName = $rs["scname"];
								$scTitle = $rs["sctitle"];
								$scUsePrefix = $rs["scuseprefix"];
								if ($scUsePrefix == 'Y') {
									$scTable = $GLOBALS["eztbPrefix"].$rs["scdb"];
								} else {
									$scTable = $rs["scdb"];
								}
								$countQuery	= "SELECT activeentry FROM ".$scTable." WHERE activeentry != '1'";
								$countres	= dbRetrieve($countQuery,true,0,0);
								$lSRecCount	= dbRowsReturned($countres);
								dbFreeResult($countres);
								$alink = '<a class="menulink" title="'.str_replace("'","\'",$scTitle).'" href="'.BuildLink($GLOBALS["rootdp"].$GLOBALS["modules_home"].$scName.'/m_'.$scName.'.php').'"'.BuildLinkMouseOver($scTitle).'>';
								if ($lSRecCount == 1) {
									echo $GLOBALS["tThereIs"].' '.$lSRecCount.' '.$alink.$scName.'</a> '.$GLOBALS["tEntryPending"].'<br />';
								} else {
									echo $GLOBALS["tThereAre"].' '.$lSRecCount.' '.$alink.$scName.'</a> '.$GLOBALS["tEntriesPending"].'<br />';
								}
							}
							dbFreeResult($result);
							?>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php


function bVerifyLogin()
{
	global $EZ_SESSION_VARS;
	$bUsergroup = '';
	if (($EZ_SESSION_VARS["LoginCookie"] != '') && ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
		$strQuery	= "SELECT * FROM ".$GLOBALS["eztbAuthors"]." a, ".$GLOBALS["eztbUsergroups"]." u WHERE a.login='".$EZ_SESSION_VARS["LoginCookie"]."' AND a.userpassword='".$EZ_SESSION_VARS["PasswordCookie"]."'";
		$result		= dbRetrieve($strQuery,true,0,0);
		$rs			= dbFetch($result);
		if ($rs["login"] == $EZ_SESSION_VARS["LoginCookie"]) { $bUsergroup = $rs["usergroup"]; }
		dbFreeResult($result);
	}
	return $bUsergroup;
} // function bVerifyLogin()

?>
