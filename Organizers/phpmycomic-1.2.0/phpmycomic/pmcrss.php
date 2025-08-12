<?php

include("./config/config.php");

?>

<rss version="2.0">
	<channel>
		<title>PMC RSS Feed</title>
		<link><?php print("$siteurl"); ?></link>
		<description>Recently Added Comics</description>
		<language>en-us</language>
<?php

// OPEN A MYSQL CONNECTION
mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
mysql_select_db($sql['data']) or die("Unable to find DB");

// GET THE 10 LATEST COMICS
$select = "SELECT * FROM pmc_comic ORDER BY 'date' DESC LIMIT 10";
$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

while ($row = mysql_fetch_array($data))
	{
    	// GET FIELDS FROM QUERY
      	$uid = $row['uid'];
      	$title = $row['title'];
      	$issue = $row['issue'];
      	$issueltr = $row['issueltr'];
      	$story = stripslashes($row['story']);
      	$added = $row['date'];
      	
      	// FORMAT THE DATE FIELD TO USER OPTION
      	$query = "SELECT date_format('$added','$dateoption')";
      	$getdate = mysql_db_query($sql['data'], $query) or die("Select Failed!");
      	$row = mysql_fetch_array($getdate);
      	$date = $row[0];
      	
      	// GET THE TITLE NAME FROM ARTIST TABLE
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$title'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$name_uid = $row['name'];
   	?>
		<item>
			<title><?php print("$name_uid #$issue$issueltr : $story"); ?></title>
			<description><?php echo $story ?></description>
			<link><?php echo $siteurl ?>comic.php/<?php echo $uid?></link>
			<pubDate><?php echo $date ?></pubDate>
		</item>
	<?
	}

?>
	</channel>
</rss>