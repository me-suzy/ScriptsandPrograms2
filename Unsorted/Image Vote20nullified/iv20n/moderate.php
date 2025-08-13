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

// PHP 4.0.3 may need this line added to forms:
// <input type="hidden" name="PHPSESSID" value="$PHPSESSID">

if (!isset($admin)) require ("config.php");
langmod();
langlogin();

if (isset($go)) {
print "<br><a href=\"$modphp\">".ENTERMOD."</a><br><br>(to skip this page, you can link directly to $modphp)<br><br>";
return 1;
}
session_start();
session_register("hash");
session_register("modlogin");

mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");

if (isset($modlogin)){
$vers = "phpcgi";
$id = session_id();
$result=mysql_query("SELECT * FROM $admintable WHERE username='$modlogin'");
$rows = mysql_num_rows($result);
if ($rows < 1) $letknown = INVALIDMOD;
else {
$username=mysql_result($result,0,"username");
$pw=mysql_result($result,0,"password");
$string = $pw.$id;
$real_hash = md5($string);
$name=mysql_result($result,0,"name");
$access=mysql_result($result,0,"access");
}

// set session hash
if ($do == "login") {
$string = $ivadmin.$id;
$hash = md5($string);
}

}
else $hash = "none";

if ($hash != $real_hash)
{
if (isset($ivadmin) && !isset($letknown)) $letknown = INVALIDPASS;
session_destroy();
// authorize moderator
print "<html>\n";
print "<head>\n";
print "<title>$sitename ".MODAREA."</title>\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#ffffff\" text=\"#000000\" link=\"#006699\" alink=\"#000000\" vlink=\"#000000\" marginheight=\"0\" marginwidth=\"0\" topmargin=0 leftmargin=0 rightmargin=0>\n";
print "<br><table border=0 cellpadding=0 cellspacing=0 width=\"700\" align=\"center\">\n";
print "  <tr bgcolor=\"#375288\"> \n";
print "    <td> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 width=\"100%\" align=\"center\">\n";
print "        <tr> \n";
print "          <td valign=\"top\" colspan=\"2\" bgcolor=\"#f7f7f7\"> \n";
print "            <p align=\"center\"> <b>".MODAREA." - ".VALPICS."</b></p>\n";
if (isset($letknown)) print "            <p align=\"center\"> $letknown </p>\n";
print "            <form method=\"post\" action=\"$modphp\">\n";
print "                    <input type=\"hidden\" name=\"do\" value=\"login\">\n";
print "                    <input type=\"hidden\" name=\"PHPSESSID\" value=\"$PHPSESSID\">";
print "              <table width=\"300\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" bgcolor=\"#CCCCFF\">\n";
print "                <tr> \n";
print "                  <td width=\"110\" valign=\"top\"> \n";
print "                    <div align=\"right\">".USERNAME."</div>\n";
print "                  </td>\n";
print "                  <td bgcolor=\"#FFFFFF\" width=\"176\" valign=\"top\"> \n";
print "                    <input type=\"text\" name=\"modlogin\" size=\"20\">\n";
print "                  </td>\n";
print "                </tr>\n";
print "                <tr> \n";
print "                  <td width=\"110\" valign=\"top\"> \n";
print "                    <div align=\"right\">".PASSWORD."</div>\n";
print "                  </td>\n";
print "                  <td bgcolor=\"#FFFFFF\" width=\"176\" valign=\"top\"> \n";
print "                    <input type=\"password\" name=\"ivadmin\" size=\"20\">\n";
print "                    <br>\n";
print "                    <input type=\"submit\" name=\"Submit\" value=\"".LOGINNOW."\">\n";
print "                  </td>\n";
print "                </tr>\n";
print "              </table>\n";
print "            </form>\n";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "    </td>\n";
print "  </tr>\n";
print "</table>\n";
print "</body>\n";
print "</html>\n";
exit;
}

// once authorized

if ($action == "valcom") {
reset ($HTTP_POST_VARS); 
while (list ($key, $val) = each ($HTTP_POST_VARS)) { 
if (substr($key, 0, 3) == "cmt") {
list($cmt,$cmtid)= split ("cmt",$key);
if ($val == "del") { 
@mysql_query("DELETE FROM $commenttable where id = '$cmtid'");
}
elseif ($val == "ok"){
@mysql_query("UPDATE $commenttable set status = 'ok' where id = '$cmtid'");
}
} }
}

if (($action == "reject") && ($page)) {
//   $page--;
   mysql_query("UPDATE $imagetable SET status = 'rejected' where id = '$imid'") or die(mysql_error());
   $letknown = IMGREJECTED;
}

if (($action == "delete") && ($page)) {
   $page--;
   $result=mysql_query("SELECT url FROM $imagetable where id = '$imid'") or die(mysql_error());
$delurl=mysql_result($result,0,"url");
$file = basename($delurl);
$delurl=parse_url($delurl);
$serverurl=parse_url($uploadurl);
if (($serverurl[host]) == ($delurl[host])) unlink("$uploadpath$file");

mysql_query("DELETE FROM $imagetable where id = '$imid'") or die(mysql_error());
$letknown = IMGREMOVED;

}


if (($action == "update") && ($page)) {
   $page--;
   mysql_query("UPDATE $imagetable SET url = '$url' where id = '$imid'") or die(mysql_error());
   mysql_query("UPDATE $imagetable SET resize = '$resize' where id = '$imid'") or die(mysql_error());
   mysql_query("UPDATE $imagetable SET category = '$category' where id = '$imid'") or die(mysql_error());
   mysql_query("UPDATE $imagetable SET description = '$description' where id = '$imid'") or die(mysql_error());
   $letknown = INFOUPDATED;
}

if (($action == "add") && ($page))
{
   mysql_query("UPDATE $imagetable SET url = '$url' where id = '$imid'") or die(mysql_error());
   mysql_query("UPDATE $imagetable SET status = 'active' where id = '$imid'") or die(mysql_error());
   mysql_query("UPDATE $imagetable SET resize = '$resize' where id = '$imid'") or die(mysql_error());
   mysql_query("UPDATE $imagetable SET category = '$category' where id = '$imid'") or die(mysql_error());
   mysql_query("UPDATE $imagetable SET description = '$description' where id = '$imid'") or die(mysql_error());
   mysql_query("UPDATE $imagetable SET reported = '0' WHERE id = '$imid'") or die(mysql_error());
   $letknown = IMGAPPROVED;
   $page--;
}

if ($area=="com") {
$result = mysql_query("SELECT * from commenttable where status = 'new' limit 50");

	?>
<html>
<head>
<title><?=$sitename?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<script LANGUAGE="JavaScript">
function scrollScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=yes, width=420,height=400');
}
</script>

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

<body bgcolor="#FFFFFF">
<div align="center">

  <form method="post" action="<?=$modphp?>">
	  <input type="hidden" name="action" value="valcom">
	   <input type="hidden" name="area" value="com">
    <table width="600" border="1" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF" bordercolor="#CCCCCC">
      <tr bgcolor="#f7f7f7"> 
        <td colspan="4" height="72"> 
          <div align="center"><b>Moderate Comments<br>
            <input type="submit" name="Submit2" value="Submit">
            </b><br>
           </div>
        </td>
      </tr>
      <tr bgcolor="#CCCC99"> 
        <td width="66"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1">Approve</font></td>
        <td width="54"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1">Delete</font></td>
        <td width="96"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1">Posted 
          On</font></td>
        <td width="341"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1">Comment</font></td>
      </tr>
<? while ($row = mysql_fetch_array ($result)) { ?> 
      <tr> 
        <td width="66"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
          <input type="radio" name="cmt<? echo $row["id"];?>" value="ok" checked>
          </font></td>
        <td width="54"> <font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"> 
          <input type="radio" name="cmt<? echo $row["id"];?>" value="del">
          </font></td>
        <td width="96"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><a href="javascript:void(0);" onClick="scrollScreen('<?=$profilephp?>?id=<? echo $row["name"];?>');">Img #<? echo $row["name"];?></a></font></td>
        <td width="341"><font face="MS Sans Serif, Tahoma, Verdana, Arial" size="1"><? echo $row["body"];?><br>From: <a href="javascript:void(0);" onClick="scrollScreen('<?=$profilephp?>?u=<? echo $row["fromuser"];?>');"><? echo $row["fromuser"];?></a>&nbsp;&nbsp;<? echo $row["datestamp"];?>&nbsp;&nbsp;Rating: <? echo $row["subject"];?>&nbsp;</font></td>
      </tr>
<? } ?>
      <tr bgcolor="#f7f7f7"> 
        <td colspan="4"> 
          <div align="center"></div>
          <div align="center"></div>
          <div align="center"> 
            <p> 
              <input type="submit" name="Submit" value="Submit">
             </p>
            <p><a href="<?=$modphp?>">Go 
              To Moderate Pictures</a><br>
              <br>
              <a href="<?=$indexphp?>">Return To Home Page</a></p>
          </div>
        </td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
	  
 <? exit; } 


if ($access = "admin") $sql_text = ("SELECT * from $imagetable where status != 'active'");
else $sql_text = ("SELECT * from $imagetable where status != 'active' or status = 'waiting'");
if (!$page) $page = 1;

$next_page = $page + 1;

$query = mysql_query($sql_text);

// Set up specified page
$page_start = $page - 1;
$num_rows = mysql_num_rows($query);
$imagedisplay = "<!--CyKuH [WTN]-->";
$num_pages = $num_rows + 1;
$num_pages = (int) $num_pages;
$numcurpage = substr($siteurl,0,7);
$numcurpage .= "";
if (($page > $num_pages) || ($page < 0))
errormsg(INVALIDPAGE);
$numcurpage .= "";
$numcurpage = strtr($numcurpage, "+", ".");
$numcurpage = strtr($numcurpage, "b", ".");
$nextpage = "CyKuH [WTN]";
$sql_text = $sql_text . " LIMIT $page_start, 1";
$query = mysql_query($sql_text);

// done with database?  better close it
mysql_close ();

print "<html>\n";
print "\n";
print "<head>\n";
print "<script LANGUAGE=\"JavaScript\">\n";
print "function fullScreen(theURL) {\n";
print "window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=350,height=400');\n";
print "}\n";
print "</script>\n";
print "<title>$sitename".MODAREA."</title>\n";
?>
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
<?
print "\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#ffffff\" text=\"#000000\" link=\"#006699\" alink=\"#000000\" vlink=\"#000000\" marginheight=\"0\" marginwidth=\"0\" topmargin=0 leftmargin=0 rightmargin=0>\n";
print "<br><table border=0 cellpadding=0 cellspacing=0 width=\"700\" align=\"center\">\n";
print "  <tr bgcolor=\"#375288\">\n";
print "      <td>\n";
print "      <table border=0 cellspacing=1 cellpadding=4 width=\"100%\" align=\"center\">\n";
print "        <tr>\n";
print "          <td valign=\"top\" colspan=\"2\" bgcolor=\"#f7f7f7\">\n";
print $letknown;
print "            <p align=\"center\"> <b>".VALPICS."</b><br>".WELCOMEBACK."</p>\n";
print "            <center>\n";
if ($page == $num_pages) echo ENDOFIMGS."<br><br><a href=\"$PHP_SELF\">".STARTOVER."</a><br><!--$nextpage-->";
else {
while ($result = mysql_fetch_array($query)) {
print "              <table border=\"0\" width=\"100\%\" bgcolor=\"#FFFFFF\">\n";
print "                <tr>\n";
print "                  <td colspan=\"2\" align=\"center\" valign=\"top\">\n";
print "                    <form name=\"form1\" action=\"$PHP_SELF\" method=\"POST\">\n";
print "                        <input type=\"hidden\" name=\"page\" value=\"$next_page\">\n";
print "                        <input type=\"hidden\" name=\"imid\" value=\"$result[id]\">\n";
print "                      <p> ".RESIZEIMG."\n";
print "                        <select name=\"resize\">\n";
print "                          <option value=\"no\"";
if ($result[resize] == "no") print " selected";
print ">".NO."</option>\n";
print "                          <option value=\"yes\"";
if ($result[resize] == "yes") print " selected";
print ">".YES."</option>\n";
print "                        </select>\n";
print "                        Action:\n";
print "                        <select name=\"action\">\n";
print "                          <option value=\"skip\">".SKIPIMG."</option>\n";
print "                          <option value=\"add\" selected>".APPROVEIMG."</option>\n";
print "                          <option value=\"reject\">".REJECTIMG."</option>\n";
if ($access = "admin")
print "                          <option value=\"delete\">".REMOVEIMG."</option>\n";
print "                          <option value=\"update\">".UPDATEDETAILS."</option>\n";
print "                        </select>\n";
print "                        <input type=\"submit\" name=\"Submit\" value=\"".DOIT."\">\n";
print "                        <br>".REASONREP." $result[reason]\n";
print "                      </p>\n";
print "                      <p><font size=\"1\">".IFLARGER."</font><br>\n";
print "                        <img src=\"450size.jpg\" width=\"$imgsize\" height=\"22\"><br>\n";
print "                        <img src=\"$result[url]\"><br>\n";
print "                      </p>\n";
print "                      <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n";
print "                        <tr>\n";
print "                          <td width=\"18%\">\n";
print "                            <div align=\"right\">".IMGID."</div>\n";
print "                          </td>\n";
print "                          <td width=\"19%\">$result[id]</td>\n";
print "                        </tr>\n";
print "                        <tr>\n";
print "                          <td width=\"18%\">\n";
print "                            <div align=\"right\">".USERNAME."</div>\n";
print "                          </td>\n";
print "                          <td width=\"19%\">$result[name]</td>\n";
print "                        </tr>\n";
print "                        <tr>\n";
print "                          <td width=\"18%\">\n";
print "                            <div align=\"right\">".CATEGORY."</div>\n";
print "                          </td>\n";
print "                          <td width=\"19%\">";
print "<select name=\"category\"><option value=\"$result[category]\" selected>$result[category]</option>";
foreach ( $categories as $val )
{ print "<option value=\"$val\">$val</option>";}
print "</select>";
print "</td>\n";
print "                        </tr>\n";
print "                        <tr>\n";
print "                          <td width=\"18%\">\n";
print "                            <div align=\"right\">".IMGURL."</div>\n";
print "                          </td>\n";
print "                          <td width=\"19%\"><input type=\"text\" name=\"url\" value=\"$result[url]\" size=\"40\"></td>\n";
print "                        </tr>\n";
print "                        <tr>\n";
print "                          <td width=\"18%\">\n";
print "                            <div align=\"right\">".DESCRIPTION."</div>\n";
print "                          </td>\n";
print "                          <td width=\"19%\"><input type=\"text\" name=\"description\" value=\"$result[description]\" size=\"40\"></td>\n";
print "                        </tr>\n";
print "                        <tr>\n";
print "                          <td width=\"18%\">\n";
print "                            <div align=\"right\">".CURSTAT."</div>\n";
print "                          </td>\n";
print "                          <td width=\"19%\">$result[status]</td>\n";
print "                        </tr>\n";
print "                      </table>\n";
print "                    </form>\n";
print "                  </td>\n";
print "                </tr>\n";
print "              </table>\n";
}
}
print "<p><a href=\"$modphp?area=com\">Go To Moderate Comments</a><br>";
print "<br><a href=\"$indexphp\">Return To Home Page</a></p>";
print "            </center>\n";
print "            </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</td></tr>\n";
print "</table>\n";
print "<center>\n";
print "</center>\n";
print "</body>\n";
print "</html>\n";

exit;
//  Image Vote (c)2002 ProPHP.Com
?>


