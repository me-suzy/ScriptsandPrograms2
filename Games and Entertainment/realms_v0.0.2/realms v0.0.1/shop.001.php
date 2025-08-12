<?php
if(!$shop){
        print "no shop specified";
        include("gamefooter.php");
        exit;
}

if(!$order){
$order=cost;
}

if(!$wayness){
$wayness=asc;
}

if (!$page1 || $page1<1){
$page1=0;
}

if (!$pages2 || $pages2<10){
$pages2=20;
}

        print "Welcome, Welcome, buy all you want, but want all you buy<br><br>";

print "<table><tr>";
print "<td><b><u>";
if($order==agility&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=agility&wayness=desc&page1=$page1&shop=$shop>agl</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=agility&page1=$page1&shop=$shop>agl</a>";
}

print"</td><td><b><u>";

if($order==defense&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=defense&wayness=desc&page1=$page1&shop=$shop>def</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=defense&page1=$page1&shop=$shop>def</a>";
}

print"</td><td><b><u>";

if($order==offense&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=offense&wayness=desc&page1=$page1&shop=$shop>att</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=offense&page1=$page1&shop=$shop>att</a>";
}

print"</td><td><b><u>";

if($order==smart&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=smart&wayness=desc&page1=$page1&shop=$shop>brn</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=smart&page1=$page1&shop=$shop>brn</a>";
}

print"</td><td><b><u>";

if($order==luck&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=luck&wayness=desc&page1=$page1&shop=$shop>luck</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=luck&page1=$page1&shop=$shop>luck</a>";
}

print"</td><td><b><u>";

if($order==max_hp&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=max_hp&wayness=desc&page1=$page1&shop=$shop>hp</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=max_hp&page1=$page1&shop=$shop>hp</a>";
}

print"</td><td><b><u>";

if($order==max_energy&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=max_energy&wayness=desc&page1=$page1&shop=$shop>egy</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=max_energy&page1=$page1&shop=$shop>egy</a>";
}

print"</td><td><b><u>";

if($order==max_mana&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=max_mana&wayness=desc&page1=$page1&shop=$shop>mana</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=max_mana&page1=$page1&shop=$shop>mana</a>";
}

print"</td><td><b><u>";

if($order==slots&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=slots&wayness=desc&page1=$page1&shop=$shop>slots</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=slots&page1=$page1&shop=$shop>slots</a>";
}

print"</td><td><b><u>";

if($order==body&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=body&wayness=desc&page1=$page1&shop=$shop>body</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=body&page1=$page1&shop=$shop>body</a>";
}


print"</td>";
print "</tr></table>";

                print "<table border=1>
        <tr>";

       print"<td><b><u>";

if($order==name&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=name&wayness=desc&page1=$page1&shop=$shop>item</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=name&page1=$page1&shop=$shop>item</a>";
}


print"</td>";
print "<td><b><u>";

if($order==stock&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=stock&wayness=desc&page1=$page1&shop=$shop>stock</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=stock&page1=$page1&shop=$shop>stock</a>";
}

print"</td><td><b><u>";

if($order==cost&&$wayness==asc){
print "<a href=$GAME_SELF?p=shop&order=cost&wayness=desc&page1=$page1&shop=$shop>cost</a>";
}else{
print "<a href=$GAME_SELF?p=shop&order=cost&page1=$page1&shop=$shop>cost</a>";
}



print"</td><td><b><u>Options</td></tr>";

if($shop=="admin"&&$stat[rank]=="Admin"){
$wsel = mysql_query("select * from `multi` where `owner`='0' order by $order $wayness limit $page1,$pages2");
}else{
$wsel = mysql_query("select * from `multi` where `owner`='0' AND `shop`='$shop' and `stock`>'0'order by $order $wayness limit $page1,$pages2");
}
while ($ash = mysql_fetch_array($wsel)) {

        $cost=0;
        if($ash[agility]>0){
        $cost=$cost+$ash[agility]*5000;
        }
        if($ash[defense]>0){
        $cost=$cost+$ash[defense]*8000;
          }
        if($ash[offense]>0){
        $cost=$cost+$ash[offense]*7000;
          }
        if($ash[smart]>0){
        $cost=$cost+$ash[smart]*15000;
           }
        if($ash[luck]>0){
        $cost=$cost+$ash[luck]*15000;
          }
        if($ash[max_hp]>0){
        $cost=$cost+$ash[max_hp]*15000;
        }
        if($ash[hp]>0){
        $cost=$cost+$ash[hp]*500;
          }
        if($ash[max_energy]>0){
        $cost=$cost+$ash[max_energy]*20000;
        }
        if($ash[energy]>0){
        $cost=$cost+$ash[energy]*500;
          }
        if($ash[max_mana]>0){
        $cost=$cost+$ash[max_mana]*30000;
          }
        if($ash[slots]>0){
        $cost=$cost+$ash[slots]*1000;
          }

          $cost=$cost+$ash[costchange];

        $cost=floor($cost);

        if($cost<0){
                         $cost=$cost-$cost-$cost;
        }

        if($ash[cost]!=$cost){
                          mysql_query("update multi set cost=$cost where id=$ash[id]");
        }


if($ash[stock]>$ash[max_stock]){
          mysql_query("update multi set stock=max_stock where id=$ash[id]");
}
                if($ash[stock]>0||$stat[rank]==Admin){
                                        $item=mysql_fetch_array(mysql_query("select * from multi where id='$ash[id]'"));
                                        $popupmsg="<b>$item[name]</b><br>$cost <font size=1>credits</font>";
                        if($item[offense]!=0){
                                $popupmsg.="<br>OF: $item[offense]";
                        }
                        if($item[defense]!=0){
                                $popupmsg.="<br>DEF: $item[defense]";
                        }
                        if($item[agility]!=0){
                                $popupmsg.="<br>AG: $item[agility]";
                        }
                        if($item[smart]!=0){
                                $popupmsg.="<br>Smart: $item[smart]";
                        }
                        if($item[luck]!=0){
                                $popupmsg.="<br>Luck: $item[luck]";
                        }
                        if($item[max_hp]!=0){
                                $popupmsg.="<br>HP: $item[max_hp]";
                        }
                        if($item[max_energy]!=0){
                                $popupmsg.="<br>Energy: $item[max_energy]";
                        }
                        if($item[max_mana]!=0){
                                $popupmsg.="<br>Mana: $item[max_mana]";
                        }
                        $popupmsg.="<br>Body: $item[body]";
                                        print "<tr><td><a href=$PHP_SELF?p=shop&shop=$shop onmouseover=\"return escape('$popupmsg')\">$ash[name]</a></td>
<td>$ash[stock]</td>
                <td>$cost</td><td>- <A href=$GAME_SELF?p=shop&buy=$ash[id]&shop=$shop>Buy</a>";

if($stat[rank]==Admin){
print"- <A href=$GAME_SELF?p=shop&buy=$ash[id]&shop=$shop&dowhat=iamgodlol>TAKE</a>";
}
print"</td></tr>";
                                }
        }



      print"</table>";
if ($buy) {

if($stat[rank]=="Admin"){
$aby = mysql_fetch_array(mysql_query("select * from multi where id=$buy"));
$ash = mysql_fetch_array(mysql_query("select * from multi where id=$buy"));
}else{
$aby = mysql_fetch_array(mysql_query("select * from multi where id=$buy and shop='$shop'"));
$ash = mysql_fetch_array(mysql_query("select * from multi where id=$buy and shop='$shop'"));
}


$cost=0;
if($ash[agility]>0){
$cost=$cost+$ash[agility]*5000;
}
if($ash[defense]>0){
$cost=$cost+$ash[defense]*8000;
  }
if($ash[offense]>0){
$cost=$cost+$ash[offense]*7000;
  }
if($ash[smart]>0){
$cost=$cost+$ash[smart]*15000;
   }
if($ash[luck]>0){
$cost=$cost+$ash[luck]*15000;
  }
        if($ash[hp]>0){
        $cost=$cost+$ash[hp]*500;
          }
        if($ash[max_energy]>0){
        $cost=$cost+$ash[max_energy]*20000;
        }
        if($ash[energy]>0){
        $cost=$cost+$ash[energy]*500;
          }
if($ash[max_energy]>0){
$cost=$cost+$ash[max_energy]*20000;
  }
if($ash[max_mana]>0){
$cost=$cost+$ash[max_mana]*30000;
  }
if($ash[slots]>0){
$cost=$cost+$ash[slots]*1000;
  }

  $cost=$cost+$ash[costchange];

$cost=floor($cost);

if($cost<0){
         $cost=$cost-$cost-$cost;
}

                if (empty ($aby[id])) {
                print "No such item.";
                include("gamefooter.php");
                exit;
                }
                if ($aby[owner] != 0) {
                print "Err, someone already owns that.... stop playing with the URL. ";
                include("gamefooter.php");
                exit;
                }
        if($stat[rank]==Admin&&$dowhat=="iamgodlol"){
                }else{
                        if ($aby[stock] <= 0) {
                print "Err, we are out of stock of that item. ";
                include("gamefooter.php");
                exit;
                        }

                        if ($cost > $stat[credits]) {
                print "You can't afford that! ";
                include("gamefooter.php");
                exit;
                        }
        }
        $newcost = ceil($cost * 0.2);

        mysql_query("insert into multi (owner, name,body ,agility,defense,offense,smart,luck,max_hp,max_energy,max_mana,slots,cost) values('$stat[id]','$aby[name]','$aby[body]','$aby[agility]','$aby[defense]','$aby[offense]','$aby[smart]','$aby[luck]','$aby[max_hp]','$aby[max_energy]','$aby[max_mana]','$aby[slots]','$newcost')") or die("<br>Could not add weapon.");
        print "You paid <b>$cost</b> credits for a $aby[name]";
        if($stat[rank]==Admin&&$dowhat=="iamgodlol"){
                        print "<br>you sly devil you<br>";
                }else{
                        mysql_query("update players set credits=credits-$cost where id=$stat[id]");
                        if($stat[rank]!=guest){
                                mysql_query("update multi set stock=stock-1 where id=$aby[id]");
                        }
                }
}
         print"<br>";

if (!$page1 || $page1<1){
$page1=0;
}
$page2=$page1+$pages2;
$page3=($page2+$pages2);
$page0=($page1-$pages2);
print"<center>
<a href=$GAME_SELF?p=shop&page1=$page0&order=$order&wayness=$wayness&shop=$shop>
(prev page)</a> - $page1 to $page2 -
<a href=$GAME_SELF?p=shop&page1=$page2&order=$order&wayness=$wayness&shop=$shop>
(next page)</a></center><br><br> ";


?>