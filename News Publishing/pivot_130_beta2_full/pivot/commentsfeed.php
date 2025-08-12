<?php


// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------


header("Content-type: application/xml");

include_once("pv_core.php");

@$file =	array_reverse(load_serialize("db/ser_lastcomm.php", true, true));

if (!isset($Current_weblog)) {
	$Current_weblog = key($Weblogs);
}

$db = new db(FALSE);


start_comment_feed();

$count = 0;
foreach($file as $item) {
	add_comment_feeditem($item);
	$count++;
	if ($count>19) { break; } 
}

finish_comment_feed();


function start_comment_feed() {
	global $atom, $atom_items, $build, $Current_user, $Weblogs, $Current_weblog, $Paths, $Users, $Cfg;

	$link= gethost() . fixPath($Paths['pivot_url'] . "/" . $Weblogs[$Current_weblog]['front_path'] . "/" . $Weblogs[$Current_weblog]['front_filename']);

	$charset = snippet_charset();

	$atom_preamble='<?xml version="1.0" encoding="%charset%"?'.'>
<feed version="0.3"
	xmlns="http://purl.org/atom/ns#"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xml:lang="%lang%">
	<title>%sitename_safe% - Comments</title>
	<link rel="alternate" type="text/html" href="%link%"/>
	<modified>%date%</modified>
	<author>
		<name>%admin-nick%</name>
		<url>%link%</url>
		<email>%admin-email%</email>
	</author>
	<tagline>Comments for %sitename_safe%</tagline>
	<id>tag:%sitename_safe%,%year%:%sitename_safe%</id>
	<generator url="http://www.pivotlog.net" version="%version%">Pivot</generator>
	<copyright>Copyright (c) %year%, Authors of %sitename%</copyright>
';

	
	reset($Users);
	$user = each($Users);
	$user = $user['value'];


	$from = array(
		"%sitename%",
		"%sitename_safe%",
		"%link%",
		"%description%",
		"%author%",
		"%admin-email%",
		"%admin-nick%",
		"%year%",
		"%date%",
		"%genagent%",
		"%version%",
		"%lang%",
		"%charset%",
	);

	$to = array(
		$Cfg['sitename'],
		str_replace("_", "", safe_string($Cfg['sitename'], TRUE)),
		$link, 
		$Weblogs[$Current_weblog]['payoff'], 
		$Current_user,
		$user['email'],
    $user['nick'],
		date("Y"),
		date("Y-m-d\TH:i:s").rss_offset(), 
		"http://www.pivotlog.net/?ver=".urlencode($build),
		$build,
		$Cfg['deflang'],
		$charset,
	);


	$atom= str_replace($from, $to, $atom_preamble);

	$atom_items = array();
	

}

function add_comment_feeditem($item) {
	global $db, $rss_items, $atom_items, $Cfg, $base_url, $Weblogs, $Current_weblog, $Allow_RSS, $Paths;


	$entry = $db->read_entry($item['code']);
	
	$link =  make_fileurl($item['uid'], "", "");

	$title = $db->entry['title'];
	
	$lang = snippet_lang();

	$date = format_date( $item['date'], "%year%-%month%-%day%T%hour24%:%minute%:00").rss_offset();
	
	$description = htmlspecialchars(strip_tags($item['comment']));
	$description = str_replace("&nbsp;"," ", $description);	
	
	$id = safe_string($item["name"],TRUE) . "-" . format_date($item["date"], "%ye%%month%%day%%hour24%%minute%");
	
	$tag = 	str_replace("_", "",  safe_string($Cfg['sitename'], TRUE)) . ",". date("Y") . ":" . $id;
	
	// make sure description is not too long..
	if ( (isset($Weblogs[$Current_weblog]['rss_full'])) && ($Weblogs[$Current_weblog]['rss_full']==0) ) {
		// don't put anything in the content.
		$content="";
	} else {
		// put the introduction and body in the content..
		$content = str_replace("&nbsp;"," ", ($introduction.$body));	
	}

	if (isemail($item['email'])) {
		$email = "\n<email>".$item['email']."</email>";	
	} else {
		$email = "";	
	}

	if (isurl($item['url'])) {
		if (strpos($item["url"], "ttp://") < 1 ) {
			$item["url"]="http://".$item["url"];
		}
		$url = "\n<url>".$item['url']."</url>";	
	} else {
		$url = "";	
	}

	
	
	$atom_item='
	<entry>
	    <title>%author% on %title%</title>
	    <link rel="alternate" type="text/html" href="%link%#%id%"/>
	    <modified>%date%</modified>
	    <issued>%date%</issued>
	    <id>tag:%tag%</id>
	
	    <created>%date%</created>
	    <summary type="text/plain">%description%</summary>
	
	 	<content type="text/html" mode="escaped" xml:lang="%lang%" xml:base="%link%">
		<![CDATA[ 
			%content%
		]]></content>
		<author>
			<name>%author%</name>%url%%email%
		</author>
	</entry>
';


	$from = array(
		"%title%", 
		"%link%", 
		"%id%",
		"%description%", 
		"%content%",
		"%author%",
		"%guid%", 
		"%date%", 
		"%tag%",
		"%lang%",
		"%url%",
		"%email%",
	);

	$to = array(
		htmlspecialchars(strip_tags($entry['title'])), 
		$link, 
		$id,
		RelativeToAbsoluteURLS($description), 
		trim(comment_format($item['comment'])),
		htmlspecialchars(unentify($item['name'])), 
		$item['uid']."@".$weblog, 
		$date, 
		$tag,
		$lang,
		$url,
		$email,
	);

	$atom_item= str_replace($from, $to, $atom_item);

	$atom_items[$date]=$atom_item;


}

function finish_comment_feed() {
	global $rss, $rss_items, $atom, $atom_items, $global_pref, $Weblogs, $Current_weblog, $VerboseGenerate;

	// sort the items..
	ksort($atom_items);
	$atom_items= array_reverse($atom_items);

	//write out the atom feed
	if($Weblogs[$Current_weblog]['atom_filename'] != "") {
			
		foreach ($atom_items as $item) {
			$atom .= $item;
		}

		$atom.="\n</feed>\n";

		echo $atom;
		

	}

}


?>
