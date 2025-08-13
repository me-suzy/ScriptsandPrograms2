<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                    track.php                     #
#                                                   #
#####################################################
#       Copyright © 2001 W. Dustin Alligood         #
#####################################################

require("./text.php");
require("./config.php");

########## Change Nothing Below This Line ###########

mysql_connect($mysql_host,$mysql_user,$mysql_password) or die ("Could not connect");
mysql_select_db($mysql_database);
$header=open("header.txt");
$footer=open("footer.txt");
$result=mysql_query("SELECT searchkey,category,hits,rating,votes,homepage,url,title,description,keywords FROM $mysql_table WHERE id=$id"); 
extract(mysql_fetch_array($result)); 
if($vote!=""){
	if($vote==$text['excellent']){
		$new_vote=5;
	}elseif($vote==$text['good']){
		$new_vote=4;
	}elseif($vote==$text['fair']){
		$new_vote=3;
	}elseif($vote==$text['poor']){
		$new_vote=2;
	}elseif($vote==$text['terrible']){
		$new_vote=1;
	}
	$rating=$rating+$new_vote;
	$votes++;
	$query="update $mysql_table set rating='$rating',votes='$votes' where id='$id'";
	$result=mysql_query($query);
	$decimal_rating=$rating/$votes;
	$decimal_rating=(intval($decimal_rating*10))/10;
	if($decimal_rating==1){
		$decimal_rating.=" ".$text['star'];
	}else{
		$decimal_rating.=" ".$text['stars'];
	}
	@$rating=intval($rating/$votes);
	$graphic_rating="<table border=0 cellpadding=0 cellspacing=0><tr><td align=center><nobr>";
	if($rating>0){
		for($r=0;$r<$rating;$r++){
			$graphic_rating.=$star;
		}
	}else{
		$graphic_rating.=$text['unrated'];
	}
	$graphic_rating.="</nobr></td></tr></table>";
	$template=open("vote_registered.txt");
	$template=preg_replace("/<!--#decimal_rate-->/", $decimal_rating, $template);
	$template=preg_replace("/<!--#graphical_rate-->/", $graphic_rating, $template);
	$template=preg_replace("/<!--#header-->/", $header, $template);
	$template=preg_replace("/<!--#footer-->/", $footer, $template);
	$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['rate_entry'], $template);
	print $template;
	exit;
}
$rate_form ="<form action=\"".$rate_script."\" method=post><input type=hidden name=id value=".$id."><table border=0>";
$rate_form.="<tr><td>".$star.$star.$star.$star.$star."</td><td width=5 rowspan=5>&nbsp;</td><td align=center><input type=submit name=vote value=\"".$text['excellent']."\"></td></tr>";
$rate_form.="<tr><td>".$star.$star.$star.$star."</td><td align=center><input type=submit name=vote value=\"".$text['good']."\"></td></tr>";
$rate_form.="<tr><td>".$star.$star.$star."</td><td align=center><input type=submit name=vote value=\"".$text['fair']."\"></td></tr>";
$rate_form.="<tr><td>".$star.$star."</td><td align=center><input type=submit name=vote value=\"".$text['poor']."\"></td></tr>";
$rate_form.="<tr><td>".$star."</td><td align=center><input type=submit name=vote value=\"".$text['terrible']."\"></td></tr>";
$rate_form.="</table></form>";
$template=open("vote.txt");
$entry_track=$track_script."?id=".$id."&url=".$url;
$category="<a href=\"".$search_script."?t=".urlencode($category_command).urlencode($category)."\">".$category."</a>";
@$rating=intval($rating/$votes);
$graphic_rating="<table border=0 cellpadding=0 cellspacing=0><tr><td align=center><nobr>";
if($rating>0){
	for($r=0;$r<$rating;$r++){
		$graphic_rating.=$star;
	}
}else{
	$graphic_rating.=$text['unrated'];
}
$graphic_rating.="</nobr></td></tr></table>";
$cache="<a href=\"".$cache_script."?c=".$id.".html&b=".urlencode($url)."\" target=cache>".$text['view_cache']."</a>";
$template=preg_replace("/<!--#url-->/", $url, $template);
$template=preg_replace("/<!--#track_url-->/", $entry_track, $template);
$template=preg_replace("/<!--#entry_title-->/", $title, $template);
$template=preg_replace("/<!--#rating-->/", $graphic_rating, $template);
$template=preg_replace("/<!--#description-->/", $description, $template);
$template=preg_replace("/<!--#keywords-->/", $keywords, $template);
$template=preg_replace("/<!--#relevance-->/", $relevance, $template);
$template=preg_replace("/<!--#hitcount-->/", $hits, $template);
$template=preg_replace("/<!--#category-->/", $category, $template);
$template=preg_replace("/<!--#cache-->/", $cache, $template);
$template=preg_replace("/<!--#id-->/", $id, $template);
$template=preg_replace("/<!--#rate_form-->/", $rate_form, $template);
$template=preg_replace("/<!--#header-->/", $header, $template);
$template=preg_replace("/<!--#footer-->/", $footer, $template);
$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['rate_entry'], $template);
print $template;

function open($filename){
	global $templates;
	$fd=@fopen($templates."/".$filename, "r");
	$template=@fread($fd, filesize ($templates."/".$filename));
	@fclose($fd);
	return $template;
}

?>