<?php

if($_POST[act]=="Send"){

$contest = getvotenumber();
$ip = "$HTTP_SERVER_VARS[REMOTE_ADDR]";
foreach($_POST as $key => $value){
if($key!="act"){
$votes = explode("_",$key);

mysql_query("insert into contest_votes (`contest`, `entry`, `user`, `ip`, `type`)
values ('$contest', '$value', '$user[id]', '$ip', '$votes[0]')") or print("<br>Could not add vote.");

}
}

}

?>