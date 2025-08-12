<?php

/***************************************************************************

 uploaddownload.php
 -------------------
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

$GLOBALS["form"] = 'downloads';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','downloads');


require_once ($GLOBALS["rootdp"]."include/filefunctions.php");


validatefiletypes('Download');
validatedirectory();


$success = True;
if (($_POST["mode"] == 'uploadfile') || ($_POST["mode"] == 'createdir')) {
	if ($GLOBALS["canadd"]) {
		if ($_POST["mode"] == 'uploadfile') {
			$success = UploadNewFile($GLOBALS["downloads_home"].$_POST["subdir"]);
		} else {
			$success = CreateNewDir($GLOBALS["downloads_home"].$_POST["subdir"],$_POST["dirname"]);
		}
	}
}


force_page_refresh();
frmDownloads($success);


function frmDownloads($success)
{
	global $_GET;

	adminheader(False);

	if ($success) {
		?>
		<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" class="mainback" onload="opener.location='<?php echo buildlink('m_downloads.php'); ?>&subdir=<?php echo $_GET["subdir"]; ?>';">
		<center>
		<table border="0" width="100%" cellspacing="3" cellpadding="3">
			<tr class="headercontent">
				<td colspan="2" align="center" class="header">
					<b><?php echo $GLOBALS["tFormTitle1"]; ?></b>
				</td>
			</tr>
		<?php
	} else {
		admintitle(2,$GLOBALS["tFormTitle1"]);
		?>
		<tr class="tablecontent"><td colspan="2">
			<?php echo $GLOBALS["errormessage"]; ?>
		</td></tr>
		<tr><td colspan="2"></td></tr>
		<?php
	}

	adminsubheader(2,$GLOBALS["tFormTitle2"]);
	?>
	<form name="form0" method="post" enctype="multipart/form-data" action="uploaddownload.php" onSubmit="return validatefile()">
	<input type="hidden" name="form_submitted" value="yes">
	<input type="hidden" name="subdir" value="<?php echo $_GET["subdir"]; ?>">
	<tr class="tablecontent">
		<?php FieldHeading("DownloadFilename","filename"); ?></td>
		<td valign=top>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $GLOBALS["maxfilesize"]; ?>">
			<input type="file" size="50" maxlength="72" name="filename"<?php echo $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<tr class="topmenuback">
		<td colspan="2">
			<input type="submit" value="<?php echo $GLOBALS["bUpload"]; ?>">
			<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
			<input type="hidden" name="mode" value="uploadfile">
		</td>
	</tr>
	</form>
	<tr><td>&nbsp;</td></tr>
	<?php
	adminsubheader(2,$GLOBALS["tFormTitle3"]);
	?>
	<form name="form1" method="post" enctype="multipart/form-data" action="uploaddownload.php" onSubmit="return validatedir()">
	<input type="hidden" name="form_submitted" value="yes">
	<input type="hidden" name="subdir" value="<?php echo $_GET["subdir"]; ?>">
	<tr class="tablecontent">
		<?php FieldHeading("Subdirectoryname","dirname"); ?></td>
		<td valign=top>
			<input type="text" size="60" maxlength="72" name="dirname"<?php echo $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<tr class="topmenuback">
		<td colspan="2">
			<input type="submit" value="<?php echo $GLOBALS["bCreateDir"]; ?>">
			<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
			<input type="hidden" name="mode" value="createdir">
		</td>
	</tr>
	</form>
	<tr class="headercontent">
		<td colspan="4" align="<?php echo $GLOBALS["right"]; ?>"><a href="javascript:window.close();"><?php echo $GLOBALS["tCloseWindow"]; ?></a></td>
	</tr>
	</table>

	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		function validatefile() {
			if (!document.form0.filename.value) {
				alert('<?php echo $GLOBALS["eNoFile"]; ?>')
				return(false)
			}
			return(true)
		}

		function validatedir() {
			if (!document.form1.dirname.value) {
				alert('<?php echo $GLOBALS["eNoDir"]; ?>')
				return(false)
			}
			return(true)
		}
		//  End -->
	</script>

	</body>
	</html>
	<?php
} // function frmDownloads()

?>
