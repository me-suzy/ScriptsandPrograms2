<?php 
include "config.php";
header("Content-Type: application/rss+xml");
$url=str_replace("rss.php","","$SERVER_NAME$SCRIPT_NAME");
$forum_name[$f]=strip_tags($forum_name[$f]);

$encoding=explode(":",$lang[1]);
print "<?xml version=\"1.0\" encoding=\"$encoding[0]\"?>\n";
print '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">'."\n";
print "<channel>\n";

print "<title>$forum_name[$f]</title>\n";
print "<description>$forum_name[$f]</description>\n";
print "<link>http://$SERVER_NAME</link>\n";
print "<language>$encoding[1]</language>\n";

$fs=open_file($log);
$fs=explode("\n",$fs);

for($i=0;$i<$rss_entries;$i++){
if(isset($fs[$i])&&strlen($fs[$i])>5){
$row=str_replace('&','',$fs[$i]);
$row=explode(":|:",$row);
print "<item>\n";
print '<title>'.$row[2].' / '.$row[5].'</title>'."\n";
print '<description>'.$row[3].'</description>'."\n";
print '<link>http://'.$url.'show.php?f='.$f.'&amp;topic='.$row[0].'</link>'."\n";
print "</item>";
}}

print"</channel></rss>\n";?>