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
// Filename: pagesprint.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Browse printable format of any page (no header/footer).
// ----------------------------------------------------------------------

include_once ("config.php");
?>
<?php include_once ("db.php") ?>
<?php include_once ("lang.php") ?>
<?php
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }
if (empty($key)) {
	 if(is_numeric($_POST["key"])){ $key = @$_POST["key"]; }
}
if (empty($key)) {
	$key = 1;
}

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$tkey = "" . $key . "";
$strsql = "SELECT * FROM `pages` WHERE `id`=" . $tkey . " AND `lang`='" . $lang . "'";
$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
if ($rs->RecordCount() == 0 ) {
	header("Location:page-1.html");
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

$x_parent_title = '';
if ($x_parent_id && $x_parent_id != $key){
  $strsql = "SELECT * FROM `pages` WHERE `id`=" . $x_parent_id . " AND `lang`='" . $lang . "'";
  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
  if ($rs->RecordCount() != 0) {
      $x_parent_title = @$rs->fields["title"];
  }
}
?>
<LINK href="./design/khaled.css" type=text/css rel=stylesheet>
<script language="JavaScript" type="text/JavaScript">
window.print();
</script>
<table width="98%" border="0" align="center" cellpadding="5" cellspacing="3" bgcolor="#FFFFFF" dir="<?php echo DIRECTION; ?>" align="center">
  <tr> 
	    <td class="pageTitle"> 
	      <?php echo $x_title; ?>&nbsp;</td>
	  </tr>
	  <tr> 
	    <td class="bodyBlock"> 
		<?php 
			//echo str_replace(chr(10), "<br>" ,@$x_content . "") 
			echo @$x_content;
		?>&nbsp;</td>
	  </tr>
	  <tr>
	    <td class="linksBlock">
	      <?php
  $strsql = "SELECT * FROM `pages` WHERE `status`=1 AND `parent_id`=" . $key . " AND `lang`='" . $lang . "'";
  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
  while (!$rs->EOF) {
      $link_title = @$rs->fields["title"];
      $link_id = @$rs->fields["id"];
      if ($key != $link_id){
		 echo "\n<img src=\"cmsimages/dot.gif\" width=16 height=16 alt=\"\"> <a href=\"page-$link_id.html\">$link_title</a><br>";
      }
	  $rs->MoveNext();
  }
?>
	      </td>
	  </tr>
	</table>
<?php $db->Close(); ?>
