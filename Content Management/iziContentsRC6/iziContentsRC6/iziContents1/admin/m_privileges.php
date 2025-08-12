<?php

/***************************************************************************

 m_privileges.php
 -----------------
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

$GLOBALS["form"] = 'privileges';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','privileges');


force_page_refresh();
frmPrivileges();


function frmPrivileges()
{
	global $_GET;

	adminheader();
	admintitle(4,$GLOBALS["tFormTitle"]);
	adminbuttons($GLOBALS["tViewPrivilege"],$GLOBALS["tAddNewPrivilege"],$GLOBALS["tEditPrivilege"],$GLOBALS["tDeletePrivilege"]);

	$strQuery = "SELECT usergroupname FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$lRecCount = dbRowsReturned($result);
	dbFreeResult($result);

	$nCurrentPage = 0;
	if($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	adminHdFt('privileges',4,$nCurrentPage,$nPages,'');

	// If we've just tried to delete a usergroup and an error was returned, display the error message
	if ((isset($_GET["errmess"])) && ($_GET["errmess"] != '')) {
		$errmess = $_GET["errmess"];
		echo '<tr bgcolor=#900000><td colspan="4"><b>'.$GLOBALS[$errmess].'</b><br />'.urldecode($_GET["errqual"]).'</td></tr>';
	}
	?>
	<tr class="teaserheadercontent">
		<?php
		adminlistitem(10,$GLOBALS["tEditDel"],'c');
		adminlistitem(60,$GLOBALS["tUsergroupname"],'');
		adminlistitem(15,$GLOBALS["tDefaultGroup"],'c');
		adminlistitem(15,$GLOBALS["tAdminGroup"],'c');
		?>
	</tr>
	<?php

	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' ORDER BY usergroupdesc";
	$result = dbRetrieve($sqlQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
	while ($rs = dbFetch($result)) {
		?>
		<tr class="teasercontent">
			<td align="center" valign="top" class="content">
				<?php admineditcheck('privilegesform','UsergroupName',$rs["usergroupname"],$rs["authorid"]);
				// Can't delete the administrator group as this serves as a template
				//	for creating new privilege groups, and it's also the admin anyway.
				if ($rs["usergroupname"] == $GLOBALS["gsAdminPrivGroup"]) { echo $GLOBALS["iBlank"];
				} else {
					// Can't delete the default administrator group for new registrants
					if ($GLOBALS["gsPrivDefaultGroup"] != $rs["usergroupname"]) {
						admindeletecheck('DelUsergroup','UsergroupName',$rs["usergroupname"]);
					} else { echo $GLOBALS["iBlank"]; }
				}
				?>
			</td>
			<td valign="top" class="content">
				<?php echo $rs["usergroupdesc"]; ?>
			</td>
			<td valign="top" align="center" class="content">
				<?php
				if ($GLOBALS["gsPrivDefaultGroup"] != $rs["usergroupname"]) {
					if ($GLOBALS["canedit"] === False) {
						?><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]."checkbox_off.gif"; ?>" border="0" alt=""><?php
					} else {
						?>
						<a href="<?php echo BuildLink('m_privdefault.php'); ?>&usergroupname=<?php echo $rs["usergroupname"]; ?>" title="<?php echo $GLOBALS["tMakeDefault"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tMakeDefault"]); ?>>
						<img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]."checkbox_off.gif"; ?>" border="0" alt="<?php echo $GLOBALS["tMakeDefault"]; ?>">
						</a>
						<?php
					}
				} else {
					?><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]."checkbox_on.gif"; ?>" border="0" alt="<?php echo $GLOBALS["tDefaultSet"]; ?>"><?php
				}
				?>
			</td>
			<td valign="top" align="center" class="content">
				<?php
				if ($GLOBALS["gsAdminPrivGroup"] != $rs["usergroupname"]) {
					if ($GLOBALS["canedit"] === False) {
						?><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]."checkbox_off.gif"; ?>" border="0" alt=""><?php
					} else {
						?>
						<a href="<?php echo BuildLink('m_privadminset.php'); ?>&usergroupname=<?php echo $rs["usergroupname"]; ?>" title="<?php echo $GLOBALS["tMakeAdmin"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tMakeAdmin"]); ?>>
						<img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]."checkbox_off.gif"; ?>" border="0" alt="<?php echo $GLOBALS["tMakeAdmin"]; ?>">
						</a>
						<?php
					}
				} else {
					?><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]."checkbox_on.gif"; ?>" border="0" alt="<?php echo $GLOBALS["tAdminSet"]; ?>"><?php
				}
				?>
			</td>
		</tr>
		<?php
	}
	dbFreeResult($result);

	adminHdFt('privileges',4,$nCurrentPage,$nPages,'');
	?>
	</table>
	</body>
	</html>
	<?php
} // function frmPrivileges()

?>
<script language="Javascript" type="text/javascript">
	<!-- Begin
	function DelUsergroup(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_privilegesdel.php'); ?>&' + sParams;
		}
	}
	//  End -->
</script>
