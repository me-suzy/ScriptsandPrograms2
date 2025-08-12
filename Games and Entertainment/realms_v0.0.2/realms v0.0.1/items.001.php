<?php
function toshop($itemid,$userid){
	$item=mysql_fetch_array(mysql_query("select * from items where id='$itemid' limit 1"));
	if($userid==$item[owner]){
		mysql_query("update items set usershop='yes' where id='$item[id]'");
		$inshop=mysql_fetch_array(mysql_query("select * from items where name='$item[name]' and owner='$item[owner]' and usershop='yes' and usershop_price>'0'"));
		mysql_query("update items set usershop_price='$inshop[usershop_price]' where id='$item[id]'");
	}
}

if($equip || $unequip){
	if($equip){
		$itemid=$equip;
	}elseif($unequip){
		$itemid=$unequip;
	}
	$item=mysql_fetch_array(mysql_query("select * from items where id='$itemid'"));
	$equipped=mysql_num_rows(mysql_query("select * from items where equip='$stat[id]'"));
	$infight=mysql_num_rows(mysql_query("select * from 1p where owner='$stat[id]'"));
	if($item[owner]!=$user[id]){
		print "not your item";
	}elseif($item[equip]!=0&&$equip){
		print "Item is already equipped";
	}elseif($item[equip]==0&&$unequip){
		print "Item is already unequipped";
	}elseif($item[type]!=weapon){
		print "Item is not a weapon";
	}elseif($equipped>=10&&$equip){
		print "You can't have more than 10 weapons equipped";
	}elseif($infight>=1){
		print "You cannot change your weapons while in fight";
	}else{
		if($equip){
			mysql_query("update items set equip='$stat[id]' where id='$item[id]' limit 1");
			print "$stat[name] has equipped <b>$item[name]</b>";
		}elseif($unequip){
			$equipped=mysql_fetch_array(mysql_query("select * from characters where id='$item[equip]' limit 1"));
			mysql_query("update items set equip='0' where id='$item[id]' limit 1");
			print "$equipped[name] has unequipped <b>$item[name]</b>";
		}
	}
	print "<br><br>";
}
if($use){
	$itemid=$use;
	$infight=mysql_num_rows(mysql_query("select * from 1p where owner='$stat[id]'"));
	$item=mysql_fetch_array(mysql_query("select * from items where id='$itemid'"));
	if($item[owner]!=$user[id]){
		print "not your item";
	}elseif($item[equip]!=0){
		print "Item is equipped";
	}elseif($item[type]!=useable){
		print "Item is not a useable item";
	}elseif($infight>0){
		print "You can't use an item while in a fight";
	}else{
		mysql_query("update characters set $item[effect]=$item[effect]+$item[effect_power] where id='$stat[id]'");
		$itemeffect=$item[effect];
		$itemeffectmax="max_"."$itemeffect";
		$stat[$itemeffect]=$stat[$itemeffect]+$item[effect_power];
		print "$stat[name] used the <b>$item[name]</b> for +$item[effect_power] $item[effect].<br>$item[effect] is now at $stat[$itemeffect]/$stat[$itemeffectmax]";
		mysql_query("delete from items where id='$item[id]' limit 1");
	}
	print "<br><br>";
}
if($toshop){
	$item=mysql_fetch_array(mysql_query("select * from items where id='$toshop' limit 1"));
	if($item[owner]!=$user[id]){
		print "not your item";
	}else{
		toshop($item[id],$user[id]);
		print "<b>$item[name]</b> has been added to your shop";
	}
	print "<br><Br>";
}

if($check){
	$item=mysql_fetch_array(mysql_query("select * from items where id='$check'"));
	if($item[owner]!=$user[id]){
		print "not your item";
	}else{
		print "<center><b>$item[name]</b><br>";
		print geticons($item[id],"0","0","");
		print "<br>Rarity: <b>$item[rarity]</b>";
		print "<br>NPC Price: $item[price]";
		print "<br><br>";
		if($item[equip]==0){
			print "<a href=\"$PHP_SELF?p=items&toshop=$item[id]\">Put in Shop</a><br>";
		}
		if($item[equip]==0&&$item[type]==weapon){
			print "<a href=\"$PHP_SELF?p=items&check=$item[id]&equip=$item[id]\">Equip</a>";
		}elseif($item[equip]!=0&&$item[type]==weapon){
			$equipped=mysql_fetch_array(mysql_query("select * from characters where id='$item[equip]' limit 1"));
			if($equipped[id]==$stat[id]){
				print "<b>";
			}else{
				print "<i>";
			}
			print "Equipped to $equipped[name]";
			if($equipped[id]==$stat[id]){
				print "</b>";
			}else{
				print "</i>";
			}
			print "<br><a href=\"$PHP_SELF?p=items&check=$item[id]&unequip=$item[id]\">Unequip</a>";
		}elseif($item[type]==useable){
			print "<br><a href=\"$PHP_SELF?p=items&use=$item[id]\">Use</a>";
		}


		print "</center><br><br><br>";
	}
}

if($act==quickstock){
	$itemsel=mysql_query("select * from items where owner='$user[id]' and usershop!='yes' and equip='0' order by name,id asc");
	if($sentqs==1){
		$shop=$_POST['shop'];
		while($item=mysql_fetch_array($itemsel)){
			if($shop[$item[id]]||$alltoshop==1){
				toshop($item[id],$user[id]);
			}
		}
	}
	
	$tdwidth=180;
	$tdheight=40;
	print "<table border=3 cellspacing=0 cellpadding=0>";
	print "<tr><td width=$tdwidth></td><td>Move to Shop</td></tr>";
	$lastitem=mysql_num_rows($itemsel);
	$onitem=1;
	print "<form method=post action=\"$PHP_SELF?p=items&act=quickstock\">";
	$itemsel=mysql_query("select * from items where owner='$user[id]' and usershop!='yes' and equip='0' order by name,id asc");
	while($item=mysql_fetch_array($itemsel)){
		$popupmsg=popup($item[id],"item");
		$popupmsg.="<br>NPC Price: <b>$item[price]</b>";
		print "<tr><td width=$tdwidth height=$tdheight>";
		print "<div align=left>";

		print "<a href=\"$PHP_SELF?p=items&check=$item[id]\" onMouseover=\"return escape('$popupmsg')\">$item[name]</a>";
		print "</div>";
		print "</td>";
		print "<td><INPUT TYPE=\"checkbox\" NAME=\"shop[$item[id]]\"></td>";
		print "</tr>";
		$onitem=$onitem+1;
	}
	print "<tr><td></td><td><a href=\"$PHP_SELF?p=items&act=quickstock&sentqs=1&alltoshop=1\">Move All To Shop</a></td></tr>";
	print "</table>";
	print "<INPUT TYPE=\"hidden\" name=sentqs value=1><INPUT TYPE=\"submit\" value=\"Submit\"></form><br><br><br>";
	
}

print "<B>Your Items:</b>";
print "<br><br>";
$tdwidth=80;
$tdheight=80;
print "<table border=0 cellspacing=2 cellpadding=2><tr><td height=$tdheight width=$tdwidth>";
$jump=6;
$jump2=$jump;
$jumpon=1;
$itemsel=mysql_query("select * from items where owner='$user[id]' and usershop!='yes' order by equip desc,name asc,id asc");
$lastitem=mysql_num_rows($itemsel);
while($item=mysql_fetch_array($itemsel)){
	print "<table border=1 cellspacing=0 cellpadding=2><tr><td width=$tdwidth height=$tdheight>";
	print "<div align=center>";
	$popupmsg=popup($item[id],"item");
	print "<a href=\"$PHP_SELF?p=items&check=$item[id]\" onMouseover=\"return escape('$popupmsg')\">$item[name]</a>";
	if($item[equip]!=0){
		$equipped=mysql_fetch_array(mysql_query("select * from characters where id='$item[equip]' limit 1"));
		if($equipped[id]==$stat[id]){
			//print "<b>";
		}else{
			print "<i>";
		}
		print "<br><font size=1>Equipped to $equipped[name]</font>";
		if($equipped[id]==$stat[id]){
			//print "</b>";
		}else{
			print "</i>";
		}
	}
	if($item[type]==useable){
		print "<br><br><a href=\"$PHP_SELF?p=items&use=$item[id]\"><font size=1>Use</font></a>";
	}
	print "</div>";
	print "</td></tr></table>";
	if($jumpon==$jump){
		$jump=$jump+$jump2;
		print "</td></tr>";
		if($jumpon!=$lastitem){
			print "<tr><td height=$tdheight width=$tdwidth>";
		}
	}else{
		print "</td>";
		if($jumpon!=$lastitem){
			print "<td height=$tdheight width=$tdwidth>";
		}else{
			print "</tr>";
		}
	}
	$jumpon=$jumpon+1;
}
print "</table>";
print "<br><a href=\"$PHP_SELF?p=items&act=quickstock\">Quick Stock</a>";