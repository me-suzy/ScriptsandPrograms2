<?php
$checksel=mysql_query("select * from skills where owner='0' and levelreq<='$stat[level]'");
while($check=mysql_fetch_array($checksel)){
	$have=mysql_num_rows(mysql_query("select * from skills where owner='$stat[id]' and name='$check[name]'"));
	if($have<=0){
		$gotit=1;
		if($check[racereq]!=$stat[race]&&$check[racereq]!="any"){
			$gotit=0;
		}
		if($check[jobreq]!=$stat[job]&&$check[jobreq]!="any"){
			$gotit=0;
		}
		if($check[job_levelreq]>$stat[job_level]){
			$gotit=0;
		}
		if($gotit==1){
			addskill("$check[name]",$stat[id]);
			print "<b>New Skill!</b> $check[name]<br><br>";
		}
	}
}
print "$stat[name]";
print "'s Skills: <br><table border=1 cellspacing=0 cellpadding=2>";
$n=1;
$sksel=mysql_query("select * from skills where owner='$stat[id]' order by levelreq,id asc");
while($sk=mysql_fetch_array($sksel)){
	print "<tr><td>$n. $sk[name]</td></tr>";
	$n=$n+1;
}
print "</table>";