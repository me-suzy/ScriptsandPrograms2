<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                   add_url.php                    #
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
	$proc=substr($url,0,7);
	if($proc!="http://"){
		$url="http://".$url;
	}
	$last=substr($url,strlen($url)-1,strlen($url));
	if($last=="/"){
		$url=substr($url,0,strlen($url)-1);
	}
	$url.="/";
	$homepage=$url;
	$raw_page=surf($url);
	if($raw_page!="404"){
		$template=open("url_submitted.txt");
		$template=preg_replace("/<!--#header-->/", $header, $template);
		$template=preg_replace("/<!--#footer-->/", $footer, $template);
		$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
		print $template;
		eregi("<title>([^<\/]+)</title>",$raw_page,$out);
		if(eregi("<title>([^<\/]+)</title>",$raw_page)){
			$title=$out[1];
		}else{
			$title=$text['none_found'];
		}
		preg_match("/<meta( |\n)name=\"?description\"?( |\n)content=\"?(.*?)\"?>/i",$raw_page,$out);
		if(preg_match("/<meta( |\n)name=\"?description\"?( |\n)content=\"?(.*?)\"?>/i",$raw_page,$out)){;
			$meta_desc=$out[3];
		}else{
			$meta_desc=$text['none_found'];
		}
		preg_match("/<meta( |\n)name=\"?keywords\"?( |\n)content=\"?(.*?)\"?>/i",$raw_page,$out);
		if(preg_match("/<meta( |\n)name=\"?keywords\"?( |\n)content=\"?(.*?)\"?>/i",$raw_page,$out)){;
			$meta_keys=$out[3];
		}else{
			$meta_keys=$text['none_found'];
		}
		$cache=striphtml($raw_page);
		if($crawl!=0){
			$links=findlinks($raw_page,$url);
		}
		$query="insert into $mysql_table values('','$key','$category','$email','0','0','0','0','$homepage','$url','$title','$meta_desc','$meta_keys','$cache','')";
		$result=mysql_query($query);
		if($crawl!=0){
			foreach($links as $url){
				$raw_page=surf($url);
				if($raw_page!="404"){
					eregi("<title>([^<\/]+)</title>",$raw_page,$out);
					if(eregi("<title>([^<\/]+)</title>",$raw_page)){
						$title=$out[1];
					}else{
						$title=$text['none_found'];
					}
					preg_match("/<meta( |\n)name=\"?description\"?( |\n)content=\"?(.*?)\"?>/i",$raw_page,$out);
					if(preg_match("/<meta( |\n)name=\"?description\"?( |\n)content=\"?(.*?)\"?>/i",$raw_page,$out)){;
						$meta_desc=$out[3];
					}else{
						$meta_desc=$text['none_found'];
					}
					preg_match("/<meta( |\n)name=\"?keywords\"?( |\n)content=\"?(.*?)\"?>/i",$raw_page,$out);
					if(preg_match("/<meta( |\n)name=\"?keywords\"?( |\n)content=\"?(.*?)\"?>/i",$raw_page,$out)){;
						$meta_keys=$out[3];
					}else{
						$meta_keys=$text['none_found'];
					}
					# $cache=striphtml($raw_page);
					$cache=$raw_page;
					$add_new=1;
					$top_id=0;
					$query="SELECT id,url from $mysql_table order by id";
					$result=mysql_query($query);
					while(list($m_id,$m_url)=mysql_fetch_row($result)){
						if($url==$m_url){
							$add_new=0;
						}
						if($m_id>$top_id){
							$top_id=$m_id;
						}
					}
					$top_id++;
					if($add_new==1){
						$query="insert into $mysql_table values('','$key','$category','$email','0','0','0','0','$homepage','$url','$title','$meta_desc','$meta_keys','')";
						$result=mysql_query($query);
						if($result==1){
							if($file=fopen($cache_dir."/".$top_id.".html","a")){
								fputs($file,$cache);
							}
						}
					}
				}
			}
		}
	}else{
		$template=open("error.txt");
		$template=preg_replace("/<!--#header-->/", $header, $template);
		$template=preg_replace("/<!--#footer-->/", $footer, $template);
		$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
		print $template;
	}
}else{
	$template=open("add_url.txt");
	$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$add_url_script."\" method=post><input type=hidden name=action value=check>", $template);
	$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
	$template=preg_replace("/<!--#url_box-->/", "<input type=text name=url size=50>", $template);
	$template=preg_replace("/<!--#email_box-->/", "<input type=text name=email size=50>", $template);
	$template=preg_replace("/<!--#category_select-->/", $category_select, $template);
	$template=preg_replace("/<!--#key_box-->/", "<input type=text name=key size=50>", $template);
	$template=preg_replace("/<!--#add_url_button-->/", "<input type=submit value=\"".$button['addurl']."\">", $template);
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
		$page=@preg_replace("/ +/si"," ",$page);
		$page=@preg_replace("/> +</si","><",$page);
		$page=@preg_replace("/::/si",":",$page);
		$page=@preg_replace("/\*\*\*/si","\*",$page);
	}
 	return $page;
}

function findlinks($html,$url){
	global $crawl;
	global $maxlinks;
	preg_match_all("/href=\"?([^\"' >]+)/i",$html,$out);
	$a=0;
	$raw_links[0]="LINKS FOUND";
	while(list(,$link)=each($out[1])){
		if(($a<$maxlinks)||($maxlinks=="*")){
			if(substr($link,0,1)=="/"){
				$link=$url.substr($link,1,strlen($link));
			}elseif((strtolower(substr($link,0,7))!="http://")&&(strtolower(substr($link,0,7))!="https:/")){
				$link=$url.$link;
			}
			$links[$a]=$link;
			$raw_links[$a]=$link;
			$a++;
		}
	}
	if($crawl==2){
		if(($a<$maxlinks)||($maxlinks=="*")){
			foreach($links as $second_link){
				$html=surf($second_link);
				preg_match_all("/href=\"?([^\"' >]+)/i",$html,$out);
				while(list(,$link)=each($out[1])){
					if(substr($link,0,1)=="/"){
						$link=$second_link.substr($link,1,strlen($link));
					}elseif((strtolower(substr($link,0,7))!="http://")&&(strtolower(substr($link,0,7))!="https:/")){
						$link=$second_link.$link;
					}
					$raw_links[$a]=$link;
					$a++;
				}		
			}
		}
	}
	return $raw_links;
}

function striphtml($html){
	eregi("<body[^>]+>(.*)</body>",$html,$out);
	$html=$out[1];
	$html=preg_replace("/<script[^>]*?>.*?<\/script>/si"," ",$html);
	$html=preg_replace("/<style[^>]*?>.*?<\/style>/si"," ",$html);
	$html=ereg_replace("<p[^>]+>"," ",$html);
	$html=ereg_replace("<br>","*break*",$html);
	$html=ereg_replace("<tr>","*break*",$html);
	$html=ereg_replace("<hr>","*break*",$html);
	$html=preg_replace("/\n/"," ",$html);
	$html=ereg_replace("<[^>]+>"," ",$html);
	$html=preg_replace("/&(nbsp|#160);/i"," ",$html);
	$html=preg_replace("/([\r\n])[\s]+/"," ",$html);
 	return $html;
}

?>