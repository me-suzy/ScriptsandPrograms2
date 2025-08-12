<?
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
if (!$calstyle) $calstyle = "1";
$nbc="EEEEEE";
$abc="E9B4A1";
$tbc="66CCFF";
$day[0]="Sun";
$day[1]="Mon";
$day[2]="Tues";
$day[3]="Wed";
$day[4]="Thurs";
$day[5]="Fri";
$day[6]="Sat";
$mth[1]="January";
$mth[2]="February";
$mth[3]="March";
$mth[4]="April";
$mth[5]="May";
$mth[6]="June";
$mth[7]="July";
$mth[8]="August";
$mth[9]="September";
$mth[10]="October";
$mth[11]="November";
$mth[12]="December";
if (!$daystart) $daystart=0;
if (!$ny) $ny=0;
if (!$nt) $nt=0;
if (!$ctime) $ctime = "-0";
if (!$tw) $tw="450";
if (!$ch) $ch="50";
if (!$algn) $algn="0";
if (!$fsm) $fsm="18";
if (!$fsd) $fsd="9"; 
if (!$fsn) $fsn="11";
if (!$fwm) $fwm="bold"; else $fwm="normal";
if (!$fwd) $fwd="normal"; else $fwd="bold"; 
if (!$fwn) $fwn="normal"; else $fwn="bold"; 
?> 
<style TYPE="text/css">
<!--
.monthyear {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: <?echo$fsm?>px; font-weight: <?echo$fwm?>; color: #000000}
.daynames {  font-family: Arial, Helvetica, sans-serif; font-size: <?echo$fsd?>px; font-weight: <?echo$fwd?>; color: #000000}
.dates {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: <?echo$fsn?>px; font-weight: <?echo$fwn?>; color: #000000}
-->
</style>
<?
$cw=$tw/7;
if ($algn==0) $algn="align='center' valign='middle'";
else $algn="align='right' valign='top'";
$ctime = $ctime*3600;
$tmo = date("m", time()+$ctime);
$tda = date("j", time()+$ctime);
$tyr = date("Y", time()+$ctime);
$tnum = (intval((date ("U", mktime(20,0,0,$tmo,$tda,$tyr))/86400)))-$daystart; 
if (!$mo) $mo=$tmo;
if (!$yr) $yr=$tyr;
$daycount = (intval((date ("U", mktime(20,0,0,$mo,1,$yr))/86400)))-$daystart; 
$mo=intval($mo);
$mn = $mth[$mo];
if ($ny!=1) {$mn = $mn." ".$yr;} 
$sd = date ("w", mktime(0,0,0,$mo,1-$daystart,$yr));
$cd = 1-$sd;
$nd = mktime (0,0,0,$mo+1,0,$yr);
$nd = (strftime ("%d",$nd))+1;
if ($mrks) {
$mrks = explode ("x",$mrks);
$smc = count ($mrks);
$mrke = explode ("x",$mrke);
$emc = count ($mrke);
if ($smc==1) {
$mrks[1]="3000-01-01";
$mrke[1]="3000-01-01";
}
}
$i=0;
while ($i < $smc) {
$mrks[$i] = ereg_replace('-','/', $mrks[$i]);
$mrke[$i] = ereg_replace('-','/', $mrke[$i]);
$start = intval(strtotime ($mrks[$i])/86400)+1;
$end = intval(strtotime ($mrke[$i])/86400)+1;
if (!$mrke[$i]) $end=$start;
if (!$bgc[$start]) {$bgc[$start]=1;} else {$bgc[$start]=4;}
$bgc[$end]=3;
for ($n = ($start+1); $n < $end; $n++) {
$bgc[$n] = 2;}
$i++;
}
?>
<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>View Calendar</strong></font><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong> 
  </strong></font></p>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><table WIDTH="<?echo$tw?>" BORDER="0" CELLSPACING="0" CELLPADDING="2" align="center">
        <tr> 
          <td CLASS="monthyear"> <div ALIGN="center"> <?echo "$mn";?> </div></td>
        </tr>
      </table>
      <table WIDTH="<?echo$tw?>" BORDER="0" CELLSPACING="2" CELLPADDING="1" CLASS="daynames" align="center">
        <tr ALIGN="center"> 
          <?
for ($I=0;$I<7;$I++) {
$dayprint=$daystart+$I;
if ($dayprint>6) $dayprint=$dayprint-7;
echo"<td WIDTH=$cw>$day[$dayprint]</td>";
}
?>
        </tr>
        <?
if ($calstyle==1) { 
for ($i = 1; $i<7; $i++) { 
if ($cd>$nd) break;
?>
        <tr <?echo$algn?> CLASS="dates" height=<?echo$ch?>> 
          <?
for ($prow = 1; $prow<8; $prow++) { 
if ($daycount==$tnum && $nt!="1" && $cd>0) {echo "<td width=$cw bgcolor=$tbc>$cd";
print "<br>";
$mo2 = sprintf("%02s",$mo);

$cd2 = sprintf("%02s",$cd);

  $ddd = "$yr-$mo2-$cd2";

$qq = mysql_query ("SELECT * FROM cnpCalendar

                         WHERE date LIKE '$ddd'
						 AND nl LIKE '$nl'

                       	ORDER BY header

");



if ($row = mysql_fetch_array($qq)) {

$cqq = mysql_num_rows($qq);

if ($cqq != 1){	

print "<b><a href=\"main.php?page=display2&nl=$nl&dayd=$ddd\"> $cqq Events </a></b>";

}

else {

print "<b><a href=\"main.php?page=display2&nl=$nl&dayd=$ddd\"> $cqq Event </a></b>";

}



} 

echo "</td>";$daycount++;$cd++;}
else { ?>
          <td width="<?echo$cw?>"<?if ($cd>0 && $cd<$nd) {echo " bgcolor=";if ($bgc[$daycount]) {echo $abc;} else {echo $nbc;} echo ">";$daycount++;} else {echo ">";} 
if ($cd>0 && $cd<$nd) {print $cd;} 
print "<br>";
$mo2 = sprintf("%02s",$mo);

$cd2 = sprintf("%02s",$cd);

  $ddd = "$yr-$mo2-$cd2";

$qq = mysql_query ("SELECT * FROM cnpCalendar

                         WHERE date LIKE '$ddd'
						 AND nl LIKE '$nl'

                       	ORDER BY header

");



if ($row = mysql_fetch_array($qq)) {

$cqq = mysql_num_rows($qq);

if ($cqq != 1){	

print "<b><a href=\"main.php?page=display2&nl=$nl&dayd=$ddd\"> $cqq Events </a></b>";
$cd = $cd+1;

}

else {

print "<b><a href=\"main.php?page=display2&nl=$nl&dayd=$ddd\"> $cqq Event </a></b>";
$cd = $cd+1;

}



}

else { 
$cd++; } 

?>

</td>
          <? }} ?>
        </tr>
        <?

}

}

?>
      </table>
      <br> <table width="120" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="15" bgcolor="#66CCFF"> <div align="center"></div></td>
          <td> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;= 
              Today </font></div></td>
        </tr>
      </table>
      <br> <hr width="100%" size="1" noshade> <br> <table width="95%" border="0" align="center" cellpadding="4" cellspacing="0">
        <tr valign="top"> 
          <td width="50%"><font size="2" face="Arial, Helvetica, sans-serif"><b>View 
            Other Months:</b><br>
            </font> <form name="" method="post" action="main.php">
              <select name="mo">
                <option value="1" selected>January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
              <br>
              <select name="yr">
                <option value="2002">2002</option>
                <option value="2003" selected>2003</option>
                <option value="2004">2004</option>
                <option value="2005">2005</option>
                <option value="2006">2006</option>
                <option value="2007">2007</option>
                <option value="2008">2008</option>
              </select>
              <br>
			  			  <input name="nl" type="hidden" value="<? print $nl; ?>">
			  <input name="page" type="hidden" value="display">
              <input type="submit" name="Submit2" value="Submit">
            </form></td>
          <td width="50%"> <div align="right"><p><font size="2" face="Arial, Helvetica, sans-serif"><b><a href="main.php?page=add1&nl=<? print $nl; ?>">Add An Event</a></b></font></div> 
</td>
        </tr>
      </table></td>
  </tr>
</table>
