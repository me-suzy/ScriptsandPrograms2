<?php

//-------------------------------------------------
//
// EXPORT COMIC INFORMATION
//
//-------------------------------------------------

// Include config file
include("config/config.php");

// Connect to MySQL and the database
mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
mysql_select_db($sql['data']) or die("Unable to find DB");

$backup = "";

foreach ($_POST['list_delete'] as $uid) {

// Getting Comic details
$select = "SELECT * FROM pmc_comic WHERE uid = '". $uid ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

// Query results
$row = mysql_fetch_array($data);

// Get the issue values
$issue_uid = $row['uid'];
$issue_name = $row['title'];
$issue_story = $row['story'];
$issue_price = $row['price'];
$issue_value = $row['value'];
$issue_user1 = $row['user1'];
$issue_user2 = $row['user2'];
$issue_number = $row['issue'];
$issue_alpha = $row['issueltr'];
$issue_volume = $row['volume'];
$issue_image = $row['image'];
$type = $row['type'];
$genre = $row['genre'];
$condition = $row['condition'];
$format = $row['format'];
$variation = $row['variation'];
$publisher = $row['publisher'];
$issue_plot = stripslashes($row['plot']);
$issue_part1 = $row['part1'];
$issue_part2 = $row['part2'];
$issue_language = $row['language'];
$issue_translator = $row['translator'];
$currency = $row['currency'];
$issue_pubdate = $row['pubdate'];
 
// GET SERIES TITLE, URL AND YEAR
$select = "SELECT * FROM pmc_artist WHERE uid = '". $issue_name ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Series Title, Url And YearFailed!");
$row = mysql_fetch_array($data);
$issue_title = $row['name'];
$issue_title_link = $row['link'];
$issue_title_year = $row['year'];

// GET ISSUE TYPE
$select = "SELECT * FROM pmc_artist WHERE uid = '". $type ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Type Failed!");
$row = mysql_fetch_array($data);
$issue_type = $row['name'];

// GET ISSUE FORMAT
$select = "SELECT * FROM pmc_artist WHERE uid = '". $format ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Format Failed!");
$row = mysql_fetch_array($data);
$issue_format = $row['name'];

// GET PUBLISHER
$select = "SELECT * FROM pmc_artist WHERE uid = '". $publisher ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Publisher Failed!");
$row = mysql_fetch_array($data);
$issue_publisher = $row['name'];
$issue_publisher_link = $row['link'];

// GET CONDITION
$select = "SELECT * FROM pmc_artist WHERE uid = '". $condition ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Condition Failed!");
$row = mysql_fetch_array($data);
$issue_condition = $row['name'];

// GET GENRE
$select = "SELECT * FROM pmc_artist WHERE uid = '". $genre ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Genre Failed!");
$row = mysql_fetch_array($data);
$issue_genre = $row['name'];

// GET VARIATION
$select = "SELECT * FROM pmc_artist WHERE uid = '". $variation ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Variation Failed!");
$row = mysql_fetch_array($data);
$issue_variation = $row['name'];

// GET CURRENCY
$select = "SELECT * FROM pmc_artist WHERE uid = '". $currency ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Currency Failed!");
$row = mysql_fetch_array($data);
$issue_currency = $row['name'];

// ADD THE ISSUE DATA
$backup .= "$issue_title||$issue_title_link||$issue_title_year||$issue_story||$issue_price||$issue_value||$issue_number||$issue_volume||$issue_user1||$issue_user2||$issue_part1||$issue_part2||noimage.jpg||$issue_type||$issue_genre||$issue_publisher||$issue_publisher_link||$issue_condition||$issue_format||$issue_plot||$issue_variation||$issue_language||$issue_translator||$issue_currency||no||no||||$issue_pubdate||1||$issue_alpha||no+";

//---------------------------------------------------------
// EXPORT ALL ARTIST RELATED TO THIS ISSUE
//---------------------------------------------------------
  	
$select = "SELECT * FROM pmc_link WHERE comic_id = '". $issue_uid ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Currency Failed!");
$row = mysql_fetch_array($data);
$artist_check = $row['uid'];

if($artist_check == '')

	{
		$backup .= "+";
	} else {
  	
	$select_writer = "SELECT * FROM pmc_link WHERE comic_id = '". $issue_uid ."'";
  	$data = mysql_db_query($sql['data'], $select_writer) or die("Select Artists Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{      	
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Artist Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$artist_name = $realname['name'];
      		$artist_type = $realname['type'];
      		
      		// ADD ARTIST DATA
      		$backup .= "+$artist_name||$artist_type";
      		
   		}
	} 

$backup .="???";

}

header('Content-type: text/richtext; charset=UTF-8');
header('Content-Length: '.strlen($backup) );
header("Content-Disposition: attachment; filename=pmcexport.pmc");

  echo $backup;

?>