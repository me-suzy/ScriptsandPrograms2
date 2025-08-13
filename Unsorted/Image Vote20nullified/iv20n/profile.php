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

require ("config.php");

mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");
if (isset($id) && (!isset($u))) {
$imageresult=mysql_query("SELECT name FROM $imagetable WHERE id = '$id'") or die(mysql_error());
$u = mysql_result($imageresult,0,"name");
}

langprofile();
langlogin();
langindex();
langtop();

$userresult=mysql_query("SELECT * FROM $usertable WHERE name = '$u'") or die(mysql_error());
$nomail = mysql_num_rows($userresult);
if ($nomail == 0) { print NOPROFILE; exit;}

if (!isset($id)) {
$idresult=mysql_query("SELECT id FROM $imagetable WHERE name = '$u' LIMIT 1") or die(mysql_error());
$nopic = mysql_num_rows($idresult);
if ($nopic > 0) $id=mysql_result($idresult,0,"id");
}
else $nopic = 1;
if ($nopic > 0 ) {
$imageresult=mysql_query("SELECT * FROM $imagetable WHERE id = '$id'") or die(mysql_error());
$average=mysql_result($imageresult,0,"average");
$votes=mysql_result($imageresult,0,"total");
$category=mysql_result($imageresult,0,"category");
$description=mysql_result($imageresult,0,"description");
$url=mysql_result($imageresult,0,"url");
}
else { $average = "0"; $votes = "0"; $category = NOPICTURE; $description = "N/A"; $url = "nopic.gif"; $commentson=0;}
$user = mysql_fetch_array($userresult);
$age=$user["age"];
foreach ( $extras as $marker ) {  // display values
 $extra[$marker][value]=$user["$marker"];
 $extra[$marker][value]=strip_tags($extra[$marker][value]);
   }

$nomail = 0;
if ($commentson > 0) {
// get mails for user
if ($commentson == 1) $mailresult=mysql_query("SELECT * FROM $commenttable WHERE name='$id' and status='ok' ORDER BY id DESC limit 20");
else $mailresult=mysql_query("SELECT * FROM $commenttable WHERE name='$id' ORDER BY id DESC limit 20");
$nomail = mysql_num_rows($mailresult);
}
mysql_close ();
?>
<html>
<head>
<title><? print PROFILE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div
align="center">
  <center>
    <table border="0" style="border: 2px solid rgb(0,0,0)" bgcolor="#000066" width="400" cellpadding="2" align="center" cellspacing="0">
      <tr bgcolor="#000033" valign="top"> 
        <td colspan="2" height="161"> 
          <table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr> 
              <td width="155"> 
                <div align="center"><img src="<?=$url?>" width="145"> </div>
              </td>
              <td valign="top"> 
                <p><b><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1" color="FFFFFF"><? print PROFILE;?><br>
                  <? print RATING; ?>: <?=$average?></b>
				 
                  ( <? print $votes." ".V; ?>)</font>
				  <font color="#FFFFFF">
				  <b><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><br><? print CATEGORY; ?>:</font></b>
				  <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><?=$category?></font>
				  <b><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><br><? print AGE; ?>:</font></b>
				  <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><?=$age?></font>

<? foreach ( $extras as $marker ) {  // display extra fields
if ($extra[$marker][value] != "") { ?>
<b><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><br><? echo $extra[$marker][name];?>: </font></b>
<font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? echo $extra[$marker][value]; ?></font>
<? }} ?>

				<b><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><br><? print ABOUTIMG; ?>:</font></b>
				<font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><?=$description?></font>
				</font>
				<br></p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr bgcolor="#e0e0e0"">
        <td colspan="2"> 
          <div align="center">
            <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="<?=$mailphp?>?to=<?=$u?>"><font size="1" face="MS Sans Serif, Tahoma, Verdana, Arial">
            <? print SENDPRIV; ?><br>
              </font></a></font><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><a href="javascript:window.close();"><? PRINT CLOSEWINDOW;?></a></font></p>
            <font size="1" face="MS Sans Serif, Tahoma, Verdana, Arial">
<?
if ($commentson > 0) {
$samples = "<b>".VIEWALLCOMS.":</b><br>";
$bgcolor = "#F2F2F2";
$count=0;
$samples.= "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">\n";
if ($nomail == 0) $samples .= "<tr bgcolor=\"$bgcolor\"><td><font color=\"#000000\" size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">".NOCOMMENTS."</font></td></tr>";
else {
while ($count <  $nomail) {
$fromuser=mysql_result($mailresult,$count,"fromuser");
$datestamp=mysql_result($mailresult,$count,"datestamp");
$body=mysql_result($mailresult,$count,"body");
$comid=mysql_result($mailresult,$count,"id");
$commentrate=mysql_result($mailresult,$count,"subject");
$samples.= "<tr bgcolor=\"$bgcolor\"><td>\n";
$samples.= "<font color=\"#000000\" size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">".BY.": <a href=\"$profilephp?u=$fromuser\">$fromuser</a>&nbsp;($datestamp)&nbsp;&nbsp;".RATING.": <font color=\"red\">$commentrate</font>";
if (isset($ivadmin)) $samples .= "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp[<a href=\"$reportphp?id=$comid&uid=$id&u=$u&type=comment\">".REMOVE."</a>]";
$samples .= "<br>$body</font></td></tr>";
if ( $bgcolor == "#F2F2F2") $bgcolor = "#E0E0E0";
elseif ( $bgcolor == "#E0E0E0") $bgcolor = "#F2F2F2";
$count++;
}
}
$samples.= "<tr bgcolor=\"$bgcolor\"><td><font color=\"#000000\" size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">\n";
$samples.= "<center><a href=\"$mailphp?to=$id&type=comment&rnum=$rnum&c=$c\">".POSTCOMMENT."</a></td></tr>";
$samples.="</table>";
print $samples;
}

?>

         </font>
          </div>
        </td>
      </tr>

      <tr bgcolor="#FFFFFF"> 
        <td colspan="2">
          <p align="center">     <font size="1" face="MS Sans Serif, Tahoma, Verdana, Arial"><a href="javascript:window.close();"><? PRINT CLOSEWINDOW;?></a></font></p>
        </td>
      </tr>
      <tr> 
        <td width="94"></td>
        <td width="341"></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </center>
</div>
</body>
</html>

<? //  Image Vote(c) 2001 ProPHP.Com   ?>
