<?php session_start();

//Get the default templates objects, includes, db connections
include("header.php");

$tpl->assignInclude("content", "themes/$themes/tpl/artistlink.tpl");

// Prepare the template
$tpl->prepare();

// Get the menu items and links
include("./lang/$language/general.lang.php");
include("./lang/$language/artistlink.lang.php");
include("menu.php");

// Assign needed values
$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);

// Getting Comic details
$select = "SELECT * FROM pmc_comic WHERE uid = '". $_GET['id'] ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

// Query results
$row = mysql_fetch_array($data);

  $uid = $row['uid'];
  $name = $row['title'];
  $issue = $row['issue'];
  $issueltr = $row['issueltr'];
  $story = $row['story'];
  $dat = date("Y-m-d");
  
  // Get the series name
  $select = "SELECT * FROM pmc_artist WHERE uid = '$name'";
  $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  $row = mysql_fetch_array($data);
  $name_uid = $row['name'];
  
  $tpl->assign("comic_name", $name_uid);
  $tpl->assign("comic_issue", $issue);
  $tpl->assign("comic_issueltr", $issueltr);
  $tpl->assign("comic_story", $story);
  $tpl->assign("comic_type", $_GET['type']);
  $tpl->assign("comic_id", $uid);
  $tpl->assign("titleid", $_GET['title']);
  $tpl->assign("get_form", 'function.php?cmd=addartistlink');
  
  // Get the artists
      $get = "SELECT * FROM pmc_artist WHERE type = '". $_GET['type'] ."' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['m'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_artist");
            $tpl->assign("pmc_artist", $name);
            $tpl->assign("selected", $sel);
         }

// Print the result
$tpl->printToScreen();

?>