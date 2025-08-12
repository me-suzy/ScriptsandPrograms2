<?
include("../conf.inc.php");
include("../functions.inc.php");
$result=@mysql_query("select * from accounting where description like '%converted to cash'");
while ($row=@mysql_fetch_array($result)){
list($amount)=split(" ",$row[description]);
$amount=$amount*100000;
@mysql_query("update accounting set description='Points',type='points',amount=$amount where transid=$row[transid]");
echo "$row[username]: $row[transid] $amount\n";
}
