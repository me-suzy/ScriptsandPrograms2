<?
  require("../conf/sys.conf");
  require("../lib/ban.lib");
  require("../lib/mysql.lib");
  require("bots/errbot");
  require("http://ocasinternetsolutions.com/sites/header.html");

$db = c();



$r=q("UPDATE sysvars set value='0' where name='requireapproval'");
$r=q("UPDATE sysvars set value='0' where name='newm_trusted'");
$r=q("UPDATE sysvars set value='0' where name='newm_expirable'");
$r=q("UPDATE sysvars set value='0' where name='newm_approval'");
$r=q("UPDATE sysvars set value='0' where name='newm_free'");
echo "All settings automated.";

	
d($db);
  require("footer.html");
?>