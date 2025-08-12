<?php
function club_api($command, $params="") {
	$clubid = "3565-35785";
	$acode = "8b51cf42bdd60fbd4bd459cedff4a778";

	return trim(implode("\n", file("http://www.creatureworld.net/clubs/api/$command.php?xc=$clubid&xp=$acode&$params")));
}
print "<table border=1 cellspacing=0 cellpadding=5><tr><td>#</td><td>username</td><td>postcount</td><td>title</td></tr>";
$mem=explode("\n", club_api("user_list", "order=postcount&direction=desc"));
$i=0;
$i2=1;
//0=USERID:1=USERNAME:2=LEVEL:3=POSTCOUNT:4=CUSTOMTITLE:5=FIELD1:6=FIELD2:7=FIELD3
while($mem[$i]!=""){
	$member=explode(":",$mem[$i]);
	print "<tr><td>$i2.</td><td>$member[1]</td><td>$member[3]</td><td>$member[4]</td></tr>";
	$i=$i+2;
	$i2=$i2+1;
}
print "</table>";

/*
print "<table border=1 cellspacing=0 cellpadding=5><tr><td>#</td><td></td><td>item</tD><td>quantity</td><td>rarity</td></tr>";
//ITEMID:NAME:QUANTITY:RARITY:DEFAULTPRICE:PICTURE
$safe_list = explode("\n", club_api("safe_list"));
$i=0;
$i2=1;
while($safe_list[$i]!=""){
	$safe=explode(":",$safe_list[$i]);
	print "<tr><tD>$i2.</td><td><img src=\"http://images.creatureworld.net/items/$safe[5]\"></td><td>$safe[1]</td><td>$safe[2]</td><td>$safe[3]</td></tr>";
	$i=$i+2;
	$i2=$i2+1;
}
print "</table>";
*/