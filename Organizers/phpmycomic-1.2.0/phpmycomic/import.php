<?php session_start();

if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {
	
// Include config file
include("config/config.php");

// Connect to MySQL and the database
mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
mysql_select_db($sql['data']) or die("Unable to find DB");
     	
//------------------------------------------
// GET THE FILE
//------------------------------------------
$file = $_FILES['imfile']['tmp_name'];
$fp = fopen($file,'r');
$a_lines = fread($fp, filesize($file));
fclose($fp);

//------------------------------------------
// EXPLODES INDIVIDUAL ISSUES
//------------------------------------------
$a_individ = explode('???', $a_lines);

foreach($a_individ as $individ)

{
	
	if($individ == '')
	
	{
		// Noting
	} else {
			
//------------------------------------------
// EXPLODE ARRAY INTO ISSUE DATA AND ARTIST DATA (0, 1)
//------------------------------------------
$a_columns = explode('++', $individ);

//------------------------------------------
// COUNT NUM_COLUMNS
//------------------------------------------
$num_columns = count($a_columns);

//------------------------------------------
// THIS EXPLODES THE ISSUE DATA BIT
//------------------------------------------
$set1 = explode('||', $a_columns[0]);

	// GET ISSUE VALUES
	$issue_name = $set1[0];
	$issue_title_link = $set1[1];
	$issue_title_year = $set1[2];
	$issue_publisher_link = $set1[16];
	$issue_story = $set1[3];
	$issue_price = $set1[4];
	$issue_value = $set1[5];
	$issue_user1 = $set1[8];
	$issue_user2 = $set1[9];
	$issue_number = $set1[6];
	$issue_volume = $set1[7];
	$issue_image = $set1[12];
	$issue_plot = $set1[19];
	$issue_part1 = $set1[10];
	$issue_part2 = $set1[11];
	$issue_language = $set1[21];
	$issue_translator = $set1[22];
	$issue_pubdate = $set1[27];	
	$issue_loan = $set1[24];
	$issue_ebay = $set1[25];
	$issue_ebay_link = $set1[26];
	$issue_qty = $set1[28];
	$issue_alpha = $set1[29];
	$issue_fav = $set1[30];

	// GET THE CHECK VALUES
	$type = $set1[13];
	$genre = $set1[14];
	$condition = $set1[17];
	$format = $set1[18];
	$variation = $set1[20];
	$publisher = $set1[15];
	$currency = $set1[23];
		
	// CHECK ISSUE TITLE
	$select = "SELECT uid FROM pmc_artist WHERE name = '". $issue_name ."' AND type = 'Series'";
	$data = mysql_db_query($sql['data'], $select) or die("Select Title Failed!");
	$row = mysql_fetch_array($data);
	$title_uid = $row['uid'];
	
	// CHECK IF ISSUE EXCISTS
	$select = "SELECT uid FROM pmc_comic WHERE title = '". $title_uid ."' AND issue = '". $issue_number ."' AND issueltr = '". $issue_alpha ."' AND volume = '". $issue_volume ."'";
	$data = mysql_db_query($sql['data'], $select) or die("Issue check failed!");
	$row = mysql_fetch_array($data);
	$check_uid = $row['uid'];
	
	if($check_uid == '')
	
		{
	
	if($title_uid == '')
		{
			mysql_query("INSERT INTO pmc_artist (name, type, link, year) VALUES ('". mysql_real_escape_string($issue_name) ."', 'Series', '". mysql_real_escape_string($issue_title_link) ."', '". mysql_real_escape_string($issue_title_year) ."')");
			
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $issue_name ."' AND type = 'Series'";
			$data = mysql_db_query($sql['data'], $select) or die("Select series name Failed!");
			$row = mysql_fetch_array($data);
			$issue_title = $row['uid'];
		} else {
			$issue_title = $title_uid;
		}
	
	// CHECK ISSUE TYPE
	$select = "SELECT uid FROM pmc_artist WHERE name = '". $type ."' AND type = 'Type'";
	$data = mysql_db_query($sql['data'], $select) or die("Select Type Failed!");
	$row = mysql_fetch_array($data);
	$type_uid = $row['uid'];
	
	if($type_uid == '')
		{
			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($type) ."', 'Type')");
			
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $type ."' AND type = 'Type'";
			$data = mysql_db_query($sql['data'], $select) or die("Select type Failed!");
			$row = mysql_fetch_array($data);
			$issue_type = $row['uid'];
		} else {
			$issue_type = $type_uid;
		}
	
	// CHECK ISSUE format
	$select = "SELECT uid FROM pmc_artist WHERE name = '". $format ."' AND type = 'Format'";
	$data = mysql_db_query($sql['data'], $select) or die("Select format Failed!");
	$row = mysql_fetch_array($data);
	$format_uid = $row['uid'];
	
	if($format_uid == '')
		{
			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($format) ."', 'Format')");
			
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $format ."' AND type = 'Format'";
			$data = mysql_db_query($sql['data'], $select) or die("Select format Failed!");
			$row = mysql_fetch_array($data);
			$issue_format = $row['uid'];
		} else {
			$issue_format = $format_uid;
		}
	
	// CHECK ISSUE condition
	$select = "SELECT uid FROM pmc_artist WHERE name = '". $condition ."' AND type = 'Condition'";
	$data = mysql_db_query($sql['data'], $select) or die("Select condition Failed!");
	$row = mysql_fetch_array($data);
	$condition_uid = $row['uid'];
	
	if($condition_uid == '')
		{
			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($condition) ."', 'Condition')");
			
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $condition ."' AND type = 'Condition'";
			$data = mysql_db_query($sql['data'], $select) or die("Select condition Failed!");
			$row = mysql_fetch_array($data);
			$issue_condition = $row['uid'];
		} else {
			$issue_condition = $condition_uid;
		}
		
	// CHECK ISSUE variation
	$select = "SELECT uid FROM pmc_artist WHERE name = '". $variation ."' AND type = 'Variation'";
	$data = mysql_db_query($sql['data'], $select) or die("Select variation Failed!");
	$row = mysql_fetch_array($data);
	$variation_uid = $row['uid'];
	
	if($variation_uid == '')
		{
			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($variation) ."', 'Variation')");
			
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $variation ."' AND type = 'Variation'";
			$data = mysql_db_query($sql['data'], $select) or die("Select variation Failed!");
			$row = mysql_fetch_array($data);
			$issue_variation = $row['uid'];
		} else {
			$issue_variation = $variation_uid;
		}
		
	// CHECK ISSUE publisher
	$select = "SELECT uid FROM pmc_artist WHERE name = '". $publisher ."' AND type = 'Publisher'";
	$data = mysql_db_query($sql['data'], $select) or die("Select publisher Failed!");
	$row = mysql_fetch_array($data);
	$publisher_uid = $row['uid'];
	
	if($publisher_uid == '')
		{
			mysql_query("INSERT INTO pmc_artist (name, type, link) VALUES ('". mysql_real_escape_string($publisher) ."', 'Publisher', '". mysql_real_escape_string($issue_publisher_link) ."')");
			
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $publisher ."' AND type = 'Publisher'";
			$data = mysql_db_query($sql['data'], $select) or die("Select publisher Failed!");
			$row = mysql_fetch_array($data);
			$issue_publisher = $row['uid'];	
		} else {
			$issue_publisher = $publisher_uid;
		}
		
	// CHECK ISSUE currency
	$select = "SELECT uid FROM pmc_artist WHERE name = '". $currency ."' AND type = 'Currency'";
	$data = mysql_db_query($sql['data'], $select) or die("Select currency Failed!");
	$row = mysql_fetch_array($data);
	$currency_uid = $row['uid'];
	
	if($currency_uid == '')
		{
			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($currency) ."', 'Currency')");
			
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $currency ."' AND type = 'Currency'";
			$data = mysql_db_query($sql['data'], $select) or die("Select currency Failed!");
			$row = mysql_fetch_array($data);
			$issue_currency = $row['uid'];	
		} else {
			$issue_currency = $currency_uid;
		}
		
	// CHECK ISSUE genre
	$select = "SELECT uid FROM pmc_artist WHERE name = '". $genre ."' AND type = 'Genre'";
	$data = mysql_db_query($sql['data'], $select) or die("Select genre Failed!");
	$row = mysql_fetch_array($data);
	$genre_uid = $row['uid'];
	
	if($genre_uid == '')
		{
			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($genre) ."', 'Genre')");
			
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $genre ."' AND type = 'Genre'";
			$data = mysql_db_query($sql['data'], $select) or die("Select genre Failed!");
			$row = mysql_fetch_array($data);
			$issue_genre = $row['uid'];	
		} else {
			$issue_genre = $genre_uid;
		}
		
		$dat = date("Y-m-d G-i-s");
		
		// Insert the new issue into the pmc_comic table
        mysql_query("INSERT INTO pmc_comic (title, story, price, part1, part2, user1, user2, image, issue, issueltr, volume, type, genre, publisher, condition, format, plot, value, date, variation, language, translator, currency, ebay, ebaylink, pubdate, qty, fav) VALUES ('$issue_title', '$issue_story', '$issue_price', '$issue_part1', '$issue_part2', '$issue_user1', '$issue_user2', '".mysql_real_escape_string($issue_image)."', '$issue_number', '$issue_alpha', '$issue_volume', '$issue_type', '$issue_genre', '$issue_publisher', '$issue_condition', '$issue_format', '$issue_plot', '$issue_value', '$dat', '$issue_variation', '$issue_language', '".mysql_real_escape_string($issue_translator)."', '$issue_currency', '$issue_ebay', '".mysql_real_escape_string($issue_ebaylink)."', '$issue_pubdate', '$issue_qty', '$issue_fav')");
		
		// GET THE LAST ADDED COMIC
		$select = "SELECT uid FROM pmc_comic WHERE title = '". $issue_title ."' AND issue = '". $issue_number ."' AND issueltr = '". $issue_alpha ."' AND volume = '". $issue_volume ."'";
		$data = mysql_db_query($sql['data'], $select) or die("Issue check 2 failed!");
		$row = mysql_fetch_array($data);
		$get_uid = $row['uid'];

//------------------------------------------
// THIS EXPLODES THE ARTIST BIT
//------------------------------------------
if($a_columns[1] == '')

	{
		// This will do nothing since no artists are connected to this issue
	} else {	
		
$artist = explode('+', $a_columns[1]);

	foreach ($artist as $gtype)
		{
			$getall = explode('||', $gtype);
			$artist_type = $getall[1];
			$artist_name = $getall[0];
			
			// CHECK ISSUE genre
			$select = "SELECT uid FROM pmc_artist WHERE name = '". $artist_name ."' AND type = '". $artist_type ."'";
			$data = mysql_db_query($sql['data'], $select) or die("Select artist Failed!");
			$row = mysql_fetch_array($data);
			$artist_uid = $row['uid'];
			
			if($artist_uid == '')
				{
					mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($artist_name) ."', '". mysql_real_escape_string($artist_type) ."')");
					
					$select = "SELECT uid FROM pmc_artist WHERE name = '". $artist_name ."' AND type = '". $artist_type ."'";
					$data = mysql_db_query($sql['data'], $select) or die("Select artist Failed!");
					$row = mysql_fetch_array($data);
					$issue_artist = $row['uid'];
					
					mysql_query("INSERT INTO pmc_link (comic_id, artist_id, title_id, type) VALUES ('". mysql_real_escape_string($get_uid) ."', '". mysql_real_escape_string($issue_artist) ."', '". mysql_real_escape_string($issue_title) ."', '". mysql_real_escape_string($artist_type) ."')");
				} else {
										
					$issue_artist = $artist_uid;
					mysql_query("INSERT INTO pmc_link (comic_id, artist_id, title_id, type) VALUES ('". mysql_real_escape_string($get_uid) ."', '". mysql_real_escape_string($issue_artist) ."', '". mysql_real_escape_string($issue_title) ."', '". mysql_real_escape_string($artist_type) ."')");
				}
				
		}	
		
	}			
		
		} else {
			
			// If comic exists then do nothing        	
		
		}

}

}

// Conformation
header("Location: confirm.php?msg=03&file=index");
exit;

 } else {

     // Login failed
     header("Location: error.php?error=01");
     exit;

     }

?> 