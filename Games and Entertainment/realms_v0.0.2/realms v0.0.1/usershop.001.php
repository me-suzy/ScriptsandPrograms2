<?php
/*
To avoid confusion...

Item ownership is based on the user account, not character

Buying things uses 'cash' of the active character, and puts money into the bank of the account that owned the item bought -- so money is going from table 'characters' to table 'users'. The bank script will convert 'cash' that a character holds to 'bank' money on the account that owns the character (deposit), and 'bank' to 'cash' (withdraw)


Event logs are based on user account

*/

print "
<head>
";

?>
<script type="text/javascript">
<?php
include("wz_tooltip.js");
?>
</script>
</head>
<?php
if($user[username]==guest){
        print "guests cannot do this";
        include("gamefooter.php");
        exit;
}
if(!$act){
        $act=myshop;
}
$updateprices=$_POST['updateprices'];
if($updateprices==yes){
        $updsel=mysql_query("select * from items where usershop='yes' and owner='$user[id]'");
        while($upd=mysql_fetch_array($updsel)){
                $price=$_POST['price'];
                $check=$_POST['check'];
                //print "$upd[name] id$upd[id] oldprice $upd[usershop_price]";
                $newprice=$price[$upd[id]];
                if($newprice!=$upd[usershop_price]){
                        $newprice=strip_tags($newprice);
                        $newprice=str_replace(",","",$newprice);
                        $newprice=str_replace(" ","",$newprice);
                        $newprice=str_replace("'","",$newprice);
                        $newprice=str_replace("where","",$newprice);
                        $newprice=str_replace("and","",$newprice);
                        $newprice=str_replace("union","",$newprice); //for security
                        //$minprice=$upd[cost]*3.75;
                        //if($newprice<$minprice&&$newprice>0){
                        //        $newprice=$minprice;
                        //}
                        if($newprice<=0){
                                $newprice=0;
                        }
                        if($check[$upd[id]]==1){
                                mysql_query("update items set usershop_price='$newprice' where name='$upd[name]' and owner='$user[id]'");
                        }
                        //print " newprice $newprice<br>";
                }
        }
        print "<b>Prices Updated</b><br>";
}
if($remove || $remove2){
        if($remove>0){
                $rem=mysql_fetch_array(mysql_query("select * from items where id='$remove'"));
        }elseif($remove2>0){
                $rem=mysql_fetch_array(mysql_query("select * from items where id='$remove2'"));
        }
        if($rem[owner]!=$user[id]){
                print "not your item";
        }elseif($rem[usershop]!="yes"){
                print "item not in shop";
        }else{
                if($remove>0){
                        mysql_query("update items set usershop='no' where id='$rem[id]'");
                        mysql_query("update items set usershop_price='0' where id='$rem[id]'");
                }elseif($remove2>0){
                        mysql_query("update items set usershop='no' where name='$rem[name]' and owner='$user[id]'");
                        mysql_query("update items set usershop_price='0' where name='$rem[name]' and owner='$user[id]'");
                }
        }
}
if($buyi){
        $buy=mysql_fetch_array(mysql_query("select * from items where id='$buyi'"));
        if($buy[usershop]!="yes"){
                print "item is not in a shop";
        }elseif($buy[usershop_price]<=0){
                print "item cannot be bought: price is 0";
        }elseif($buy[usershop_price]>$stat[cash]){
                print "you don't have enough cash to buy";
        }else{
                mysql_query("update items set usershop='no' where id='$buy[id]'");
                //$owner=mysql_fetch_array(mysql_query("select * from users where id='$buy[owner]'"));
                mysql_query("update characters set cash=cash-$buy[usershop_price] where id='$stat[id]'");
                mysql_query("update users set bank=bank+$buy[usershop_price] where id='$buy[owner]'");
                mysql_query("update items set owner='$user[id]' where id='$buy[id]'");
                mysql_query("insert into `log` (owner,log) values('$buy[owner]','$stat[name] bought the $buy[name] from your shop for $buy[usershop_price] cash')");
                print "<b>You bought $buy[name] for $buy[usershop_price] cash</b><br>";
                mysql_query("update items set usershop_price='0' where id='$buy[id]'");
        }
}
if($act==myshop){
        print "
        <script type=\"text/javascript\">
        var linkset3=new Array()
        </script>
        ";
        $i=0;
        print "<center><b>Your Shop</b></center>";
        print "<form method=post action=$PHP_SELF?p=usershop><INPUT TYPE=hidden name=updateprices value=yes>";
        print "<table border=1 cellspacing=2 width=70%><tr><td colspan=2>item</td><td width=\"2%\">price</td><!--<td>minimum<br>price</td>--><td>remove</td></tr>";
        $olditemname="asdgqgimpossible";
        $itemsel=mysql_query("select * from items where usershop='yes' and owner='$user[id]' order by name asc");
        while($item=mysql_fetch_array($itemsel)){
                $popupmsg=popup($item[id],"item");
				$popupmsg.="<br>NPC Price: <b>$item[price]</b>";
                //$minprice=$item[cost]*3.75;
                if($item[name]!=$olditemname){
                        $itemnum=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner='$user[id]' and usershop='yes'"));
                        $niceitemname=str_replace(" ","_",$item[name]);
                        print "<tr><td><a href=\"$PHP_SELF?p=usershop&act=search&itemname=$niceitemname&step=2\" onMouseover=\"return escape('$popupmsg')\">$item[name]</a></td><td>x$itemnum</td><td><INPUT TYPE=text NAME=\"price[$item[id]]\" value=$item[usershop_price] size=16><INPUT TYPE=hidden NAME=\"check[$item[id]]\" value=1></td><!--<td>$minprice</td>--><td><a href=$PHP_SELF?p=usershop&remove=$item[id]>x1</a> :: <a href=$PHP_SELF?p=usershop&remove2=$item[id]>all</a></td></tr>";
                }
                /*else{
                        print "<INPUT TYPE=hidden NAME=\"price[$item[id]]\" value=$item[usershop_price]><INPUT TYPE=hidden NAME=\"hidden[$item[id]]\" value=yes>";
                }*/
                $olditemname=$item[name];
        }
        print "</table>";
        print "<br><INPUT TYPE=submit value=\"Update Prices\"></form>";
}
if($act==shop){
        $owner=mysql_fetch_array(mysql_query("select * from users where id='$ownerid'"));
        print "<center><b>$owner[username]'s shop</b></center>";
        $tdwidth=150;
		$tdheight=100;
		print "<table border=0 cellspacing=2 cellpadding=2><tr><td height=$tdheight width=$tdwidth>";
		$jump=6;
		$jump2=$jump;
		$jumpon=1;
		$olditemname="";
		$lastitem=mysql_num_rows(mysql_query("select * from items where owner='$owner[id]'"));
        $itemsel=mysql_query("select * from items where owner='$owner[id]' and usershop='yes' and usershop_price>'0' order by name,usershop_price asc");
        $olditemname="warqwgqrgasgasgimpossible";
        while($item=mysql_fetch_array($itemsel)){
				if($item[name]!=$olditemname){
					$quan=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner='$owner[id]'"));
					print "<table border=1 cellpadding=2 cellspacing=0";
					if($herefor==$item[id]){
						print " bgcolor=\"#D2E4D1\"";
					}
					print "><tr><td height=$tdheight width=$tdwidth>";
					print "<div align=center>";
					$popupmsg=popup($item[id],"item");
					$niceitemname=str_replace(" ","_",$item[name]);
					print "<a href=\"$PHP_SELF?p=usershop&act=search&itemname=$niceitemname&step=2\" onMouseover=\"return escape('$popupmsg')\"><b>$item[name]</b></a>";
					print "<br><font size=1>$quan in stock</font>";
					$niceprice=number_format($item[price]);
					print "<br>Cost: $niceprice";
					print "<br><a href=\"$PHP_SELF?p=usershop&act=shop&ownerid=$owner[id]&buyi=$item[id]\">BUY</a>";

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
			$olditemname=$item[name];
        }
        print "</table>";
}
if($act==search){
        if(!$step){
                $step=1;
        }
        if($step==1){
                print "<center><b>Item Search</b></center>";
                print "<form method=post action=$PHP_SELF?p=usershop&act=search&step=2>";
                print "<table border=0>";
                print "<tr><td>Item name?</td><td> <INPUT TYPE=text NAME=itemname> </td></tr>";
                /*print "<tr><td>Body type?</td><td> <select name=bodytype><option>Any</option><option value=main_hand>Main Hand</option><option value=other_hand>Other Hand</option><option value=head>Head</option><option value=neck>Neck</option><option value=top>Top</option><option value=bottom>Bottom</option><option value=feet>Feet</option><option>finger1</option><option>finger2</option><option>finger3</option><option>finger4</option><option>finger5</option></select></td></tr>";
                print "<tr><td>Stats?</td><td> <select name=highstat><option>agility</option><option>offense</option><option>defence</option><option>smart</option><option>luck</option><option>max_hp</option><option>max_energy</option><option>max_mana</option></select> at least <INPUT TYPE=text NAME=highstatnum size=7 value=0></td></tr>";*/
                print "<tr><td colspan=2 valign=center><INPUT TYPE=submit value=Search></td></tr></table></form>";
        }
        if($step==2){
                $itemname=str_replace("_"," ",$itemname);
                print "<b>Results for <b>$itemname</b>:</b><br><table border=1 cellspacing=2 width=70%><tr><td>seller</td><td>item</td><td>#</td><td>price</td></tr>";
                $itemname=str_replace("'","wb45b7w4b68ERROR",$itemname);
              
                $strlen=strlen($itemname);
                
				if($itemname!=""){
					//this is to show NPC items in the shop search
					$searchsel=mysql_query("select * from items where LEFT(name,$strlen)='$itemname' and price>'0' and owner='-1' order by price,name asc");
					$olditemname="sgfqwegehwthe";
					$found=0;
					while($search=mysql_fetch_array($searchsel)){
							$item=mysql_fetch_array(mysql_query("select * from items where id='$search[id]'"));
							$niceprice=number_format($item[price]);
							$popupmsg=popup($item[id],"item");
							if($item[name]!=$olditemname){
									$itemnum=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner='$item[owner]' and price='$item[price]'"));
									print "<tr><td>NPC Shops</td><td> <div align=right><a href=\"$PHP_SELF?p=shops\"  onMouseover=\"return escape('$popupmsg')\">$search[name]</a></div></td><td>x$itemnum</td> <td>$niceprice cash</td></tr>";
									$found=$found+1;
							}
							$olditemname=$item[name];
					}
					if($found>0){
						print "<tr><td colspan=4 bgcolor=\"black\">&nbsp; </td></tr>";
					}
				}

				//and this shows the player owned items
				$searchsel=mysql_query("select * from items where LEFT(name,$strlen)='$itemname' and usershop='yes' and usershop_price>'0' order by usershop_price,name,owner asc");
                $olditemname="sgfqwegehwthe";
                $olditemowner="no one dood w3tg3gqerg";
                while($search=mysql_fetch_array($searchsel)){
                        $item=mysql_fetch_array(mysql_query("select * from items where id='$search[id]'"));
                        $niceprice=number_format($item[usershop_price]);
                        $seller=mysql_fetch_array(mysql_query("select * from users where id='$item[owner]'"));
                        $popupmsg=popup($item[id],"item");
                        if($item[name]!=$olditemname){
                                $itemnum=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner='$item[owner]' and usershop_price='$item[usershop_price]' and usershop='yes'"));
                                print "<tr><td><a href=$PHP_SELF?p=view&view=$seller[id]>$seller[username]</a></td><td>  <div align=right><a href=\"$PHP_SELF?p=usershop&act=shop&ownerid=$search[owner]&herefor=$search[id]\"  onMouseover=\"return escape('$popupmsg')\">$search[name]</a></div></td><td>x$itemnum</td><td>$niceprice cash</td></tr>";
                        }elseif($item[owner]!=$olditemowner){
                                $itemnum=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner='$item[owner]' and usershop_price='$item[usershop_price]' and usershop='yes'"));
                                print "<tr><td><a href=$PHP_SELF?p=view&view=$seller[id]>$seller[username]</a></td><td> <div align=right><a href=\"$PHP_SELF?p=usershop&act=shop&ownerid=$seller[id]&herefor=$search[id]\"  onMouseover=\"return escape('$popupmsg')\">$search[name]</a></div></td><td>x$itemnum</td><td>$niceprice cash</td></tr>"; //same thing
                        }
                        $olditemowner=$item[owner];
                        $olditemname=$item[name];
                }
                print "</table>";
        }
}
print "<br><table border=0 width=\"100%\" bgcolor=\"#D8EDD9\"><tr><td align=center width=100%><a href=$PHP_SELF?p=usershop&act=myshop>My Shop</a> | <a href=$PHP_SELF?p=usershop&act=search>Search for an Item</a> | <a href=$PHP_SELF?p=usershop&act=search&step=2>View all Shop Items</a></td></tr></table>";