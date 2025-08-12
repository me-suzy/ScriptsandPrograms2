<?php

$thispage = mysql_fetch_array(mysql_query("select * from pages where page='$p' and realm='$stat[realm]'"));

if(!$thispage[id]){
$thispage = mysql_fetch_array(mysql_query("select * from pages where page='$p' and realm='all'"));
}

if(!$thispage[id]){
$thispage = mysql_fetch_array(mysql_query("select * from pages where page='lostpage'"));
}

print"<table class=\"ontop\"><tr><td>$thispage[description]</td></tr></table>";
