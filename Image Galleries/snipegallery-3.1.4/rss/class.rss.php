<?php 
class myRSS 
{ 
// Create our channel variables 
var $channelTitle; 
var $channelLink; 
var $channelDesc; 
var $siteLink; 
var $useGallery;

// Create our image variables 
var $imageTitle; 
var $imageLink; 
var $imageURL; 

	function checkValues() 
	{ 
		// Make sure all channel and image values are set 
		if($this->channelTitle == "") 
		die("Please specify a channel title"); 

		if(ereg("$http://", $this->channelLink) == false) 
		die("Please specify a channel link"); 

		if($this->channelDesc == "") 
		die("Please specify a channel description"); 

		if($this->imageTitle == "") 
		die("Please specify an image title"); 

		if(ereg("$http://", $this->imageLink) == false) 
		die("Please specify an image link"); 

		if(ereg("$http://", $this->imageURL) == false) 
		die("Please specify an image URL"); 
	} 


	// Connect to the database, generate the RSS XML and return it 
	function GetRSS($dbServer, $dbUser, $dbPass, $dbName, $tableName, $titleFieldName, $linkFieldName, $linkTemplate, $dateFieldName) 
	{ 
	// Make sure all channel/image values have been set 
	$this->checkValues(); 

	$rssValue = "<?xml version=\"1.0\"  encoding=\"ISO-8859-1\" ?>\r\n<!DOCTYPE rss SYSTEM \"http://www.pet-abuse.com/rss/rss.dtd\">\n"; 
	// $rssValue .= "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\" \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\r\n"; 
	$rssValue .= "<rss version=\"2.0\">\r\n"; 

	// Build the channel tag 
	$rssValue .= "<channel>\r\n"; 
	$rssValue .= "<title>" . $this->channelTitle . "</title>\r\n"; 
	$rssValue .= "<link>" . $this->channelLink . "</link>\r\n"; 
	$rssValue .= "<description>" . $this->channelDesc . "</description>\r\n"; 
	$rssValue .= "<language>en-us</language>\r\n"; 
	 

	// Build the image tag 
	$rssValue .= "<image>\r\n"; 
	$rssValue .= "<title>" . $this->imageTitle . "</title>\r\n"; 
	$rssValue .= "<url>" . $this->imageURL . "</url>\r\n"; 
	$rssValue .= "<link>" . $this->imageLink . "</link>\r\n"; 
	$rssValue .= "</image>\r\n"; 

	 

	// Connect to the database and build the <item> tags 
	$svrConn = @mysql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to database"); 
	$dbConn = @mysql_select_db($dbName, $svrConn) or die("Couldn't select database"); 

	// Make sure the table exists 
	$tableExists = false; 
	$tResult = mysql_list_tables($dbName); 

	while($tRow = mysql_fetch_row($tResult)) 
	{ 
	if(strtolower($tableName) == strtolower($tRow[0])) 
	{ 
	$tableExists = true; 
	break; 
	} 
	} 

	if(!$tableExists) 
	die("Table $tableName doesn't exist in the database!"); 

	$sql_rss_query = "select id, filename,  thumbname,  img_date,  title, details,  author,  location,  cat_id from $tableName where publish=1 ";
		if ($this->useGallery!="") {
			$sql_rss_query .= " AND cat_id='".$this->useGallery."'";
		}
	$sql_rss_query .= " order by added desc, id desc limit 15";
	$rResult = mysql_query($sql_rss_query) or die("An error occured while retrieving records: " . mysql_error()."<br>$sql_rss_query"); 

	// The records were retrieved OK, let's start building the item tags 
	while($rRow = mysql_fetch_array($rResult)) 
	{ 
		$break = explode(" ", $rRow['added']); 
		$datebreak = explode("-", $rRow['added']); 		 
		$datetime = date("M j, Y", mktime($datebreak[1],$datebreak[2],$datebreak[0]));

			list ($case_year, $case_month, $case_day) = split ('[/.-]', $rRow['added']);
				if ($case_month=="00") {
					$case_month="";
				} else {
					$case_month=$case_month."/";
				}

				if ($case_day=="00") {
					$case_day="";
				} else {
					$case_day=$case_day."/";
				}
				$new_imagedesc = str_replace("<BR>"," ", $rRow['details']);
				$new_imagedesc = str_replace("<P>"," ", $new_imagedesc);
				$new_imagedesc = str_replace("&nbsp;"," ", $new_imagedesc);
				$new_imagedesc = str_replace("&","&amp;", $new_imagedesc);
				$new_imagedesc = str_replace("<P>"," ", $new_imagedesc);
				$new_imagedesc = str_replace("<p>"," ", $new_imagedesc);
				$new_imagedesc = str_replace("</P>"," ", $new_imagedesc);
				$new_imagedesc = str_replace("</p>"," ", $new_imagedesc);
				$new_imagedesc = str_replace("<BR>","<br />", $new_imagedesc);
				$new_imagedesc = str_replace("<br>","<br />", $new_imagedesc);
				$new_imagedesc = str_replace('"',"&quot;", $new_imagedesc);
				$new_imagedesc = str_replace("","'", $new_imagedesc);

				$new_title = str_replace("&","&amp;", $rRow['title']);
				$new_title = str_replace("","'", $new_title);

				if ($new_title=="") {
					$new_title = "(No title)";
				}


		$rssValue .= "<item>\r\n"; 
		$rssValue .= "<title>". $new_title . "</title>\r\n"; 
		$rssValue .= "<description>" . stripslashes(shorten_text($new_imagedesc, 30)) . "</description>\r\n"; 
		$rssValue .= "<link>" . $this->siteLink."/image.php?image_id=".$rRow['id']."&amp;gallery_id=".$rRow['cat_id']."</link>\r\n"; 
		$rssValue .= "<pubDate>".$datetime."</pubDate>\r\n";
		$rssValue .= "</item>\r\n"; 
	} 

	$rssValue .= "</channel>\r\n";
	// Add the closing rss tag and return the value 
	$rssValue .= "</rss>\r\n"; 
	return $rssValue; 
	}


} 

function shorten_text($text, $shortened_text_num) {

	$text_shortened = explode(" ", $text);
	$text_total_words = count($text_shortened);


	// truncate the crime to 8 words
	$x = 0;
	while ($x < $shortened_text_num) {
		$shortened_text .= "$text_shortened[$x] ";
	++$x;
	}
	if ($text_total_words > $shortened_text_num ) {
		$shortened_text .= "... ";
	}
	return $shortened_text;

}
?>
