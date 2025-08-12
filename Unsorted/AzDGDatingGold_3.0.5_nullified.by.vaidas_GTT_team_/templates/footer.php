<?
##################################################################
# \-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/ #
##################################################################
# AzDGDatingGold                Version 3.0.5                     #
# Status                        Paid                             #
# Writed by                     AzDG (support@azdg.com)          #
# Created 21/09/02              Last Modified 21/09/02           #
# Scripts Home:                 http://www.azdg.com              #
##################################################################
include_once $int_path."/classes/AzDGOnlineUsers.class.inc.php";

$t = new Template;
$t->set_file($int_path."/templates/".$template_name."/footer.html");
$ol = new oup();
$mtime2 = explode(" ", microtime());
$endtime = $mtime2[1] + $mtime2[0];
$totaltime = ($endtime - $starttime);
$totaltime = number_format($totaltime, 7);
$stat=$ol->view();
$proctime=W_PROC_TIME.": ".$totaltime." ".W_SEC;
$copyrights="&copy;AzDGDatingGold, Version 3.0.5<br>Designed&Programming by <a href=\"http://www.azdg.com\" target=\"_blank\" class=menu>AzDG</a>";
$t->set_var("C_URL", $url);
$t->set_var("STAT", $stat);
$t->set_var("PROCTIME", $proctime);
$t->set_var("COPYRIGHTS", $copyrights);
$t->parse("footer", "footer", true);
$t->pparse();
?>