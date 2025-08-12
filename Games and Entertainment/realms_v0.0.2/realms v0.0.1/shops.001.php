<?php
$NPCid=-1;
if($buy){
        $item=mysql_fetch_array(mysql_query("select * from items where id='$buy' limit 1"));
        if($item[id]<=0){
                print "Error: item doesnt exist";
        }elseif($item[owner]!=$NPCid){
                print "Error: item must be owned by the NPC";
        }elseif($stat[cash]<$item[price]){
                print "You can't afford it";
        }elseif($user[username]==guest){
                print "guest cannot do this";
        }else{
                mysql_query("update items set owner='$user[id]' where id='$item[id]'");
                mysql_query("update characters set cash=cash-$item[price] where id='$stat[id]'");
                print "You bought the item \"<b>$item[name]</b>\"";
        }
        print "<br><br>";
}


print "<B>Items For Sale</b><br><br>";
$tdwidth=150;
$tdheight=100;
print "<table border=0 cellspacing=2 cellpadding=2><tr><td height=$tdheight width=$tdwidth>";
$jump=6;
$jump2=$jump;
$jumpon=1;
$olditemname="";
$lastitem=mysql_num_rows(mysql_query("select * from items where owner='$NPCid'"));
$itemsel=mysql_query("select * from items where owner='$NPCid' and type!='' and type!='todo' and name!='$olditemname' order by name asc");
while($item=mysql_fetch_array($itemsel)){
        if($item[name]!=$olditemname){
                $quan=mysql_num_rows(mysql_query("select * from items where name='$item[name]' and owner='$NPCid'"));
                print "<table border=1 cellpadding=2 cellspacing=0><tr><td height=$tdheight width=$tdwidth>";
                print "<div align=center>";
				$popupmsg=popup($item[id],"item");
				$niceitemname=str_replace(" ","_",$item[name]);
                print "<a href=\"$PHP_SELF?p=usershop&act=search&itemname=$niceitemname&step=2\" onMouseover=\"return escape('$popupmsg')\"><b>$item[name]</b></a>";
                print "<br><font size=1>$quan in stock</font>";
				$niceprice=number_format($item[price]);
                print "<br>Cost: $niceprice";
                print "<br><a href=$PHP_SELF?p=shops&buy=$item[id]>BUY</a>";

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
print "<br><br><a href=\"$PHP_SELF?p=usershop&act=search&step=2\">Other Peoples Shops</a>";