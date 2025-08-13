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

function error($message){
print $message;
exit;
}

function validemail($email) {
if (eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $email)) {
   return TRUE;
} else {
       return FALSE;
 }
}

function badwords($string) {
	global $bad_words;
	 for ($i=0; $i<count($bad_words); $i++)
     if (strstr(strtoupper($string), strtoupper($bad_words[$i])))
        return true;
 return false;
}



function messagesent ($to, $from) {
print "<html>\n";
print "<head>\n";
print "<title>".MSGSENT."</title>\n";
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
print "            <div align=\"center\"> \n";
print "              <p>&nbsp;</p>\n";
print "              <p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b>".THANKYOU."</b></font></p>\n";
print "              <p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"black\"><b>\n";
print "                ".MSGSENT."</b><br>\n";
print "                </font> </p>\n";
if ($GLOBALS[type] == "outer") 
{
print "                <p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><br>\n";
print "                <a href=".$GLOBALS[siteurl].$GLOBALS[mailphp]."?imgid=".$GLOBALS[imgid]."&type=outer&fromname=".$GLOBALS[fromname].">".MOREFRIENDS."</a></font> </p>\n";
}
else 
{
print "                <p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><br>\n";
print "                  ".CANVIEW."</font> </p>\n";
}
print "                <p>&nbsp; </p>\n";
print "                       <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"javascript:window.close();\">\n";
print "                ".CLOSEWINDOW."</a></font> \n";
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
}

function spaceback($input) {
$len = 75;
$l = 0;
$output = "";
for ($i = 0; $i < strlen($input); $i++) {
$char = substr($input,$i,1);
if ($char != " ") { $l++; } else { $l = 0; }
if ($l == $len) { $l = 0; $output .= " "; }
$output .= $char;
}
return($output);
}

function toofilthy () {
print "<html>\n";
print "<head>\n";
print "<title>$sitename</title>\n";
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
print "            <div align=\"center\"> \n";
print "              <p>&nbsp;</p>\n";
print "              <p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b>".BADLANG."</b></font></p>\n";
print "              <p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"black\"><b>\n";
print "                ".MSGNOTSENT."</b><br>\n";
print "                </font> </p>\n";
print "                <p>&nbsp; </p>\n";
print "                       <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"javascript:window.close();\">\n";
print "                ".CLOSEWINDOW."</a></font> \n";
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;
}

function commentposted ($to, $from) {
print "<html>\n";
print "<head>\n";
print "<title>".COMMENTPOSTED."</title>\n";
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
print "            <div align=\"center\"> \n";
print "              <p>&nbsp;</p>\n";
print "              <p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b>".THANKYOU."</b></font></p>\n";
print "              <p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"black\"><b>\n";
print COMMENTPOSTED."</b><br>\n";
print "                </font> </p>\n";
print "                <p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><br>\n";
print "                <p>&nbsp; </p>\n";

print "                       <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=javascript:opener.parent.location=\"$GLOBALS[votephp]?id=$to&rnum=$GLOBALS[rnum]&c=$GLOBALS[c]\";self.close()>\n";
print CLOSEWINDOW."</a></font> \n";
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
}

function messageform ($from, $to){
print "<html>\n";
print "<head>\n";
if ($GLOBALS[type] == "outer")  print "<title>$GLOBALS[whatdo]</title>\n";
else print "<title>$GLOBALS[whatdo] Image #$to</title>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" text=\"#000000\">\n";
print "<table border=\"0\" width=\"99%\" align=\"center\" bgcolor=\"#375288\" height=\"396\">\n";
print "  <tr align=\"center\"> \n";
print "    <td valign=\"top\"> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 align=\"center\" width=\"98%\">\n";
print "        <tr bgcolor=\"#FFFFFF\"> \n";
print "          <td valign=\"top\"> \n";
print "            <div align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><b>\n";

if ($GLOBALS[type] == "comment")  print "              $GLOBALS[whatdo] ".IMAGENO."$to</b></font> \n";
else   print "              $GLOBALS[whatdo] $to</b></font> \n";
print "              <form method=\"POST\" action=\"$GLOBALS[mailphp]\">\n";
print "                        <input type=\"hidden\" name=\"type\" value=\"$GLOBALS[type]\">\n";
if ($GLOBALS[type] != "outer")  {
print " <input type=\"hidden\" name=\"to\" value=\"$to\">\n";
print " <input type=\"hidden\" name=\"c\" value=\"$GLOBALS[c]\">\n";
print " <input type=\"hidden\" name=\"rnum\" value=\"$GLOBALS[rnum]\">\n";
}
else print " <input type=\"hidden\" name=\"imgid\" value=\"$GLOBALS[imgid]\">\n";
print "                <div align=\"center\">\n";
print "                  <center>\n";
print "                    <table border=\"0\">\n";
if ($GLOBALS[type] != "comment") {
if ($GLOBALS[type] == "outer") {
print "                      <tr> \n";
print "                        <td width=\"17%\" height=\"25\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".FROMNAME."</font></b></td>\n";
print "                        <td width=\"83%\" height=\"25\"> \n";
print "                          <input type=\"text\" name=\"fromname\" value=\"$GLOBALS[fromname]\" size=\"25\" maxlength=\"35\"";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td width=\"17%\" height=\"25\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".FRIENDSNAME."</font></b></td>\n";
print "                        <td width=\"83%\" height=\"25\"> \n";
print "                          <input type=\"text\" name=\"toname\" size=\"25\" maxlength=\"35\"";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td width=\"17%\" height=\"25\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".FRIENDSEMAIL."</font></b></td>\n";
print "                        <td width=\"83%\" height=\"25\"> \n";
print "                          <input type=\"text\" name=\"toemail\" size=\"25\" maxlength=\"35\"";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td width=\"17%\" height=\"25\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".SUBJECT."</font></b></td>\n";
print "                        <td width=\"83%\" height=\"25\"> \n";
print "                          <input type=\"text\" name=\"subject\" size=\"25\" maxlength=\"25\"";
print " value=\"".FRIENDMSGTITLE."\">\n";
print "                      </tr>\n";
}
else {print "                      <tr> \n";
print "                        <td width=\"17%\" height=\"25\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".SUBJECT."</font></b></td>\n";
print "                        <td width=\"83%\" height=\"25\"> \n";
print "                          <input type=\"text\" name=\"subject\" size=\"25\" maxlength=\"25\">\n";
print "                         </td>\n";
print "                      </tr>\n";
}
}
else {
print "                      <tr> \n";
print "                        <td width=\"17%\" height=\"25\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">Rating:</font></b></td>\n";
print "                        <td width=\"83%\" height=\"25\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n";
print "                            <select name=\"subject\"><option value=\"10\" selected>10</option><option value=\"9\">9</option><option value=\"8\">8</option><option value=\"7\">7</option><option value=\"6\">6</option><option value=\"5\">5</option><option value=\"4\">4</option><option value=\"3\">3</option><option value=\"2\">2</option><option value=\"1\">1</option></select>\n";
print "                          (".PLEASERATE.")</font></td>\n";
print "                      </tr>\n";

}
print "                      <tr> \n";
print "                        <td width=\"17%\" valign=\"top\" height=\"67\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".MESSAGE."</font></b></td>\n";
print "                        <td width=\"83%\" valign=\"top\" height=\"79\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n";
print "                          <textarea rows=\"9\" name=\"body\" cols=\"25\" wrap=\"VIRTUAL\">";
if ($GLOBALS[type] == "outer") print FRIENDMSG1.$GLOBALS[siteurl]."?id=".$GLOBALS[imgid].FRIENDMSG2;
print "                          </textarea>\n";
print "                          </font></td>\n";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td colspan=\"2\" valign=\"top\" height=\"21\"> \n";
print "                          <div align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n";
print "                            <input type=\"submit\" name=\"send\">\n";
print "                            </font></div>\n";
print "                        </td>\n";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td width=\"100%\" valign=\"top\" height=\"21\" colspan=\"2\"> \n";
print "                          <div align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n";
print "                            <input type=\"submit\" value=\"".CLOSEWINDOW."\" onClick=\"javascript:window.close();\" name=\"submit\">\n";
print "                            </font> </div>\n";
print "                        </td>\n";
print "                      </tr>\n";
print "                    </table>\n";
print "                  </center>\n";
print "                </div>\n";
print "              </form>\n";
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";

}

require ("config.php");
langlogin();
langmail();

mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");

$cookieinfo = $HTTP_COOKIE_VARS[logged];

$usertest=mysql_query("SELECT name from $usertable where name = '$cookieinfo' && validate='ok'");
if (mysql_num_rows($usertest) < 1) { 
	if (!$type) {$type = "private";}
if ($type == "comment") {
    $dbname = $commenttable;
    $whatdo = POSTCOMON;
    $gowhere = "comment";
}
elseif ($type == "outer") {
    $whatdo = EMAILFRIEND;
    $gowhere = "friend";
}
else { $dbname = $mailtable;
    $whatdo = SENDMSGTO;
    $gowhere = "mail";
}

print "<html>\n";
print "<head>\n";
print "<title>$whatdo $to</title>\n";
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
print "              <p><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".MUSTLOGIN."</font></b></p>\n";
print "              <form method=\"post\" action=\"$loginphp\">\n";
print "                <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> <b>\n";
print "                <input type=\"hidden\" name=\"go\" value=\"$gowhere\">\n";
if ($type == "outer") print "                <input type=\"hidden\" name=\"imgid\" value=\"$imgid\">\n";
else print "                <input type=\"hidden\" name=\"goto\" value=\"$to\">\n";
print "                </b> </font> \n";
print "                <table width=\"210\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\" align=\"center\">\n";
print "                  <tr> \n";
print "                    <td valign=\"top\" width=\"74\" bgcolor=\"#EFEFEF\"> \n";
print "                      <div align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".ACCOUNTID."</font></div>\n";
print "                    </td>\n";
print "                    <td colspan=\"3\" valign=\"top\" width=\"154\"> \n";
print "                      <div align=\"left\"> \n";
print "                        <input type=\"text\" name=\"loginuser\" size=\"15\" maxlength=\"20\">\n";
print "                      </div>\n";
print "                    </td>\n";
print "                  </tr>\n";
print "                  <tr> \n";
print "                    <td valign=\"top\" height=\"21\" width=\"74\" bgcolor=\"#EFEFEF\"> \n";
print "                      <div align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".PASSWORD."</font></div>\n";
print "                    </td>\n";
print "                    <td valign=\"top\" height=\"21\" colspan=\"3\" width=\"154\"> \n";
print "                      <div align=\"left\"> \n";
print "                        <input type=\"text\" name=\"loginpw\" size=\"15\" maxlength=\"40\">\n";
print "                        <br>\n";
print "                        <input type=\"submit\" name=\"Submit2\" value=\"".LOGINNOW."\">\n";
print "                        <br>\n";
print "                      </div>\n";
print "                    </td>\n";
print "                  </tr>\n";
print "                </table>\n";
print "              </form>\n";
print "              <p>&nbsp;</p>\n";
print "              <p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><i>Don't \n";
print "                have an account?</i><br>\n";
print "                <a href=\"javascript:window.close();\">".CLOSEWINDOW."</a><br>".ANDJOIN."</font></p>\n";
print "              <p>&nbsp;</p>\n";
print "              </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;
//end new code
}


if (!$type) {$type = "private";}
if ($type == "comment") {
    $dbname = $commenttable;
    $whatdo = POSTCOMON;
    $gowhere = "comment";
}
elseif ($type == "outer") {
    $whatdo = EMAILFRIEND;
    $gowhere = "friend";
}
else { $dbname = $mailtable;
    $whatdo = SENDMSGTO;
    $gowhere = "mail";
}

// delete e-mail
if ($delete) {

$mailresult=mysql_query("SELECT name FROM $dbname WHERE id = '$delid'") or die(mysql_error());
$name=mysql_result($mailresult,0,"name");
$name=strtolower($name);$cookieinfo=strtolower($cookieinfo);

if ($name != $cookieinfo) {print NOTYOURS;exit;}
mysql_query("DELETE LOW_PRIORITY FROM $dbname WHERE id = '$delid'");
mysql_close ();
print "<html>\n";
print "<head>\n";
print "<title>".MSGDEL."</title>\n";
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
print "            <div align=\"center\"> \n";
print "              <p>&nbsp;</p>\n";
print "              <p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b>".THANKYOU."</b></font></p>\n";
print "              <p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"black\"><b>".MSGDEL."</b><br>\n";
print "                </font> </p>\n";
print "                <p>&nbsp; </p>\n";
print "                       <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"javascript:window.close();\">".CLOSEWINDOW."</a></font> \n";
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;

}


// read e-mail
if (isset($id)) {
$mailresult=mysql_query("SELECT * FROM $mailtable WHERE id = '$id'") or die(mysql_error());
$nomail = mysql_num_rows($mailresult);
if ($nomail < 1) { print MSGDEL; exit;}
$count = 0;
$id=mysql_result($mailresult,$count,"id");
$name=mysql_result($mailresult,$count,"name");
if ($name != $cookieinfo) {print NOTYOURS;exit;}
$fromuser=mysql_result($mailresult,$count,"fromuser");
$subject=mysql_result($mailresult,$count,"subject");
$datestamp=mysql_result($mailresult,$count,"datestamp");
$body=mysql_result($mailresult,$count,"body");
$status=mysql_result($mailresult,$count,"status");
print "<html>\n";
print "<head>\n";
print "<title>".READMSG."</title>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" text=\"#000000\">\n";
print "<table border=\"0\" width=\"99%\" align=\"center\" bgcolor=\"#375288\" height=\"396\">\n";
print "  <tr align=\"center\"> \n";
print "    <td valign=\"top\"> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 align=\"center\" width=\"98%\">\n";
print "        <tr bgcolor=\"#FFFFFF\"> \n";
print "          <td valign=\"top\"> \n";
print "            <div align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><b>".MSGFROM."</b></font> \n";
print "              <form method=\"POST\" action=\"$PHP_SELF\">\n";
print "                          <input type=\"hidden\" name=\"subject\" value=\"".RE." $subject\">\n";
print "                          <input type=\"hidden\" name=\"delid\" value=\"$id\">\n";
print "                <div align=\"center\">\n";
print "                  <center>\n";
print "                    <table border=\"0\">\n";
print "                      <tr> \n";
print "                        <td width=\"17%\" height=\"25\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".SUBJECT."</font></b></td>\n";
print "                        <td width=\"83%\" height=\"25\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">$subject \n";
print "                          </font></td>\n";
print "                        <input type=\"hidden\" name=\"to\" value=\"$fromuser\">\n";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td width=\"17%\" valign=\"top\" height=\"40\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".MESSAGE."</font></b></td>\n";
print "                        <td width=\"83%\" valign=\"top\" height=\"40\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n";
print "                      $body\n";
print "                          </font></td>\n";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td width=\"17%\" valign=\"top\" height=\"67\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".REPLY."</font></b></td>\n";
print "                        <td width=\"83%\" valign=\"top\" height=\"79\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n";
print "                          <textarea rows=\"2\" name=\"body\" cols=\"25\" wrap=\"VIRTUAL\"></textarea>\n";
print "                          </font></td>\n";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td colspan=\"2\" valign=\"top\" height=\"21\"> \n";
print "                          <div align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n";
print "                            <input type=\"submit\" value=\"".SENDREPLY."\" name=\"send\"> \n";
print "                            <input type=\"submit\" value=\"".DELMSG."\" name=\"delete\">\n";
print "                            </font></div>\n";
print "                        </td>\n";
print "                      </tr>\n";
print "                      <tr> \n";
print "                        <td width=\"100%\" valign=\"top\" height=\"21\" colspan=\"2\"> \n";
print "                          <div align=\"center\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> \n";
print "                            <input type=\"submit\" value=\"".CLOSEWINDOW."\" onClick=\"javascript:window.close();\" name=\"submit\">\n";
print "                            </font> </div>\n";
print "                        </td>\n";
print "                      </tr>\n";
print "                    </table>\n";
print "                  </center>\n";
print "                </div>\n";
print "              </form>\n";
print "            </div>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
mysql_query("UPDATE $mailtable SET status = 'read' where id = '$id'") or die(mysql_error());
mysql_close ();
exit;
}


if (isset($logged)) {

$toresult=mysql_query("SELECT * FROM $usertable WHERE name = '$u'") or die(mysql_error());
$fromresult=mysql_query("SELECT * FROM $usertable WHERE name = '$cookieinfo'") or die(mysql_error());
$fromuser = mysql_fetch_array($fromresult);
if ($fromuser["validate"] != "ok" && $validate != "no") {
print "<html>\n";
print "<head>\n";
print "<title>$whatdo $to</title>\n";
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
print "            <br><p align=\"center\">\n";

print MUSTVAL."</p><p align=\"center\"><a href=\"".$siteurl.$userphp."?action=resendval&then=e\">".RESENDVAL."</a>";
print " </p>";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</td></tr>";
print "</table>\n";
print "</body>\n";
print "</html>\n";

exit;}
}
else {
print "<html>\n";
print "<head>\n";
print "<title>$whatdo $to</title>\n";
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
print "              <p><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">".MUSTLOGIN."</font></b></p>\n";
print "              <form method=\"post\" action=\"$loginphp\">\n";
print "                <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"> <b>\n";
print "                <input type=\"hidden\" name=\"go\" value=\"$gowhere\">\n";
if ($type == "outer") print "                <input type=\"hidden\" name=\"imgid\" value=\"$imgid\">\n";
else print "                <input type=\"hidden\" name=\"goto\" value=\"$to\">\n";
print "                </b> </font> \n";
print "                <table width=\"210\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\" align=\"center\">\n";
print "                  <tr> \n";
print "                    <td valign=\"top\" width=\"74\" bgcolor=\"#EFEFEF\"> \n";
print "                      <div align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".ACCOUNTID."</font></div>\n";
print "                    </td>\n";
print "                    <td colspan=\"3\" valign=\"top\" width=\"154\"> \n";
print "                      <div align=\"left\"> \n";
print "                        <input type=\"text\" name=\"loginuser\" size=\"15\" maxlength=\"20\">\n";
print "                      </div>\n";
print "                    </td>\n";
print "                  </tr>\n";
print "                  <tr> \n";
print "                    <td valign=\"top\" height=\"21\" width=\"74\" bgcolor=\"#EFEFEF\"> \n";
print "                      <div align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".PASSWORD."</font></div>\n";
print "                    </td>\n";
print "                    <td valign=\"top\" height=\"21\" colspan=\"3\" width=\"154\"> \n";
print "                      <div align=\"left\"> \n";
print "                        <input type=\"text\" name=\"loginpw\" size=\"15\" maxlength=\"40\">\n";
print "                        <br>\n";
print "                        <input type=\"submit\" name=\"Submit2\" value=\"".LOGINNOW."\">\n";
print "                        <br>\n";
print "                      </div>\n";
print "                    </td>\n";
print "                  </tr>\n";
print "                </table>\n";
print "              </form>\n";
print "              <p>&nbsp;</p>\n";
print "              <p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><i>Don't \n";
print "                have an account?</i><br>\n";
print "                <a href=\"javascript:window.close();\">".CLOSEWINDOW."</a><br>".ANDJOIN."</font></p>\n";
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

if(isset($send)) {
if (!$subject && $type != "comment") $message.= SUBJECTREQ."<br>";
elseif (!$subject && $type = "comment") $subject = "none";
if ($type == "outer") 
{
if (!$toemail && !validemail($toemail)) $message.= ENTERFRIENDSEMAIL."<br>";
if (!$toname) $message.= ENTERFRIENDSNAME."<br>";
if (!$fromname) $message.= ENTERFROMNAME."<br>";
}
if (!isset($body)) $message.= MSGREQ."<br>";
if (isset($message)) {errormsg ($message); exit;}
$body = strip_tags($body);
$body = spaceback($body);
if (badwords($body)) toofilthy();
if (badwords($subject)) toofilthy();
$subject = strip_tags($subject);
$subject = spaceback($subject);

$status = "new";
$datestamp=date('m-d-Y H:i');
if ($type != "outer") mysql_query("INSERT INTO $dbname (name, fromuser, subject, body, datestamp, status) VALUES ('$to','$cookieinfo','$subject','$body','$datestamp','$status')") or die(mysql_error());
if ($type == "private") {messagesent($to,$cookieinfo);
$result=mysql_query("SELECT * FROM $usertable WHERE name = '$to'") or die(mysql_error());
$notifypriv=mysql_result($result,0,"notifypriv");
if ($notifypriv == "1") {
$email=mysql_result($result,0,"email");
$recipient = "$to <$email>";
$headers = "From: $sitename <$admin>\n";
if ($email != "" && $email != "null@null") mail($recipient, $subjectmsg, $notifmsg, $headers);
}
}
elseif ($type == "outer") {
$result=mysql_query("SELECT * FROM $usertable WHERE name='$cookieinfo'") or die(mysql_error());
$user=mysql_fetch_array($result);
$fromemail=$user["email"];
$recipient = "$toname <$toemail>";
$headers = "From: $fromname <$fromemail>\n";
$body .= FRIENDMSGBOTTOM;
mail($recipient, $subject, $body, $headers);
messagesent($toname,$cookieinfo);
}
else {commentposted($to,$cookieinfo);
$result=@mysql_query("SELECT * FROM $imagetable WHERE id = '$to'") or die(mysql_error());
$notifypub=@mysql_result($result,0,"notifypub");
$name=@mysql_result($result,0,"name");
if ($notifypub == "1") {
$poster = $cookieinfo;
$result=@mysql_query("SELECT * FROM $usertable WHERE name = '$name'") or die(mysql_error());
$email=@mysql_result($result,0,"email");
$recipient = "$name <$email>";
$headers = "From: $sitename <$admin>\n";
if ($email != "" && $email != "null@null") mail($recipient, $subjectcmmnt, $notifcmmnt, $headers);
}
}
}

else
{
if ($type != "comment" && $type != "outer") {
$result=@mysql_query("SELECT name FROM $usertable WHERE name = '$to'") or die(mysql_error());
$nomail = mysql_num_rows($result);
if ($nomail == 0) { print NOMSGS; exit;}
}
    messageform($cookieinfo, $to);
    
}

mysql_close ();
exit;
//  Image Vote(c) 2002 ProPHP.Com
?>



