<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: pagesadd.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Add new page to the system
// ----------------------------------------------------------------------

session_start();
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("lang.php") ?>
<?php include_once ("security.inc.php") ?>
<?php if (@$_SESSION["status"] <> "login" || ($useSSL && $_SERVER['HTTPS'] != 'on')) header("Location: login.php") ?>
<?php if ($_SESSION["ip"] != getip()) header("Location: login.php") ?>
<?php
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }

if (empty($key)) {
	if(is_numeric($_POST["key"])){ $key = @$_POST["key"]; }
}

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if((!($_SESSION["privilege"] & 4) && !($_SESSION["privilege"] & 8)) || !inTree($db, $_SESSION["rootpage"], $key)){
   $db->Close();
   noPrivilege();
}

if(!($_SESSION["privilege"] & 8)){ $default_page = 0; }

if (isset($_POST['submit'])){
   $db->BeginTrans();
   $ok = 1;
   foreach($activeLang as $langparam=>$langicon){
		// get the form values
		$x_title = htmlspecialchars(@$_POST["x_title"]);

		$x_content = @$_POST["x_content"];

		// One of the easiest way to do XSS is to use one of the on* attributes, like onclick or onload.
		// With this you can easily execute a script, without the user even having to do something (with onload, etc)
		// or just having to click or hover over something. We just remove them all with
		$x_content = preg_replace('#(<[^>]+[\s\r\n\"\'])(on|xmlns)[^>]*>#iU',"$1>",$x_content);

		// As you certainly know, can you use javascript: and vbscript: as protocol handlers instead of http:// and others.
		// Something like <a href="javascript:alert('foobar')">lll</a> executes just nicely if a user clicks on it.
		// We of course remove that as well. IE as also the strange behaviour that something like "java script :" is also valid,
		// so we have to check for a whitespace between every character.
		$x_content = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU','$1=$2nojavascript...',$x_content);
		$x_content = preg_replace('#([a-z]*)[\x00-\x20]*=([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU','$1=$2novbscript...',$x_content);

		// We removed all namespace declarations above, here we remove all elements, which have a prefix, they are not needed in HTML..
		$x_content = preg_replace('#</*\w+:\w[^>]*>#i',"",$x_content);

		// There are quite some elements in HTML, which you definitively don't want in something like user comments.
		// The reason for the while loop is, that stuff like
		// <sc<script>ript>alert('hello')</sc</script>ript>
		// We remove them with:
		do {
		   $oldstring = $x_content;
		   $x_content = preg_replace('#</*(\?xml|applet|meta|xml|blink|link|style|script|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i',"",$x_content);
		} while ($oldstring != $x_content);

		if(is_numeric($_POST["x_key"])){ $x_parent_id = @$_POST["x_key"]; }else{ $x_parent_id = 1; }
		$x_modified = time();
		$x_create = time();
		$x_user_id = htmlspecialchars(@$_SESSION["user_id"]);
		if(@$_POST["x_to_sell"]){ $x_to_sell=1; }else{ $x_to_sell=0; };
		if(is_numeric($_POST["x_price"])){ $x_price = @$_POST["x_price"]; }else{ $x_price = 0; }

		// Wrapping text around an image is easy when using the CSS Float attribute.
		$x_content = preg_replace("/<IMG /", "<IMG  style=\"float:left; margin: 4px;\" ", $x_content);

		// add the values into an array

		// Get record id
		if(empty($id)){ $id = $db->GenID('pages'); }
		$fieldList["`id`"] = $id;

		// title
		$theValue = $x_title;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "''";
		$fieldList["`title`"] = $theValue;

		// content
		$theValue = $x_content;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "''";
		$fieldList["`content`"] = $theValue;

		// to sell
		$theValue = $x_to_sell;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`to_sell`"] = $theValue;

		// item price
		$theValue = $x_price;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`price`"] = $theValue;

		// user id
		$theValue = $x_user_id;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`user_id`"] = $theValue;

		// language
		$theValue = $langparam;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`lang`"] = $theValue;

		// parent id
		$theValue = $key;
		$fieldList["`parent_id`"] = $theValue;

		// modified date
		$theValue = $x_modified;
		$fieldList["`modified`"] = $theValue;

		// created date
		$theValue = $x_create;
		$fieldList["`create`"] = $theValue;

		// default status
		$theValue = $default_page;
		$fieldList["`status`"] = $theValue;

		// insert into database
		$strsql = "INSERT INTO `pages` (";
		$strsql .= implode(",", array_keys($fieldList));
		$strsql .= ") VALUES (";
		$strsql .= implode(",", array_values($fieldList));
		$strsql .= ")";

		if ($ok) $ok = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

		$id = $db->Insert_ID();
		$strsql = "UPDATE `pages` SET `order`=$id WHERE `id`=$id";
		if ($ok) $ok = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
   }
   if ($ok) $db->CommitTrans();
   else $db->RollbackTrans();
   $db->Close();
   header("Location: pagesedit.php?key=$key");
}

$x_parent_title = '';
$strsql = "SELECT * FROM `pages` WHERE `id`=" . $key . " AND `lang`='" . $lang . "'";
$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
if ($rs->RecordCount()) {
  $x_parent_title = @$rs->fields["title"];
}
?>
<?php include_once ("header.php") ?>

<script language="JavaScript" type="text/javascript" src="html2xhtml.js"></script>
<!-- To decrease bandwidth, use richtext_compressed.js instead of richtext.js //-->
<script language="JavaScript" type="text/javascript" src="richtext.js"></script>

<script language="JavaScript">
<!-- start Javascript
function  checkForm(frm) {
	//make sure hidden and iframe values are in sync before submitting form
	//to sync only 1 rte, use updateRTE(rte)
	//to sync all rtes, use updateRTEs
	updateRTE('x_content');
	//updateRTEs();

      if(frm.x_title.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_title.focus();
	      return false;
      }

      if(frm.x_content.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_content.focus();
	      return false;
      }

      if(frm.x_to_sell.checked && frm.x_price.value==0){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_price.focus();
	      return false;
      }

	//change the following line to true to submit form
	return true;
}
initRTE("jsimages/", "", "", true);

// end JavaScript -->
</script>
<form  action="pagesadd.php" method="post" name="editpage" onSubmit="return checkForm(this);">
<input type="hidden" name="key" value="<?php echo $key; ?>">
		  <table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
		    <tr>
		      <td class="linksBlock">
			<?php if ($x_parent_title){ ?>
			<img src="cmsimages/up.gif" width="15" height="15" border="0" alt="">
			<a href="pagesedit.php?key=<?php echo $key; ?>"><?php echo LBL_BACK . " " . $x_parent_title; ?></a>
			<?php } ?>
		      </td>
		      <td align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>">
			  <img src="cmsimages/new.png" width="48" height="48" border="0" alt="<?php echo LBL_ADDPAGE; ?>">
		      </td>
		    </tr>

<tr>
<td colspan="2" class="pageTitle"><?php echo LBL_TITLE; ?>&nbsp;</td>
</tr><tr>
<td colspan="2" class="bodyBlock"><input type="text" name="x_title" size="30" maxlength="255" value="<?php echo htmlspecialchars(@$x_title); ?>">&nbsp;</td>
</tr><tr>
<td colspan="2" class="pageTitle"><?php echo LBL_CONTENT; ?>&nbsp;</td>
</tr><tr>
<td colspan="2" class="bodyBlock">
    <script language="JavaScript" type="text/javascript"><!--
    //Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
    writeRichText('x_content', '<?=$x_content;?>', '100%', 400, true, false);
    //--></script>
&nbsp;</td>
</tr>

<?php if($cart_mode==1){ ?>
		  <tr>
			<td colspan="2" class="bodyBlock">
			<input type="checkbox" name="x_to_sell" <?php echo @$x_to_sell; ?> onClick="if(this.checked){ price_blk.style.display=''; }else{ price_blk.style.display='none'; }">
			 <?php echo LBL_TO_SELL; ?>
			<span id="price_blk" style="display:<?php if($x_to_sell == ""){ ?>none<?php } ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <?php echo LBL_PRICE; ?> <input type="text" name="x_price" value="<?php echo @$x_price; ?>" size="6"> USD.
			</span>
			&nbsp;</td>
		  </tr>
<?php } ?>
<tr>
<td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo LBL_ADD; ?>"></td>
</tr>
</table>
</form>
<?php 
	$db->Close();
	include_once ("footer.php")
?>
