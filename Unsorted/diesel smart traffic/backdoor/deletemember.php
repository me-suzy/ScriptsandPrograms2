<?
  require("../conf/sys.conf");
  require("../lib/ban.lib");
  require("../lib/mysql.lib");
  require("bots/errbot");
  require("header.html");

$db = c();

if (!$id) exit;

if ($sure)
{
if (!$mid) exit;
$r=q("DELETE FROM camp_groups cg USING camp_groups cg, campaigns cm where cg.cid=cm.id and cm.user_id='$mid'");
$r=q("DELETE FROM campaigns cm where cm.user_id='$mid'");
$r=q("DELETE FROM members where id='$mid'");
echo " Deleted.";
}else{
$mem=f(q("select * from members where id='$id'"));
echo "<b>DELETE MEMBER !</b><br><br>Username : $mem[login]<br>Full name : $mem[fname] $mem[lname]<br>Email : $mem[email]<br><br>Are you sure you want to delete this member with all attached campaigns and groups listings ? <B><a href=deletemember.php?mid=$id&id=$id&sure=1>YES!</a></B>";
}

	
d($db);
  require("footer.html");
?>