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

function idexists($user)
{
    global $ex;

	$sql = "select * from $GLOBALS[usertable] where lower(name)='$user'";
    mysql_connect($GLOBALS[host],$GLOBALS[user],$GLOBALS[pass]);
    @mysql_select_db($GLOBALS[database]) or die( "Unable to select database idexists");
	$query = mysql_query($sql);
	$rows = mysql_num_rows($query);

if ($rows > 0){ mysql_close (); $ex = 1; return 1;}

$sql = "select * from $GLOBALS[admintable] where username='$user'";
$query = mysql_query($sql);
$rows = mysql_num_rows($query);

if ($rows > 0){ mysql_close (); $ex = 2; return 1;}
mysql_close ();
$ex=0;
return 0;
}


require("config.php");
langlogin();

$message = '';
if (!$loginuser){
print "<html>\n";
print "<head>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" text=\"#000000\">\n";
print "<table border=\"0\" width=\"99%\" align=\"center\" bgcolor=\"#375288\" height=\"396\">\n";
print "  <tr align=\"center\"> \n";
print "    <td valign=\"top\"> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 align=\"center\" width=\"99%\" height=\"396\">\n";
print "        <tr bgcolor=\"#FFFFFF\"> \n";
print "          <td valign=\"top\"> \n";
print "            <div align=\"center\">\n";
print "              <p><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">\n";
print "                ".LOGINTOUSE."</font></b></p>\n";
print "              <form method=\"post\" action=\"$PHP_SELF\">\n";
print "                <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> <b>\n";
print "                <input type=\"hidden\" name=\"go\" value=\"close\">\n";
print "                </b> </font> \n";
print "                <table width=\"210\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\" align=\"center\">\n";
print "                  <tr> \n";
print "                    <td valign=\"top\" width=\"74\" bgcolor=\"#EFEFEF\"> \n";
print "                      <div align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".ACCOUNTID."</font></div>\n";
print "                    </td>\n";
print "                    <td colspan=\"3\" valign=\"top\" width=\"154\"> \n";
print "                      <div align=\"left\"> \n";
print "                        <input type=\"text\" name=\"loginuser\" size=\"20\" maxlength=\"50\">\n";
print "                      </div>\n";
print "                    </td>\n";
print "                  </tr>\n";
print "                  <tr> \n";
print "                    <td valign=\"top\" height=\"21\" width=\"74\" bgcolor=\"#EFEFEF\"> \n";
print "                      <div align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".PASSWORD."</font></div>\n";
print "                    </td>\n";
print "                    <td valign=\"top\" height=\"21\" colspan=\"3\" width=\"154\"> \n";
print "                      <div align=\"left\"> \n";
print "                        <input type=\"password\" name=\"loginpw\" size=\"20\" maxlength=\"40\">\n";
print "                        <br>\n";
print "                        <input type=\"submit\" name=\"Submit2\" value=\"".LOGINNOW."\">\n";
print "                        <br>\n";
print "                      </div>\n";
print "                    </td>\n";
print "                  </tr>\n";
print "                </table>\n";
print "              </form>\n";
print "              <p>&nbsp;</p>\n";
print "              <p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><i>".DONTHAVEACCT."</i><br>\n";
print "                <a href=javascript:opener.parent.location=\"$signupphp\";self.close()>".CLOSEWINDOW."</a><br>".ANDJOIN."</font></p>\n";
print "              <p>&nbsp;</p>\n";
print "              </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;
}
$ex=0;
$loginuser = strtolower($loginuser);
idexists($loginuser);
if ($ex == 0 ) errormsg(INVALIDUSER);
mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");
if ($ex==2) $query="SELECT * FROM $admintable WHERE username='$loginuser'";
else $query="SELECT * FROM $usertable WHERE name='$loginuser'";
$result=mysql_query($query);


$pw=mysql_result($result,0,"password");
if ($pw != $loginpw) {$message = INVALIDPASS; errormsg($message);}

$name=mysql_result($result,0,"name");
$name = strtolower($name);

$idresult=mysql_query("SELECT id FROM $imagetable WHERE name = '$u' LIMIT 1") or die(mysql_error());
$nopic = mysql_num_rows($idresult);
if ($nopic > 0) $to=mysql_result($idresult,0,"id");
else {
$idresult=mysql_query("SELECT id FROM $imagetable WHERE status = 'active' LIMIT 1") or die(mysql_error());
if (mysql_num_rows($idresult) > 0)
$to=mysql_result($idresult,0,"id");
}


mysql_close ();

if ($message) { errormsg ($message); exit; }

if  ($ex==2) {
header ("Set-Cookie: logged=$loginuser; expires=Friday, 16-Jan-2037 00:00:00 GMT; path=/;");
header ("Set-Cookie: ivadmin=$pw; expires=Friday, 16-Jan-2037 00:00:00 GMT; path=/;");
}
else
header ("Set-Cookie: logged=$loginuser; expires=Friday, 16-Jan-2037 00:00:00 GMT; path=/;");
if ($go == "close") {
print "<html>\n";
print "<head>\n";
print "<title>".NOWLOGGEDIN."</title>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
print "</head>\n";
print "<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" text=\"#000000\">\n";
print "<table border=\"0\" width=\"98%\" align=\"center\" bgcolor=\"#375288\" height=\"396\">\n";
print "  <tr align=\"center\"> \n";
print "    <td valign=\"top\"> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 align=\"center\" width=\"99%\" height=\"395\">\n";
print "        <tr bgcolor=\"#FFFFFF\"> \n";
print "          <td valign=\"top\"> \n";
print "            <div align=\"center\"> \n";
print "              <p>&nbsp;</p>\n";
print "              <p align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><i>\n";
print "                ".WELCOMEBACK." $loginuser!<br>".NOWLOGGEDIN."</i><br><br>\n";
print "                <p>&nbsp; </p>\n";
print "  <a href=javascript:opener.parent.location=\"$GLOBALS[votephp]?id=$to\";self.close()>".CLOSEWINDOW."</a>";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;

}
elseif ($go == "user") header ("Refresh: 1; url=$gophp?go=userphp");
elseif ($go == "vote") header ("Refresh: 1; url=$votephp");
elseif ($go == "mail") header ("Refresh: 1; url=$mailphp?to=$goto");
elseif ($go == "friend") header ("Refresh: 1; url=$mailphp?imgid=$imgid&type=outer");
elseif ($go == "chat") header ("Refresh: 1; url=$chaturl");
elseif ($go == "comment") header ("Refresh: 1; url=$mailphp?to=$goto&type=comment");
else header ("Refresh: 1; url=$votephp");
echo LOGGINGIN;
//  Image Vote(c) 2001 ProPHP.Com
?>
