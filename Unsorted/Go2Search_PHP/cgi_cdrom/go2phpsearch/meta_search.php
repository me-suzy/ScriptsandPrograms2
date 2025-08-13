<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                 meta_search.php                  #
#                                                   #
#####################################################
#       Copyright © 2001 W. Dustin Alligood         #
#####################################################

require("./text.php");
require("./config.php");

########## Change Nothing Below This Line ###########

$header=open("header.txt");
$footer=open("footer.txt");
$raw_dmoz=surf("http://search.dmoz.org/cgi-bin/search?search=".$terms);
preg_match_all("/<li>(.+?)<small>/i",$raw_dmoz,$out);
$dmoz_results="<p><center><table border=0 cellpadding=0 cellspacing=0><tr><td align=center><table border=0 bgcolor=#ffffff cellpadding=0 cellspacing=1><tr><td><table border=0 bgcolor=#669933><tr><td width=12 align=right><font color=#ffffff face=Arial>d</font></td></tr></table></td><td><table border=0 bgcolor=#669933><tr><td width=12 align=right><font color=#ffffff face=Arial>m</font></td></tr></table></td><td><table border=0 bgcolor=#669933><tr><td width=12 align=right><font color=#ffffff face=Arial>o</font></td></tr></table></td><td><table border=0 bgcolor=#669933><tr><td width=12 align=right><font color=#ffffff face=Arial>z</font></td></tr></table></td></tr></table></td></tr><tr><td align=center>".$text['top_five']."</td></tr></table></center></p>";
$dmoz_count=0;
foreach($out[0] as $raw_link){
	if((!preg_match("/http:\/\/dmoz.org\//i",$raw_link))&&($dmoz_count<5)){
		$dmoz_count++;
		preg_match("/<a href=\"(.+?)\">(.+?)<\/a>(.+?)\- (.+?)<br>/i",$raw_link,$out);
		$href=$out[1];
		$href=preg_replace("/<(.+?)>/i","",$href);
		$title=$out[2];
		$title=preg_replace("/<(.+?)>/i","",$title);
		$desc=$out[4];
		$desc=preg_replace("/<(.+?)>/i","",$desc);
		if($desc==""){
			$desc=$text['none_found'];
		}
		$template=open("meta_entry.txt");
		$template=preg_replace("/<!--#meta_url-->/", $href, $template);
		$template=preg_replace("/<!--#meta_title-->/", $title, $template);
		$template=preg_replace("/<!--#meta_description-->/", $desc, $template);
		$dmoz_results.=$template;
	}
}
$raw_google=surf("http://www.google.com/search?q=".$terms);
preg_match_all("/<p>(.+?)<br>(.+?)<br>/i",$raw_google,$google_out);
$google_results="<p><center><b><font size=+1><font color=#0240C6 face=\"Times New Roman\">G</font><font color=#D01D08 face=\"Times New Roman\">o</font><font color=#F3C517 face=\"Times New Roman\">o</font><font color=#0240C6 face=\"Times New Roman\">g</font><font color=#289B28 face=\"Times New Roman\">l</font><font color=#D01D08 face=\"Times New Roman\">e</font></font></b><br>".$text['top_five']."</center></p>";
$n=0;
foreach($google_out[1] as $out_line){
	preg_match("/<A HREF=(.+?)>(.+?)<\/A>/i",$out_line,$out);
	$href=$out[1];
	$href=preg_replace("/<(.+?)>/i","",$href);
	$title=$out[2];
	$title=preg_replace("/<(.+?)>/i","",$title);
	$desc=$google_out[2][$n];
	$desc=preg_replace("/<(.+?)>/i","",$desc);
	if(($desc=="Similar pages")||(substr($desc,0,9)=="Category:")){
		$desc=$text['none_found'];
	}
	$n++;
	if($n<=5){
		$template=open("meta_entry.txt");
		$template=preg_replace("/<!--#meta_url-->/", $href, $template);
		$template=preg_replace("/<!--#meta_title-->/", $title, $template);
		$template=preg_replace("/<!--#meta_description-->/", $desc, $template);
		$google_results.=$template;
	}
}
$results=$dmoz_results.$google_results;
$template=open("meta_results.txt");
$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$search_script."\" method=post>", $template);
$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
$template=preg_replace("/<!--#small_terms_box-->/", "<input type=text name=t size=10>", $template);
$template=preg_replace("/<!--#terms_box-->/", "<input type=text name=t size=50>", $template);
$template=preg_replace("/<!--#large_terms_box-->/", "<input type=text name=t size=100>", $template);
$template=preg_replace("/<!--#search_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['search']."\">", $template);
$template=preg_replace("/<!--#quick_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['quick']."\">", $template);
$template=preg_replace("/<!--#meta_results-->/", $results, $template);
$template=preg_replace("/<!--#meta_title-->/", $title, $template);
$template=preg_replace("/<!--#meta_description-->/", $desc, $template);
$template=preg_replace("/<!--#header-->/", $header, $template);
$template=preg_replace("/<!--#footer-->/", $footer, $template);
$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['meta_search_results'], $template);
print $template;

function open($filename){
	global $templates;
	$fd=@fopen($templates."/".$filename, "r");
	$template=@fread($fd, filesize ($templates."/".$filename));
	@fclose($fd);
	return $template;
}
function surf($url){
	global $max_spider;
	$fd=@fopen($url,"r");
	$page=fread($fd, $max_spider);
	fclose($fd);
 	return $page;
}
function str_pd($string,$length){
	while(strlen($string)<$length){
		$string="0".$string;
	}
	return $string; 
}

?>