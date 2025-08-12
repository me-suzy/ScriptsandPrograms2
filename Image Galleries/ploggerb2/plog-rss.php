<?php

// this file handles the generation of the RSS feed.
include_once("plog-functions.php");
include_once("plog-globals.php");



 function generate_RSS_feed ($level, $id, $search = "") {
   global $TABLE_PREFIX;
   global $config;

   if (!empty($search)) $level = "search";

  	if ($level == "collection") {  // aggregate feed of all albums with collection specified by id
  		 $query = "SELECT * , UNIX_TIMESTAMP(`date_submitted`) AS `date` FROM ".$TABLE_PREFIX."pictures WHERE `parent_collection` = $id ORDER BY `id` DESC LIMIT $config[feed_num_entries]";
  		 $prequery = "SELECT * FROM ".$TABLE_PREFIX."collections WHERE `id` = $id";
  		 $result = mysql_query ($prequery); 
			 $row = mysql_fetch_assoc ($result);
  		 
  		 $config["feed_title"] .= ": $row[name] Collection";
  	}
  	else if ($level == "album") { 
  		 $query = "SELECT * , UNIX_TIMESTAMP(`date_submitted`) AS `date` FROM ".$TABLE_PREFIX."pictures WHERE `parent_album` = $id ORDER BY `id` DESC LIMIT $config[feed_num_entries]";
  		 $prequery = "SELECT * FROM ".$TABLE_PREFIX."albums WHERE `id` = $id";
  		 $result = mysql_query ($prequery); $row = mysql_fetch_assoc ($result);
  		 
  		 $config["feed_title"] .= ": $row[name] Album";
  	}
  	else if ($level == "search") {
			$terms = explode(" ",$search);
			
			$query = "SELECT UNIX_TIMESTAMP(`date_submitted`) AS `date`, `caption`,`path`,`".$TABLE_PREFIX."pictures`.`id`,`".$TABLE_PREFIX."comments`.`comment` FROM `".						$TABLE_PREFIX."pictures` ";
			
			if ((count($terms) != 1) || ($terms[0] != '')){
				$query .= " LEFT JOIN `".$TABLE_PREFIX."comments`
					ON `".$TABLE_PREFIX."pictures`.`id` = `".$TABLE_PREFIX."comments`.`parent_id` WHERE ( ";
				
				foreach ($terms as $term) {
					$query .= " 
						`path` LIKE '%".mysql_escape_string($term)."%' OR 
						`comment` LIKE '%".mysql_escape_string($term)."%' OR 
						`caption` LIKE '%".mysql_escape_string($term)."%' OR ";
				}
				
				$query = substr($query, 0, strlen($query) - 3) .") ";
				
				$config["feed_title"] .= ": Custom Search for $search";
			}
			else{
				$query .= " WHERE 1 ";
			}
			
			$query .= " GROUP BY `".$TABLE_PREFIX."pictures`.`id` ORDER BY `id` DESC LIMIT $config[feed_num_entries]";
  	
  	}
  	else if ($level == "") {
  		
  		 $query = "SELECT * , UNIX_TIMESTAMP(`date_submitted`) AS `date` FROM ".$TABLE_PREFIX."pictures ORDER BY `id` DESC LIMIT $config[feed_num_entries]";
  		 $config["feed_title"] .= ": Entire Gallery";
  	}
	  	$result = mysql_query ($query);
		$header = 1;

 		// generate RSS header
    	$rssFeed = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<rss version=\"2.0\">";
    
    	$rssFeed.= "<channel>\n";
    	$rssFeed.= "<title>".$config['feed_title']."</title>\n";
    	$rssFeed.= "<description>Plogger RSS Feed</description>\n";
    	$rssFeed.= "<language>".$config['feed_language']."</language>\n";
    	$rssFeed.= "<docs>http://blogs.law.harvard.edu/tech/rss</docs>\n";
    	$rssFeed.= "<generator>http://www.plogger.org</generator>\n";
    	$rssFeed.= "<link>".$config['baseurl']."</link>\n";

	  while ($row = mysql_fetch_assoc ($result)) {
  		$id = $row["id"];
  		$date = date("D, d M Y H:i:s O", $row["date"]);
			
			if ($header) {
				 		$rssFeed.= "<pubDate>". $date . "</pubDate>\n";
				 		$rssFeed.= "<lastBuildDate>". $date . "</lastBuildDate>\n";
						$header = 0;
			}
			
  		$rssFeed .= "<item>\n";

		$caption = htmlentities($row['caption']);

		$thumbpath = generate_thumb($row['path'],$row['id'],'rss');
  		$pagelink = $config['baseurl']."index.php?level=picture&amp;id=".$row["id"];
  		
		$discript = '&lt;p&gt;&lt;a href="'.$pagelink.'"  
		title="'.$caption.'"&gt;
		&lt;img src="'.$thumbpath.'" alt="'.$caption.'" style="border: 1px solid #000000;" /&gt;
		&lt;/a&gt;&lt;/p&gt;&lt;p&gt;'.$caption.'&lt;/p&gt;';
			
		if ($row["caption"] == "") $caption = "New Photograph (no caption)";
		$rssFeed .= "<pubDate>" . $date . "</pubDate>\n";
  		$rssFeed .= "<title>" .$caption .  "</title>\n";
  		$rssFeed .= "<link>" . $pagelink .  "</link>\n";
  		$rssFeed .= "<description>" . $discript .  "</description>\n"; 
  		$rssFeed .= "<guid isPermaLink=\"false\">".$thumbpath."</guid>";
  		$rssFeed .= "</item>\n";
  	}
	
	$rssFeed .= "</channel></rss>";
	echo $rssFeed;

}

// send proper header
header("Content-Type: application/xml");

$level = isset($_GET["level"]) ? $_GET["level"] : "";
$id = isset($_GET["id"]) ? $_GET["id"] : "";

// process path here - is set if mod_rewrite is in use
if (!empty($_REQUEST["path"])) {
	// the followling line calculates the path in the album and excludes any subdirectories if
	// Plogger is installed in one
	$path = join("/",array_diff(explode("/",$_SERVER["REQUEST_URI"]),explode("/",$_SERVER["PHP_SELF"])));
	$resolved_path = resolve_path($path);
	// there is no meaningful RSS feed for images
	if (is_array($resolved_path) && $resolved_path["level"] != "picture") {
		$level = $resolved_path["level"];
		$id = $resolved_path["id"];
	};
};

$parts = parse_url($_SERVER["REQUEST_URI"]);
parse_str($parts["query"],$query_parts);
generate_RSS_feed($level, $id, $query_parts["searchterms"]);

?>
