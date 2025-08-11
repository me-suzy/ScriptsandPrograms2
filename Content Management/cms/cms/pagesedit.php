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
// Filename: pagesedit.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Edit page contents
// ----------------------------------------------------------------------

session_start();
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("security.inc.php") ?>
<?php if (@$_SESSION["status"] <> "login" || ($useSSL && $_SERVER['HTTPS'] != 'on')) header("Location: login.php") ?>
<?php if ($_SESSION["ip"] != getip()) header("Location: login.php") ?>
<?php include_once ("lang.php") ?>
<?php
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }
if (empty($key)) {
	if(is_numeric($_POST["key"])){ $key = @$_POST["key"]; }
}
if (empty($key)) {
	header("Location: index.php");
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

// get fields from form
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

$x_on = htmlspecialchars(@$_POST["x_on"]);
$x_off = htmlspecialchars(@$_POST["x_off"]);
if(@$_POST["x_to_sell"]){ $x_to_sell=1; }else{ $x_to_sell=0; };
if(is_numeric($_POST["x_price"])){ $x_price = @$_POST["x_price"]; }else{ $x_price = 0; }
$x_mode = (is_numeric($_POST["x_mode"])) ? $_POST["x_mode"] : 0;

if (!isset($_POST['submit'])){
		$tkey = "" . $key . "";
		$strsql = "SELECT * FROM `pages` WHERE `id`=" . $tkey . " AND `lang`='" . $lang . "'";
		$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		if ($rs->RecordCount() == 0) {
			header("Location: index.php");
		}

		// get the field contents
		$x_id = @$rs->fields["id"];
		$x_title = @$rs->fields["title"];
		$x_content = @$rs->fields["content"];
		$x_parent_id = @$rs->fields["parent_id"];
		$x_status = @$rs->fields["status"];
		$x_lang = @$rs->fields["lang"];
		$x_privilege = @$rs->fields["privilege"];
		$x_modified = @$rs->fields["modified"];
		$x_create = @$rs->fields["create"];
		$x_user_id = @$rs->fields["user_id"];
		$x_on = @$rs->fields["on"];
		$x_off = @$rs->fields["off"];
		$x_to_sell = @$rs->fields["to_sell"];
		$x_price = @$rs->fields["price"];
		$x_mode = @$rs->fields["mode"];
		$x_mode_ext = @$rs->fields["mode_ext"];

		if($x_on){ $x_on = date("d/m/Y",$x_on); }else{ $x_on = ''; }
		if($x_off){ $x_off = date("d/m/Y",$x_off); }else{ $x_off = ''; }
		if($x_to_sell){ $x_to_sell="checked"; }else{ $x_to_sell=""; }
		$x_content = rteSafe($x_content);

		$x_parent_title = '';
		if ($x_parent_id && $x_parent_id != $key){
		  $strsql = "SELECT * FROM `pages` WHERE `id`=" . $x_parent_id . " AND `lang`='" . $lang . "'";
		  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		  if ($rs->RecordCount() != 0) {
			  $x_parent_title = @$rs->fields["title"];
		  }
		}
}else{
		$tkey = "" . $key . "";

		if(!($_SESSION["privilege"] & 8)){
		    $fieldList["`status`"] = 0;
		}

		// Wrapping text around an image is easy when using the CSS Float attribute.
		$x_content = preg_replace("/<IMG /", "<IMG  style=\"float:left; margin: 4px;\" ", $x_content);

		// add the values into an array

		// title
		$theValue = $x_title;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$thisFieldList["`title`"] = $theValue;

		// content
		$theValue = $x_content;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$thisFieldList["`content`"] = $theValue;

		// user id
		$theValue = $x_user_id;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`user_id`"] = $theValue;

		// to sell
		$theValue = $x_to_sell;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`to_sell`"] = $theValue;

		// item price
		$theValue = $x_price;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`price`"] = $theValue;

		// iteractive mode
		$theValue = $x_mode;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`mode`"] = $theValue;

		// Email form defenitions
		$oldFields = split("\|",trim(htmlspecialchars($_POST['x_mode_ext'])));
		$delFields = $_POST['delFields'];
		$addFields = split("\n",trim(htmlspecialchars($_POST['addFields'])));
		$newFields = implode("|",$addFields);
		foreach ($oldFields as $field) {
			if(!in_array($field, $delFields)) {
			  if($newFields == ""){ $newFields .= "$field"; }else{ $newFields .= "|$field"; }
			}
		}
		$newFields = str_replace("\r","",$newFields);
		$newFields = str_replace("\n","",$newFields);
		$theValue = $newFields;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$thisFieldList["`mode_ext`"] = $theValue;

		// date on
		$theValue = $x_on;
		$date_arr = split("/",$theValue);
		$unix_date = mktime(0,0,0,$date_arr[1],$date_arr[0],$date_arr[2]);
		if($unix_date == -1){ $unix_date = 0; }
		$fieldList["`on`"] = $unix_date;

		// date off
		$theValue = $x_off;
		$date_arr = split("/",$theValue);
		$unix_date = mktime(0,0,0,$date_arr[1],$date_arr[0],$date_arr[2]);
		if($unix_date == -1){ $unix_date = 0; }
		$fieldList["`off`"] = $unix_date;

		// modified date
		$fieldList["`modified`"] = time();

		// modified user
		$theValue = htmlspecialchars($_SESSION["user_id"]);
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`user_id`"] = $theValue;

		// update all pages (same id)
		$updateSQL = "UPDATE `pages` SET ";
		foreach ($fieldList as $key=>$temp) {
			$updateSQL .= "$key = $temp, ";
		}
		if (substr($updateSQL, -2) == ", ") {
			$updateSQL = substr($updateSQL, 0, strlen($updateSQL)-2);
		}
		$updateSQL .= " WHERE `id`=" . $tkey;
		$db->Execute($updateSQL) or die("Error in query: $updateSQL. " . $db->ErrorMsg());

		// update content of this page only
		$updateSQL = "UPDATE `pages` SET ";
		foreach ($thisFieldList as $key=>$temp) {
			$updateSQL .= "$key = $temp, ";
		}
		if (substr($updateSQL, -2) == ", ") {
			$updateSQL = substr($updateSQL, 0, strlen($updateSQL)-2);
		}
		$updateSQL .= " WHERE `id`=" . $tkey . " AND `lang`='" . $lang . "'";
		$db->Execute($updateSQL) or die("Error in query: $updateSQL. " . $db->ErrorMsg());

		$db->Close();
		header("Location: pagesview.php?key=$tkey");
}
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

	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);

	return $tmpString;
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

<?php if($cart_mode==1){ ?>
      if(frm.x_to_sell.checked && frm.x_price.value==0){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_price.focus();
	      return false;
      }
<?php } ?>

<?php if($x_status==2){ ?>
      if(frm.x_on.value.length != 0){
	      split  = frm.x_on.value.indexOf('/');
	      sDay   = frm.x_on.value.substring(0, split);
	      split  = frm.x_on.value.indexOf('/', split+1);
	      sMonth = frm.x_on.value.substring((sDay.length + 1), split);
	      sYear  = frm.x_on.value.substring(split + 1);

	      if(sDay<1 || sDay>31 || sMonth<1 || sMonth>12 || sYear<1970 || sYear>2038){
		  alert('<?php echo INVALID_DATE; ?>');
		  frm.x_on.focus();
		  return false;
	      }
      }

      if(frm.x_off.value.length != 0){
	      split  = frm.x_off.value.indexOf('/');
	      sDay   = frm.x_off.value.substring(0, split);
	      split  = frm.x_off.value.indexOf('/', split+1);
	      sMonth = frm.x_off.value.substring((sDay.length + 1), split);
	      sYear  = frm.x_off.value.substring(split + 1);

	      if(sDay<1 || sDay>31 || sMonth<1 || sMonth>12 || sYear<1970 || sYear>2038){
		  alert('<?php echo INVALID_DATE; ?>');
		  frm.x_off.focus();
		  return false;
	      }
      }
<?php } ?>

	//change the following line to true to submit form
	return true;
}
initRTE("jsimages/", "", "", true);
// end JavaScript -->
</script>
<form action="pagesedit.php" method="post" name="editpage" onSubmit="return checkForm(this);">
<input type="hidden" name="key" value="<?php echo $key; ?>">
	    <table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
	      <tr> 
			<td class="linksBlock"> 
			  <?php if ($x_parent_title){ ?>
			  <img src="cmsimages/up.gif" width="15" height="15" border="0" alt="">
			  <a href="pagesedit.php?key=<?php echo $x_parent_id; ?>"><?php echo LBL_BACK . " " . $x_parent_title; ?></a>&nbsp;&nbsp;
			  <?php } ?>
			</td>
			<td align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>">
			    <a href="pagesview.php?key=<?php echo $key; ?>"><img src="cmsimages/preview.png" width="32" height="32" border="0" alt="<?php echo LBL_VIEWPAGE; ?>"></a>&nbsp;&nbsp;
			    <a href="pagesadd.php?key=<?php echo $key; ?>"><img src="cmsimages/new_f2.png" width="32" height="32" border="0" alt="<?php echo LBL_ADDPAGE; ?>"></a>&nbsp;&nbsp;

			    <?php
				 foreach($activeLang as $langparam=>$langicon){
				     if($langparam != $lang){
			    ?>
				<a href="pagesedit.php?key=<?php echo $key; ?>&lang=<?php echo $langparam; ?>"><img src="<?php echo $langicon; ?>" border=0 alt="<? echo $lang; ?>"></a>
			    <?php
				     }
				 }
			    ?>
			</td>
	      </tr>
	      <tr>
			<td colspan="2" class="pageTitle"><?php echo LBL_TITLE; ?>&nbsp;</td>
	      </tr>
	      <tr> 
		<td colspan="2" class="bodyBlock">
		  <input type="text" name="x_title" size="30" maxlength="255" value="<?php echo htmlspecialchars(@$x_title); ?>">
		  &nbsp;</td>
	      </tr>
	      <tr> 
			<td colspan="2" class="pageTitle"><?php echo LBL_CONTENT; ?>&nbsp;</td>
	      </tr>
	      <tr> 
		<td colspan="2" class="bodyBlock">
		    <script language="JavaScript" type="text/javascript"><!--
		    //Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
		    writeRichText('x_content', '<?=$x_content;?>', '100%', 400, true, false);
		    //--></script>
		  </td>
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
			<td colspan="2" class="bodyBlock">
			<?php echo LBL_INTERACTIVE; ?>
			<select name="x_mode" onChange="if(document.editpage.x_mode.options(document.editpage.x_mode.selectedIndex).value == 4){ field_blk.style.display=''; }else{ field_blk.style.display='none'; }">
			<option value="0" <?php if($x_mode == 0){ echo "selected"; } ?>><?php echo MODE_NONE; ?></option>
			<option value="4" <?php if($x_mode == 4){ echo "selected"; } ?>><?php echo MODE_EMAIL; ?></option>
			<option value="1" <?php if($x_mode == 1){ echo "selected"; } ?>><?php echo MODE_ADMIN; ?></option>
			<option value="2" <?php if($x_mode == 2){ echo "selected"; } ?>><?php echo MODE_MODIRATED; ?></option>
			<option value="3" <?php if($x_mode == 3){ echo "selected"; } ?>><?php echo MODE_OPEN; ?></option>
			</select>
			<span id="field_blk" style="display:<?php if($x_mode != 4){ ?>none<?php } ?>">
				  <br><br>
				  <input type="hidden" name="x_mode_ext" value="<?php echo $x_mode_ext; ?>">
				  <table border=0 cellspacing="2" cellpadding="5"><tr><td align=center class="pageTitle"><b>+</b></td><td align=center class="pageTitle"><b>-</b></td></tr>
				  <tr><td class="bodyBlock"><textarea cols=15 rows=12 name=addFields></textarea></td>
				  <td class="bodyBlock"><select name="delFields[]" size=10 multiple>
				  <?php
				    $frmFields = split("\|",$x_mode_ext);
				    foreach ($frmFields as $field) {
					echo "<option value=\"$field\">$field</option>";
				    }
				  ?>
				  </select></td></tr></table>
			</span>
			&nbsp;</td>
		  </tr>

<?php if($x_status==2){ ?>
		  <tr>
				<td colspan="2" class="bodyBlock">
				<script language="JavaScript" src="popcalendar.js"></script>
				<img src=cmsimages/publish_y.png width=12 height=12 border=0 alt="<?php echo LBL_SCHADUAL; ?>"><b> <?php echo LBL_SCHADUAL; ?> </b>
				<b><?php echo LBL_FROM; ?>:</b> <input type=text size=20 name="x_on" value="<?php echo $x_on; ?>">
						<img src="cmsimages/ew_calendar.gif" alt="<?php echo ALT_CALENDAR; ?>" onClick="popUpCalendar(this, document.editpage.x_on,'dd/mm/yyyy');" border="0" width="16" height="15">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<b><?php echo LBL_TO; ?>:</b> <input type=text size=20 name="x_off" value="<?php echo $x_off; ?>">
						<img src="cmsimages/ew_calendar.gif" alt="<?php echo ALT_CALENDAR; ?>" onClick="popUpCalendar(this, document.editpage.x_off,'dd/mm/yyyy');return false;" border="0" width="16" height="15">
				&nbsp;</td>
		  </tr>
<?php } ?>
	      <tr>
			<td colspan="2" class="pageTitle"><?php echo LBL_LINKS; ?>&nbsp;</td>
	      </tr>
	      <tr>
		<td colspan="2" class="linksBlock">
<table border=0>
		  <?php
  $strsql = "SELECT * FROM `pages` WHERE `parent_id`=" . $key . " AND `lang`='" . $lang . "' ORDER BY `order`";
  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
  
  while (!$rs->EOF) {
      $link_title = @$rs->fields["title"];
      $link_id = @$rs->fields["id"];
      $x_order = @$rs->fields["order"];
      if ($key != $link_id){
		  echo "\n<tr class=linksBlock><td><img src=\"cmsimages/dot.gif\" width=16 height=16 alt=\"\"> <a href=\"pagesedit.php?key=$link_id\">$link_title</a>&nbsp;&nbsp;</td><td>";
	
		  $img_activate = "<a href=\"pagesstatus.php?key=$link_id&newstatus=1&back_id=$key\"><img src=cmsimages/publish_g.png width=12 height=12 border=0 alt=\"" . LBL_ACTIVE . "\"></a>";
		  $img_deactivate = "<a href=\"pagesstatus.php?key=$link_id&newstatus=0&back_id=$key\"><img src=cmsimages/publish_r.png width=12 height=12 border=0 alt=\"" . LBL_DEACTIVE . "\"></a>";
		  $img_schadual = "<a href=\"pagesstatus.php?key=$link_id&newstatus=2&back_id=$key\"><img src=cmsimages/publish_y.png width=12 height=12 border=0 alt=\"" . LBL_SCHADUAL . "\"></a>";
	
		  if (@$rs->fields["status"] == 1){
			 echo "<table border=0 cellspacing=2 cellpadding=2><tr><td class=\"selectBlock\">$img_activate</td><td>$img_deactivate</td><td>$img_schadual</td></tr></table></td><td>";
		  }elseif (@$rs->fields["status"] == 0){
			 echo "<table border=0 cellspacing=2 cellpadding=2><tr><td>$img_activate</td><td class=\"selectBlock\">$img_deactivate</td><td>$img_schadual</td></tr></table></td><td>";
		  }else{
			 echo "<table border=0 cellspacing=2 cellpadding=2><tr><td>$img_activate</td><td>$img_deactivate</td><td class=\"selectBlock\">$img_schadual</td></tr></table></td><td>";
		  }
	
		  echo "<a href=\"pagesdelete.php?key=$link_id\"><img src=cmsimages/publish_x.png width=12 height=12 border=0 alt=\"" . LBL_DELETE . "\"></a></td><td>";
		  echo "<a href=\"pagesorder.php?mod=up&order=$x_order&parent_id=$key\"><img src=cmsimages/ord_up.gif width=15 height=15 border=0 alt=\"" . LBL_ORD_UP . "\"></a></td><td>";
		  echo "<a href=\"pagesorder.php?mod=down&order=$x_order&parent_id=$key\"><img src=cmsimages/ord_down.gif width=15 height=15 border=0 alt=\"" . LBL_ORD_DOWN . "\"></a></td></tr>";
      }
	  $rs->MoveNext();
  }
?>
</table>
		  </td>
	      </tr>
	      <tr> 
			<td colspan="2" align="center">
				<input type="submit" name="submit" value="<?php echo LBL_EDIT; ?>">
				&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Reset" value="<?php echo LBL_RESET; ?>" onClick="editor_setHTML('x_content',x_content.value)">
			</td>
	      </tr>
	    </table> 
</form>
<?php 
	$db->Close();
	include_once ("footer.php")
?>
