
<?php
include "../tpl/clients_top.ihtml";

$db = c();
$c1="";
if ($eid) $c1="and id='$eid'";
$nr1=f(q("select count(id) as e from event where status <>0 and (user_id='$auth' or user_id='-1')"));
echo "<table border=0 cellspacing=1 cellpadding=2 width=80% bgcolor=AAAAAA align=center>";
echo "<tr><td bgcolor='$color_head'><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td>&nbsp;<b>INBOX ($nr1[e])</B></td>";
if ($nr1[e]) {
	  echo "<td align=right><b>&nbsp;&nbsp; [<a href=news.php> view all </a>]</b></td></tr></table>";
	  echo "<table border=0 cellspacing=0 cellpadding=0 width=100%>";
	  $r=q("select id, sender, title,  type,  credits, status, rdate, contents from event where status <>0 and (user_id='$auth' or user_id='-1') $c1 ORDER BY rdate DESC");
                 while  ($ev = f($r)){
			$t1="<table width=100% border=0 cellspacing=0 cellpadding=0><td>";
			if ($ev[credits]) $t1.= " ($ev[credits]) &nbsp; ";
			if ($ev[type]=="ppemail" && $ev[status]==1) $t1.=" Paid email : Not read. ";
			if ($ev[type]=="ppemail" && $ev[status]==2) $t1.=" Paid email : Bonus received. ";
			if ($ev[type]=="payment" && $ev[status]==1) $t1.=" Payment : Pending. ";
			if ($ev[type]=="payment" && $ev[status]==2) $t1.=" Payment : Processed. ";
			if ($ev[type]=="withdraw" && $ev[status]==1) $t1.=" Withdrawal : Pending. ";
			if ($ev[type]=="withdraw" && $ev[status]==2) $t1.=" Withdrawal : Processed. ";


			$t1.="</td><td align=right> ".(date("d M Y H:i:s",$ev[rdate]))."</td></table>";
			echo "<tr><td bgcolor=E0E0E0>$t1</td></tr>";
			echo "<tr><td bgcolor=F0F0F0 align=center><b>$ev[title]</b></td></tr>";
			echo "<tr><td bgcolor=FFFFFF>$ev[contents]</td></tr>";
			};
	echo "</td></tr></table>";
	}
else echo "</td></tr>";
echo "</table><br>";
include "../tpl/clients_bottom.ihtml";
?>

