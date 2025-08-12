<?php
// Cjultra v2.1

if (ini_get('register_globals') != 1) {
    $supers = array('_REQUEST','_ENV','_SERVER','_COOKIE','_GET','_POST');
    foreach ($supers as $__s) {
        if (is_array($$__s) == true) extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
}

include("./common.php");
$linkid = db_connect();
if (!$linkid) error_message(sql_error());

###########
switch ($action) {
         case "":
         login();
         break;

         case "Login":
         checkpass();
         menu();
         break;

         case "Traffic":
         checkpass();
         traffic();
         break;

         case "Links":
         checkpass();
         links();
         break;

         case "Referrers":
         checkpass();
         ref();
         break;

         case "Settings":
         checkpass();
         settings();
         break;

         case "Blacklist":
         checkpass();
         blacklist();
         break;

         case "Add Trade":
         checkpass();
         add();
         break;
         
         case "Edit":
         checkpass();
         edit();
         break;
         
         case "Delete":
         checkpass();
         delete();
         break;

         case "Back To Menu":
         checkpass();
         menu();
         break;

         case "Refresh":
         checkpass();
         menu();
         break;
         
         case "Add a Domain":
         checkpass();
         blackadd();
         break;
         
         case "Stats":
         checkpass();
         hourstats();
         break;

         case "Edit Stats":
         checkpass();
         editstats();
         break;
         
         case "Hourly":
         checkpass();
         hourstats();
         break;

         case "Toplist":
         checkpass();
         toplist();
         break;
         
         case "Mass Edit":
         checkpass();
         medit();
         break;
         
         case "Graphs":
         checkpass();
         graphs();
         break;

         default:
         checkpass();
         goback();
         break;
}
##############
##############


function login() {
        ?>
        <html>

<head>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; background-color: #222244}
TR {}
BODY {  font-family:Arial ; font-size:10pt; OVERFLOW:scroll;OVERFLOW-X:hidden}
.DEK {POSITION:absolute;VISIBILITY:hidden;Z-INDEX:200;}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
td{ border-color: #336699; border-width: 1; border-style: outset}
table {border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; background-color: #333355}
-->
</style>
<title>CjUltra Login</title>
</head>

<body bgcolor="#555555" text="#FFFFFF" link="#00FFFF" vlink="#00FFFF" alink="#00FFFF">

<p align="center"><font face="Arial" size="4">CjUltra v2.1 Admin Area Login</font></p>
<div align="center">
<form  method="POST">
<p align="center"><b><font size="3" face="Arial">Enter Password:</font></b>
<font face="Arial">
<input type="hidden" name="action" value="Login">
<input type="password" size="20" name="b12">
<input type="submit" name="action" value="Login"></font></form>
</div>
<p align="center">&nbsp;
<p align="center"><a href="http://www.cjultra.com"><font face="Arial">Powered
By CjUltra v2.1<br><br>
Click here to get CJULTRA</font></a>
</p>
<p>
<?php
/// cjstats
if (!file_exists('cjstats'))
{
    echo "Warning: Folder 'cjstats' does not exist. Please create it and chmod it to 777<br>";
}
else
{
    if (!is_writeable('cjstats'))
    {
        echo "Warning: Folder 'cjstats' does not have writing permissions. Please chmod it to 777<br>";
    }
}
/// iplog.txt
if (!file_exists('iplog.txt'))
{
    echo "Warning: File 'iplog.txt' does not exist. Please create or upload it and chmod it to 777<br>";
}
else
{
    if (!is_writeable('iplog.txt'))
    {
        echo "Warning: File 'iplog.txt' does not have writing permissions. Please chmod it to 777<br>";
    }
}
/// common.php
if (!file_exists('common.php'))
{
    echo "Warning: File 'common.php' does not exist. You might need to re-install cjultra<br>";
}
/// setup.php
if (file_exists('setup.php'))
{
	echo "Warning: File 'setup.php' is still on your server. Please delete it for security reasons<br>";
}

/// topheader.txt
if (!file_exists('topheader.txt'))
{
    echo "Warning: File 'topheader.txt' does not exist. Please create or upload it and chmod it to 777<br>";
}
else
{
    if (!is_writeable('topheader.txt'))
    {
        echo "Warning: File 'topheader.txt' does not have writing permissions. Please chmod it to 777<br>";
    }
}
/// toplines.txt
if (!file_exists('toplines.txt'))
{
    echo "Warning: File 'toplines.txt' does not exist. Please create or upload it and chmod it to 777<br>";
}
else
{
    if (!is_writeable('toplines.txt'))
    {
        echo "Warning: File 'toplines.txt' does not have writing permissions. Please chmod it to 777<br>";
    }
}
/// topfooter.txt
if (!file_exists('topfooter.txt'))
{
    echo "Warning: File 'topfooter.txt' does not exist. Please create or upload it and chmod it to 777<br>";
}
else
{
    if (!is_writeable('topfooter.txt'))
    {
        echo "Warning: File 'topfooter.txt' does not have writing permissions. Please chmod it to 777<br>";
    }
}

?>
</body>
</html>
        <?php
        exit;
        }

###############
###############

function menu() {
global $sortby, $b12;
if (!$sortby) $sortby = "a1 asc";
$day = date("w");
$yday = date("w", time() - 86400);
$hour = date("G");
$th_r = $th_u = $th_o = $th_c = $tt_r = $tt_u = $tt_o = $tt_c = $tt_f = 0;

$query2 = "select * from day";
$result2 = mysql_query($query2);
if(!$result2) error_message(sql_error());

$rtoday = $utoday = $otoday = $ctoday = 0;
$g6 =  $g7 =  $g8 =  $g9 =  0;
while ($data2 = mysql_fetch_array($result2)) {
      for ($i = 0; $i <= 23; $i ++) {
              $rtoday += $data2["zr$i"];
              $utoday += $data2["zu$i"];
              $otoday += $data2["zo$i"];
              $ctoday += $data2["zc$i"];
              }
      $g6 +=  $data2["zr$hour"];
      $g7 +=  $data2["zu$hour"];
      $g8 +=  $data2["zo$hour"];
      $g9 +=  $data2["zc$hour"];
}


?>
<html>
<head>
<title>CjUltra Admin</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; background-color: #222244}
TR {}
BODY {  font-family:Arial ; font-size:10pt; OVERFLOW:scroll;OVERFLOW-X:hidden}
.DEK {POSITION:absolute;VISIBILITY:hidden;Z-INDEX:200;}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
td{ border-color: #336699; border-width: 1; border-style: outset}
table {border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; background-color: #333355}
-->
</style>
</head>

<body bgcolor="#555555" text="#ddf5dd" link="#00DDDD" vlink="#00DDDD" alink="#00FFFF">
<DIV ID="dek" CLASS="dek"></DIV>

<SCRIPT TYPE="text/javascript">
<!--

Xoffset=-60;    // modify these values to ...
Yoffset= 20;    // change the popup position.

var nav,old,iex=(document.all),yyy=-1000;
if(navigator.appName=="Netscape"){(document.layers)?nav=true:old=true;}

if(!old){
var skn=(nav)?document.dek:dek.style;
if(nav)document.captureEvents(Event.MOUSEMOVE);
document.onmousemove=get_mouse;
}

function popup(msg,bak){
var content="<TABLE  WIDTH=150 BORDER=1 BORDERCOLOR=black CELLPADDING=2 CELLSPACING=0 "+
"BGCOLOR="+bak+"><TD ALIGN=center><FONT COLOR=white SIZE=2>"+msg+"</FONT></TD></TABLE>";
if(old){}
else{yyy=Yoffset;
 if(nav){skn.document.write(content);skn.document.close();skn.visibility="visible"}
 if(iex){document.all("dek").innerHTML=content;skn.visibility="visible"}
 }
}

function get_mouse(e){
var x=(nav)?e.pageX:event.x+document.body.scrollLeft;skn.left=x+Xoffset;
var y=(nav)?e.pageY:event.y+document.body.scrollTop;skn.top=y+yyy;
}

function kill(){
if(!old){yyy=-1000;skn.visibility="hidden";}
}

//-->
</SCRIPT>
<script language="JavaScript">
function con(aa)
{
aa.style.backgroundColor='#111133';
}
function coff(aa)
{
aa.style.backgroundColor='#333355';
}
function newt(aa)
{
aa.style.backgroundColor='#DD00DD';
}
</script>
<div align="center">
  <center>
  <table  cellpadding="0" cellspacing="0" width="500" bgcolor="#000066">
    <tr>
      <td valign="middle" align="center" rowspan="2">
	  <FONT SIZE="3" face=verdana COLOR="#00DD00">CjUltra-Free</FONT><BR><FONT SIZE="2" COLOR="#FFA500">v2.1</FONT></td>
           <th valign="middle" align="center" colspan="5">Traffic  Summary</td>
    </tr>
    <tr>
      <th valign="middle" align="center">R.In</td>
      <th valign="middle" align="center">U.In</td>
      <th valign="middle" align="center">U.Out</td>
      <th valign="middle" align="center">Clicks</td>
      <th valign="middle" align="center">Prod. %</td>
    </tr>
    <tr bgcolor="#333377">
      <td valign="middle" align="center">Hour</td>
      <td valign="middle" align="center"><?php echo $g6; ?></td>
      <td valign="middle" align="center"><?php echo $g7; ?></td>
      <td valign="middle" align="center"><?php echo $g8; ?></td>
      <td valign="middle" align="center"><?php echo $g9; ?></td>
      <td valign="middle" align="center"><?php
      if ($g7 > 0) echo ceil(100 * $g9/$g6);
      else echo "N/A"; ?>%</td>
    </tr>
    <tr bgcolor="#333377">
      <td valign="middle" align="center">Day</td>
      <td valign="middle" align="center"><?php echo "$rtoday"; ?></td>
      <td valign="middle" align="center"><?php echo "$utoday"; ?></td>
      <td valign="middle" align="center"><?php echo "$otoday"; ?></td>
      <td valign="middle" align="center"><?php echo "$ctoday"; ?></td>
      <td valign="middle" align="center"><?php
      if ($utoday > 0) echo ceil(100 * $ctoday/$rtoday);
      else  echo "N/A"; ?>%</td>
    </tr>
	<tr>
      <td valign="middle" align="center" colspan=6>&nbsp;</td>
    </tr>
  </table>
  </center>
</div>
  <p>
<div align="center">
    <center>
    <form method="POST">
    <input type="hidden" name="b12" value="<?php echo $b12; ?>">
      <table cellpadding=0 cellspacing=0 bgcolor="#000066" width=700>
        <tr>
        <th><font size=3 COLOR="#D098FF">&nbsp;Stats:</font></th>
        <td>
	<TABLE width=100% border=0>
	  <TR>
	    <TD width="25%"><p><input type="radio" value="Traffic" name="action" onClick="submit()">
		<font size=2 color="#FFFF00">Traffic</FONT></p></TD>
	    <TD width="25%"><p><input type="radio" value="Links" name="action" onClick="submit()">
		<font size=2 color="#FFFF00">Link Report</FONT></p></TD>
    	    <TD width="25%"><p><input type="radio" value="Referrers" name="action" onClick="submit()">
		<font size=2 color="#FFFF00">Referrers</FONT></p></TD>
               <TD width="25%"></td>
     </TR>
    </TABLE>
		</td>
      </tr>
      <tr>
        <th><font size=3 COLOR="#D098FF">&nbsp;Admin:</font></th>
        <td>
	<TABLE width=100% border=0>
	  <TR>
               <td width="25%" nowrap><p><input type="radio" value="Add Trade" name="action" onClick="submit()">
		<font size=2 color="#FFFF00">Add Trade</font></p></td>
		<TD width="25%"><p><input type="radio" value="Settings" name="action" onClick="submit()">
		<font size=2 color="#FFFF00">Settings</FONT></p></TD>
               <TD width="25%" align="left"><p><input type="radio" value="Blacklist" name="action" onClick="submit()">
		<font size=2 color="#FFFF00">Blacklist</FONT></p></TD>
                <td width="25%" nowrap><p><input type="radio" value="Mass Edit" name="action" onClick="submit()">
		<font size=2 color="#FFFF00">Mass Edit</font></p></td>
				<td width="20%" nowrap><p><input type="radio" value="Toplist" name="action" onClick="submit()">
		<font size=2 color="#FFFF00">Auto Toplist</font></p></td>
	  </TR>
    </TABLE>
	    </td>
      </tr>
	  <tr>
		<td colspan=2 align=center>
		<p><input type="submit" name="Submit" value="Submit" style="width: 100"></p>
   		</td>
	  </tr>
     </table>
    </form>
   </center>
  </div>
<p>
<div align="center">
<center>
<form method="POST">
    <input type="hidden" name="b12" value="<?php echo $b12; ?>">
    <input type="submit" name="action" value="Refresh" style="width: 100"></p>
</form>
</div>
</center>
<p>
<div align="center">
   <center>
   <form method="POST">
   <input type="hidden" name="b12" value="<?php echo $b12; ?>">
 <table style="border-width:0" cellspacing="0" cellpadding="0" bgcolor="#000066" width="90%">
          <tr>
            <td valign="middle" align="left" width="50%">
              <input type="submit" name="action" value="Edit" style="width: 60">
	      <input type="submit" name="action" value="Delete" style=width: 70">
              <input type="submit" name="action" value="Stats" style="width: 60">
            </td>
            <td valign="middle" align="center" width="25%">Date:&nbsp;<?php echo date ("F jS, Y"); ?></td>
            <td valign="middle" align="center" width="25%">Time:&nbsp;<?php echo date ("g:i a"); ?>&nbsp;</td>
          </tr>
</table>

  <table cellspacing="0" cellpadding="0" bgcolor="#000066" width="90%">
    <tr>
      <th valign="middle" align="center" rowspan="2">Sel.</td>
      <th valign="middle" align="center" rowspan="2">Domain/Contact</td>
      <th valign="middle" align="center" colspan="4">This Hour</td>
      <th rowspan=2>&nbsp;</th>
      <th valign="middle" align="center" colspan="5">Daily</td>
      <th rowspan=2>&nbsp;</th>
      <th valign="middle" align="center" rowspan="2">Hourly<br>Force</td>
      <th valign="middle" align="center" rowspan="2">Actual<br>Ratio</td>
      <th valign="middle" align="center" rowspan="2">Ratio<br>Limit</td>
      <th valign="middle" align="center" rowspan="2">Auto<br>Suspend</td>
    </tr>
    <tr>
      <th valign="middle" align="center">R.In</td>
      <th valign="middle" align="center">U.In</td>
      <th valign="middle" align="center">U.Out</td>
      <th valign="middle" align="center">Clicks</td>
      <th valign="middle" align="center">R.In</td>
      <th valign="middle" align="center">U.In</td>
      <th valign="middle" align="center">U.Out</td>
      <th valign="middle" align="center">Clicks</td>
      <th valign="middle" align="center">Prod</td>
    </tr>

<?php

$query = "select * from trade order by $sortby";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$numberofsites = mysql_num_rows ($result);


////
$query2 = "select * from day where z = 'noref'";
$result2  = mysql_query($query2);
if(!$result2) error_message(sql_error());
if (mysql_num_rows($result2)) {
$data["a22"] = $data["a23"] = $data["a24"] = $data["a25"] = 0;
$data2 = mysql_fetch_array($result2);
for ($i = 0; $i <= 23; $i++) {
$data["a22"] += $data2["zr$i"];
$data["a23"] += $data2["zu$i"];
$data["a24"] += $data2["zo$i"];
$data["a25"] += $data2["zc$i"];
}
}
else {
$data["a22"] = $data["a23"] = $data["a24"] = $data["a25"] = 0;
$data2["zr$hour"] = $data2["zu$hour"] = $data2["zo$hour"] = $data2["zc$hour"] = 0;
}
$data["a26"] = $data["a27"] = $data["a28"] = $data["a29"] = 0;
$data["a26"] = $data2["zr$hour"];
$data["a27"] = $data2["zu$hour"];
$data["a28"] = $data2["zo$hour"];
$data["a29"] = $data2["zc$hour"];
$tt_r += $data["a22"];
$tt_u += $data["a23"];
$tt_o += $data["a24"];
$tt_c += $data["a25"];
$th_r += $data["a26"];
$th_u += $data["a27"];
$th_o += $data["a28"];
$th_c += $data["a29"];
$tt_f += $data["a18"];

if ($data["a22"] > 0) $prod1 = ceil(100 * $data["a25"] / $data["a22"]);
else $prod1 = "0";
?>
    <tr onmouseover="con(this);" onmouseout="coff(this);">
      <td valign="middle" align="center">
       <input type="radio" name="a1" value="noref">
      </td>
      <td valign="middle" align="center">noref</td>
      <td valign="middle" align="center"><?php echo $data["a26"]; ?></td>
      <td valign="middle" align="center"><?php echo $data["a27"]; ?></td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center"><?php echo $data["a29"]; ?></td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center"><?php echo $data["a22"]; ?></td>
      <td valign="middle" align="center"><?php echo $data["a23"]; ?></td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center"><?php echo $data["a25"]; ?></td>
      <td valign="middle" align="center"><?php echo $prod1; ?>%</td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
    </tr>
<?php
////
////
$query2 = "select * from day where z = 'nocookie'";
$result2  = mysql_query($query2);
if(!$result2) error_message(sql_error());
if (mysql_num_rows($result2)) {
$data["a22"] = $data["a23"] = $data["a24"] = $data["a25"] = 0;
$data2 = mysql_fetch_array($result2);
for ($i = 0; $i <= 23; $i++) {
$data["a22"] += $data2["zr$i"];
$data["a23"] += $data2["zu$i"];
$data["a24"] += $data2["zo$i"];
$data["a25"] += $data2["zc$i"];
}
}
else {
$data["a22"] = $data["a23"] = $data["a24"] = $data["a25"] = 0;
$data2["zr$hour"] = $data2["zu$hour"] = $data2["zo$hour"] = $data2["zc$hour"] = 0;
}
$data["a26"] = $data["a27"] = $data["a28"] = $data["a29"] = 0;
$data["a26"] = $data2["zr$hour"];
$data["a27"] = $data2["zu$hour"];
$data["a28"] = $data2["zo$hour"];
$data["a29"] = $data2["zc$hour"];
$tt_r += $data["a22"];
$tt_u += $data["a23"];
$tt_o += $data["a24"];
$tt_c += $data["a25"];
$th_r += $data["a26"];
$th_u += $data["a27"];
$th_o += $data["a28"];
$th_c += $data["a29"];
$tt_f += $data["a18"];

if ($data["a22"] > 0) $prod1 = ceil(100 * $data["a25"] / $data["a22"]);
else $prod1 = "0";
?>
    <tr onmouseover="con(this);" onmouseout="coff(this);">
      <td valign="middle" align="center">
       <input type="radio" name="a1" value="nocookie">
      </td>
      <td valign="middle" align="center">nocookie</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center"><?php echo $data["a29"]; ?></td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center"><?php echo $data["a25"]; ?></td>
      <td valign="middle" align="center">-</td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
    </tr>
<?php
////
////
$query2 = "select * from day where z = 'exout'";
$result2  = mysql_query($query2);
if(!$result2) error_message(sql_error());
if (mysql_num_rows($result2)) {
$data["a22"] = $data["a23"] = $data["a24"] = $data["a25"] = 0;
$data2 = mysql_fetch_array($result2);
for ($i = 0; $i <= 23; $i++) {
$data["a22"] += $data2["zr$i"];
$data["a23"] += $data2["zu$i"];
$data["a24"] += $data2["zo$i"];
$data["a25"] += $data2["zc$i"];
}
}
else {
$data["a22"] = $data["a23"] = $data["a24"] = $data["a25"] = 0;
$data2["zr$hour"] = $data2["zu$hour"] = $data2["zo$hour"] = $data2["zc$hour"] = 0;
}
$data["a26"] = $data["a27"] = $data["a28"] = $data["a29"] = 0;
$data["a26"] = $data2["zr$hour"];
$data["a27"] = $data2["zu$hour"];
$data["a28"] = $data2["zo$hour"];
$data["a29"] = $data2["zc$hour"];
$tt_r += $data["a22"];
$tt_u += $data["a23"];
$tt_o += $data["a24"];
$tt_c += $data["a25"];
$th_r += $data["a26"];
$th_u += $data["a27"];
$th_o += $data["a28"];
$th_c += $data["a29"];
$tt_f += $data["a18"];


if ($data["a22"] > 0) $prod1 = ceil(100 * $data["a25"] / $data["a22"]);
else $prod1 = "0";
?>
    <tr onmouseover="con(this);" onmouseout="coff(this);">
      <td valign="middle" align="center">
       <input type="radio" name="a1" value="exout">
      </td>
      <td valign="middle" align="center">exout</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center"><?php echo $data["a28"]; ?></td>
      <td valign="middle" align="center">-</td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center"><?php echo $data["a24"]; ?></td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
    </tr>
<?php
////




while ($data = mysql_fetch_array($result)) {
$data["a26"] = $data["a27"] = $data["a28"] = $data["a29"] = 0;
$query2 = "select * from day where z = '" . $data["a1"] . "'";
$result2  = mysql_query($query2);
if(!$result2) error_message(sql_error());

if (!mysql_num_rows($result2) == 0) {
$data2 = mysql_fetch_array($result2);
for ($i = 0; $i <= 23; $i++) {
$data["a22"] += $data2["zr$i"];
$data["a23"] += $data2["zu$i"];
$data["a24"] += $data2["zo$i"];
$data["a25"] += $data2["zc$i"];
}
}
else {
$data["a22"] = $data["a23"] = $data["a24"] = $data["a25"] = 0;
$data2["zr$hour"] = $data2["zu$hour"] = $data2["zo$hour"] = $data2["zc$hour"] = 0;
}
$data["a26"] = $data2["zr$hour"];
$data["a27"] = $data2["zu$hour"];
$data["a28"] = $data2["zo$hour"];
$data["a29"] = $data2["zc$hour"];
$tt_r += $data["a22"];
$tt_u += $data["a23"];
$tt_o += $data["a24"];
$tt_c += $data["a25"];
$th_r += $data["a26"];
$th_u += $data["a27"];
$th_o += $data["a28"];
$th_c += $data["a29"];
$tt_f += $data["a18"];



if ($hour > 0) $yhour = $hour - 1;
else $yhour = 23;

if ($data["a23"] > 0) $prod1 = ceil(100 * $data["a25"] / $data["a22"]);
else $prod1 = "0";
if ($data["a23"] > 0) $rat = ceil(100 * $data["a24"] / $data["a23"]);
else $rat = "N/A";
if ($data["a15"]) $offs = $data["a16"];
else $offs = "-";

if ($data["a4"]) $icq = "<a ONMOUSEOVER=\"popup('$data[a5] - $data[a4] <img height=18 src=http://wwp.icq.com/scripts/online.dll?icq=$data[a4]&img=5>','yellow')\"; ONMOUSEOUT=\"kill()\" target=\"_blank\" href=\"$data[a2]\">$data[a1]</a>";
else $icq = "<a target=\"_blank\" href=\"$data[a2]\">$data[a1]</a>";
if ($data["a20"]) $active = "YES";
else $active = "NO";
if ($data["a22"]) $rat = (ceil ($data["a24"]/$data["a22"] * 100)) / 100;
else $rat = 0;
$rlimit = ($data["a15"] == -1) ? $data[a8] / 100 : "-";
$asusp = ($data["a9"] == -1) ? "on" : "off";
if ($data["a9"] == -1 && $hour > 0 && $data2["zr$hour"] + $data2["zr$yhour"] == 0 && $data["a18"] > 0) $icq .= "<br><font color=yellow>force auto-suspended</font>";
if ($data["a16"] == -1) $icq .= "<br><font color=lime>trade suspended by admin</font>";


if ($data[a7] != -1) {
?>
    <tr onmouseover="con(this);" onmouseout="coff(this);" >
     <td valign="middle" align="center">
       <input type="radio" name="a1" value="<?php echo $data["a1"]; ?>">
      </td>
      <td valign="middle" align="center">
	  <?php echo $icq; ?></td>
<?php
}
// if new trade
else
{
?>

    <tr onmouseover="con(this);" onmouseout="coff(this);" >
      <td valign="middle" align="center">
       <input type="radio" name="a1" value="<?php echo $data["a1"]; ?>">
      </td>
      <td valign="middle" align="center">
	  <?php echo "<font color=red>new:</font> $icq"; ?></td>
<?php
}
?>
      <td valign="middle" align="center"><?php echo $data["a26"]; ?></td>
      <td valign="middle" align="center"><?php echo $data["a27"]; ?></td>
      <td valign="middle" align="center"><?php echo $data["a28"]; ?></td>
      <td valign="middle" align="center"><?php echo $data["a29"]; ?></td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center"><?php echo $data["a22"]; ?></td>
      <td valign="middle" align="center"><?php echo $data["a23"]; ?></td>
      <td valign="middle" align="center"><?php echo $data["a24"]; ?></td>
      <td valign="middle" align="center"><?php echo $data["a25"]; ?></td>
      <td valign="middle" align="center"><?php echo $prod1; ?>%</td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center"><?php echo $data["a18"]; ?></td>
      <td valign="middle" align="center"><?php echo $rat; ?></td>
      <td valign="middle" align="center"><?php echo $rlimit; ?></td>
      <td valign="middle" align="center"><?php echo $asusp; ?></td>
    </tr>
<?php
}
if ($tt_u > 0) $tt_prod = ceil(100 * $tt_c / $tt_r);
else $tt_prod = "0";
?>
<tr bgcolor="#333377">
      <td valign="middle" align="center" colspan="2">TABLE TOTALS</td>
      <td valign="middle" align="center"><?php echo $th_r; ?></td>
      <td valign="middle" align="center"><?php echo $th_u; ?></td>
      <td valign="middle" align="center"><?php echo $th_o; ?></td>
      <td valign="middle" align="center"><?php echo $th_c; ?></td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center"><?php echo $tt_r; ?></td>
      <td valign="middle" align="center"><?php echo $tt_u; ?></td>
      <td valign="middle" align="center"><?php echo $tt_o; ?></td>
      <td valign="middle" align="center"><?php echo $tt_c; ?></td>
      <td valign="middle" align="center"><?php echo $tt_prod; ?>%</td>
      <td width=2>&nbsp;</td>
      <td valign="middle" align="center"><?php echo $tt_f; ?></td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
      <td valign="middle" align="center">-</td>
    </tr>
</table>
   </center>
</div>
</body>
</html>
<?php
$query = "update trade set a7 = 0";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
exit;
}

##############



####################
function goback()
{
global $action, $b12, $pass;
switch ($action) {
    case "Add Domain":
    addfinal();
    break;
    
    case "Save Settings":
    setfinal();
    break;
    
    case "Edit Member":
    editfinal();
    break;
    
    case "Delete Selected Domain from List":
    blackdelete();
    break;
    
    case "Add Domain to Blacklist":
    blackaddfinal();
    break;
    
    case "Delete Domain":
    deletefinal();
    break;
    
    case "Activate":
    activate();
    break;
    
    case "Save Stats":
    savestats();
    break;
    
    case "Reset Links":
    resetlinks();
    break;
    
    case "Save Toplist":
    topsave();
    break;

    case "Edit Values":
    msave();
    break;
    
    default:
    menu;
}
?>
<html>
<head>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
<title>CjUltra Admin</title>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
    <p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
      <td align=center>
        <font face="Arial" size="4">OPERATION COMPLETE</font></b>
      </td>
    </tr>
    <tr>
      <td align=center>
        <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100">
      </td>
    </tr>
  </table>
  </center>
</div>
</form>
</body>
</html>
<?php
}

###################

function checkpass()
{
global $b12;
$query = "Select b12 from settings";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_array($result);
if (!(ereg("^aa",$data["b12"]))) {
    $ps = crypt($data["b12"],"aa");
    $data["b12"] = $ps;
    $query = "update settings set b12 = '$ps'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
}
if (crypt($b12,"aa") != $data["b12"]) die ("WRONG PASS!!");
}
####################
function settings()
{
global $b12;
$query = "select * from settings";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_array($result);
?>
<html>
<head>
<title>CjUltra Settings</title>

<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>

</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<input type="hidden" name="b11" value="<?php echo $data["b11"]; ?>">
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
	  <td align=center colspan=2><FONT SIZE="5">CjUltra Settings</FONT></td>
    </tr>
    <tr>
      <td>Your Website Url:<br><font size="2"><small>example: <i>http://www.yourdomain.com/</i></small></font></td>
      <td valign="top"><!--webbot bot="Validation"  B-Value-Required="TRUE" I-Minimum-Length="10" -->
      <input type="text" size="40" name="b1" value="<?php echo $data["b1"]; ?>"></td>
    </tr>
    <tr>
      <td>Admin Password:<br>Enter only if you want to change it!</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="pass" value=""></td>
    </tr>
    <tr>
      <td>Your ICQ #:</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="b3" value="<?php echo $data["b3"]; ?>"></td>
    </tr>
    <tr>
      <td>ICQ Nick:<br></td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="b4" value="<?php echo $data["b4"]; ?>"></td>
    </tr>
    <tr>
      <td>E-mail:<br></td>
      <td valign="top"><input type="text" size="20" maxlength="50" name="b2" value="<?php echo $data["b2"]; ?>"></td>
    </tr>
    <tr>
      <td>Exout Url:<br><font size="2"><small>Where to send extra hits. ex: <I>sponsor, etc.</I><br>(do not put out.php)</small></font></td>
      <td valign="top"><!--webbot bot="Validation" B-Value-Required="TRUE" I-Minimum-Length="10" -->
	  <input type="text" size="40" name="b5" value="<?php echo $data["b5"]; ?>"></td>
    </tr>
    <tr>
      <td>Default Hourly Force:<br><font size="2"><small>How many hits per hour to force ex: <i>3</i></small></font></td>
      <td valign="top"><input type="text" size="5" maxlength="50" name="b7" value="<?php echo $data["b7"]; ?>"></td>
    </tr>
    <tr>
      <td colspan=2><input type="submit" name="action" value="Save Settings" style="font-weight: bold; font-size: 10 px; width: 100">
      <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100"></td>
    </tr>
  </table>
  </center>
</div>
</form>
<?php
}
##################
function setfinal()
{
global $b1, $b2, $b3, $b4, $b5, $b6, $b7, $b8, $b9, $b10, $b11, $b12, $b13, $b14, $pass;


if (strlen($pass)) {
    $b12 = $pass;
    $pass = crypt($pass,"aa");
}
else $pass = crypt($b12,"aa");
$query = "delete from settings";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$query = "insert into settings values('$b1','$b2','$b3','$b4','$b5','$b6','$b7','$b8','$b9',
'$b10','$b11','$pass','$b13','$b14')";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
echo "SETTINGS SAVED";
}
##################
function edit()
{
global $a1, $b12;
if (!$a1 or $a1 == "noref" or $a1 == "exout" or $a1 == "cookie") menu();

$query = "select * from trade where a1 = '$a1'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_array($result);
$lasthit = date("g:i a F jS, Y",$data["a6"]);
if (!$data["a6"]) $lasthit = "N/A";
$data["a16"] = ($data["a16"] == -1) ? " checked" : "";
$data["a9"] = ($data["a9"] == -1) ? " checked" : "";
$data["a15"] = ($data["a15"] == -1) ? " checked" : "";
$data["a8"] /= 100;
?>
<html>
<head>
<title>CjUltra Edit <?php echo $data["a1"]; ?></title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<input type="hidden" name="a1old" value="<?php echo $data["a1"]; ?>">
<input type="hidden" name="a6" value="<?php echo $data["a6"]; ?>">
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
	  <td align=center colspan=2><FONT SIZE="5">Edit <?php echo $data["a1"]; ?></FONT></td>
    </tr>
	<tr>
      <td>Domain Name:<BR><font size="2"><small><i>domain.com</i> (no http://www.)</small></font></td>
      <td valign="top"><input type="text" size="20" maxlength="100" name="a1"  value="<?php echo $data["a1"]; ?>"></td>
    </tr>
    <tr>
      <td>Url to Send Hits To:<br><FONT size=2><small><i>http://www.domain.com/</i></small></font></td>
      <td valign="top"><input type="text" size="40" name="a2" value="<?php echo $data["a2"]; ?>"></td>
    </tr>
    <tr>
      <td>Site Name for toplist:</td>
      <td valign="top"><input type="text" size="20" maxlength="50" name="a21" value="<?php echo $data["a21"]; ?>"></td>
    </tr>
    <tr>
      <td>ICQ #:</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a4" value="<?php echo $data["a4"]; ?>"></td>
    </tr>
    <tr>
      <td>ICQ Nick:</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a5" value="<?php echo $data["a5"]; ?>"></td>
    </tr>
    <tr>
      <td>E-mail:</td>
      <td valign="top"><input type="text" size="20" maxlength="100" name="a3" value="<?php echo $data["a3"]; ?>"></td>
    </tr>
    <tr>
      <td>Last Hit:<br><small>The date of last hit sent by this trade</small></td>
      <td valign="top"><?php echo $lasthit; ?></td>
    </tr>
    <tr>
      <td>Hourly Force:<br><font size="2"><small>How many hits per hour to force. ex: <i>3</i></small></font></td>
      <td valign="top"><input type="text" size="5" maxlength="4" name="a18" value="<?php echo $data["a18"]; ?>"></td>
    </tr>
    <tr>
      <td>Suspend trade on/off:<br><font size="2"><small>This option will stop you sending hits to trade</small></font></td>
      <td valign="top"><input type="checkbox" name="a16" value="-1"<?php echo $data["a16"]; ?>>Check for ON, uncheck for OFF</td>
    </tr>
    <tr>
      <td>Force-Auto-Suspend on/off:<br><font size="2"><small>This option will stop hourly force if the trade<br>didnt send any hits during current and previous hour</small></font></td>
      <td valign="top"><input type="checkbox" name="a9" value="-1"<?php echo $data["a9"]; ?>>Check for ON, uncheck for OFF</td>
    </tr>
    <tr>
      <td>Ratio Limit on/off:<br><font size="2"><small>This will stop you sending hits more than the ratio limit</small></font></td>
      <td valign="top"><input type="checkbox" name="a15" value="-1"<?php echo $data["a15"]; ?>>Check for ON, uncheck for OFF</td>
    </tr>
    <tr>
      <td>Ratio Limit:<br><font size="2"><small>example:  1.8</small></font></td>
      <td valign="top"><input type="text" size="5" maxlength="4" name="a8" value="<?php echo $data["a8"]; ?>"></td>
    </tr>
    <tr>
      <td colspan="2" align="center">HIT STATS</td>
    </tr>
    <tr>
      <td>Raw Hits Total:</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a10" value="<?php echo $data["a10"]; ?>"></td>
    </tr>
    <tr>
      <td>Unique Hits Total:</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a11" value="<?php echo $data["a11"]; ?>"></td>
    </tr>
    <tr>
      <td>Hits Out Total:</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a12" value="<?php echo $data["a12"]; ?>"></td>
    </tr>
    <tr>
      <td>Clicks Total:</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a13" value="<?php echo $data["a13"]; ?>"></td>
    </tr>
    <tr>
      <td colspan=2><input type="submit" name="action" value="Edit Member" style="font-weight: bold; font-size: 10 px; width: 100">
      <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100"></td>
    </tr>
  </table>
  </center>
</div>
</form>
<?php
}
####################
function editfinal()
{
global $a1old,$a1,$a2,$a3,$a4,$a5,$a6,$a7,$a8,$a9,$a10,$a11,$a12,$a13,$a14,$a15,$a16,$a17,$a18,$a20,$a21;
if (strlen($a8) > 0) $a8 *= 100;
$query = "delete from trade where a1 = '$a1old'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$query = "insert into trade values('$a1','$a2','$a3','$a4','$a5','$a6','$a7','$a8','$a9','$a10',
'$a11','$a12','$a13','$a14','$a15','$a16','$a17','$a18','100','1','$a21')";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
echo "domain: $a1 edited";
}
###################
function blacklist()
{
global $b12;
$query = "select * from blacklist";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
?>
<html>
<head>
<title>CjUltra Blacklist</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
    <center>
  </center>
<div align="center">
<p>
<b><font face="Arial" size="4">BLACKLIST</font></b><center>
</center>
</div>
<p>
    <center>
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<div align="center">
   <center>
   <table border="1" cellspacing="0" cellpadding="0" bgcolor="#000066">
    <tr>
      <td valign="middle" align="left" colspan="2">
      <input type="submit" name="action" value="Delete Selected Domain from List" style="font-weight: bold; font-size: 10 px; width: 100">
	  <input type="submit" name="action" value="Add a Domain" style="font-weight: bold; font-size: 10 px; width: 100">
	  <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100">
      </td>
    </tr>
    <tr>
      <td valign="middle" align="center">Select</td>
      <td valign="middle" align="center">Domain</td>
    </tr>
    <?php
    while ($data = mysql_fetch_array($result)) {
    ?>
    <tr bgcolor="#333377">
      <td valign="middle" align="center">
      <input type="radio" name="e1" value="<?php echo $data["e1"]; ?>">
      </td>
      <td valign="middle" align="center"><?php echo $data["e1"]; ?></td>
    </tr>
<?php
}
?>
   </table>
   </center>
</div>
</form>
</center>
</html>
<?php
}
####################
function blackdelete()
{
global $e1;
if (!$e1) {
    echo "no domain was selected";
}
else {
$query = "delete from blacklist where e1 = '$e1'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
echo "domain: $e1 removed from blacklist";
}
}
###################
function blackadd()
{
global $b12;
?>
<html>
<head>
<title>CjUltra Blacklist</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<p align="center"><font size="4"><b>BLACKLIST</b></font></p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
      <td>Domain Name:<br><font size="2">ex: <i>domain.com</i> (no http://www.)</font></td>
      <td valign="top"><input type="text" size="20" name="e1"></td>
    </tr>
    <tr>
      <td colspan="2" align=center><input  type="submit" name="action" value="Add Domain to Blacklist"></td>
    </tr>
  </table>
  </center>
</div>
</form>
<?php
}
#########################
function blackaddfinal()
{
global $e1;
$query = "select count(*) from blacklist where e1 = '$e1'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_row($result);
if ($data[0] > 0) echo "domain is already in the blacklist";
else {
    $query = "insert into blacklist values('$e1')";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
}
}
########################
function delete()
{
global $b12, $a1;
if (!$a1 or $a1 == "noref" or $a1 == "exout" or $a1 == "cookie") menu();
?>
<html>
<head>
<title>CjUltra Delete <?php echo $a1; ?>?</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:12pt; font-weight:bold; background-color: #222244}
TR {}
BODY {  font-family:Arial ; font-size:10pt; OVERFLOW:scroll;OVERFLOW-X:hidden}
.DEK {POSITION:absolute;VISIBILITY:hidden;Z-INDEX:200;}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
td{ border-color: #336699; border-width: 1; border-style: outset}
table {border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:12pt; font-weight:bold; background-color: #333355}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<input type="hidden" name="a1" value="<?php echo $a1; ?>">
<p align="center"><font size="4" face="Arial">DELETE <?php echo $a1; ?>?</font></p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
      <td align=center>
        Are you sure you want to delete: <?php echo $a1; ?>?</td>
    </tr>
    <tr>
      <td align=center>
        <input type="submit" name="action" value="Delete Domain" style="font-weight: bold; font-size: 10 px; width: 100">
		<input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100">
      </td>
    </tr>
  </table>
  </center>
</div>
</form>
<?php
}
#########################
function deletefinal()
{
global $a1;
$query = "delete from trade where a1 = '$a1'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
echo "domain: $a1 deleted";
}
#########################

function traffic()
{

global $b12,$day,$a1;


$today = date("w");
$fn = date("Ymd", time() - (86400 * $day));
$dt = date("D, F jS", time() - (86400 * $day));
$query2 = "select * from day";
$result2  = mysql_query($query2);
$hour = date("G");
$hour2 = date("G");
$max2 = 0;
$max = 0;
////////////
if ($day > 0) {
if (!file_exists("cjstats/$fn.txt")) {
?>
<html>
<head>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
<title>CjUltra Admin</title>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF"><form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<center><font size="4">Stats not available on date: <?php echo $dt; ?><br>Please go back</font>
<p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day - 1); ?>">
        <input type="submit" name="null" value="NEXT DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Traffic">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p>
</center>
</div>
</form>
<?php
exit;
}

$f_arr = file("cjstats/$fn.txt");
while ($ln = each($f_arr)) {
      $ln_arr = explode("|",trim($ln[1]));
      for ($i=0;$i<=23;$i++) {
          $rtotal[$i] += $ln_arr[$i+1];
          $utotal[$i] += $ln_arr[$i+25];
          $ototal[$i] += $ln_arr[$i+49];
          $ctotal[$i] += $ln_arr[$i+73];
          if ($rtotal[$i] > $max) $max = $rtotal[$i];
          if ($utotal[$i] > $max) $max = $utotal[$i];
          if ($ototal[$i] > $max) $max = $ototal[$i];
          if ($ctotal[$i] > $max) $max = $ctotal[$i];
          }
      }
}

else {
///////////////
while ($data2 = mysql_fetch_array($result2)) {

for ($i = 0; $i <= $hour; $i++) {
$rtotal[$i] += $data2["zr$i"];
$utotal[$i] += $data2["zu$i"];
$ototal[$i] += $data2["zo$i"];
$ctotal[$i] += $data2["zc$i"];
if ($rtotal[$i] > $max) $max = $rtotal[$i];
if ($utotal[$i] > $max) $max = $utotal[$i];
if ($ototal[$i] > $max) $max = $ototal[$i];
if ($ctotal[$i] > $max) $max = $ctotal[$i];
}
}
}
///////////////
?>
<html>
<head>
<title>CjUltra Hourly Stats</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">


<form method="POST">
<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="750" cellspacing="0" cellpadding="0" bgcolor="#000066">
  <tr>
        <td align="left" width="50">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day + 1); ?>">
        <input type="submit" name="null" value="PREV. DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Traffic">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p></td>
  <?php
        if ($day > 0) {
        ?>
        <td align="left" width="50">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day - 1); ?>">
        <input type="submit" name="null" value="NEXT DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Traffic">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p>
        <?php
        }
        ?>

  </td>

      <td align="center">
        <p align="center"><font face="Arial" size="4"> <?php echo $dt; ?> &nbsp;HOURLY STATS</font></td>
  <td align="right" width="100">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100"></form></p>
  </td>

  </tr></table>
  <table border="1" width="750" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
      <td align="center" bgcolor="#000000" width="40">Hour</td>
      <td align="center" width="40">R.In</td>
      <td align="center" width="40">U.In</td>
      <td align="center" width="40">U.Out</td>
      <td align="center" width="40">Clicks</td>
      <td align="left"><p align="center">Graph</p></td>
    </tr>
    <?php
    if ($day > 0) $hour = 23;
    for ($i = 0; $i <= $hour; $i++) {
    $rt += $rtotal[$i];
    $ut += $utotal[$i];
    $ot += $ototal[$i];
    $ct += $ctotal[$i];
    ?>

      <tr>
      <td align="center" height="50" bgcolor="#000000" width="40">
        <font size="3"><?php echo $i; ?></font>
      </td>
      <td align="center" width="40">
        <font size="3" color="#808080"><?php echo $rtotal[$i]; ?></font>
      </td>
      <td align="center" width="40">
        <font  size="3"><?php echo $utotal[$i]; ?></font>
      </td>
      <td align="center" width="40">
        <font size="3" color="#FF0000"><?php echo $ototal[$i]; ?></font>
      </td>
      <td align="center" width="40">
        <font size="3" color="#00FF00"><?php echo $ctotal[$i]; ?></font>
      </td>
      <td align="left">
        <p><img border="2" src="gray.gif" width="<?php echo ($rtotal[$i] / $max * 450); ?>" height="6"><br>
        <img border="2" src="white.gif" width="<?php echo ($utotal[$i] / $max * 450); ?>" height="6"><br>
        <img border="2" src="red.gif" width="<?php echo ($ototal[$i] / $max * 450); ?>" height="6"><br>
        <img border="2" src="green.gif" width="<?php echo ($ctotal[$i] / $max * 450); ?>" height="6"></p>
        </td>
    </tr>
        <?php
    }
     ?>
   <tr bgcolor="#333377">
    <td bgcolor="#333377" align="center" width="40">
        <font size="3">TOTAL</font>
    </td>
      <td bgcolor="#333377" align="center" width="40">
        <font  size="3" color="#808080"><?php echo $rt; ?></font>
      </td>
       <td bgcolor="#333377" align="center" width="40">
        <font  size="3"><?php echo $ut; ?></font>
      </td>
      <td bgcolor="#333377" align="center" width="40">
        <font size="3" color="#FF0000"><?php echo $ot; ?></font>
      </td>
      <td bgcolor="#333377" align="center" width="40">
        <font size="3" color="#00FF00"><?php echo $ct; ?></font>
      </td>
      <td bgcolor="#333377" align="left">
        <p><img border="2" src="gray.gif" width="<?php echo ($rt / $max * 450 / ($hour + 1)); ?>" height="3"><br>
        <img border="2" src="white.gif" width="<?php echo ($ut / $max * 450 / ($hour + 1)); ?>" height="3"><br>
        <img border="2" src="red.gif" width="<?php echo ($ot / $max * 450 / ($hour + 1)); ?>" height="3"><br>
        <img border="2" src="green.gif" width="<?php echo ($ct / $max * 450 / ($hour + 1)); ?>" height="3"></p>
        </td>
        </tr>
    </table>
  </center>
</div>
</form>
</body>
</html>
<?php

}
###############################
function ref()
{
global $b12,$day,$a1;

$today = date("w");
$fn = date("Ymd", time() - (86400 * $day));
$dt = date("D, F jS", time() - (86400 * $day));
$hour = date("G");
$query2 = "select * from day";
$result2  = mysql_query($query2);
$max2 = 0;
$max = 0;
$rtotal = $utotal = $ototal = $ctotal = array();
if ($day == 0) {
   while ($data2 = mysql_fetch_array($result2)) {
   for ($i = 0; $i <= 23; $i++) {
   $rtotal[$data2["z"]] += $data2["zr$i"];
   $utotal[$data2["z"]] += $data2["zu$i"];
   $ototal[$data2["z"]] += $data2["zo$i"];
   $ctotal[$data2["z"]] += $data2["zc$i"];
   if ($rtotal[$data2["z"]] > $max) $max = $rtotal[$data2["z"]];
   if ($utotal[$data2["z"]] > $max) $max = $utotal[$data2["z"]];
   if ($ototal[$data2["z"]] > $max) $max = $ototal[$data2["z"]];
   if ($ctotal[$data2["z"]] > $max) $max = $ctotal[$data2["z"]];
   }
}
}
else {
    if (!file_exists("cjstats/$fn.txt")) {
?>
<html>
<head>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
<title>CjUltra Admin</title>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF"><form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<center><font size="4">Stats not available on date: <?php echo $dt; ?><br>Please go back</font>
<p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day - 1); ?>">
        <input type="submit" name="null" value="NEXT DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Referrer">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p>
</center>
</div>
</form>
<?php
exit;
}
    $f_arr = file("cjstats/$fn.txt");
    while ($ln = each($f_arr)) {
      $ln_arr = explode("|",trim($ln[1]));
      for ($i=0;$i<=23;$i++) {
          $rtotal[$ln_arr[0]] += $ln_arr[$i+1];
          $utotal[$ln_arr[0]] += $ln_arr[$i+25];
          $ototal[$ln_arr[0]] += $ln_arr[$i+49];
          $ctotal[$ln_arr[0]] += $ln_arr[$i+73];
          if ($rtotal[$ln_arr[0]] > $max) $max = $rtotal[$ln_arr[0]];
          if ($utotal[$ln_arr[0]] > $max) $max = $utotal[$ln_arr[0]];
          if ($ototal[$ln_arr[0]] > $max) $max = $ototal[$ln_arr[0]];
          if ($ctotal[$ln_arr[0]] > $max) $max = $ctotal[$ln_arr[0]];
          }
      }

}






arsort($rtotal);

?>
<html>
<head>
<title>CjUltra Referer Stats</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">

<form method="POST">
<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="770" cellspacing="0" cellpadding="0" bgcolor="#000066">
  <tr>
  <td align="left" width="50">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day + 1); ?>">
        <input type="submit" name="null" value="PREV. DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Referrers">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p></td>
  <?php
        if ($day > 0) {
        ?>
        <td align="left" width="50">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day - 1); ?>">
        <input type="submit" name="null" value="NEXT DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Referrers">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p>
        <?php
        }
        ?>

  </td>
  <td align="center">
        <p align="center"><b><font face="Arial" size="4"> <?php echo $dt; ?> &nbsp;REFERER STATS</b></font></td>
  <td align="right" width="100">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100"></form></p>
  </td>

  </tr></table>
  <table border="1" width="770" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>

    <td align="center">&nbsp;</td>
      <td align="center">Referrer</td>
      <td align="center" width="40">R.In</td>
      <td align="center" width="40">U.In</td>
      <td align="center" width="40">U.Out</td>
      <td align="center" width="40">Clicks</td>
      <td align="left"><p align="center">Graph</p></td>
    </tr>
    <?php
    if(sizeof($rtotal))
    {
            reset($rtotal);
        while ($data = each($rtotal)) {
                      if ($day == 0 and $rtotal[$data[0]] == 0 and $utotal[$data[0]] == 0 and $ototal[$data[0]] == 0 and $ctotal[$data[0]] == 0) {
                      $query = "delete from day where z = '$data[0]'";
                      $result = mysql_query($query);
                      if(!$result) error_message(sql_error());
                      }
        else {
    ?><tr>
      <td align="center" width="10"><p><form method="POST"><input type="submit" name="action" value="Hourly" style="font-weight: bold; font-size: 10 px; width: 50">
      <input type="hidden" name="b12" value="<?php echo $b12; ?>"><input type="hidden" name="a1" value="<?php echo $data[0]; ?>"><input type="hidden" name="day" value="<?php echo $day; ?>"></form></p>
      <td align="center" height="50" bgcolor="#000000"><font size="3"><?php echo $data[0]; ?></font></td>
      <td align="center" width="40"><font size="3" color="#808080"><?php echo $rtotal[$data[0]]; ?></font></td>
      <td align="center" width="40"><font size="3"><?php echo $utotal[$data[0]]; ?></font></td>
      <td align="center" width="40"><font size="3" color="#FF0000"><?php echo $ototal[$data[0]]; ?></font></td>
      <td align="center" width="40"><font size="3" color="#00FF00"><?php echo $ctotal[$data[0]]; ?></font></td>
      <td align="left">
        <p><img border="2" src="gray.gif" width="<?php echo ($rtotal[$data[0]] / $max * 300); ?>" height="6"><br>
        <img border="2" src="white.gif" width="<?php echo ($utotal[$data[0]] / $max * 300); ?>" height="6"><br>
        <img border="2" src="red.gif" width="<?php echo ($ototal[$data[0]] / $max * 300); ?>" height="6"><br>
        <img border="2" src="green.gif" width="<?php echo ($ctotal[$data[0]] / $max * 300); ?>" height="6"></p>
        </td>
    </tr>
        <?php
    }}}
    ?>
    </table>
  </center>
</div>
</form>
</body>
</html>
<?php

}
#########################################

function dbadd($d)
{
    $query = "select z from day where z = '$d'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
    if ((mysql_num_rows($result) == 0) and $d) {
        $query2 = "insert into day values('$d'";
        for ($i = 0; $i < 96; $i++) {
            $query2 = $query2 . ",'0'";
        }
        $query2 = $query2 . ")";
        $result2 = mysql_query($query2);
        if(!$result2) error_message(sql_error());
}
}
#######################
function istrade($d)
{
     $queryist = "select * from trade where a1 = '$d'";
     $resultist = mysql_query($queryist);
     return (mysql_num_rows($resultist) > 0);
}
########################
function links()
{
global $b12;
$query = "select * from links order by c2 desc";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
?>
<html>
<head>
<title>CjUltra - LINKS</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<center>
<p>
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<div align="center">
   <center>
   <table border="1" cellspacing="0" cellpadding="0" bgcolor="#000066">
    <tr>
      <td valign="middle" align="center" colspan="3">
      <input type="submit" name="action" value="Reset Links" style="font-weight: bold; font-size: 10 px; width: 100">
      <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100">
      </td>
    </tr>
    <tr>
      <td valign="middle" align="center">Link</td>
      <td valign="middle" align="center">Clicks</td>
      <td valign="middle" align="center">Ratio</td>
    </tr>
    <?php
    $query2 = "select * from links order by c2 desc";
    $result2 = mysql_query($query2);
    if(!$result2) error_message(sql_error());
    while ($data2 = mysql_fetch_array($result2)) {
          $tot += $data2[c2];
    }
    while ($data = mysql_fetch_array($result)) {
    $proc = (ceil (($data[c2] / $tot) * 10000)) / 100;
    ?>
    <tr bgcolor="#333377">
      <td width=80 valign="middle" align="center">
      <font face="Arial" size="3"><?php echo $data["c1"]; ?></font>
      </td>
      <td width=80 valign="middle" align="center">
      <font face="Arial" size="3"><?php echo $data["c2"]; ?></font>
      </td>
      <td width=80 valign="middle" align="center">
      <font face="Arial" size="3"><?php echo $proc; ?>%</font>
    </tr>
<?php
}
?>
   </table>
   </center>
</div>
</form>
</center>
</html>
<?php
}
#######################
function resetlinks()
{
    $query = "delete from links";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
    echo "Links Reset done";
}
#######################
function hourstats()
{
global $b12,$day,$a1;
if (!$day) $day = 0;
if (!$a1) {
    menu();
    exit;}

$fn = date("Ymd", time() - (86400 * $day));
$today = date("w");
$dt = date("D, F jS", time() - (86400 * $day));
$query2 = "select * from day where z = '$a1'";
$result2  = mysql_query($query2);
if(!$result2) error_message(sql_error());
$hour = date("G");
$hour2 = date("G");
if ($day > 0) $hour = 23;
if ($day and !file_exists("cjstats/$fn.txt")) {
?>
<html>
<head>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
<title>CjUltra Admin</title>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF"><form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<center><font size="4">Stats not available on date: <?php echo $dt; ?><br>Please go back</font>
<p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day - 1); ?>">
        <input type="submit" name="null" value="NEXT DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Hourly">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p>
</center>
</div>
</form>
<?php
exit;
}
if (!$day) $data2 = mysql_fetch_array($result2);
else {
    $f_arr = file("cjstats/$fn.txt");
    while ($ln = each($f_arr)) {
          $ln_arr = explode("|",trim($ln[1]));
          if ($ln_arr[0] == $a1) {
              for ($i=0;$i<=23;$i++) {
                  $data2["zr$i"] = $ln_arr[$i+1];
                  $data2["zu$i"] += $ln_arr[$i+25];
                  $data2["zo$i"] += $ln_arr[$i+49];
                  $data2["zc$i"] += $ln_arr[$i+73];
              }
          }
    }
}
          
      
$data = $data2;

$max2 = 0;


?>
<html>
<head>
<title>CjUltra Stats For <?php echo $a1; ?></title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">

<form method="POST">
<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="750" cellspacing="0" cellpadding="0" bgcolor="#000066">
  <tr>
  <td align="left" width="50">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day + 1); ?>">
        <input type="submit" name="null" value="PREV. DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Hourly">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p></td>
  <?php
        if ($day > 0) {
        ?>
        <td align="left" width="50">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="hidden" name="day" value="<?php echo ($day - 1); ?>">
        <input type="submit" name="null" value="NEXT DAY" style="font-weight: bold; font-size: 10 px; width: 100">
        <input type="hidden" name="action" value="Hourly">
        <input type="hidden" name="a1" value="<?php echo $a1; ?>"></form></p>
        <?php
        }
        ?>

  </td>
  <td align="center">
        <font size="4"> <?php echo $dt; ?> &nbsp;STATS for <?php echo $a1; ?> </font></td>
  <td align="right" width="100">
        <p><form method="POST">
        <input type="hidden" name="b12" value="<?php echo $b12; ?>">
        <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100"></form></p>
  </td>

  </tr></table>
  <table border="1" width="750" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
      <td align="center" bgcolor="#000000" width="40">Hour</td>
      <td align="center" width="40">R.In</td>
      <td align="center" width="40">U.In</td>
      <td align="center" width="40">U.Out</td>
      <td align="center" width="40">Clicks</td>
      <td align="left"><p align="center">Graph</td>
    </tr>
    <?php
    natsort($data);
    $data = implode('|',$data);
    $data = explode('|',$data);
    $max = $data[sizeof($data) - 3];
    for ($i = 0; $i <= $hour; $i++) {
    ?><tr>
      <td align="center" height="50" bgcolor="#000000" width="40"><font size="3"><?php echo $i; ?></font></td>
      <td align="center" width="40"><font size="3" color="#808080"><?php echo $data2["zr$i"]; ?></font></td>
      <td align="center" width="40"><font size="3"><?php echo $data2["zu$i"]; ?></font></td>
      <td align="center" width="40"><font size="3" color="#FF0000"><?php echo $data2["zo$i"]; ?></font></td>
      <td align="center" width="40"><font size="3" color="#00FF00"><?php echo $data2["zc$i"]; ?></font></td>
      <td align="left">
        <p><img border="2" src="gray.gif" width="<?php echo ($data2["zr$i"] / $max * 300); ?>" height="6"><br>
        <img border="2" src="white.gif" width="<?php echo ($data2["zu$i"] / $max * 300); ?>" height="6"><br>
        <img border="2" src="red.gif" width="<?php echo ($data2["zo$i"] / $max * 300); ?>" height="6"><br>
        <img border="2" src="green.gif" width="<?php echo ($data2["zc$i"] / $max * 300); ?>" height="6"></p>
        </td>
    </tr>
        <?php
    }
    ?>
    </table>
  </center>
</div>
</form>
</body>
</html>
<?php
}
#################

function add() {

global $b12;
$query = "select * from settings";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_array($result);


?>
<html>
<head>
<title>CjUltra Add Domain</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000066" cellpadding="0">
   <tr>
	  <td align=center colspan=2><font size="5">Add Trade</font></td>
    </tr>
	<tr>
      <td><font size="3">Domain Name:</font><br>
	  <font size="2"><small><i>yourdomain.com</i> (no http://www.)</small></font></td>
      <td valign="top"><input type="text" size="20" name="a1"></td>
    </tr>
    <tr>
      <td><font size="3">Url to Send Hits To:</font><br>
       <font size="2"><small><i>http://www.yourdomain.com/</i></small></font></td>
      <td valign="top"><input type="text" size="40" name="a2" value="http://"></td>
    </tr>
    <tr>
      <td><font size="3">Site Name for Toplist:</font>&nbsp;</td>
      <td valign="top"><input type="text" size="20" maxlength="50"  name="a21"></td>
    </tr>
    <tr>
      <td><font size="3">ICQ #:</font></td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a4"></td>
    </tr>
    <tr>
      <td><font size="3">Nickname:</font></td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a5"></td>
    </tr>
    <tr>
      <td><font size="3">E-mail:</font></td>
      <td valign="top"><input type="text" size="20" maxlength="100" name="a3"></td>
    </tr>
    <tr>
      <td><font size="3">Hourly Force:</font><br><font size="2"><small>How many hits per hour to force&nbsp;&nbsp;ex: <i>3</i></small></font></td>
      <td valign="top"><input type="text" size="5" maxlength="4" name="a18" value="<?php echo $data["b7"]; ?>"></td>
    </tr>
      <td colspan=2><input type="submit" name="action" value="Add Domain" style="font-weight: bold; font-size: 10 px; width: 100">
      <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100"></td>
    </tr>
  </table>
  </center>
</div>
</form>
<?php
}
####################
function blacklisted($dom)
{
    $query = "select * from blacklist where e1 = '$dom'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
    return (mysql_num_rows($result) > 0);
}
###################
function addfinal()
{
global $b12, $a1, $a2, $a3, $a4, $a5, $a14, $a15, $a16, $a17, $a18, $a19, $a20, $a21;
if (blacklisted($a1)) die("Error: Domain $a1 is in blacklist");

$query = "select count(*) from trade where a1 = '$a1'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_row($result);
if (strlen($a1) < 3) die ("Error: domain name too short. Go back");
if ($data[0] > 0) die ("Error: Domain already exists in the database. Go back");
$query = "select count(*) from trade where a1 = '$a1'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$query = "insert into trade values('$a1', '$a2', '$a3', '$a4', '$a5', '0', '0',
'0','0','0','0','0','0','$a14','0','0','0','$a18','$a19','1','$a21')";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
dbadd($a1);
echo "domain $a1 added";
}
###################
########################
function toplist()
{
global $b12;
$query = "select b11 from settings";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_row($result);
$cols = floor($data[0] / 100);
$rows = $data[0] - (100 * $cols);
if ($cols < 1) $cols = 1;
if ($rows < 1) $rows = 1;
?>
<html>
<head>
<title>CjUltra - Toplist</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<center>
<p>
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<div align="center">
   <center>
   <table width="600" border="1" cellspacing="0" cellpadding="0" bgcolor="#000066">
   <tr><td><font face="Arial" size="3">How many rows?</font> <input type="text" size="4" name="rows" value="<?php echo $rows; ?>">
   </td></tr>
   <tr><td><font face="Arial" size="3">How many columns?</font> <input type="text" size="4" name="cols" value="<?php echo $cols; ?>">
   </td></tr>
   <tr><td><font face="Arial" size="3">The header:<br><br>
   <small>See examples below.<br>
   <textarea rows="6" cols="60" name="header"><?php echo implode('',file("topheader.txt")); ?></textarea>
   </td></tr>
      <tr><td><font face="Arial" size="3">The format of the links:<br>
      <small>Use these codes:<br>
      *name* --- name of the site<br>
      *domain* --- domain of the site<br>
      *url* --- url of the site<br>
      *hits* --- hits of the site<br>
      *number* --- rank of the site<br><br></small>
      <small>See examples below.<br>
      <textarea rows="6" cols="60" name="links"><?php echo implode('',file("toplines.txt")); ?></textarea>
   </td></tr>
      <tr><td><font face="Arial" size="3">The footer:</font><br><br>
      <small>See examples below.<br>
   <textarea rows="6" cols="60" name="footer"><?php echo implode('',file("topfooter.txt")); ?></textarea>
   </td></tr>
   </tr><td><input type="submit" name="action" value="Save Toplist" style="font-weight: bold; font-size: 10 px; width: 100"><input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100">
   </td></tr>
   </table>
   <div align=left>
   <p><font size="4">Example for 1 column toplist:</font></p>
<p>How many rows? 10</p>
<p>How many columns? 1</p>
<p>Header:</p>
<p>&lt;table width=300&gt;<br>
&lt;tr&gt;&lt;td align=center colspan=3&gt;&lt;font size=3&gt;Top 10
Sites&lt;/font&gt;&lt;/td&gt;&lt;/tr&gt;</p>
<p>Links:</p>
<p>&lt;tr&gt;<br>
&lt;td&gt;&lt;font size=2&gt;*number*&lt;/font&gt;&lt;/td&gt;<br>
&lt;td&gt;&lt;a target=_blank href=out.php?perm=*domain*&gt;&lt;font
size=2&gt;*name*&lt;/font>&lt;/a>&lt;/td&gt;<br>
&lt;td&gt;&lt;font size=2&gt;*hits*&lt;/font&gt;&lt;/td&gt;<br>
&lt;/tr&gt;</p>
<p>Footer:</p>
<p>&lt;/table&gt;</p>
<p>&nbsp;</p>
<p><font size="4">Example for 2 column toplist:</font></p>
<p>How many rows? 10</p>
<p>How many columns? 2</p>
<p>Header:</p>
<p>&lt;table width=500&gt;<br>
&lt;tr&gt;&lt;td align=center colspan=6&gt;&lt;font size=3&gt;Top 20
Sites&lt;/font&gt;&lt;/td&gt;&lt;/tr&gt;</p>
<p>Links:</p>
<p>&lt;tr&gt;<br>
&lt;td&gt;&lt;font size=2&gt;*number*&lt;/font&gt;&lt;/td&gt;<br>
&lt;td&gt;&lt;a target=_blank href=out.php?perm=*domain*&gt;&lt;font
size=2&gt;*name*&lt;/font>&lt;/a>&lt;/td&gt;<br>
&lt;td&gt;&lt;font size=2&gt;*hits*&lt;/font&gt;&lt;/td&gt;<br>
&lt;/tr&gt;</p>
<p>Footer:</p>
<p>&lt;/table&gt;</p>

   </body>
   </html>
<?php
}
#####################
function topsave()
{
global $header,$links,$footer,$rows,$cols;
$header = stripslashes($header);
$links = stripslashes($links);
$footer = stripslashes($footer);
$b11 = (100 * $cols) + $rows;

$fp = fopen("topheader.txt","w");
fwrite($fp,$header);
fclose($fp);
$fp = fopen("toplines.txt","w");
fwrite($fp,$links);
fclose($fp);
$fp = fopen("topfooter.txt","w");
fwrite($fp,$footer);
fclose($fp);
$query = "update settings set b11 = '$b11'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
echo "Toplist saved";
}
####################
function medit()
{
global $b12;

?>
<html>
<head>
<title>CjUltra Mass Edit</title>
<style>
<!--
.icq:hover { text-decoration: none; color: "orange";}
A { text-decoration: none }
A:hover {COLOR: yellow }
TH { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#D098FF; background-color: #222244}
TD { border-style: outset; border-color: #336699;border-width: 1;font-family:Arial ; font-size:10pt; font-weight:bold; color:#FFFFC0; background-color: #333355}
BODY { font-family:Arial ; font-size:10pt; color:#EFFFFF}
input { font-family: Verdana ; font-size:10pt;}
img { border-width: 0}
table {border-color: #003366;  border-width: 1}
-->
</style>
</head>
<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<form method="POST">
<input type="hidden" name="b12" value="<?php echo $b12; ?>">
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="center">
  <center>
  <table border="1" width="600" cellspacing="0" bgcolor="#000066" cellpadding="0">
    <tr>
    <td width=20>Check to edit</td>
	  <td align=center colspan=2><FONT SIZE="5">Mass Edit Trades</FONT></td>
    </tr>
    <tr>
    <td><input type="checkbox" name="xa15" value="1"></td>
      <td>Ratio Limit On/Off:</td>
      <td valign="top"><input type="checkbox" name="a15" value="1">Check for ON, uncheck for OFF</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="xa8" value="1"></td>
      <td>Ratio Limit Value:<br><small>example: 1.8</small></td>
      <td valign="top"><input type="text" size="10" maxlength="5" name="a8" value=""></td>
    </tr>
    <tr>
    <td><input type="checkbox" name="xa9" value="1"></td>
      <td>Force-Auto-Suspend On/Off:</td>
      <td valign="top"><input type="checkbox" name="a9" value="1">Check for ON, uncheck for OFF</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="xa18" value="1"></td>
      <td>Hourly Force:</td>
      <td valign="top"><input type="text" size="10" maxlength="50" name="a18" value=""></td>
    </tr>
    <tr>
      <td colspan=3><input type="submit" name="action" value="Edit Values" style="font-weight: bold; font-size: 10 px; width: 100">
      <input type="submit" name="action" value="Back To Menu" style="font-weight: bold; font-size: 10 px; width: 100"></td>
    </tr>
  </table>
  
  </center>
</div>
</form>
<?php
}
#####################
function msave()
{
    global $a8,$a9,$a15,$a18,$xa8,$xa9,$xa15,$xa18;
    if (strlen($a8) > 0) $a8 *= 100;
    if ($xa15) {
        if ($a15) $query = "update trade set a15 = '-1'";
        else $query = "update trade set a15 = '0'";
        $result = mysql_query($query);
        if(!$result) error_message(sql_error());
        if ($a15) echo "Ratio Limit On";
        else echo "Ratio Limit Off";
        echo "<br>";
    }
    if ($xa9) {
        if ($a9) $query = "update trade set a9 = '-1'";
        else $query = "update trade set a9 = '0'";
        $result = mysql_query($query);
        if(!$result) error_message(sql_error());
        if ($a9) echo "Force-Auto-Suspend On";
        else echo "Force-Auto-Suspend Off";
        echo "<br>";
    }
    if ($xa8) {
        if (strlen($a8) > 0) $query = "update trade set a8 = '$a8'";
        else die("Error: You checked a field but left it empty<br>please go back");
        $result = mysql_query($query);
        if(!$result) error_message(sql_error());
        $a8 /= 100;
        echo "Ratio Limit Set To: $a8";
        echo "<br>";
    }
    if ($xa18) {
        if (strlen($a18) > 0) $query = "update trade set a18 = '$a18'";
        else die("Error: You checked a field but left it empty<br>please go back");
        $result = mysql_query($query);
        if(!$result) error_message(sql_error());
        echo "Hourly Force Set To: $a18";
        echo "<br>";
    }
    if (!($xa15 || $xa9 || $xa8 || $xa18))
    {
        echo "No changes made";
    }
}
#####################

