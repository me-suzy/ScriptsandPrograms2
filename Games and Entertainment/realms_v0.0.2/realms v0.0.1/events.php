<?

if($staff=="yes"&&$_GET[random]>0){
$random=$_GET[random];
}else{
$random=rand(0,100);
}

$event = mysql_fetch_array(mysql_query("select * from `events` where `id`='$random' and `timesperday`>0"));

if($event[id]>0){
	$event[effect]=str_replace("STAR","*",$event[effect]);
eval($event[effect]);
print"<table class=\"ontop\"><tr><td>";
eval($event[event]);
print"</td></tr></table>";
mysql_query("update `events` set `timesperday`=`timesperday`-1 where id=$event[id]");
}