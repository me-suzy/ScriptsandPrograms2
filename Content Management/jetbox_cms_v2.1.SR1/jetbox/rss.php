<?
include("feedcreator.class.php");
//echo "<html>".time();
$container_id=35;
$rss = new UniversalFeedCreator(); 
//$rss->useCached(); // use cached version if age<1 hour
$rss->title = $sitename; 
$rss->description = $sitename." Blog"; 
//optional
$rss->descriptionTruncSize = 500;
$rss->descriptionHtmlSyndicated = true;

$rss->link = $front_end_url."view/blog";
$rss->syndicationURL = $front_end_url."view/rss"; 

//$image = new FeedImage(); 
//$image->title = "dailyphp.net logo"; 
//$image->url = "http://www.dailyphp.net/images/logo.gif"; 
//$image->link = "http://www.dailyphp.net"; 
//$image->description = "Feed provided by dailyphp.net. Click to visit."; 

//optional
//$image->descriptionTruncSize = 500;
//$image->descriptionHtmlSyndicated = true;

//$rss->image = $image; 

// get your news items from somewhere, e.g. your database: 
$res = mysql_prefix_query("SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(blog.date) as udate FROM blog, struct WHERE struct.container_id=".$container_id." ".$wfqadd." AND struct.content_id=blog.b_id ORDER BY blog.b_id DESC LIMIT 10") or die(mysql_error());
while ($data = mysql_fetch_object($res)) { 
    $item = new FeedItem(); 
    $item->title = $data->title; 
    $item->link = $front_end_url."view/blog/item/".$data->struct_id; 
    $item->description = $data->brood; 
    
    //optional
    $item->descriptionTruncSize = 500;
    $item->descriptionHtmlSyndicated = true;
    $item->date = $data->udate*1; 
    //$item->source = "http://www.dailyphp.net"; 
    $item->author = $data->name; 
     
    $rss->addItem($item); 
} 

// valid format strings are: RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
// MBOX, OPML, ATOM, ATOM0.3, HTML, JS
echo $rss->createFeed("RSS1.0");
?>