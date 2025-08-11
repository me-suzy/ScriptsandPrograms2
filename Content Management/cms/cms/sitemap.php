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
// Filename: sitemap.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Build dynamic site map
// ----------------------------------------------------------------------

session_start();
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

?>
<?php include_once ("lang.php") ?>
<?php include_once ("header.php") ?>
<table width="98%" border="0" cellspacing="3" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
	    <tr>
	      <td><img src="cmsimages/sitemap_larg.gif" width="48" height="48" border="0" alt=""></td>
	      <td class="linksBlock" align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>" valign="bottom">
	      <?php
		   foreach($activeLang as $langparam=>$langicon){
		     if($langparam != $lang){
	      ?>

		    <a href="sitemap.php?lang=<?php echo $langparam; ?>"><img src="<?php echo $langicon; ?>" border=0 alt="<? echo $lang; ?>"></a>
	      <?php
		     }
		   }
	      ?>
	      </td>
	    </tr>
	    <tr>
	      <td class="pageTitle" colspan="2"><?php echo LBL_MAP; ?></td>
	    </tr>
	    <tr>
	      <td class="bodyBlock" colspan="2">
		<?php
echo "<b>&#8230;</b> <a href=\"page-1.html\">" . LBL_HOME . "</a><br>\n";
child(1,0,$db,$lang);

function child($x_id, $x_depth, $db, $lang){
	  $x_depth++;
	  $strsql = "SELECT * FROM `pages` WHERE (`status`=1 OR (`status`=2 AND `on` < " . time() . " AND `off` > " . time() . ")) AND `parent_id`=" . $x_id . " AND `lang`='" . $lang . "' ORDER BY id";
	  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
	  while (!$rs->EOF) {
		  $link_title = @$rs->fields["title"];
		  $link_id = @$rs->fields["id"];
		  if ($x_id != $link_id){
				echo str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $x_depth);
				echo "<b>&#8230;</b> <a href=\"page-$link_id.html\">$link_title</a><br>\n";
				child($link_id, $x_depth, $db, $lang);
		  }
		  $rs->MoveNext();
	  }
}
?>
		</td>
	    </tr>
	  </table>
<?php 
	$db->Close(); 
	include_once ("footer.php");
?>
