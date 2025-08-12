<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

// time.php
// $s[daily]=cas denniho jobu; $s[d]=datum kdy se denni job delal (pro predchozi den)
// $s[mini]= mini update - jednou za 3 hodiny;
// $s[lock] zapise se pred zacatkem minirebuildu a denniho jobu - pak se 15 minut nic nezacne
//include('functions.php'); update_sliding_ratios(1);
###############################################################################

function check_if_job() {
global $s;
include("$s[phppath]/data/time.php");
if ( ($s[d]!=(date('j',$s[cas]))) AND ($s[lock]<($s[cas]-300)) )
{ $s[lock] = $s[cas]; save_time_php(); daily_job($s[cas],1); }
}

###############################################################################

function check_mini_job($cas) {
global $s; // musi byt kvuli predani do save_time_php
if (!include('./data/time.php')) include('../data/time.php');
if ( ($s[mini]<($cas-9600)) AND ($s[lock]<($cas-300)) )
{ $s[lock] = $cas; save_time_php();
  day_to_days_all($cas,1,0); days_to_months_all($cas,1,0);
  $s[mini] = $cas; save_time_php(); }
}

###############################################################################

function save_time_php() {
global $s;
if (!$s[daily]) $s[daily] = 0; if (!$s[d]) $s[d] = 0; if (!$s[mini]) $s[mini] = 0; if (!$s[lock]) $s[lock] = 0;
$data = '<?PHP $s[daily]='.$s[daily].';$s[d]='.$s[d].';$s[mini]='.$s[mini].';$s[lock]='.$s[lock].'; ?>';
$x = fopen("$s[phppath]/data/time.php",'w'); fwrite($x,$data); fclose($x);
chmod("$s[phppath]/data/time.php",0666);
}

###############################################################################
###############################################################################
###############################################################################

function daily_job($cas,$return) {
global $s,$m;
// nepoustet jindy nez tesne po pulnoci, jinak bude povazovat dnesni data v day za vcerejsi
dq("delete from $s[pr]ip",0);
day_to_days_all($cas,1,1);
days_to_months_all($cas,1,1);
release_deferred_impressions(1);
if ((day_number(0))==1) update_sliding_ratios(1);
delete_old($cas,1);
$s[daily] = $cas; $s[d] = day_number($cas);
save_time_php();
$info = 'Daily job done';
if ($return) return $info; else $s[info] = $info;
}

###############################################################################

function day_to_days_all($cas,$return,$is_yesterday) {
global $s;
// zkopirovat cisla z day do days$size
// pokud je $is_yesterday, tak jde o soucast daily job => beru vse jako vcerejsi udaje
// jinak se jenom kopiruji dnesni udaje do dnesni kolonky tabulky $days...
if ($is_yesterday) $cas = $cas - 86400;
list($day,$month,$year) = split('-',date('j-n-Y',$cas));
// vymazat den z days$size pokud tam uz jsou pro nektere cleny
for ($size=1;$size<=3;$size++)
dq("delete from $s[pr]days$size where d = '$day' AND m = '$month' AND y = '$year'",0);
// vytahnout a secist vsechno z tabulky day pro kazdeho clena
$q = dq("select number,userid,size,cl_m,cl_w,m0+m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12+m13+m14+m15+m16+m17+m18+m19+m20+m21+m22+m23,w0+w1+w2+w3+w4+w5+w6+w7+w8+w9+w10+w11+w12+w13+w14+w15+w16+w17+w18+w19+w20+w21+w22+w23 from $s[pr]day",0);
while ($r = mysql_fetch_row($q))
{ set_time_limit(30);
  if ( ($r[3]) OR ($r[4]) OR ($r[5]) OR ($r[6]) ) // a vlozit to do days$size jako vcerejsi udaje
  dq("insert into $s[pr]days$r[2] values ('$r[0]','$r[1]','$day','$month','$year','$r[5]','$r[6]','$r[3]','$r[4]')",0);
}
// a vymazat vsechno z day, pokud jde o vcerejsi udaje
if ($is_yesterday) dq("update $s[pr]day set cl_m=0,cl_w=0,m0=0,m1=0,m2=0,m3=0,m4=0,m5=0,m6=0,m7=0,m8=0,m9=0,m10=0,m11=0,m12=0,m13=0,m14=0,m15=0,m16=0,m17=0,m18=0,m19=0,m20=0,m21=0,m22=0,m23=0,w0=0,w1=0,w2=0,w3=0,w4=0,w5=0,w6=0,w7=0,w8=0,w9=0,w10=0,w11=0,w12=0,w13=0,w14=0,w15=0,w16=0,w17=0,w18=0,w19=0,w20=0,w21=0,w22=0,w23=0",0);
$info = 'Day-by-day statistic updated';
if ($return) return $info; else $s[info] .= $info;
}

###############################################################################

function days_to_months_all($cas,$return,$is_yesterday) {
global $s;
// zkopirovat cisla z days$size do months$size
// pokud je $is_yesterday, tak jde o soucast daily job => beru vse jako vcerejsi udaje
if ($is_yesterday) $cas = $cas - 86400;
list($month,$year) = split('-',date('n-Y',$cas));
$sponsors = sponsors_in_array(); // seznam cisel sponsoru v poli
for ($size=1;$size<=3;$size++)
{ // smazat ten mesic z tabulky months$size
  dq("delete from $s[pr]months$size where m = '$month' AND y = '$year'",0);
  // vytahnout (secist) udaje pro cely mesic z tabulky days$size
  $q = dq("select number,userid,sum(i_m),sum(cl_m),sum(i_w),sum(cl_w) from $s[pr]days$size where m = '$month' AND y = '$year' group by number",0);
  while ($r = mysql_fetch_row($q))
  { set_time_limit(30);
    if ( ($r[2]) OR ($r[3]) OR ($r[4]) OR ($r[5]) )
    { if (in_array($r[0],$sponsors)) $sponsor = 1; else $sponsor = 0;
      if ($r[2]) $ratio_m = round(100*($r[3]/$r[2]),2); else $ratio_m = 0;
      if ($r[4]) $ratio_w = round(100*($r[5]/$r[4]),2); else $ratio_w = 0;
      // a vlozit to do tabulky months$size
      dq("insert into $s[pr]months$size values ('$r[0]','$r[1]','$month','$year','$r[2]','$r[3]','$ratio_m','$r[4]','$r[5]','$ratio_w','$sponsor')",0);
    }
  }
}
$info = 'Month-by-month statistic updated';
if ($return) return $info; else $s[info] .= $info;
}

###############################################################################

function delete_old($cas,$return) {
global $s;
$b = dq("select number,user from $s[pr]s_orders where order_time < ($cas - 604800) AND not(paylink='NULL') AND not(paylink='')",0);
while ($c = mysql_fetch_row($b))
{ dq("delete from $s[pr]s_orders where number = '$c[0]'",0);
  $q = dq("select count(*) from $s[pr]s_orders where user = '$c[1]'",0);
  $orders = mysql_fetch_row($q);
  $q = dq("select count(*) from $s[pr]s_orders where user = '$c[1]' and paylink = ''",0);
  $paid = mysql_fetch_row($q);
  dq("update $s[pr]members set s_orders = '$orders[0]', s_paid_ord = '$paid[0]' where number = '$c[1]'",0);
}
$s[no_stop] = 1;
$q = dq("select number from $s[pr]members where date < ($cas - 604800) AND s_orders < 1 AND s_funds < 1 AND sponsor = 1",1);
while ($c = mysql_fetch_assoc($q)) user_delete($c);
$info = 'Unpaid orders older than 7 days deleted, sponsors with no one order who joined more than 7 days ago deleted';
if ($return) return $info; else $s[info] .= $info;
}

###############################################################################

function release_deferred_impressions($return) {
global $s;
$q = dq("select * from $s[pr]wait_imp",0);
while ($r = mysql_fetch_row($q))
{ if ($r[0]==0)
  { dq("update $s[pr]wait_imp set rest=rest-daily where user='0' and size='$r[1]'",0);
    if ($r[2]>$r[3])
    dq("update $s[pr]stats$r[1] set i_free=i_free+$r[3],i_nu=i_nu+$r[3] where sponsor = '0'",0);
    else
    { dq("update $s[pr]stats$r[1] set i_free=i_free+$r[2],i_nu=i_nu+$r[2] where sponsor = '0'",0);
      dq("delete from $s[pr]wait_imp where user='0' and size='$r[1]'",0);
    }
  }
  else
  { dq("update $s[pr]wait_imp set rest=rest-daily where user='$r[0]' and size='$r[1]'",0);
    if ($r[2]>$r[3])
    dq("update $s[pr]stats$r[1] set i_free=i_free+$r[3],i_nu=i_nu+$r[3] where number='$r[0]'",0);
    else
    { dq("update $s[pr]stats$r[1] set i_free=i_free+$r[2],i_nu=i_nu+$r[2] where number='$r[0]'",0);
      dq("delete from $s[pr]wait_imp where user='$r[0]' and size='$r[1]'",0);
    }
  }
}
$info = 'One-day quota of deferred impressions released';
if ($return) return $info; else $s[info] .= $info;
}

###############################################################################

function update_sliding_ratios($return) {
global $s;
if (month_number(0)==1) { $year = year_number(0) - 1; $month = 12; }
else { $year = year_number(0); $month = month_number(0) - 1; }
for ($size=1;$size<=3;$size++)
{ if (!$s["sliding_r$size"]) continue;
  $any_action = 1; set_time_limit(300); unset($min,$max,$ratio);
  $q = dq("select * from $s[pr]ratios where size = '$size'",0);
  while ($r = mysql_fetch_assoc($q)) { $min[] = $r[min]; $max[] = $r[max]; $ratio[] = $r[ratio]; }
  $q = dq("select number,r_m from $s[pr]months$size where m = '$month' AND y = '$year' AND i_m > '99' AND sponsor = '0'",1);
  while ($months = mysql_fetch_row($q))
  { foreach ($min as $k => $v) 
    { if (($months[1]>=$v) AND ($months[1]<=$max[$k]))
      dq("update $s[pr]stats$size set exratio = '$ratio[$k]' where number = '$months[0]' AND no_slide = 0",1);
    }
  }
}
if (!$any_action) return false;
$info = 'Sliding ratios updated';
if ($return) return $info; else $s[info] .= $info;
}

###############################################################################
###############################################################################
###############################################################################


?>