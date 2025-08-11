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
// Filename: search.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Search in the hole system pages
// ----------------------------------------------------------------------

session_start();
include_once ("config.php");
?>
<?php include_once ("db.php") ?>
<?php
$keyword = trim(htmlspecialchars(@$_GET["q"]));

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$str_term = str_replace(" ","</u> <u>",$keyword);
$str_term = "<u>$str_term</u>";
?>
<?php include_once ("lang.php") ?>
<?php include_once ("header.php"); ?>
<table width="98%" border="0" cellspacing="3" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
	    <tr>
	      <td><img src="cmsimages/search.gif" width="49" height="48" border="0" alt=""></td>
	    </tr>
	    <tr> 
	      <td class="pageTitle"><?php echo LBL_TERM . $str_term; ?>&nbsp;</td>
	    </tr>
	    <tr> 
	      <td class="bodyBlock">
		<?php
	if($search_mode == 0){
		$words = explode(" ",$keyword);
		$condition = "";
		foreach ($words as $word){
			$word = strtolower($word);
			if($condition != ""){ $condition .= " $search_logic "; }
			$condition .= " (`title` LIKE '%$word%' OR `content` LIKE '%$word%') ";
		}
		$strsql = "SELECT * FROM `pages` WHERE (`status`=1 OR (`status`=2 AND `on` < " . time() . " AND `off` > " . time() . ")) AND ($condition)";
	}else{
	      if($lang == 'ar'){
		  include_once('arquery.class.php');
		  $where = new ArQuery();
		  $where->setStrFields('title,content');
		  $where->setMode(0);
		  $condition = $where->getWhereCondition("$keyword");
		  $strsql = "SELECT * FROM `pages` WHERE (`status`=1 OR (`status`=2 AND `on` < " . time() . " AND `off` > " . time() . ")) AND ($condition)";
	      }else{
		  $strsql = "SELECT *, MATCH(title,content) AGAINST('$keyword') AS score FROM `pages` WHERE (`status`=1 OR (`status`=2 AND `on` < " . time() . " AND `off` > " . time() . ")) AND MATCH(title,content) AGAINST('$keyword') ORDER BY score DESC";
	      }
	}
	$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
	
  while (!$rs->EOF) {
      $link_title = @$rs->fields["title"];
      $link_id = @$rs->fields["id"];
	  echo "\n<b>&#8230;</b> <a href=\"page-$link_id.html\">$link_title</a><br>";
	  $rs->MoveNext();
  }
?>
		&nbsp;</td>
	    </tr>
	  </table>
<?php 
	$db->Close();
	include_once ("footer.php");
?>
