<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Image Vote - Photo Rating System                  //
// Release Version      : 2.0.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////

if (!isset($topvote)) $topvotes  = 2;  // number of votes needed to display in Top 10 listings
else $topvotes = $topvote;
$top10votes = $topvotes;
if (!isset($topnumber)) $topnumber  = 10;  // number of listings to display on the top list
if (!isset($rowcolor1)) $rowcolor1 = "#E6E6E6"; // alternating row colors
if (!isset($rowcolor2)) $rowcolor2 = "#F3F3F3"; // alternating row colors
if (!isset($listunique)) $listunique = "yes";

require_once ("config.php");
langtop();

$originalc=$c;
// $u (user)  $c (category)  $f (function) $id (img id #)  $w top or bottom $n (start from)
if(!$c) $c = "all";
if (!$n) $n = "0";
$n1=$n-10;
$n2=$n+10;

$lastpicture="";

// connect to the database until the end
mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");

if ($c == "all") {
if ($w == "bottom")
$result=mysql_query("SELECT name, id, url, MAX(average) as average, total FROM $imagetable WHERE status = 'active' and total >= $topvotes group by name order by average LIMIT $n,$topnumber") or die(mysql_error());
else
$result=mysql_query("SELECT name, id, url, MAX(average) as average, total FROM $imagetable WHERE status = 'active' and total >= $topvotes group by name order by average DESC LIMIT $n,$topnumber") or die(mysql_error());

}
else {
if ($w == "bottom")
$result=mysql_query("SELECT name, id, url, MAX(average) as average, total FROM $imagetable WHERE category = '$c' and total >= $topvotes and status = 'active' group by name order by average LIMIT $n,$topnumber") or die(mysql_error());
else $result=mysql_query("SELECT name, id, url, MAX(average) as average, total FROM $imagetable WHERE category = '$c' and total >= $topvotes and status = 'active' group by name order by average DESC LIMIT $n,$topnumber") or die(mysql_error());
}
$rows = mysql_num_rows($result);
$rows--;


for ($num = 0; $num <= $topnumber; $num++) {
if ($rows >= $num) {
${$num."name"}=mysql_result($result,$num,"name");
${$num."id"}=mysql_result($result,$num,"id");
${$num."pic"}=mysql_result($result,$num,"url");
${$num."name"} = addslashes(${$num."name"});
$res1=mysql_query("select url, id from $imagetable where name = '${$num."name"}' order by average desc");
$highpic = mysql_result($res1,0,"url");
${$num."av"}=mysql_result($result,$num,"average");
${$num."votes"}=mysql_result($result,$num,"total");
${$num."pic"} = "<img src=\"".$highpic."\" width=\"80\" border=0>\n";
}
else {

${$num."name"}="none";
${$num."votes"}="0";
${$num."pic"} = "<img src=\"nopic.gif\" width=\"80\" border=0>\n";
}

}


// done with database?  better close it
mysql_close ();

?>
<? if (!isset($go)) {?>
<html>

<head>
<script LANGUAGE="JavaScript">
function fullScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=350,height=400');
}
</script>
<title><?=$sitename?></title>
<STYLE type=text/css>

A:visited 	{TEXT-DECORATION: underline}
A:hover 	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474}
A:link		{TEXT-DECORATION: underline}
A:active 	{TEXT-DECORATION: none}
BODY 		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
UL		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
LI 		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
P		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TD 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TR 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TEXTAREA	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
FORM 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
</STYLE>
</head>

<body bgcolor="#ffffff" text="#000000" link="#006699" alink="#000000" vlink="#000000" marginheight="0" marginwidth="0" topmargin=0 leftmargin=0 rightmargin=0>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr bgcolor="#375288">
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr bgcolor="#ffffff">
      <td valign="bottom" align="left" width="203">
        <div align="left"><a href="<?=$siteurl?>"><img src="picturevoting.gif" height="60" width="161" border="0" alt="Vote your opinion about pictures at <?=$sitename?>"></a> 
        </div>
      </td>
      <td align="right" nowrap valign="bottom" width="537">
        <div align="center"> </div>
      </td>
    </tr>
  </table>
</center>
<table border=0 cellpadding=2 cellspacing=2 width="760" align="center" bgcolor="#375288">
  <tr bgcolor="#FFFFFF"> 
    <td colspan="2">
<? } ?>
      <div align="center"><p><b> 
<? if ($c=="all" && $w == "bottom") print BOTTOM;
   elseif ($c=="all" && $w != "bottom") print TOP; 
   elseif ($c!="all" && $w == "bottom") print BOTTOM." ".ucfirst($c);
   else print TOP." ".ucfirst($c);
?>
          </b></p>
          </b><br><font size="1"><? print WITHVOTES; ?></font>
        </p>
        <table width="600" border="1" cellspacing="0" cellpadding="0">

<? for ($num = 0; $num < $topnumber; $num++) {
if ($bgcolor == $rowcolor2) $bgcolor = $rowcolor1; else $bgcolor = $rowcolor2;
?>

          <tr bgcolor="<?=$bgcolor?>">
            <td width="125"> 
              <div align="center"><a href="<?=$votephp?>?id=<?=${$num."id"}?>&c=<?=$c?>"><?=${$num."pic"}?></a></div>
            </td>
            <td width="204">#<? $num2 = $n + $num +1; print $num2; ?> - <?=${$num."av"}?><br>(<?=${$num."votes"}?> <? print V; ?>)</td>
            <td width="271"><?=${$num."name"}?></td>
          </tr>
<? } ?>

        </table>
        <p><? if ($n>=10) print "<a href=\"$gophp?go=topphp&c=$c&amp;w=$w&amp;n=$n1\">Previous 10</a> -";
         print " <a href=\"$gophp?go=topphp&c=$c&amp;w=$w&amp;n=$n2\">Next 10</a><br>";  ?></p>
       <? print CLICKIMG; ?><br>
        <a href="<?=$votephp?>"> <? print RETURNTO; ?></a>
<p> <? print ALSOVIEW;?><br>
<?
foreach ($categories as $a) {
print "<a href=\"$gophp?go=topphp&c=$a&amp;w=top\">".TOP." ".ucfirst($a)."</a> - <a href=\"$gophp?go=topphp&c=$a&amp;w=bottom\">".BOTTOM." ".ucfirst($a)."</a><br>";
}
?>
      </p>
        </div>
<? if (!$go) {?>
	</td>
     </tr>
</table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr>
      <td valign="center" align="center"> <br>
        &copy; 2001 <?=$sitename?> <br>
        <br>
      </td>
    </tr>
  </table>
  </center>
</body>
</html>
<? } //  Image Vote(c) 2001 ProPHP.Com   ?>