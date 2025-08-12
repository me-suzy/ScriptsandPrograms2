<?php

if(file_exists("$file.$stat[world].php")){
$file="$file.$stat[world].php";
}elseif(file_exists("$file.001.php")){
$file="$file.001.php";
}elseif(file_exists("$file.php")){
$file="$file.php";
}elseif(file_exists("$file")){
$file=$file;
}elseif($file){
$file="404.001.php";
}


$file = @implode('
', file($file));


foreach($secret as $key => $value){
$file = str_replace("$value","[secret]",$file);
}


if (substr($file, 0, 1) == "/") $file = substr($file, 1);
if (substr($file, 0, 1) == "/") $file = substr($file, 1);
if (substr($file, 0, 1) == "/") $file = substr($file, 1);
if (substr($file, 0, 1) == "/") $file = substr($file, 1);
$pageTitle = "Code Snippets : $file";


ini_set("highlight.string", "#6060FF");
ini_set("highlight.comment", "#999999");
ini_set("highlight.keyword", "#333399");
ini_set("highlight.default", "#660303");
ini_set("highlight.html", "#993333");

print "<table width='98%' class='dtable' cellspacing='0' cellpadding='0' border='0'>";
print "<tr><td class=''>";
if ($file) {
highlight_string($file);
}elseif($listem){
$handle=opendir("./");
while (false !== ($file = readdir($handle)))
{

$mystring = "$file";
$findme  = ".php";
$pos = strpos("$mystring", "$findme");
foreach($secret as $key => $value){
$mystring = str_replace("$value","[secret]",$mystring);
}
if($pos){
$rest = substr("$mystring", 0, "$pos");
$direct .= "<a href=\"$GAME_SELF?p=source&file=$rest\">$rest</a><BR>";

}

}
closedir($handle);

print"$direct";
}

print "</td></tr><tr><td><a href=$GAME_SELF?p=source&listem=true>List All Files</a></td></tr>";
print "</table>";

?>