<?
include "../tpl/clients_top.ihtml";
require("../conf/sys.conf");
require("../lib/mysql.lib");
 

$db = c();

if (!$uid){
   if (!e(q("select id from campaigns where user_id='$auth'"))) $cm=f(q("select id from campaigns where user_id='$auth' ORDER BY RAND()"));
  $uid=$cm["id"];
}

$r = q("select * from campaigns where id='$uid' and status='1' and user_id='$auth'");
if (e($r)) {echo "Campaign not found or disabled/deleted. Go <a href=index.php>home</a> !"; exit;};
$camp=f($r);


if ($delete&&$smid) q("delete from safelistdata where uid='$uid' and id='$smid'");
$r = q("select * from safelistdata where uid='$uid'");

if (e($r)) {echo "No members, yet.";}else
{
	echo "<table border=0 cellspacing=1 cellpadding=2 bgcolor=AAAAAA align=center>";
	echo "<tr><td colspan=3 bgcolor='$color_head'>$camp[title] Safelist Members</td></tr>";
	echo "<tr bgcolor=F0F0F0><td>Name</td><td>Status</td><td>Remove</td></tr>";
while ($mem=f($r)) 
{
if ($mem[status]==0) $mem[status]= "not confirmed";
if ($mem[status]==1) $mem[status]= "available";
if ($mem[status]==3) $mem[status]= "on vacation";
if ($mem[status]==2) $mem[status]= "suspended"; 
echo "<tr bgcolor=FFFFFF><td>$mem[fname] $mem[lname]</td><td>$mem[status]</td><td><a href=safelistmembers.php?smid=$mem[id]&delete=1>Delete</a></td></tr>";
};
	echo "</table>";
};

d($db);
include "../tpl/clients_bottom.ihtml";
?>