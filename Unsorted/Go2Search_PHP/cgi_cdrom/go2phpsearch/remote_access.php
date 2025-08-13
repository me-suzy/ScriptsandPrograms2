<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                remote_access.php                 #
#                                                   #
#####################################################
#       Copyright © 2001 W. Dustin Alligood         #
#####################################################

require("./text.php");
require("./config.php");

########## Change Nothing Below This Line ###########

$url=preg_replace("/ /","",$url);
$url=preg_replace("/\+/","%2B",$url);
if(strtolower($return_bar_off)=="true"){
	header("Location:".base64_decode($url));
	exit;
}
if($frame=="return"){
	$back=base64_decode($back);
	$url=base64_decode($url);
	preg_match("/f=(.+?)&/i",$back,$out);
	$fd=@fopen($temps."/search".$out[1].".txt","r");
	$raw_flat_links=@fread($fd, filesize ($temps."/search".$out[1].".txt"));
	@fclose($fd);
	$raw_links=split("\*\*\*end_of_line\*\*\*",$raw_flat_links);
	$links="<table border=0>";
	$top=count($raw_links)-1;
	if($top>10){
		$top=10;
	}
	for($i=0;$i<$top;$i++){
		list($home_indent,$entry_total,$entry_home,$entry_url,$entry_matches,$entry_title,$entry_desc,$entry_keys,$entry_rating,$entry_hits,$entry_email,$entry_cat,$entry_key,$entry_cache,$entry_id)=split("::",$raw_links[$i]);
		if(strlen($entry_title)>15){
			$entry_title=substr($entry_title,0,15)."...";
		}
		if($entry_url!="***none***"){
			$links.="<tr><td valign=top><font size=-2>-</font></td><td valign=top><font size=-2><a href=\"".$entry_url."\" target=page_frame>".$entry_title."</a></font></td></tr>";
		}
	}
	$links.="</table>";
	$template=open("return_bar.txt");
	$template=preg_replace("/<!--#back_to_search-->/", $back, $template);
	$template=preg_replace("/<!--#url-->/", $url, $template);
	$template=preg_replace("/<!--#links-->/", $links, $template);
	$template=preg_replace("/<!--#title-->/", $page['title'], $template);
	print $template;

}else{
	$back=urldecode($back);
	$back=eregi_replace(" ","",$back);
	$back=eregi_replace("\+","%2B",$back);
	print "<html><head><title>".$title."</title></head><frameset ";
	if(strtolower($return_bar_poz)=="bottom"){
		print "rows=*,".$return_bar_size."><frame noresize src=\"".base64_decode($url)."\" name=page_frame><frame noresize src=\"".$remote_script."?frame=return&url=".$url."&back=".$back."\" ".$return_bar_attrib.">";
	}elseif(strtolower($return_bar_poz)=="left"){
		print "cols=".$return_bar_size.",*><frame noresize src=\"".$remote_script."?frame=return&url=".$url."&back=".$back."\" ".$return_bar_attrib."><frame noresize src=\"".base64_decode($url)."\" name=page_frame>";
	}elseif(strtolower($return_bar_poz)=="right"){
		print "cols=*,".$return_bar_size."><frame noresize src=\"".base64_decode($url)."\" name=page_frame><frame noresize src=\"".$remote_script."?frame=return&url=".$url."&back=".$back."\" ".$return_bar_attrib.">";
	}else{
		print "rows=".$return_bar_size.",*><frame noresize src=\"".$remote_script."?frame=return&url=".$url."&back=".$back."\" ".$return_bar_attrib."><frame noresize src=\"".base64_decode($url)."\" name=page_frame>";
	}
	print "</frameset></html>";
}

function open($filename){
	global $templates;
	$fd=@fopen($templates."/".$filename, "r");
	$template=@fread($fd, filesize ($templates."/".$filename));
	@fclose($fd);
	return $template;
}

?>