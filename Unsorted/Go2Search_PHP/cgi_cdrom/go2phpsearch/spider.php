<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                   spider.php                     #
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

$clock++;
$clock=intval($clock);
if($clock>4){
	$clock=1;
}
if($clock==1){
	$img=$clocks."/clock12.gif";
}elseif($clock==2){
	$img=$clocks."/clock3.gif";
}elseif($clock==3){
	$img=$clocks."/clock6.gif";
}elseif($clock==4){
	$img=$clocks."/clock9.gif";
}

$fd=fopen($temps."/".$file,"r");
$raw_file=fread($fd,filesize($temps."/".$file));
fclose($fd);
$addurlfile=split("\n",$raw_file);
$line=intval($line)+1;
if($line>=count($addurlfile)-1){
	$template=open("spider_finished.txt");
	$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$add_url_script."\" method=post><input type=hidden name=action value=check>", $template);
	$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
	$template=preg_replace("/<!--#url_box-->/", "<input type=text name=url size=50>", $template);
	$template=preg_replace("/<!--#email_box-->/", "<input type=text name=email size=50>", $template);
	$template=preg_replace("/<!--#category_select-->/", $category_select, $template);
	$template=preg_replace("/<!--#key_box-->/", "<input type=text name=key size=50>", $template);
	$template=preg_replace("/<!--#add_url_button-->/", "<input type=submit value=\"".$button['addurl']."\">", $template);
	$template=preg_replace("/<!--#restrict_check-->/", "<input type=checkbox name=restrict value=yes checked>", $template);
	$template=preg_replace("/<!--#clock-->/", "<img src=\"".$img."\">", $template);
	$template=preg_replace("/<!--#header-->/", $header, $template);
	$template=preg_replace("/<!--#footer-->/", $footer, $template);
	$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
	print $template;
	exit;
}
$percent=$line/(count($addurlfile)-1);
$percent=intval($percent*10000)/100;
list($email,$category,$searchkey,$homepage,$other)=split("\|\|",$addurlfile[0]);
$url=$addurlfile[$line];
$webpage=stripslashes(surf($url));
$nf=0;
preg_match("/<title>(.+?)<\/title>/i",$webpage,$out);
$title=$out[1];
if($title==""){
	$title=$text['none_found'];
	$nf++;
}
preg_match("/<meta(.+?)name=\"?description\"?(.+?)content=\"?(.+?)\"?\/?>/i",$webpage,$out);
$desc=$out[3];
if($desc==""){
	$desc=$text['none_found'];
	$nf++;
}
preg_match("/<meta(.+?)name=\"?keywords\"?(.+?)content=\"?(.+?)\"?\/?>/i",$webpage,$out);
$keys=$out[3];
if($keys==""){
	$keys=$text['none_found'];
	$nf++;
}
if($nf<3){
	$keys.=strip_tags($webpage);
	$keys=preg_replace("/<script(.+?)\/script>/"," ",$keys);
	$keys=preg_replace("/<style(.+?)\/style>/"," ",$keys);
	$keys=preg_replace("/[^A-Za-z0-9 ]/"," ",$keys);
	$query="SELECT * FROM $mysql_table WHERE url='$url' GROUP BY id";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	$i=0;
	while($i<$num){
		$existing_id=mysql_result($result,$i,"id");
		++$i;
	}
	if(($existing_id=="")||($existing_id==0)){
		$query="INSERT INTO $mysql_table SET id='$id',searchkey='$searchkey',category='$category',email='$email',hits='0',priority='0',rating='0',votes='0',homepage='$homepage',url='$url',title='$title',description='$desc',keywords='$keys',other='$other'";
		$result=mysql_query($query);
	}else{
		$query="UPDATE $mysql_table SET title='$title', description='$desc', keywords='$keys', searchkey='$searchkey', category='$category', email='$email' WHERE id='$existing_id'";
		$result=mysql_query($query);
	}
	$query="SELECT * FROM $mysql_table WHERE url='$url' GROUP BY id";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	$i=0;
	while($i<$num){
		$id_num=mysql_result($result,$i,"id");
		$fp=fopen($cache_dir."/".$id_num.".html","w");
		fputs($fp,$webpage);
		@fclose($fp);
		++$i;
	}
}else{
	$result="404";
}
$template=open("clockpage.txt");
$template=preg_replace("/<!--#percent-->/", $percent, $template);
$template=preg_replace("/<!--#clock-->/", "<img src=\"".$img."\">", $template);
$template=preg_replace("/<!--#skip-->/", "<a href=\"".$spider_script."?file=".$file."&clock=".$clock."&line=".$line."\">".$text['skip']."</a>", $template);
$template=preg_replace("/<!--#refresh-->/", "<meta http-equiv=Refresh content=\"0; URL=".$spider_script."?file=".$file."&clock=".$clock."&line=".$line."\">", $template);
$template=preg_replace("/<!--#header-->/", $header, $template);
$template=preg_replace("/<!--#footer-->/", $footer, $template);
$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['add_url'], $template);
print $template;

function open($filename){
	global $templates;
	$fd=@fopen($templates."/".$filename, "r");
	$template=@fread($fd, filesize ($templates."/".$filename));
	@fclose($fd);
	return $template;
}

function surf($remote_url){
	global $max_spider;
	if(!$fd=@fopen($remote_url, "r")){
		$page="404";
	}else{
		$page=fread($fd, $max_spider);
		fclose($fd);
		$page=@preg_replace("/::/si",":",$page);
		$page=@preg_replace("/\*\*\*/si","\*",$page);
	}
 	return $page;
}

?>