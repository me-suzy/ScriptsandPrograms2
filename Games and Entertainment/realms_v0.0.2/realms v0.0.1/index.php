<?php



include("mysql.php");
include("config.php");
include("usercheck.php");
include("clocktower.001.php");



$temple="$user[template]";

if($_GET[temple]){
$temple="$_GET[temple]";
}

if(!file_exists("templates/$temple.php")){
$temple="realmsie";
}





if(!$p){
$p=updates;
}

if(!$stat[id]||$stat[id]<1){
$p="character";
}

if(!file_exists("$p.$stat[realm].php")&&!file_exists("$p.001.php")){
$oldp=$p;
$p="404";
}



$ctime = time();
mysql_query("update users set lastseen=$ctime where id=$user[id]");
mysql_query("update characters set lastseen=$ctime where id=$stat[id]");
mysql_query("update users set `site`='$sitecode' where id=$user[id]");
$ip = "$HTTP_SERVER_VARS[REMOTE_ADDR]";
mysql_query("update users set ip='$ip' where id=$user[id]");
mysql_query("update users set page='$p' where id=$user[id]");



include("template.php");
$template=new template;
$template->define_file("$temple.php");
$template->add_region("title",'<?php print"$user[username] - $stat[name] - $p - $gametitle"; ?>');
$template->add_region("content",'
<?php if(file_exists("$p.$stat[realm].php")){
include("$p.$stat[realm].php");
}else{
include("$p.001.php");
} ?>
');


$template->add_region("junk",'
<?php include("junkjunk.php");  ?>
');

$template->add_region("lowchat",'
<?php if($p!=chat){
        include("lowchat.php");
        }  ?>
');

$template->add_region("event",'
<?php include("events.php"); ?>
');

$template->add_region("pages",'
<?php include("pages.php"); ?>
');

$template->add_region("logged",'
<?php include("logged.php"); ?>
');

$template->add_region("lowregion",'
<?php include("lowregion.php");  ?>
');

$template->add_region("userid",'<?php print"$user[id]";  ?>');


$template->add_region("menu",'
<?php if(file_exists("menu.$stat[realm].php")){
include("menu.$stat[realm].php");
}else{
include("menu.001.php");
} ?>
');
$template->add_region("right",'
<?php if(file_exists("rightbar.$stat[realm].php")){
include("rightbar.$stat[realm].php");
}else{
include("rightbar.001.php");
} ?>
');
$template->add_region("left",'
<?php if(file_exists("leftbar.$stat[realm].php")){
include("leftbar.$stat[realm].php");
}else{
include("leftbar.001.php");
} ?>
');

$template->make_template();

?>