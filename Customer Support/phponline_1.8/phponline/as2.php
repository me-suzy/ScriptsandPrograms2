<?php
include_once('noca.php');
include_once('rcq.php');

$CCode = $HTTP_GET_VARS['ccode'];
$LanguageSel = $HTTP_GET_VARS['language_sel'];
$AdmID = $HTTP_GET_VARS['admid'];

list($usec, $sec) = explode(" ",microtime()); 
$TTime = ((double)$sec)+((double)$usec);

$dbh=mysql_connect($DBHost, $DBUsername, $DBPassword,true) or die ('res=0');
mysql_select_db($DBDatabase,$dbh);

$TTM = (1*time())-432000;
mysql_query("DELETE FROM msgdb WHERE ttime<$TTM",$dbh);
mysql_query("DELETE FROM cs WHERE lastact<$TTM",$dbh);
mysql_query("UPDATE cs SET assign=1 WHERE ccode=$CCode",$dbh);
mysql_close($dbh);


?>


<HTML>
<HEAD>
<meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">
<TITLE>phpOnline - Admin side chat script</TITLE>
</HEAD>
<BODY bgcolor="#FFFFFF">

<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
 HEIGHT="400" WIDTH="500" id="asc" ALIGN="">
 <PARAM NAME=movie VALUE="asc.swf?ccode=<?php echo $CCode;?>&language_sel=<?php echo $LanguageSel;?>&admid=<?php echo $AdmID;?>&ct=<?php echo $TTime;?>"> 
 <PARAM NAME=menu VALUE=false> 
 <PARAM NAME=quality VALUE=high> <PARAM NAME=scale VALUE=exactfit> <PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src="asc.swf?ccode=<?php echo $CCode;?>&language_sel=<?php echo $LanguageSel;?>&admid=<?php echo $AdmID;?>&ct=<?php echo $TTime;?>" menu=false quality=high scale=exactfit HEIGHT="400" WIDTH="500" bgcolor=#FFFFFF  NAME="asc" ALIGN=""
 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>
</BODY>
</HTML>