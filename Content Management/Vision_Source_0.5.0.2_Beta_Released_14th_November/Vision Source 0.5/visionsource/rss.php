<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 20th March 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: rss.php										//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/
	
define ( 'DIRECT' , 1 );
$info = array();
require("includes/config.php");
require("classes/class_db.php");
$db =  new db;
$db->connect();

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?>
<rdf:RDF
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
  xmlns:admin="http://webns.net/mvcb/"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:cc="http://web.resource.org/cc/"
  xmlns="http://purl.org/rss/1.0/">

<channel rdf:about="'.$info['base_url'].'/index.php?id=about">
<title>'.$info['title'].'</title>
<link>'.$info['base_url'].'</link>

<items>
<rdf:Seq>';
		$db->query("SELECT* FROM vsource_news ORDER BY id DESC LIMIT 10");
		while ($row = $db->fetchrow()) {
		$id = $row['id'];
	echo	'<rdf:li rdf:resource="'.$info['base_url'].'/index.php?id=news&amp;do=2&amp;item='.$id.'" />
	';
	}

echo "
</rdf:Seq>
</items>

</channel>";
	    $db->query("SELECT* FROM vsource_news ORDER BY id DESC LIMIT 10");
		while ($row = $db->fetchrow()) {
			$newstitle	 = $row['newstitle'];
			$newstext	 = $row['newstext'];
			$postdetails = $row['poster'];
			$id			 = $row['id'];
			$date		 = $row['thedate'];
			
		echo '<item rdf:about="'.$info['base_url'].'/index.php?id=news&amp;do=2&amp;item='.$id.'">
			  <title>'.$newstitle.'</title>
			  <link>'.$info['base_url'].'/index.php?id=news&amp;do=2&amp;item='.$id.'</link>
			  <description>'.$newstext.'</description>
			  <dc:creator>'.$postdetails.'</dc:creator>
			  <dc:date>'.$date.'</dc:date>
			  </item>';
		}
		
echo "</rdf:RDF>";
			
		

?>