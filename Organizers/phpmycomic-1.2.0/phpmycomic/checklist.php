<?php

// Include needed files
include("./class.TemplatePower.inc.php");
include("./config/config.php");

// Create a new template object
$tpl = new TemplatePower("themes/$themes/tpl/checklist.tpl");

// Prepare the template
$tpl->prepare();

$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);

// MySQL Connection
mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
mysql_select_db($sql['data']) or die("Unable to find DB");

	// Get the series name from the artist table
    $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['check_name']) ."' AND type = 'Series'";
    $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
    $row = mysql_fetch_array($data);
    $name_uid = $row['uid'];
    
    $start = $_POST['check_start'];
	$ending = $_POST['check_end'];

	$comicname = $_POST['check_name'];
	
	for ($i = $start; $i <= $ending; $i++) {
	
	// The MySQL command to run
	$select = "SELECT * FROM pmc_comic WHERE issue = $i and volume = '". $_POST['check_volume'] ."' and title = $name_uid";
	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

	$row = mysql_fetch_array($data);
   
      // Get all the fields
      $issue = $row['issue'];
      
      $tpl->newBlock("check_list");
      
      if($issue == $i) {      	
      	$tpl->assign("check_name", $comicname);
      	$tpl->assign("volume", $_POST['check_volume']);
      	$tpl->assign("check_num", $i);
      	$tpl->assign("check_image", "check_yes.gif");
      } else {
      	$tpl->assign("check_name", $comicname);
      	$tpl->assign("volume", $_POST['check_volume']);
      	$tpl->assign("check_num", $i);
      	$tpl->assign("check_image", "check_no.gif");
      }
   		
}

// Print the result
$tpl->printToScreen();

?>