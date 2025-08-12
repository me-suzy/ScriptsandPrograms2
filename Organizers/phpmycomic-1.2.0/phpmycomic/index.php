<?php session_start();

// IF CONFIG FILE DOES NOT EXCISTS THEN RUN THE INSTALL SCRIPT
if(!file_exists("./config/config.php")){ header("Location: install/install.php"); exit; }

// IF USER HAS NOT DELETED THE INSTALL FILE AFTER INSTALLATION THEN SEND WARNING
if(file_exists("./install/install.php")) { header("Location: error.php?error=19"); exit; } else {

// GET HEADER AND PAGE TEMPLATE FILE
include("header.php");
$tpl->assignInclude("content", "themes/$themes/tpl/index.tpl");

if($statsenable == 'Yes')
	{
		if($statstype == 'Short')
			{
				$tpl->assignInclude("stats", "themes/$themes/tpl/shortstats.tpl");
			} else {
				$tpl->assignInclude("stats", "themes/$themes/tpl/fullstats.tpl");
			}
			
	} else {
		
	}

// PREPARE THE TEMPLATE
$tpl->prepare();

// GET THE MENU AND LANGUAGE FILES
include("./lang/$language/general.lang.php");
include("./lang/$language/index.lang.php");
include("menu.php");

// INCLUDE THE SKIN AND LANGUAGE FILE
include("themes/$themes.skin.php");
include("lang/$language.lang.php");

// ASSIGN NEEDED VALUES
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("theme", $themes);
$tpl->assign("version", $version);
$tpl->assign("themename", $themename);
$tpl->assign("langname", $langname);

if($listtype == 'Latest')
	{
		$tpl->assign("lang_head_custom", $lang_head_latest);
	} elseif($listtype == 'Favorite') {
		$tpl->assign("lang_head_custom", $lang_head_favs);
	}
	
// PRINT THE ADMIN LINK TO SCREEN IF ADMIN IS LOGGED IN
if($_SESSION['loggedin'] == 'yes')
	{
    	$username = strtolower($_SESSION['username']);
      	if($username == 'admin')
        	{
            	$tpl->assign("adminlink", '[ <b><a href="admin.php" class="defaultlink">Goto Admin Pages</a></b> ]');
         	} else {
            	$tpl->assign("adminlink", '');
         	}
   		} else {

      	$tpl->assign("adminlink", "");
   	}

// GET THE TOTAL NUMBER OF TITLES
$select = "SELECT DISTINCT title FROM pmc_comic";
$num = mysql_db_query($sql['data'], $select) or die("Select Failed!");
$issues = mysql_num_rows($num);
$tpl->assign("pmc_issues", $issues);

// GET TOTAL NUMBER OF COMICS IN COLLECTION
$select = "SELECT * FROM pmc_comic";
$num = mysql_db_query($sql['data'], $select) or die("Select Failed!");
$comics = mysql_num_rows($num);
$tpl->assign("pmc_comics", $comics);

// GET TOTAL NUMBER OF PUBLISHERS
$get_publisher = "SELECT * FROM pmc_artist WHERE type = 'Publisher'";
$num_publisher = mysql_db_query($sql['data'], $get_publisher) or die("Select Total Publisher Failed!");
$publishers = mysql_num_rows($num_publisher);
$tpl->assign("pmc_publishers", $publishers);

// GET TOTAL NUMBER OF WRITERS
$get_writer = "SELECT * FROM pmc_artist WHERE type = 'Writer'";
$num_writer = mysql_db_query($sql['data'], $get_writer) or die("Select Total Writers Failed!");
$writers = mysql_num_rows($num_writer);
$tpl->assign("pmc_writers", $writers);

// GET TOTAL NUMBER OF INKERS
$get_inker = "SELECT * FROM pmc_artist WHERE type = 'Inker'";
$num_inker = mysql_db_query($sql['data'], $get_inker) or die("Select Total Inkers Failed!");
$inkers = mysql_num_rows($num_inker);
$tpl->assign("pmc_inkers", $inkers);

// GET TOTAL NUMBER OF PENCILERS
$get_penciler = "SELECT * FROM pmc_artist WHERE type = 'Penciler'";
$num_penciler = mysql_db_query($sql['data'], $get_penciler) or die("Select Total Pencilers Failed!");
$pencilers = mysql_num_rows($num_penciler);
$tpl->assign("pmc_pencilers", $pencilers);

// GET TOTAL NUMBER OF LETTERERS
$get_letterer = "SELECT * FROM pmc_artist WHERE type = 'Letterer'";
$num_letterer = mysql_db_query($sql['data'], $get_letterer) or die("Select Total Letterers Failed!");
$letterers = mysql_num_rows($num_letterer);
$tpl->assign("pmc_letterers", $letterers);

// GET TOTAL NUMBER OF COLORISTS
$get_colorist = "SELECT * FROM pmc_artist WHERE type = 'Colorist'";
$num_colorist = mysql_db_query($sql['data'], $get_colorist) or die("Select Total Colorists Failed!");
$colorists = mysql_num_rows($num_colorist);
$tpl->assign("pmc_colorists", $colorists);

// GET TOTAL NUMBER OF COVERARTISTS
$get_coverartist = "SELECT * FROM pmc_artist WHERE type = 'Coverartist'";
$num_coverartist = mysql_db_query($sql['data'], $get_coverartist) or die("Select Total Coverartists Failed!");
$coverartists = mysql_num_rows($num_coverartist);
$tpl->assign("pmc_coverartists", $coverartists);

// GET THE TOTAL PRICE AND VALUE SORTED AFTER CURRENCIES USED!
$select = "SELECT DISTINCT currency FROM pmc_comic";
$data = mysql_db_query($sql['data'], $select) or die("sdf");		

while($row = mysql_fetch_array($data))
	{
		$currency = $row['currency'];
		
		// GET TOTAL PRICE
		$get_price = mysql_query("SELECT SUM(price * qty) AS priceTotal FROM pmc_comic WHERE currency = '". $currency ."'") or die("Could not get total price of collection!");
		$total_price = mysql_result($get_price, 'priceTotal');
		
		// GET TOTAL VALUE
		$get_value = mysql_query("SELECT SUM(value * qty) AS valueTotal FROM pmc_comic WHERE currency = '". $currency ."'") or die("Could not get total value of collection!");
		$total_value = mysql_result($get_value, 'valueTotal');
		
		// GET NAME OF CURRENCY FROM ARTIST TABLE		
		$select_currency = mysql_query("SELECT name FROM pmc_artist WHERE uid = '". $currency ."'") or die("Failed to select currency name!");
		$getdata = mysql_fetch_assoc($select_currency);
		$currency_name = $getdata['name'];
		
		// SET TEMPLATE VALUES
		$tpl->newBlock("comic_value");
		$tpl->assign("total_currency", $currency_name);
		$tpl->assign("total_price", $total_price);
		$tpl->assign("total_value", $total_value);
		$tpl->assign("lang_price_in", $lang_price_in);
      	$tpl->assign("lang_value_in", $lang_value_in);
	}

// GET THE 10 LATEST COMICS

if($listtype == 'Latest')
	{
		$select = "SELECT * FROM pmc_comic ORDER BY 'date' DESC LIMIT $rownumber";
	} elseif($listtype == 'Favorite') {
		$select = "SELECT * FROM pmc_comic WHERE fav = 'Yes' ORDER BY 'date' DESC LIMIT $rownumber";
	}
	
$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

while ($row = mysql_fetch_array($data))
	{
    	// GET FIELDS FROM QUERY
      	$uid = $row['uid'];
      	$title = $row['title'];
      	$story = stripslashes($row['story']);
      	$issue = $row['issue'];
      	$issueltr = $row['issueltr'];
      	$volume = $row['volume'];
      	$added = $row['date'];

      	// FORMAT THE DATE FIELD TO USER OPTION
      	$query = "SELECT date_format('$added','$dateoption')";
      	$getdate = mysql_db_query($sql['data'], $query) or die("Select Failed!");
      	$row = mysql_fetch_array($getdate);
      	$date = $row[0];

      	// START NEW TEMPLATE BLOCK
      	$tpl->newBlock("comic_latest");

      	// GET THE TITLE NAME FROM ARTIST TABLE
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$title'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$name_uid = $row['name'];

      	// SET TEMPLATE VALUES
      	$tpl->assign("pmc_name", $name_uid);
      	$tpl->assign("pmc_story", $story);
      	$tpl->assign("pmc_uid", $uid);
      	$tpl->assign("pmc_issue", $issue);
      	$tpl->assign("pmc_issueltr", $issueltr);
      	$tpl->assign("pmc_volume", $volume);
      	$tpl->assign("pmc_date", $date);
   	}

// PRINT THE RESULT TO SCREEN
$tpl->printToScreen();

}

?>