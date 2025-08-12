<?php
print "<a href=\"$PHP_SELF?p=itemedit&order=$order\">Back</a><br><br>";

$NPCid=-1;

if($user[position]!=Admin){
	print "youre not an admin";
	include("gamefooter.php");
	exit;
}
if(!$order){
	$order=name;
}

if($edit==1){
	$item=mysql_fetch_array(mysql_query("select * from items where id='$itemid'"));
	print "Edited $item[name]<br>";
	mysql_query("update items set rarity='$rarity' where name='$item[name]'");
	mysql_query("update items set icons='$icons' where name='$item[name]'");
	mysql_query("update items set icon_def='$icon_def' where name='$item[name]'");
	mysql_query("update items set type='$type' where name='$item[name]'");
	mysql_query("update items set price='$price' where name='$item[name]' and owner!='$NPCid'");
	mysql_query("update items set image='$image' where name='$item[name]'");
	mysql_query("update `items` set `phrase`='$phrase' where `name`='$item[name]'");
	mysql_query("update `items` set `phrase2`='$phrase2' where `name`='$item[name]'");
	mysql_query("update `items` set `effect`='$effect' where `name`='$item[name]'");
	mysql_query("update `items` set `effect_power`='$effect_power' where `name`='$item[name]'");
	mysql_query("update `items` set `heal_min`='$heal_min' where `name`='$item[name]'");
	mysql_query("update `items` set `heal_max`='$heal_max' where `name`='$item[name]'");
	mysql_query("update `items` set `uses`='$uses' where `name`='$item[name]'");
	mysql_query("update items set name='$name' where name='$item[name]'");
}
if($newitem==1){
	mysql_query("insert into items (`name`,`rarity`) values (' New Item', '101')");
}

print "<a href=$PHP_SELF?p=itemedit&newitem=1>Create New Item</a><br><br>";
print "<table border=1 cellspacing=0 cellpadding=2>";
if(!$itemid){
	$itemsel=mysql_query("select * from items where owner='0' order by $order asc");
	print "<tr><td><a href=$PHP_SELF?p=itemedit&order=id>id</a></td><td><a href=$PHP_SELF?p=itemedit&order=name>name</a></td><td><a href=$PHP_SELF?p=itemedit&order=rarity>rarity</a></td></tr>";
	while($item=mysql_fetch_array($itemsel)){
		$popupmsg=popup($item[id],"item");
		print "<tr><td>$item[id]</td><td><a href=\"$PHP_SELF?p=itemedit&order=$order&itemid=$item[id]\" onMouseover=\"return escape('$popupmsg')\">$item[name]</a></td> <td>r$item[rarity]</td></tr>";
	}
}else{
	$item=mysql_fetch_array(mysql_query("select * from items where owner='0' and id='$itemid'"));
	if($act==prev){
		$item=mysql_fetch_array(mysql_query("select * from items where owner='0' and $order<'$item[$order]' order by $order desc limit 1"));
	}elseif($act==next){
		$item=mysql_fetch_array(mysql_query("select * from items where owner='0' and $order>'$item[$order]' order by $order asc limit 1"));
	}
	print "<form method=post action=$PHP_SELF?p=itemedit&itemid=$item[id]&order=$order&edit=1>";
	print "<tr><td>Name: <INPUT TYPE=text NAME=name value=\"$item[name]\"> (id:<b>$item[id]</b>)</td><td>r<INPUT TYPE=text NAME=rarity value=\"$item[rarity]\"></td></tr>";
	print "<tr><td>stab, slash, arrow, fire, water, lightning<br>Icons: <INPUT TYPE=text NAME=icons value=\"$item[icons]\"></td><td>stab, slash, arrow, fire, water, lightning<br>Icon Def: <INPUT TYPE=text NAME=icon_def value=\"$item[icon_def]\"></td></tr>";
	print "<tr><td>Heal_min: <INPUT TYPE=text NAME=heal_min value=\"$item[heal_min]\"></td><td>Heal_max: <INPUT TYPE=text NAME=heal_max value=\"$item[heal_max]\"></td></tr>";
	print "<tr><td>Type: <INPUT TYPE=text NAME=type value=\"$item[type]\"></td><td>Price: <INPUT TYPE=text NAME=price value=\"$item[price]\"></td></tr>";
	print "<tr><td>Image: <INPUT TYPE=text NAME=image value=\"$item[image]\"></td><td>Uses (multi,once,once_ever): <INPUT TYPE=text NAME=uses value=\"$item[uses]\"></td></tr>";
	print "<tr><td colspan=2>Phrase: <br><INPUT TYPE=text NAME=phrase value=\"$item[phrase]\" size=80></td></tr>";
	print "<tr><td colspan=2>Phrase2 <font size=1>(when player uses weapon)</font>: <br><INPUT TYPE=text NAME=phrase2 value=\"$item[phrase2]\" size=80></td></tr>";
	print "<tr><td>Effect: <INPUT TYPE=text NAME=effect value=\"$item[effect]\"></td><td>Effect_Power: <INPUT TYPE=text NAME=effect_power value=\"$item[effect_power]\"></td></tr>";
	$numtotal=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner!='0'"));
	$numNPC=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner='$NPCid'"));
	$numbyplayers=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner>'0'"));
	print "<tr><td>Total in Game: <b>$numtotal</b><br>NPC Owned: $numNPC<br>User Owned: $numbyplayers";
	if($numbyplayers<=25){
		print "<br>";
		$itsel=mysql_query("select * from items where name='$item[name]' and owner>'0' order by owner asc");
		$onnum=1;
		while($it=mysql_fetch_array($itsel)){
			$owner=mysql_fetch_array(mysql_query("select * from users where id='$it[owner]'"));
			print "<a href=\"$PHP_SELF?p=view&view=$owner[id]\">$owner[username]</a>, ";
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
if($item[id]){
	print "<br><a href=\"$PHP_SELF?p=itemedit&itemid=$item[id]&order=$order&act=prev\">Previous</a> | <a href=\"$PHP_SELF?p=itemedit&itemid=$item[id]&order=$order&act=next\">Next</a>";
}
print "<br><a href=\"$PHP_SELF?p=itemedit&order=$order\">Back</a>";