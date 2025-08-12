<?php
include("class.rss.php"); 
include("../inc/config.php"); 

// Instantiate the myRSS class 
$myRSS = new myRSS; 

$myRSS->channelTitle = "New From Snipe Gallery"; 
$myRSS->channelLink = $cfg_app_url; 
$myRSS->channelDesc = "Newest Products"; 

$myRSS->imageTitle = "Snipe Gallery"; 
$myRSS->imageLink = $cfg_app_url; 
$myRSS->siteLink = $cfg_app_url; 
$myRSS->imageURL = "http://www.snipegallery.com/images/banner-bug.gif"; 

$myRSS->useGallery = $_REQUEST['gallery_feed'];

// Get the RSS data 
$rssData = $myRSS->GetRSS($cfg_database_host, $cfg_database_user, $cfg_database_pass, $cfg_database_name , "snipe_gallery_data", "title", "id", $cfg_app_url."/image.php?image_id={linkId}&gallery_id={cat_id}", "date"); 

// Output the generated RSS XML 
header("Content-type: text/xml"); 
echo $rssData; 
 
 

?>