<?php

/***************************************************************************

 m_polloptions.php
 ------------------
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

$GLOBALS["ModuleName"] = 'poll';
include("moduleref.php");

$GLOBALS["rootdp"] = '../../';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

include ($GLOBALS["rootdp"]."include/access.php");

$GLOBALS["form"] = 'subcontent';
$GLOBALS["validaccess"] = VerifyAdminLogin();


include ($GLOBALS["rootdp"]."include/settings.php");
include ($GLOBALS["rootdp"]."include/functions.php");
include ($GLOBALS["rootdp"].$GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include_languagefile ($GLOBALS["modules_home"].$GLOBALS["ModuleRef"].'/',$GLOBALS["gsLanguage"],'lang_poll.php');
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminbutton.php");
include ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");


GetSpecialData($GLOBALS["ModuleRef"]);

if ($_POST["PollID"] != "") {
	$_GET["PollID"] = $_POST["PollID"];
}
frmPoll();


function frmPoll()
{
	global $_GET;

	adminheader();
	admintitle(4,$GLOBALS["tFormTitle"]);

	// Generate image tags for the different images that appear on the page
	adminbuttons($GLOBALS["tViewPollOption"],$GLOBALS["tAddNewPollOption"],$GLOBALS["tEditPollOption"],$GLOBALS["tDeletePollOption"]);

	$strQuery="SELECT question,polltype,pollvotes,authorid FROM ".$GLOBALS["scTable"]." WHERE pollid='".$_GET["PollID"]."'";
	$result = dbRetrieve($strQuery,true);
	$rsPoll = dbFetch($result);
	$question = $rsPoll["question"];
	$polltype = $rsPoll["polltype"];
	$pollvotes = $rsPoll["pollvotes"];
	$pollauthorid = $rsPoll["authorid"];
	dbFreeResult($result);

	?>
	<tr class="teaserheadercontent">
		<td colspan="4" align="<?php echo $GLOBALS["left"]; ?>" nowrap>
			<?php echo $question; ?>
		</td>
	</tr>
	<?php
	frmPollOptionsHdFt(4,$nCurrentPage,$nPages);
	?>
	<tr class="teaserheadercontent">
	<?php
		adminlistitem(8,$GLOBALS["tEditDel"],'');
		adminlistitem(76,$GLOBALS["tOption"],'');
		adminlistitem(8,'','');
		adminlistitem(8,'','');
	?>
	</tr>
	<?php

    // We display all options rather than a paged set of options, so we retrieve the full option count for the main query
	$strQuery = "SELECT count(*) AS optscount FROM ".$GLOBALS["scTable"]."options WHERE pollid='".$_GET["PollID"]."'";
	$result = dbRetrieve($strQuery,true);
	$rsPollCount = dbFetch($result);
	$optscount = $rsPollCount["optscount"];
	dbFreeResult($result);

	$strQuery = "SELECT * FROM ".$GLOBALS["scTable"]."options WHERE pollid='".$_GET["PollID"]."' ORDER BY optioncount DESC,polloption";
	$result = dbRetrieve($strQuery,true,0,$optscount);
	while ($rsPoll = dbFetch($result)) {
		?>
		<tr class="teasercontent">
			<td align="center" valign="top" class="content">
				<?php admineditcheck2('polloptionsform','PollID',$rsPoll["pollid"],'PollOptionID',$rsPoll["polloptionid"],$pollauthorid); ?>
				<?php admindeletecheck('DelEntry','PollOptionID',$rsPoll["polloptionid"]); ?>&nbsp;
			</td>
			<td valign="top" class="content"><?php echo $rsPoll["polloption"]; ?></td>
			<td valign="top" align="right" class="content"><?php echo $rsPoll["optioncount"]; ?></td>
			<td valign="top" align="right" class="content"><?php if ($pollvotes == 0) { echo '0.00%'; } else { $display = number_format($rsPoll["optioncount"] / $pollvotes * 100, "2"); echo $display.'%'; } ?></td>
		</tr>
		<?php
	}
	dbFreeResult($result);

	?>
	<tr class="teasercontent">
		<td class="content" colspan="2"></td>
		<td valign="top" align="right" class="teaserheadercontent"><?php echo $pollvotes; ?></td>
		<td valign="top" align="right" class="teaserheadercontent"><?php echo '100.00%'; ?></td>
	</tr>
	<?php

	frmPollOptionsHdFt(4,$nCurrentPage,$nPages);
	frmModuleReturn(4);
	?>
	</table>
	</form>
	</body>
	</html>
	<?php
}

?>
<script language="Javascript" type="text/javascript">
    <!-- Begin
       function DelEntry(sParams) {
          if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
             location.href='<?php echo BuildLink('m_'.$GLOBALS["ModuleName"].'optiondel.php'); ?>&' + sParams;
          }
       }
    //  End -->
</script>
<?php

function frmPollOptionsHdFt($colspan,$nCurrentPage,$nPages)
{
	global $_GET;

	$pLink = BuildLink('m_'.$GLOBALS["ModuleName"].'options.php');
	$fLink = BuildLink('m_'.$GLOBALS["ModuleName"].'optionsform.php').'&PollID='.$_GET["PollID"];
	$hlink = '<a href="'.$fLink.'&page='.$nCurrentPage.'" title="'.$GLOBALS["tAddNew"].'" '.BuildLinkMouseOver($GLOBALS["tAddNew"]).'>';

	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>" valign="bottom">
			<?php echo displaybutton('addbutton',$GLOBALS["ModuleName"],$GLOBALS["tAddNew"].'...',$hlink); ?>
		</td>
	</tr>
	<?php
} // function frmPollOptionsHdFt()

?>
