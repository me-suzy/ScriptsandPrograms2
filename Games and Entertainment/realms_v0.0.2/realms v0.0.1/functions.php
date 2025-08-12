<?php
function clanlist($player)
{
  global $indent;
  $res = mysql_query("SELECT * FROM `users` WHERE `under`='$player'");

  while($clanho = mysql_fetch_array($res))
  {
  if(!$indent){
    print"+";
  }else{
    print"-";
  }

    $indented = str_repeat("&nbsp;&nbsp;&nbsp;", $indent);
    print"$indented $clanho[user]<br>";
    ++$indent;
    clanlist($clanho[id]);
    --$indent;
    if(!$indent){
    print"<BR>";
    }
  }
}

////////////////////////////////////
//clanlist(player)
////////////////////////////////////


function clanslist($player, $id)
{
  global $indent;

  $res = mysql_query("SELECT * FROM `users` WHERE `under`='$player' order by numunder desc");


$stat = mysql_fetch_array(mysql_query("select * from users where id='$id'"));
$numleader = mysql_num_rows(mysql_query("SELECT * FROM `users` WHERE `under`='leader'"));

  while($clanho = mysql_fetch_array($res))
  {



  if(!$indent){
           print"<hr width=50% align=left>";
          }

    $indented = str_repeat("&nbsp;&nbsp;-&nbsp;&nbsp;", $indent);
    print"<br>&nbsp;&nbsp;-&nbsp;&nbsp; $indented &nbsp; $clanho[user]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

    if($clanho[id]!=$stat[id]&&$clanho[id]!=$stat[under]){

$master = mysql_fetch_array(mysql_query("select * from users where id='$clanho[under]'"));
if(!$master[id]){
       $master[under]="leader";
        }

                    if($clanho[under]!="no leader"&&$numleader<1){
                    print"can't join";
                    }elseif($master[under]!="no leader"&&$master[under]!="leader"){
                    print"can't join";
                    }else{
                    print"<a href=$GAME_SELF?p=clans&join=$clanho[id]>Serve this player</a>";
                    }



    }elseif($clanho[id]==$stat[under]){
            print"<a href=$GAME_SELF?p=clans&join=quit>Leave this player</a>";
            }else{
            print"can't join";
            }

    print"<br>";
    ++$indent;
    clanslist($clanho[id], $id);
    --$indent;

  }

}

////////////////////////////////////
//clanlist(player)
////////////////////////////////////


function findStr($search, $target) {
   $matches = 0;
   $search = strtolower($search);
   $target = strtolower($target);
   $output = "";
   // Create the "search" array, which holds all our search terms
   $search = explode("*",$search); // You could change this to: '$search = explode(" ",$search);' if you wanted your search terms to be split by a space.
   $pos = 0;
   for ($i=0; $i<count($search); $i++) {
       // Check if the current search term is in our target
       if (strpos($target, $search[$i], $pos) != '' && strlen($search[$i])>0) {
           $pos = strpos($target, $search[$i], $pos);
           $matches++;
       }
       if (strlen($search[$i])<1) {
           $matches++;
       }
   }
   if ($matches == count($search)) {
       return true;
   } else {
       return false;
   }
}

////////////////////////////////////
//findStr("search","in this");
////////////////////////////////////




function getstim($wstat,$level,$race){
$stimwhat=mysql_fetch_array(mysql_query("select * from stimit where race='$race'"));
if($stimwhat[$wstat]==0){
$stimwhat=mysql_fetch_array(mysql_query("select * from stimit where race='human'"));
}

if($wstat==bank){
$extra=$stimwhat[bankextra];
}elseif($wstat==expn){
$extra=$stimwhat[expnextra];
}else{
$extra=1;
}

        $stim[$wstat]=pow($level,$stimwhat[$wstat]);
        $stim[$wstat]=$stim[$wstat]*$extra;
        $stim[$wstat]=$stim[$wstat]+1;
        $stim[$wstat]=ceil($stim[$wstat]);

        return $stim[$wstat];
}

////////////////////////////////////
//getstim(whatstat,$level,$race)
////////////////////////////////////




function autolink( &$text, $target='_blank', $nofollow=true )
{
  $urls  =  _autolink_find_URLS( $text );
  if( !empty($urls) ) // i.e. there were some URLS found in the text
  {
    array_walk( $urls, '_autolink_create_html_tags', array('target'=>$target, 'nofollow'=>$nofollow) );
    $text  =  strtr( $text, $urls );
  }
}

function _autolink_find_URLS( $text )
{
  $scheme         =       '(http:\/\/|https:\/\/)';
  $www            =       'www\.';
  $ip             =       '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
  $subdomain      =       '[-a-z0-9_]+\.';
  $name           =       '[a-z][-a-z0-9]+\.';
  $tld            =       '[a-z]+(\.[a-z]{2,2})?';
  $the_rest       =       '\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1}';
  $pattern        =       "$scheme?(?(1)($ip|($subdomain)?$name$tld)|($www$name$tld))$the_rest";

  $pattern        =       '/'.$pattern.'/is';
  $c              =       preg_match_all( $pattern, $text, $m );
  unset( $text, $scheme, $www, $ip, $subdomain, $name, $tld, $the_rest, $pattern );
  if( $c )
  {
    return( array_flip($m[0]) );
  }
  return( array() );
}

function _autolink_create_html_tags( &$value, $key, $other=null )
{
  $target = $nofollow = null;
  if( is_array($other) )
  {
    $target      =  ( $other['target']   ? " target=\"$other[target]\"" : null );
    $nofollow    =  ( $other['nofollow'] ? ' rel="nofollow"'            : null );
  }
  $value = "<a href=\"$key\"$target$nofollow>$key</a>";
}





function rand_pass() {
  $array = array(
                 "ap","dus","tin","rog","sti","rev","pik","sty","lev","qot","rel","vid",
                 "kro","xo","pro","wia","axi","jer","foh","mu","ya","zol","gu","pli","cra",
                 "den","bi","sat","ry","qui","wip","fla","gro","tav","peh","gil","lot",
                 "kal","zan","noc","bat","tev","lun","pal","hom","cun","wos","vox"
                 );
  $num_letters = 8;
  $uppercased = 3;
  mt_srand ((double)microtime()*1000000);
  for($i=0; $i<$num_letters; $i++)
    $pass .= $array[mt_rand(0, (count($array) - 1))];
  for($i=1; $i<strlen($pass); $i++) {
    if(substr($pass, $i, 1) == substr($pass, $i-1, 1))
      $pass = substr($pass, 0, $i) . substr($pass, $i+1);
  }
  for($i=0; $i<strlen($pass); $i++) {
    if(mt_rand(0, $uppercased) == 0)
      $pass = substr($pass,0,$i) . strtoupper(substr($pass, $i,1)) . substr($pass, $i+1);
  }
  $pass = substr($pass, 0, $num_letters);
  return $pass;
}





function geticons($itemid,$itemicons2,$itemicon_def2,$aorb){
        //SO.. if you have just an item id, call as
        //geticons($item[id],0,0)
        //but, if you have icon and icon_def sets to show as pics, like 0,0,1,0,2,4, call as
        //geticons("0","$icons","$icon_def")
        $icons[0]=stab;
        $icons[1]=slash;
        $icons[2]=arrow;
        $icons[3]=fire;
        $icons[4]=water;
        $icons[5]=lightning;
        if(!$itemicons2&&!$itemicon_def2){
                $item=mysql_fetch_array(mysql_query("select * from items where id='$itemid' limit 1"));
                $itemicons2=$item[icons];
                $itemicon_def2=$item[icon_def];
        }
        $itemicons=explode(",",$itemicons2);
        $itemicon_def=explode(",",$itemicon_def2);
        $returnstrA="";//attack icons
        $returnstrB="";//def icons
        $returnstrC="";//heal/drain
        $returnstr="";
                $highrange=array(0,0,0,0,0,0);
                $highrange_def=array(0,0,0,0,0,0);
        $i=0;
        while($i<=5){
                        if(strpos($itemicons[$i],"-")>0){
                                $iconrange=explode("-",$itemicons[$i]);
                                $highrange[$i]=$iconrange[1]-$iconrange[0];
                                $countdown[$i]=1;
                                while($countdown[$i]<=$iconrange[0]){
                                                $returnstrA.="<img src=/img/icon_"."$icons[$i]".".gif>";
                                                $countdown[$i]=$countdown[$i]+1;
                                }
                                                }elseif(strpos($itemicons[$i],"%")>0){
                                                        $returnstrA.=" ($itemicons[$i]<img src=/img/icon_"."$icons[$i]".".gif>) ";
                        }elseif($itemicons[$i]>0){
                                        $countdown[$i]=1;
                                        while($countdown[$i]<=$itemicons[$i]){
                                                        $returnstrA.="<img src=/img/icon_"."$icons[$i]".".gif>";
                                                        $countdown[$i]=$countdown[$i]+1;
                                        }
                                        $highrange[$i]=0;
                        }

                        if(strpos($itemicon_def[$i],"-")>0){
                                $iconrange=explode("-",$itemicon_def[$i]);
                                $highrange_def[$i]=$iconrange[1]-$iconrange[0];
                                $countdown2[$i]=1;
                                while($countdown2[$i]<=$itemicon_def[$i]){
                                        $returnstrB.="<img src=/img/icon_"."$icons[$i]"."_def.gif>";
                                        $countdown2[$i]=$countdown2[$i]+1;
                                }
                        }elseif($itemicon_def[$i]=="all"){
                                $returnstrB.=" (ALL<img src=/img/icon_"."$icons[$i]"."_def.gif>) ";
                                $highrange_def[$i]=0;
                        }elseif($itemicon_def[$i]>0){
                                $countdown2[$i]=1;
                                while($countdown2[$i]<=$itemicon_def[$i]){
                                        $returnstrB.="<img src=/img/icon_"."$icons[$i]"."_def.gif>";
                                        $countdown2[$i]=$countdown2[$i]+1;
                                }
                                $highrange_def[$i]=0;
                        }
                        $i=$i+1;
        }
                if(array_sum($highrange)>0){
                        $returnstrA.=" + ";
                        $i=0;
                        while($i<=5){
                                $countdown[$i]=1;
                                while($countdown[$i]<=$highrange[$i]){
                                        $returnstrA.="<img src=/img/icon_"."$icons[$i]".".gif>";
                                        $countdown[$i]=$countdown[$i]+1;
                                }
                                $i=$i+1;
                        }
                        $returnstrA.="";
                }
                if(array_sum($highrange_def)>0){
                        $returnstrB.=" + ";
                        $i=0;
                        while($i<=5){
                                $countdown2[$i]=1;
                                while($countdown2[$i]<=$highrange_def[$i]){
                                        $returnstrB.="<img src=/img/icon_"."$icons[$i]"."_def.gif>";
                                        $countdown2[$i]=$countdown2[$i]+1;
                                }
                                $i=$i+1;
                        }
                        $returnstrB.="";
                }
                if($item[heal_min]<0){
                                        if(strpos($item[heal_min],"%")>0){
                                                $putpercent1=1;
                                        }
                                        if(strpos($item[heal_max],"%")>0){
                                                $putpercent2=1;
                                        }
                        $healb=$item[heal_max];
                        if($healb>0){
                                $healb=0;
            }
            $healb=abs($healb);
            $heala=abs($item[heal_min]);
            $returnstrC.="<br><img src=img/icon_greenheart.gif>$healb";
                        if($putpercent2==1){
                                $returnstrC.="%";
                        }
                        $returnstrC.="-$heala";
                        if($putpercent1==1){
                                $returnstrC.="%";
                        }
        }
        if($item[heal_max]>0){
                        if(strpos($item[heal_min],"%")>0){
                                $putpercent1=1;
                        }
                        if(strpos($item[heal_max],"%")>0){
                                $putpercent2=1;
                        }
            $healb=$item[heal_min];
            if($healb<0){
                                $healb=0;
            }
            $heala=abs($item[heal_max]);
            $returnstrC.="<br><img src=img/icon_heart.gif>$healb";
                        if($putpercent2==1){
                                $returnstrC.="%";
                        }
                        $returnstrC.="-$heala";
                        if($putpercent1==1){
                                $returnstrC.="%";
                        }
        }
        if($aorb=="A"){
                        $returnstr=$returnstrA;
        }elseif($aorb=="B"){
                        $returnstr=$returnstrB;
        }elseif($aorb=="C"){
                        $returnstr=$returnstrC;
        }else{
                        $returnstr="$returnstrA<br>$returnstrB<br>$returnstrC";
        }
        return $returnstr;
}




function maxstatbar($checkstat,$id){
$stat = mysql_fetch_array(mysql_query("select * from characters where id='$id'"));
if($checkstat=="hp"){
$checkit=$stat[hp];
$checkmax=$stat[max_hp];
}elseif($checkstat=="energy"){
$checkit=$stat[energy];
$checkmax=$stat[max_energy];
}elseif($checkstat=="mana"){
$checkit=$stat[mana];
$checkmax=$stat[max_mana];
}else{
$checkstat="hp";
$checkit=$stat[hp];
$checkmax=$stat[max_hp];
}
$pct=100*($checkit/$checkmax);
$pct = round($pct,"0");
if($pct>100){
$pct=100;
}
if($pct<0){
$pct=0;
}
$opct=100-$pct;
$nm = $checkit-$checkmax;
$rtrn="<table width=\"160\"><tr><td width=\"60\"><!-- $checkstat = $checkit / $checkmax -->
$checkstat: </td><td width=\"100\"><table width=\"100\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" height=5><tr><td width=\"$pct\" class=\"hpbarfull\"><a href=\"javascript:;\" onmouseover=\"return escape('$checkit/$checkmax')\"><img src=\"img/small_blank.gif\" width=\"$pct\" height=8 border=0></a></td><td width=\"$opct\" class=\"barempty\"><a href=\"javascript:;\" onmouseover=\"return escape('$checkit/$checkmax')\"><img src=\"img/small_blank.gif\" width=\"$opct\" height=8 border=0></a></td></tr></table></td></tr></table>";
return $rtrn;
}



function popup($itemid,$itemorskill){
        if($itemorskill=="skill"){
                        $item=mysql_fetch_array(mysql_query("select * from skills where id='$itemid'"));
                }else{
                        $item=mysql_fetch_array(mysql_query("select * from items where id='$itemid'"));
                }
        $niceprice=number_format($item[usershop_price]);
        $niceprice2=number_format($item[price]);
        $seller=mysql_fetch_array(mysql_query("select * from users where id='$item[owner]'"));
        if($item[owner]==-1){
                $seller[username]="NPC";
        }
        $popupmsg="<b>$item[name]</b>";
                if($item[usershop]==yes || $item[owner]==-1){
                        $popupmsg.="<br><font size=1>seller:</font> $seller[username]";
                        if($item[owner]==-1){
                                        $popupmsg.="<br>$niceprice2 <font size=1>cash</font>";
                        }else{
                                        $popupmsg.="<br>$niceprice <font size=1>cash</font>";
                        }
                }
                if($item[type]=="weapon"){
                        $geticonsa=geticons($item[id],0,0,"A");
                        $geticonsb=geticons($item[id],0,0,"B");
                        $geticonsc=geticons($item[id],0,0,"C");
                        if($geticonsa){
                                $popupmsg.="<br>$geticonsa";
                        }
                        if($geticonsb){
                                $popupmsg.="<br>$geticonsb";
                        }
                        if($geticonsc){
                                $popupmsg.="<br>$geticonsc";
                        }
                }elseif($itemorskill=="skill"){
                                        $geticons=geticons("0","$item[icons]","$item[icon_def]");
                                        $popupmsg.="<br>$geticons";
                                }
                if($item[type]==useable){
                        $popupmsg.="<br>+$item[effect_power] $item[effect]";
                }

        return $popupmsg;
}
//////////
//popup($item[id])
//////////


function usersonline($minutes,$checkwhen){
if(!$minutes){
$minutes=10;
}
$seconds = $minutes * 60;

$tpsel = mysql_query("select * from users where activechar>0 order by lastseen desc");

$ctime = time();

while ($pl = mysql_fetch_array($tpsel)) {
        $span = ($ctime - $pl[lastseen]);
        if ($span <= $seconds&&$checkwhen=="on") {
                $charpsel = mysql_fetch_array(mysql_query("select * from characters where id=$pl[activechar] and owner=$pl[id]"));
                if ($pl[position] == Admin) {
                        $ton = "$ton <font class=admin>[@<A href=\"$GAME_SELF?p=view&amp;view=$pl[id]\">$pl[username]</a>&nbsp;($charpsel[name])]&nbsp;&nbsp;</font>";
                } elseif ($pl[position] == Moderator) {
                        $ton = "$ton <font class=mod>[%<A href=\"$GAME_SELF?p=view&amp;view=$pl[id]\">$pl[username]</a>&nbsp;($charpsel[name])]&nbsp;&nbsp;</font>";
                } else {
                        $ton = "$ton [<A href=\"$GAME_SELF?p=view&amp;view=$pl[id]\">$pl[username]</a>&nbsp;($charpsel[name])]&nbsp;&nbsp; ";
                }
        }elseif($span >= $seconds&&$checkwhen=="off"){
        $charpsel = mysql_fetch_array(mysql_query("select * from characters where id=$pl[activechar] and owner=$pl[id]"));
        $ton = "$ton [<A href=\"$GAME_SELF?p=view&amp;view=$pl[id]\">$pl[username]</a>&nbsp;($charpsel[name])]&nbsp;&nbsp; ";
        }
}
return $ton;

}

function additem($itemid,$ownerid){
        $item=mysql_fetch_array(mysql_query("select * from items where owner='0' and id='$itemid' limit 1"));
        mysql_query("INSERT INTO `items` (`name` , `type` , `owner` , `image` , `price` , `icons` , `icon_def` , `heal_min`, `heal_max` , `rarity` , `phrase`, `phrase2`, `effect`, `effect_power`, `uses`) VALUES ('$item[name]', '$item[type]', '$ownerid', '$item[image]', '$item[price]', '$item[icons]', '$item[icon_def]', '$item[heal_min]', '$item[heal_max]', '$item[rarity]', '$item[phrase]', '$item[phrase2]', '$item[effect]', '$item[effect_power]', '$item[uses]')");
}
//////////
// additem($item[id],$user_to_own_item);
/////////

function addskill($skillname,$ownerid){
        $skill=mysql_fetch_array(mysql_query("select * from skills where owner='0' and name='$skillname' limit 1"));
        mysql_query("INSERT INTO `skills` (`name` , `owner` , `icons` , `icon_def` , `heal_min`, `heal_max`, `uses`) VALUES ('$skill[name]', '$ownerid', '$skill[icons]', '$skill[icon_def]', '$skill[heal_min]', '$skill[heal_max]', '$skill[uses]')");
}
//////////
// addskill($skill[name],$char_to_own_skill);
/////////