<?
// SOME GENERAL SETTINGS

// The $t template is the main template for the whole site
// Per page you can set the template that should be used
// In this case frontpage_tpl is used
$t->set_file("block", "frontpage_tpl.html");
$t->set_file("block", "main_tpl_no_nav.html");
// Set the general meta information for this page
$t->set_var("pagekeywords", $_SETTINGS["meta_keywords"]);
$t->set_var("pagedescription", $_SETTINGS["meta_description"]);
$t->set_var("robots", $_SETTINGS["meta_robot"]);

$t2 = new Template("./");
$t2->set_file("block", "frontpage_tpl.html");	    

// Set the contents for the items: news, events and outputs/ downloads
// In the template you'll find {news}, {events} & {outputs}
$t2->set_var("news", fp_news());
$t2->set_var("events", fp_events());
$t2->set_var("outputs", fp_outputs());

// Set the highlight, with the contents of a content block.
// To change the contents of this contents block open the admin menu and edit
// content block number 16 
$t2->set_var("highlight", openitem('16'));
$t2->parse("b", "block");
$t->set_var("containera", $t2->get("b"));


//Show news on the front-page
function fp_news(){
	global $wfqadd,  $t, $absolutepathfull;
	$sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_news.date) AS udate FROM plug_news, struct WHERE struct.container_id='14' AND struct.content_id=plug_news.id ".$wfqadd." ORDER BY date DESC LIMIT 3";
	$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());
	$newscount= mysql_num_rows($result1);
	if ($newscount>'0') {
		$newst = new Template("./");
		$newst->set_file("block", "fp_news_tpl.html");	    
		$newst->set_block("block", "news","newz");
		$newst->set_block("block", "month","monthz");
		$newst->set_block("news", "AUTHOR");

		while ($resultarray = mysql_fetch_array($result1)){
			//echo $resultarray["struct_id"];
			$newst->set_var("url", $absolutepathfull."view/news/item/".$resultarray["struct_id"]);
			if ($resultarray["author"]<>''){
				$newst->set_var(array ("authorname"=> $resultarray["author"]));
			}
			else{
				$newst->set_var("AUTHOR", "");
			}
			$newst->set_var(array ("title"=> $resultarray["title"],"date"=> date("d-m-Y",$resultarray["udate"]),"brood"=> substr($resultarray["brood"], "0", "160")."..."));
			if ($lastdate=='') {
				$newst->set_var("MONTH", date("F",$resultarray["udate"]));
				$newst->parse("newz", "month", true);
			}
			elseif(date("m",$lastdate)<>date("m",$resultarray["udate"])){
				$newst->set_var("MONTH", date("F",$resultarray["udate"]));
				$newst->parse("newz", "month", true);
			}
			$lastdate=$resultarray["udate"];
			$newst->parse("newz", "news", true);
		}
		$newst->parse("b", "block");
		return $newst->get("b");

	}
	else{
		return "No news available.";
	}
}

//Show events on the front-page
function fp_events(){
	global $wfqadd,  $t, $absolutepathfull, $absolutepath;
	//select all event in the future 
	$sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_event.date) AS udate FROM plug_event, struct WHERE struct.container_id='17' AND struct.content_id=plug_event.id AND plug_event.date>='".date("Y-m-d")."' ".$wfqadd." ORDER BY date ASC LIMIT 2";
	$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());
	$newscount= mysql_num_rows($result1);
	if ($newscount>'0') {
		$plugnewst = new Template("./");
		$plugnewst->set_file("block", "fp_event_tpl.html");	    
		$plugnewst->set_block("block", "event","eventz");
		$plugnewst->set_block("event", "URL");
		$plugnewst->set_block("event", "PHONE");
		//$plugnewst->set_block("news", "AUTHOR");
		$eventcounter=0	;
		while ($resultarray = mysql_fetch_array($result1)){
			$eventcounter>0 ? $resultarray["name"]=$resultarray["name"] : $resultarray["name"];
			$eventcounter++;
			$plugnewst->set_var(array ("name"=> $resultarray["name"],
				"subject"=> $resultarray["subject"],
				"location"=> $resultarray["location"],
				"email"=> $resultarray["email"],
				"phone"=> $resultarray["phone"],
				"url"=> $resultarray["url"],
				"location"=> $resultarray["location"],
				"date"=> date("d-M-Y",$resultarray["udate"]),
				"contact"=> $resultarray["contact"],
				"morelink"=> $absolutepathfull."view/events/item/".$resultarray["struct_id"]));
			if ($resultarray['phone']==''){
				$plugnewst->set_var("PHONE", "");
			}
			if ($resultarray['url']==''){
				$plugnewst->set_var("URL", "");
			}
			$plugnewst->parse("eventz", "event", true);
		}
		$plugnewst->parse("b", "block");
		return $plugnewst->get("b");
	}
	else {
		return "No events.";
	}
} // end func

//Show downloads on the front-page
function fp_outputs(){
	global $wfqadd,  $t,$absolutepathfull, $absolutepath, $BASE_ROOT_FILES;
	$sqlselect1 = "SELECT *, struct.id AS struct_id, UNIX_TIMESTAMP(plug_outputs.date) AS udate FROM plug_outputs, struct WHERE struct.container_id='5' AND struct.content_id=plug_outputs.id ".$wfqadd." ORDER BY date DESC LIMIT 2";
	$result1 = mysql_prefix_query ($sqlselect1) or die (mysql_error());
	$newscount= mysql_num_rows($result1);
	if ($newscount>'0') {
		$plugnewst = new Template("./");
		$plugnewst->set_file("block", "fp_outputs_tpl.html");	    
		$plugnewst->set_block("block", "output","outputz");
		$plugnewst->set_block("output", "FILE");

		//$plugnewst->set_block("news", "AUTHOR");

		while ($resultarray = mysql_fetch_array($result1)){
			$plugnewst->set_var(array ("title"=> $resultarray["title"],
				"summary"=> $resultarray["summary"],
				"author"=> $resultarray["author"],
				"date"=> date("d-M-Y",$resultarray["udate"]),
				"filename"=> $BASE_ROOT_FILES."/".$resultarray["filename"],
				"morelink"=> $absolutepathfull."view/outputs/item/".$resultarray["struct_id"]));
			if ($resultarray['filename']==''){
				$plugnewst->set_var("FILE", "");
			}
			$plugnewst->parse("outputz", "output", true);
		}
		$plugnewst->parse("b", "block");
		return $plugnewst->get("b");
	}
	else {
		return "No downloads.";
	}
} // end func
?>