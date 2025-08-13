<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                   add_url.php3                    #
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
$category_select ="<select name=category>";
$cats=split("\*\*\*",$categories);
foreach($cats as $cat){
	$category_select.="<option value=\"".$cat."\">".$cat."</option>";
}
$category_select.="</select>";

if($action=="check"){
	if(($url=="")||($url=="http://")){
		$template=open("no_url.txt");
		$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$add_url_script."\" method=post><input type=hidden name=action value=check>", $template);
		$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
		$template=preg_replace("/<!--#url_box-->/", "<input type=text name=url size=50>", $template);
		$template=preg_replace("/<!--#email_box-->/", "<input type=text name=email size=50>", $template);
		$template=preg_replace("/<!--#category_select-->/", $category_select, $template);
		$template=preg_replace("/<!--#key_box-->/", "<input type=text name=key size=50>", $template);
		$template=preg_replace("/<!--#add_url_button-->/", "<input type=submit value=\"".$button['addurl']."\">", $template);
		$template=preg_replace("/<!--#restrict_check-->/", "<input type=checkbox name=restrict value=yes checked>", $template);
		$template=preg_replace("/<!--#header-->/", $header, $template);
		$template=preg_replace("/<!--#footer-->/", $footer, $template);
		$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
		print $template;
		exit;
	}
	if($email==""){
		$template=open("no_email.txt");
		$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$add_url_script."\" method=post><input type=hidden name=action value=check>", $template);
		$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
		$template=preg_replace("/<!--#url_box-->/", "<input type=text name=url size=50>", $template);
		$template=preg_replace("/<!--#email_box-->/", "<input type=text name=email size=50>", $template);
		$template=preg_replace("/<!--#category_select-->/", $category_select, $template);
		$template=preg_replace("/<!--#key_box-->/", "<input type=text name=key size=50>", $template);
		$template=preg_replace("/<!--#add_url_button-->/", "<input type=submit value=\"".$button['addurl']."\">", $template);
		$template=preg_replace("/<!--#restrict_check-->/", "<input type=checkbox name=restrict value=yes checked>", $template);
		$template=preg_replace("/<!--#header-->/", $header, $template);
		$template=preg_replace("/<!--#footer-->/", $footer, $template);
		$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
		print $template;
		exit;
	}
	if($key!=""){
		$query="SELECT * FROM $mysql_table WHERE searchkey='$key'";
		$result=mysql_query($query);
		$num=mysql_numrows($result);
		if($num>0){
			$template=open("key_taken.txt");
			$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$add_url_script."\" method=post><input type=hidden name=action value=check>", $template);
			$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
			$template=preg_replace("/<!--#url_box-->/", "<input type=text name=url size=50>", $template);
			$template=preg_replace("/<!--#email_box-->/", "<input type=text name=email size=50>", $template);
			$template=preg_replace("/<!--#category_select-->/", $category_select, $template);
			$template=preg_replace("/<!--#key_box-->/", "<input type=text name=key size=50>", $template);
			$template=preg_replace("/<!--#add_url_button-->/", "<input type=submit value=\"".$button['addurl']."\">", $template);
			$template=preg_replace("/<!--#restrict_check-->/", "<input type=checkbox name=restrict value=yes checked>", $template);
			$template=preg_replace("/<!--#header-->/", $header, $template);
			$template=preg_replace("/<!--#footer-->/", $footer, $template);
			$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
			print $template;
			exit;
		}
	}
	if(substr($url,0,7)!="http://"){
		$url="http://".$url;
	}
	if(substr($url,strlen($url)-1,strlen($url))=="/"){
		$url=substr($url,0,strlen($url)-1);
	}
	$url.="/";
	$found_pages[]=$url;
	$start_time=time();
	$raw_page=stripslashes(surf($url));
	list($number,$new_urls,$found_pages)=findlinks($raw_page,$url,0,0,$restrict);
	$file=@fopen($temps."/addurl_count.txt","r");
	$temp_total=@fread($file,filesize($temps."/addurl_count.txt"));
	@fclose($file);
	$temp_total++;
	if($file=fopen($temps."/addurl_count.txt","w")){
		rewind($file);
		fputs($file,$temp_total);
	}
	$file=fopen($temps."/addurl".$temp_total.".txt","a");
	fputs($file,$email."||".$category."||".$key."||".$url."||".$other."\n");
	foreach($found_pages as $found_page){
		chop($found_page);
		$found_page=ereg_replace("(\r\n|\n|\r)"," ",$found_page);
		fputs($file,$found_page."\n");
	}
	@fclose($file);
	$end_time=time();
	$seconds_took=$end_time-$start_time;
	$template=open("spider.txt");
	$template=preg_replace("/<!--#crawl_time-->/", $seconds_took, $template);
	$template=preg_replace("/<!--#number-->/", $number, $template);
	$template=preg_replace("/<!--#spider_button-->/", "<form action=\"".$spider_script."\" method=post><input type=hidden name=file value=\"addurl".$temp_total.".txt\"><input type=submit value=\"".$text['spider_now']."\"></form>", $template);
	$template=preg_replace("/<!--#header-->/", $header, $template);
	$template=preg_replace("/<!--#footer-->/", $footer, $template);
	$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
	print $template;
}else{
	$template=open("add_url.txt");
	$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$add_url_script."\" method=post><input type=hidden name=action value=check>", $template);
	$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
	$template=preg_replace("/<!--#url_box-->/", "<input type=text name=url size=50>", $template);
	$template=preg_replace("/<!--#email_box-->/", "<input type=text name=email size=50>", $template);
	$template=preg_replace("/<!--#category_select-->/", $category_select, $template);
	$template=preg_replace("/<!--#key_box-->/", "<input type=text name=key size=50>", $template);
	$template=preg_replace("/<!--#add_url_button-->/", "<input type=submit value=\"".$button['addurl']."\">", $template);
	$template=preg_replace("/<!--#restrict_check-->/", "<input type=checkbox name=restrict value=yes checked>", $template);
	$template=preg_replace("/<!--#header-->/", $header, $template);
	$template=preg_replace("/<!--#footer-->/", $footer, $template);
	$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
	print $template;
}

function open($filename){
	global $templates;
	$fd=@fopen($templates."/".$filename, "r");
	$template=@fread($fd, filesize ($templates."/".$filename));
	@fclose($fd);
	return $template;
}

function surf($url){
	global $max_spider;
	if(!$fd=@fopen($url, "r")){
		$page="404";
	}else{
		$page=fread($fd, $max_spider);
		fclose($fd);
		$page=@preg_replace("/::/si",":",$page);
		$page=@preg_replace("/\*\*\*/si","\*",$page);
	}
 	return $page;
}

function findlinks($html,$url,$levels,$number,$restrict,$new_urls,$found_pages){
	global $crawl;
	global $maxlinks;
	global $text;
	$url=preg_replace("/http:\/\//i","",$url);
	if(ereg("/",$url)){
		$url_bits=split("/",$url);
		if(preg_match("/(.+?)\.(.+?)/",$url_bits[count($url_bits)-1])){
			$url="<b>".$url_bits[0]."</b>";
			for($i=1;$i<count($url_bits)-1;$i++){
				$url.="/".$url_bits[$i];
			}
		}
	}
	$url="http://".$url;
	$levels++;
	preg_match_all("/href=\"?([^\"' >]+)/i",$html,$out);
	if(@count($out[1])>0){
		foreach($out[1] as $link){
			if(substr($link,0,1)=="/"){
				$raw_links[]="http://".$url_bits[0]."/".substr($link,1,strlen($link));
			}elseif(substr($link,0,7)=="http://"){
				if($restrict=="yes"){
					if(preg_match("/$url_bits[0]/i",$link)){
						$raw_links[]=$link;
					}
				}else{
					$raw_links[]=$link;
				}
			}elseif(!substr($link,0,1)=="#"){
				$raw_links[]=$url.$link;
			}
		}
	}
	if(@count($raw_links)>0){
		foreach($raw_links as $raw_link){
			if(substr($raw_link,strlen($raw_link)-1,strlen($raw_link))=="/"){
				$test_link=substr($raw_link,0,strlen($raw_link)-1);
			}else{
				$test_link=$raw_link;
			}
			if(preg_match("/\#/",$test_link)){
				list($test_link,$junk)=split("#",$test_link);
			}
			if($new_urls[$test_link]==""){
				$new_urls[$test_link]=$raw_link;
				$final_links[]=$raw_link;
			}
		}
	}
	if(@count($final_links)>0){
		foreach($final_links as $final_link){
			if($number<=$maxlinks){
				$number++;
				$found_pages[]=$final_link;
				if($levels<$crawl){
					$subpage=stripslashes(surf($final_link));
					list($number,$new_urls,$found_pages)=findlinks($subpage,$final_link,$levels,$number,$restrict,$new_urls,$found_pages);
				}
			}
		}
		if($levels>3){
			$levels=0;
		}
	}
	return array($number,$new_urls,$found_pages);
}

?>