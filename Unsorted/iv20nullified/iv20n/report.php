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
langreport();
langindex();
langlogin();

mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");

if ($type=="comment"){

mysql_query("DELETE FROM $commenttable where id = '$id'") or die(mysql_error());

print "<html>\n";
print "<head>\n";
print "<title>".COMRMV."</title>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" text=\"#000000\">\n";
print "<table border=\"0\" width=\"98%\" align=\"center\" bgcolor=\"#375288\" height=\"396\">\n";
print "  <tr align=\"center\"> \n";
print "    <td valign=\"top\"> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 align=\"center\" width=\"99%\" height=\"395\">\n";
print "        <tr bgcolor=\"#FFFFFF\"> \n";
print "          <td valign=\"top\"> \n";
print "            <div align=\"center\"><br> \n";
print COMRMV."<br><br>\n";
if (isset($rnum)) {
   echo "<a href=javascript:opener.parent.location=\"$votephp?id=$uid&rnum=$rnum\";self.close()>".CLOSEWINDOW."</a></p>\n";
             }
else {
    echo "<a href=\"$profilephp?u=$u&id=$uid\">";
    echo CONTIN;
    echo "</a></p>\n";
              }
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;

}
if (isset($aself)) errormsg("<br><261>");
if (!$reason) {?>
<html>
<head>
<title>Report This Image</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<form method="post" action="<?=$reportphp?>">
    <input type="hidden" name="id" value="<?=$id?>" maxlength="50" size="20">
  <p><font size="2"><b><? print RPTIMG;?></b></font></p>
  <p><font size="2"><? print IMGNO;?> <?=$id?></font></p>
  <p><font size="2"><? print WHYRPT;?><br>
    <input type="text" name="reason" maxlength="50" size="20">
    </font></p>
  <p> <font size="2">
    <input type="submit">
    </font></p>
</form>
</body>
</html>
<?
exit;
}


$result=mysql_query("SELECT reported FROM $imagetable WHERE id = '$id'") or die(mysql_error());
$reports=mysql_result($result,0,"reported");
$reports++;
mysql_query("UPDATE $imagetable SET reported = '$reports', reason = '$reason' WHERE id = '$id'") or die(mysql_error());
if ( $reports >= $maxreport)
mysql_query("UPDATE $imagetable SET status = 'reported' WHERE id = '$id'") or die(mysql_error());
mysql_close ();


print "<html>\n";
print "<head>\n";
print "<title>".THANKS."</title>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" text=\"#000000\">\n";
print "<table border=\"0\" width=\"98%\" align=\"center\" bgcolor=\"#375288\" height=\"396\">\n";
print "  <tr align=\"center\"> \n";
print "    <td valign=\"top\"> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 align=\"center\" width=\"99%\" height=\"395\">\n";
print "        <tr bgcolor=\"#FFFFFF\"> \n";
print "          <td valign=\"top\"> \n";
print "            <div align=\"center\"><br> \n";
print REPORTED."<br><br>\n";
print "<a href=\"javascript:window.close();\">".CLOSEWINDOW."</a></p>\n";
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;

//  Image Vote (c)2002 ProPHP.Com   ?>
