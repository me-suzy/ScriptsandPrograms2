<?php
print "<a href=\"$PHP_SELF?p=skilledit&order=$order\">Back</a><br><br>";

if($user[position]!=Admin){
	print "youre not an admin";
	include("gamefooter.php");
	exit;
}
if(!$order){
	$order=levelreq;
}

if($edit==1){
	$skill=mysql_fetch_array(mysql_query("select * from skills where id='$skillid'"));
	print "Edited $skill[name]<br>";
	mysql_query("update skills set icons='$icons' where name='$skill[name]'");
	mysql_query("update skills set icon_def='$icon_def' where name='$skill[name]'");
	mysql_query("update skills set `heal_min`='$heal_min' where `name`='$skill[name]'");
	mysql_query("update skills set `heal_max`='$heal_max' where `name`='$skill[name]'");
	mysql_query("update skills set `uses`='$uses' where `name`='$skill[name]'");
	mysql_query("update skills set levelreq='$levelreq' where `name`='$skill[name]'");
	mysql_query("update skills set racereq='$racereq' where `name`='$skill[name]'");
	mysql_query("update skills set jobreq='$jobreq' where `name`='$skill[name]'");
	mysql_query("update skills set job_levelreq='$job_levelreq' where `name`='$skill[name]'");
	mysql_query("update skills set name='$name' where name='$skill[name]'");
}
if($newskill==1){
	mysql_query("insert into skills (`name`,`owner`,`racereq`) values (' New Skill', '0', 'norace')");
}

print "<a href=$PHP_SELF?p=skilledit&newskill=1>Create New Skill</a><br><br>";
print "<table border=1 cellspacing=0 cellpadding=2>";
if(!$skillid){
	$skillsel=mysql_query("select * from skills where owner='0' order by $order asc");
	print "<tr><td><a href=$PHP_SELF?p=skilledit&order=id>id</a></td><td><a href=$PHP_SELF?p=skilledit&order=name>name</a></td><td><a href=$PHP_SELF?p=skilledit&order=levelreq>levelreq</a></td><td><a href=$PHP_SELF?p=skilledit&order=racereq>racereq</a></td></tr>";
	while($skill=mysql_fetch_array($skillsel)){
		$popupmsg=popup($skill[id],"skill");
		print "<tr><td>$skill[id]</td><td><a href=\"$PHP_SELF?p=skilledit&order=$order&skillid=$skill[id]\" onMouseover=\"return escape('$popupmsg')\">$skill[name]</a></td><td>$skill[levelreq]</td><td>$skill[racereq]</td></tr>";
	}
}else{
	$skill=mysql_fetch_array(mysql_query("select * from skills where owner='0' and id='$skillid'"));
	if($act==prev){
		$skill=mysql_fetch_array(mysql_query("select * from skills where owner='0' and $order<'$skill[$order]' order by $order desc limit 1"));
	}elseif($act==next){
		$skill=mysql_fetch_array(mysql_query("select * from skills where owner='0' and $order>'$skill[$order]' order by $order asc limit 1"));
	}
	print "<form method=post action=$PHP_SELF?p=skilledit&skillid=$skill[id]&order=$order&edit=1>";
	print "<tr><td>Name: <INPUT TYPE=text NAME=name value=\"$skill[name]\"> (id:<b>$skill[id]</b>)</td><td>LevelReq: <INPUT TYPE=text NAME=levelreq value=\"$skill[levelreq]\"></td></tr>";
	print "<tr><td>stab, slash, arrow, fire, water, lightning<br>Icons: <INPUT TYPE=text NAME=icons value=\"$skill[icons]\"></td><td>stab, slash, arrow, fire, water, lightning<br>Icon Def: <INPUT TYPE=text NAME=icon_def value=\"$skill[icon_def]\"></td></tr>";
	print "<tr><td>Heal_min: <INPUT TYPE=text NAME=heal_min value=\"$skill[heal_min]\"></td><td>Heal_max: <INPUT TYPE=text NAME=heal_max value=\"$skill[heal_max]\"></td></tr>";
	print "<tr><td>Uses (multi,1,2,3...): <INPUT TYPE=text NAME=uses value=\"$skill[uses]\"></td><td>RaceReq: <INPUT TYPE=text NAME=racereq value=\"$skill[racereq]\"></td></tr>";
	print "<tr><td>JobReq: <INPUT TYPE=text NAME=jobreq value=\"$skill[jobreq]\"></td><td>Job Level Req: <INPUT TYPE=text NAME=job_levelreq value=\"$skill[job_levelreq]\"></td></tr>";
	$numtotal=mysql_num_rows(mysql_query("select * from skills where name='$skill[name]' and owner!='0'"));
	print "<tr><td>Total in Game: <b>$numtotal</b>";
	if($numtotal<=25){
		print "<br>";
		$itsel=mysql_query("select * from skills where name='$skill[name]' and owner>'0' order by owner asc");
		$onnum=1;
		while($it=mysql_fetch_array($itsel)){
			$owner=mysql_fetch_array(mysql_query("select * from characters where id='$it[owner]'"));
			print "<a href=\"$PHP_SELF?p=view&view=$owner[owner]\">$owner[name]</a>, ";
			if($onnum>=4){
				print "<br>";
				$onnum=$onnum-4;
			}
			$onnum=$onnum+1;
		}
	}
	print "</td><td><INPUT TYPE=submit></td></tr>";
	print "</form>";
}


print "</table>";
if($skill[id]){
	print "<br><a href=\"$PHP_SELF?p=skilledit&skillid=$skill[id]&order=$order&act=prev\">Previous</a> | <a href=\"$PHP_SELF?p=skilledit&skillid=$skill[id]&order=$order&act=next\">Next</a>";
}
print "<br><a href=\"$PHP_SELF?p=skilledit&order=$order\">Back</a>";