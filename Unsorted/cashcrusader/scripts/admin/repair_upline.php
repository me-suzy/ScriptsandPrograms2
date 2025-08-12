<?
include("../conf.inc.php");
require_once("../func.inc.php");
$result=@mysql_query("select * from levels where level=0");
while($row=@mysql_fetch_array($result)){
@mysql_query("update users set upline='$row[upline]' where username='$row[username]'");}
