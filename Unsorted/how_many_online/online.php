<?php 
//Copyright Cgixp team
//Do not modify anything in this script as you don't need to do so
$log_file="online.txt";
$min_online="1";
if ($HTTP_X_FORWARDED_FOR == "") {
$ip = getenv(REMOTE_ADDR);
}
else {
$ip = getenv(HTTP_X_FORWARDED_FOR);
}
$day =date("d");
$month =date("m");
$year =date("Y");
$date="$day-$month-$year";
$ora = date("H");
$minuti = date("i");
$secondi = date("s");
$time="$ora:$minuti:$secondi";
$users_read = fopen("$log_file", "r");
$users = fread($users_read, filesize("$log_file"));
fclose($users_read);
$to_write="$ip|$time|$date";
if($users==0){
$user_write = fopen("$log_file", "w");
fputs($user_write , $to_write );
fclose($user_write );
}
else{
$users=explode("\n",$users);
$user_da_tenere=array();
while (list ($key, $val) = each ($users)) {
$user_sing=explode("|",$val);
if($date==$user_sing[2]){
$h=explode(":",$user_sing[1]);
if($ip!=$user_sing[0]){
if(($h[0]==$ora)and(($minuti-$h[1])<=$min_online)){
$user_da_tenere[]=$val;}
if(($h[0]==($ora-1))and((($minuti+2)-$h[1])<=$min_online)){
$user_da_tenere[]=$val;
}
}
}
}
$user_da_tenere[]=$to_write;
$user_write = fopen("$log_file", "w");
fputs($user_write , "" );
fclose($user_write );
while (list ($k, $v) = each ($user_da_tenere)) {
$new_file_log = fopen ("$log_file", "a");
fwrite($new_file_log,"$v\n");
fclose($new_file_log);
}
}
$users_online_read = fopen("$log_file", "r");
$users_online = fread($users_online_read, filesize("$log_file"));
fclose($users_online_read);
$users_online=explode("\n",$users_online);
$n_u_online=count($users_online)-1;
echo "<font face=arial size=2><b>Users Online: $n_u_online</b>";
echo"<meta http-equiv=\"Refresh\" content=\"5; URL=online.php\" >";
?>
<html>
<body background="gradient1.gif">
</html>