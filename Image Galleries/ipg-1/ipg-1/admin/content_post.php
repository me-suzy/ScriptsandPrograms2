<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/

include("../includes/config.php");
include("../includes/functions/fns_std.php");
include("../includes/functions/fns_db.php");
include('../includes/settings.php');

if(!$_SESSION['admin']){  redirect('../login.php'); }

$DOC_TITLE = "Post Your Content";

$cid = strlen($_GET['cid'])?$_GET['cid']:$_POST['cid'];

$sql = "SELECT * FROM " . PDB_PREFIX . "content WHERE id = " . $cid;
db_connect();
$result = db_query($sql);
$row = db_fetch_array($result);
 if(db_num_rows($result)) {
 	$insert = false;		// There is a record for this text already, do an update instead.
 } else {
 	$insert = true;			// There is no record for this text yet, do an insert.
 }

include('./templates/header.php');

if($_POST['submit']) {
	if(!$insert){
		$sql = "UPDATE " . PDB_PREFIX . "content  
		SET title = '" . $_POST['title'] . "',
		menu_label = '" . $_POST['menu_label'] . "', 
		body = '" . $_POST['rte1'] . "'  
		WHERE id = " . $_POST['cid'];
		db_query($sql);
	} elseif($insert) {
		$sql = "INSERT INTO " . PDB_PREFIX . "content VALUES (0,'" . $_POST['title'] . "', 
		'" . $_POST['rte1'] . "', " . $_POST['menu_label'] . ")";
		db_query($sql);
	}//end if
 	$msg = 'Content/changes submitted.';
}//end if submit

$sql = "SELECT * FROM " . PDB_PREFIX . "content WHERE id = " . $cid;
$result = db_query($sql);
$row = db_fetch_array($result);

?>


<table width="444" border="0" cellpadding="10">
  <tr> 
    <td> <br>
      <p><span class="admin_section_title">Content Manager</span></p>
      <p><span class="admin_error_mark">
        <?php print $msg ?>
        </span></p>
      <form onSubmit="return submitForm();" name="form1" method="post" action="<?php print $PHP_SELF ?>" enctype="multipart/form-data">
        <table width="100%" class="admin_form_box">
          <tr> 
            <td colspan="2"> 
              <p class="admin_form_header">&nbsp; EDIT CONTENT FOR 
                <?php print $row['title']; ?>
              </p>
            </td>
          </tr>
          <tr> 
            <td> 
              <div class="admin_form_label">Title</div>
              <input type="text" name="title" value="<?php print htmlentities($row['title']); ?>" size="50" maxlength="64">
          <tr> 
          <tr> 
            <td> 
              <div class="admin_form_label">Menu Label</div>
              <input type="text" name="menu_label" value="<?php print htmlentities($row['menu_label']); ?>" size="50" maxlength="64">
          <tr> 
            <td align="left" valign="top"> 

<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
	//make sure hidden and iframe values are in sync before submitting form
	//to sync only 1 rte, use updateRTE(rte)
	//to sync all rtes, use updateRTEs
	updateRTE('rte1');
	//updateRTEs();
	//change the following line to true to submit form
	return true;
}

//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML)
initRTE("rte/images/", "rte/", "rte/", true);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>

<script language="JavaScript" type="text/javascript">
<!--
<?php
//format content for preloading
if (!(isset($_POST["rte1"]))) {
	$content = $row['body'];
	$content = rteSafe($content);
} else {
	//retrieve posted value
	$content = rteSafe($_POST["rte1"]);
}
?>//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)

writeRichText('rte1', '<?=$content;?>', 700, 400, true, false);

//-->
</script>

<?php
function rteSafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = $strText;
	
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
//	$tmpString = str_replace("\"", "\"", $tmpString);
	
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);
	
	return $tmpString;
}
?>


            </td>
          </tr>
          <tr> 
            <td align="left" valign="top"> 
              <input type="hidden" name="cid" value="<?php print $row['id']; ?>">
              <input type="submit" name="submit" value="Submit">
            </td>
          </tr>
        </table>
        <br>
      </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<?
include('./templates/footer.php');

function validate_form($frm, &$errors) 
{
	/* Take form data and reference to the errors array.
	 * Return a string formatted for display. */
	$msg = "";
	// only validate if we are not deleting the content	
    if($frm['delete_content'] != 'true') {
		if(	trim($frm["title"]) === '' ||
			strlen($frm["title"]) < 1 ||
			strlen($frm["title"]) > 64 ) {
			$errors["title"] = true;
			$msg .= "Please enter a Title.<br>";
		}
	
		
		
		if(	trim($frm["menu_label"]) === '') {
			$errors["menu_label"] = true;
			$msg .= "Please enter a Menu Label.<br>";
	
		}
	}
    return $msg;
}// end validate_form
?>