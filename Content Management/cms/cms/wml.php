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
// Filename: wml.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Browse pages contents in WAP format
// ----------------------------------------------------------------------

include_once ("config.php");
include_once ("db.php");
include_once ("lang.php");

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
if ($rs->RecordCount()	== 0 ) {
    $strsql = "SELECT * FROM `pages` WHERE `id`=" . $tkey;
    $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
    if ($rs->RecordCount()  == 0 ) {
	    header("Location:wml.php?key=1");
    }
}

// get the field contents
$x_id = @$rs->fields["id"];
$x_title = @$rs->fields["title"];
$x_content = @$rs->fields["content"];
$x_views = @$rs->fields["views"];
$x_parent_id = @$rs->fields["parent_id"];
$x_status = @$rs->fields["status"];
$x_lang = @$rs->fields["lang"];
$x_privilege = @$rs->fields["privilege"];
$x_modified = @$rs->fields["modified"];
$x_create = @$rs->fields["create"];
$x_user_id = @$rs->fields["user_id"];

// clean html pages
$x_content = str_replace(chr(10), "<br/>" ,@$x_content);
$x_content = str_replace("EM>", "i>", $x_content);
$x_content = str_replace("STRONG>", "b>", $x_content);
$x_content = str_replace("U>", "u>", $x_content);
$x_content = str_replace("</A>", "</a>", $x_content);
$x_content = str_replace("<A ", "<a ", $x_content);
$x_content = strip_tags($x_content, '<a><b><i><u><br>');
$x_content = str_replace("<BR>", "<br/>", $x_content);

// get the parent page id and title
$x_parent_title = '';
if ($x_parent_id && $x_parent_id != $key){
  $strsql = "SELECT * FROM `pages` WHERE `id`=" . $x_parent_id . " AND `lang`='" . $lang . "'";
  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
  if ($rs->RecordCount()) {
      $x_parent_title = @$rs->fields["title"];
  }
}

?>
<?php
// send wml headers
header('Content-type: text/vnd.wap.wml');
echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml><card title="" newcontext="true">
<?php if ($x_parent_title){ ?>
<a href="wml.php?key=<?php echo $x_parent_id; ?>"><?php echo "Back to " . $x_parent_title; ?></a>
<br/>
<?php } ?>
<b><?php echo $x_title; ?></b><br/>
<?php echo $x_content; ?><br/>
<?php
      $strsql = "SELECT * FROM `pages`  WHERE (`status`=1 OR (`status`=2 AND `on` < " . time() . " AND `off` > " . time() . ")) AND `parent_id`=" . $key . " AND `lang`='" . $lang . "' ORDER BY `order`";
      $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
      while (!$rs->EOF) {
	  $link_title = @$rs->fields["title"];
	  $link_id = @$rs->fields["id"];
	  if ($key != $link_id){
	     echo "<a href=\"wml.php?key=$link_id\">$link_title</a><br/>\n";
	  }
	  $rs->MoveNext();
      }
    ?>
</card></wml>
<?php $db->Close(); ?>
