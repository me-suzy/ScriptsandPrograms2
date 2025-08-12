<?php 
include "config.php";
header("Content-Type: application/rdf+xml");
$url=str_replace("rdf.php","","$SERVER_NAME$SCRIPT_NAME");
$forum_name[$f]=strip_tags($forum_name[$f]);

$encoding=explode(":",$lang[1]);
print "<?xml version=\"1.0\" encoding=\"$encoding[0]\"?>\n";
print '<rdf:RDF xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:h="http://www.w3.org/1999/xhtml" xmlns:hr="http://www.w3.org/2000/08/w3c-synd/#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/">'."\n";
print '<channel rdf:about="http://'.$SERVER_NAME.$SCRIPT_NAME.'">'."\n";
print '<title>'.$forum_name[$f].'</title>'."\n";
print '<description>'.$forum_name[$f].'</description>'."\n";
print '<link>http://'.$SERVER_NAME.'</link>'."\n";
print '<dc:date>'.date('Y-m-d').'</dc:date>'."\n";
print '<items><rdf:Seq>'."\n";

$temp_array=array();
$fs=open_file($log);
$fs=explode("\n",$fs);

for($i=0;$i<$rss_entries;$i++){
if(isset($fs[$i])&&strlen($fs[$i])>5){
$fs[$i]=str_replace('&','',$fs[$i]);
$temp_array[$i]=$fs[$i];
$row=explode(":|:",$fs[$i]);
print '<rdf:li rdf:resource="http://'.$url.'show.php?f='.$f.'&amp;topic='.$row[0].'" />'."\n";
}}
print '</rdf:Seq></items></channel>'."\n\n";

for($i=0;$i<count($temp_array);$i++){
if(strlen($temp_array[$i])>5){
$row=explode(":|:",$temp_array[$i]);

print '<item rdf:about="http://'.$url.'show.php?f='.$f.'&amp;topic='.$row[0].'">'."\n";
print '<title>'.$row[2].' / '.$row[5].'</title>'."\n";
print '<description>'.$row[3].'</description>'."\n";
print '<link>http://'.$url.'show.php?f='.$f.'&amp;topic='.$row[0].'</link>'."\n";

if(strstr($row[1],' ')){
$vdt=explode(" ",$row[1]);
switch($vdt[1]){
case'Jan':$vdt[1]='01';break;
case'Feb':$vdt[1]='02';break;
case'Mar':$vdt[1]='03';break;
case'Apr':$vdt[1]='04';break;
case'May':$vdt[1]='05';break;
case'Jun':$vdt[1]='06';break;
case'Jul':$vdt[1]='07';break;
case'Aug':$vdt[1]='08';break;
case'Sep':$vdt[1]='09';break;
case'Oct':$vdt[1]='10';break;
case'Nov':$vdt[1]='11';break;
case'Dec':$vdt[1]='12';break;
}
$valid_date="$vdt[2]-$vdt[1]-$vdt[0]T$vdt[3]";}
else{$valid_date=gmdate("Y-m-d h:i",$row[1]);
$valid_date=str_replace(' ','T',$valid_date);}

print '<dc:date>'.$valid_date.':00-00:00</dc:date>'."\n";
print '</item>'."\n\n";

}}print '</rdf:RDF>';?>