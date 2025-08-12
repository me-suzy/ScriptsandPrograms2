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
require("engine.inc.php");
?>
<?
if($nl == ""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#EAEAEA">
  <tr> 
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center" bgcolor="#FFFFFF">
        <tr bgcolor="#EAEAEA"> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b>Calendar 
              Name </b></font></div></td>
        </tr>
        <?php 
$result = mysql_query ("SELECT * FROM cnpLists
                         WHERE name != ''
                       	ORDER BY name
						");
if ($row = mysql_fetch_array($result)) {

do {



?>
        <tr <? if ($cpick == 0){ ?>bgcolor="#F3F3F3"<? } else{ ?>bgcolor="#E9E9E9"<? } ?>> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <a href="events.php?nl=<? print $row["id"]; ?>"> <font size="2"> <font color="#000000"> 
              <?php print $row["name"]; ?> </font></font></a></font></div></td>
        </tr>
        <?php
				   if ($cpick == 0){
  $cpick = 1; 
  }
  else {
  $cpick = 0;
  }

} while($row = mysql_fetch_array($result));

} else {
?>
        <tr bgcolor="#FFFFFF"> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <font size="2"> <font color="#000000">There are no calendars to 
              choose from.</font></font></font></div></td>
        </tr>
        <?php
				   if ($cpick == 0){
  $cpick = 1; 
  }
  else {
  $cpick = 0;
  }
}
?>
      </table></td>
  </tr>
</table>
<?
die();
}
?>
<?
if (!$calstyle) $calstyle = "1";
$nbc="EEEEEE"; // NORMAL BACKGROUND COLOR
$abc="E9B4A1"; // MARKED BACKGROUND COLOR
$tbc="66CCFF"; // TODAY'S BACKGROUND COLOR
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
//OFFSET TIME +/-
if (!$ctime) $ctime = "-0";
if (!$tw) $tw="450"; // Table Width
if (!$ch) $ch="50";   // Cell Height
if (!$algn) $algn="0";
if (!$fsm) $fsm="18"; // FONT SIZE MONTH
if (!$fsd) $fsd="9";  // FONT SIZE DAY NAMES
if (!$fsn) $fsn="11"; // FONT SIZE NUMBERS
if (!$fwm) $fwm="bold"; else $fwm="normal"; // FONT WEIGHT MONTH
if (!$fwd) $fwd="normal"; else $fwd="bold"; // FONT WEIGHT DAY NAMES
if (!$fwn) $fwn="normal"; else $fwn="bold"; // FONT WEIGHT NUMBERS
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
$tnum = (intval((date ("U", mktime(20,0,0,$tmo,$tda,$tyr))/86400)))-$daystart; // TODAY'S DAY NUMBER
if (!$mo) $mo=$tmo;
if (!$yr) $yr=$tyr;
$daycount = (intval((date ("U", mktime(20,0,0,$mo,1,$yr))/86400)))-$daystart; // FIRST OF MONTH DAY NUMBER
$mo=intval($mo);
$mn = $mth[$mo]; // SET MONTH NAME
if ($ny!=1) {$mn = $mn." ".$yr;} // ADD YEAR TO MONTH NAME?
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
if (!$mrke[$i]) $end=$start; // MARK SINGLE DAY WITH ONLY MRKS VARIABLE
if (!$bgc[$start]) {$bgc[$start]=1;} else {$bgc[$start]=4;}
$bgc[$end]=3;
for ($n = ($start+1); $n < $end; $n++) {
$bgc[$n] = 2;}
$i++;
}
?>
<title>Calendar</title><font size="4" face="Arial, Helvetica, sans-serif"><b><font color="#003366">Calendar 
of Events</font></b></font> 
<table WIDTH="<?echo$tw?>" BORDER="0" CELLSPACING="0" CELLPADDING="2" align="center">
  <tr> 
    <td CLASS="monthyear"> 
      <div ALIGN="center"> 
        <?echo "$mn";?>
      </div>
    </td>
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
print "<b><a href=\"events2.php?dayd=$ddd&nl=$nl\"> $cqq Events </a></b>"; 
}
else {
print "<b><a href=\"events2.php?dayd=$ddd&nl=$nl\"> $cqq Event </a></b>";
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
print "<b><a href=\"events2.php?dayd=$ddd&nl=$nl\"> $cqq Events </a></b>"; $cd = $cd+1;
}
else {
print "<b><a href=\"events2.php?dayd=$ddd&nl=$nl\"> $cqq Event </a></b>"; $cd = $cd+1;
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
<br>
<table width="120" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td width="15" bgcolor="#66CCFF"> 
      <div align="center"></div>
    </td>
    <td> 
      <div align="left"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;= 
        Today </font></div>
    </td>
  </tr>
</table>
<br>
<hr width="100%" size="1" noshade>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr valign="top"> 
    <td width="50%">
      <p><font size="2" face="Arial, Helvetica, sans-serif"><b>View Other Months:</b></font></p>
      <form name="form1" method="post" action="events.php">
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
        <select name="yr">
          <option value="2002">2002</option>
          <option value="2003" selected>2003</option>
          <option value="2004">2004</option>
          <option value="2005">2005</option>
          <option value="2006">2006</option>
          <option value="2007">2007</option>
          <option value="2008">2008</option>
          <option value="2009">2009</option>
          <option value="2010">2010</option>
        </select>
        <input type="submit" name="Submit" value="Submit">
        <input name="nl" type="hidden" id="nl" value="<? print $nl; ?>">
      </form>
      
    </td>
  </tr>
</table>
