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

session_start();
set_time_limit(900);
$modon=1;
if (isset($go)) {
print "<br><a href=\"$modphp\">Click here to enter the moderators area</a><br><br>(to skip this page, you can link directly to $modphp)<br><br>";
return 1;
}

session_start();
session_register("hash");
session_register("modlogin");
if ($info == "newsetup") $pass = "dbpassword";
if (!isset($admin)) require ("config.php");
if ($pass != "dbpassword" && $info != "newsetup") {
if ($action == "modifysetup") {$pass = $submitpass; $user = $submituser;}
mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database $database $info");
}

if (isset($modlogin)){
$vers = "phpcgi";
$id = session_id();
$query = "SELECT * FROM $admintable WHERE username='$modlogin'";
$result=mysql_query($query);
$rows = mysql_num_rows($result);
if ($rows < 1) $letknown ="Invalid admin login";
else {
$username=mysql_result($result,0,"username");
$pw=mysql_result($result,0,"password");
$string = $pw.$id;
$real_hash = md5($string);
$name=mysql_result($result,0,"name");
$access=mysql_result($result,0,"access");
}

// set session hash
if ($do2 == "login") {
$string = $ivadmin.$id;
$hash = md5($string);
}
if ($access != "admin" && $hash == $real_hash) {
$letknown = "Admin panel is available to authorized administrators only.  Your access level is set to moderator.";
$hash = "mod";
}

}
else $hash = "none";

if ($hash != $real_hash && $pass != "dbpassword")
{
if (isset($ivadmin) && !isset($letknown)) $letknown = "Invalid password";
session_destroy();
// authorize moderator
print "<html>\n";
print "<head>\n";
print "<title>$sitename Administrators</title>\n";
print "</head>\n";
print "\n";
print "<body bgcolor=\"#ffffff\" text=\"#000000\" link=\"#006699\" alink=\"#000000\" vlink=\"#000000\" marginheight=\"0\" marginwidth=\"0\" topmargin=0 leftmargin=0 rightmargin=0>\n";
print "<br><table border=0 cellpadding=0 cellspacing=0 width=\"700\" align=\"center\">\n";
print "  <tr bgcolor=\"#375288\"> \n";
print "    <td> \n";
print "      <table border=0 cellspacing=1 cellpadding=4 width=\"100%\" align=\"center\">\n";
print "        <tr> \n";
print "          <td valign=\"top\" colspan=\"2\" bgcolor=\"#f7f7f7\"> \n";
print "            <p align=\"center\"> <b>Administration Panel</b></p>\n";
if (isset($letknown)) print "            <p align=\"center\"> $letknown </p>\n";
print "            <form method=\"post\" action=\"$PHP_SELF\">\n";
print "                    <input type=\"hidden\" name=\"do2\" value=\"login\">\n";
print "                    <input type=\"hidden\" name=\"PHPSESSID\" value=\"$PHPSESSID\">";
print "              <table width=\"300\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" bgcolor=\"#CCCCFF\">\n";
print "                <tr> \n";
print "                  <td width=\"110\" valign=\"top\"> \n";
print "                    <div align=\"right\">Login:</div>\n";
print "                  </td>\n";
print "                  <td bgcolor=\"#FFFFFF\" width=\"176\" valign=\"top\"> \n";
print "                    <input type=\"text\" name=\"modlogin\" size=\"20\">\n";
print "                  </td>\n";
print "                </tr>\n";
print "                <tr> \n";
print "                  <td width=\"110\" valign=\"top\"> \n";
print "                    <div align=\"right\">Password:</div>\n";
print "                  </td>\n";
print "                  <td bgcolor=\"#FFFFFF\" width=\"176\" valign=\"top\"> \n";
print "                    <input type=\"password\" name=\"ivadmin\" size=\"20\">\n";
print "                    <br>\n";
print "                    <input type=\"submit\" name=\"Submit\" value=\"Sign In\">\n";
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


$ver = "2.0.0";
function wr($var) {
global $$var,$fp,$configphp;
$vvar=stripslashes($vvar);
fwrite ($fp, '$'.$var.'="'.$$var.'";'."\r\n");
$configphp .= '$'.$var.'="'.$$var.'";'."\n";
}

if ($action == "modifysetup") {
@unlink("config.php");
$fp = fopen ("config.php",'w') or die("Could open config.php");
flock ($fp,2); // lock file for a moment
fwrite ($fp, "<?\r\n");
$configphp= "&lt;?\n";
$pass = $submitpass; $user = $submituser;
wr("database");wr("user");wr("pass");wr("host");wr("sitename");wr("siteurl");wr("admin");wr("votesneeded");
$listcategories=ereg_replace (", ", ",", $listcategories);
$listcategories=ereg_replace (" ,", ",", $listcategories);
$categories=explode(",",$listcategories);
$listcategories="";
foreach ($categories as $cate){
if ($cate == ($categories[count($categories)-1])) $listcategories.="\"$cate\"";
else $listcategories.= "\"$cate\",";}
fwrite ($fp, '$categories = array('.$listcategories.');'."\r\n");
wr("pickcat");
fwrite ($fp, '$des = array("'.$des0."\",\"".$des1."\",\"".$des2."\",\"".$des3."\",\"".$des4."\",\"".$des5."\",\"".$des6."\",\"".$des7."\",\"".$des8."\",\"".$des9.'");'."\r\n");
$configphp .= '$des = array("'.$des0."\",\"".$des1."\",\"".$des2."\",\"".$des3."\",\"".$des4."\",\"".$des5."\",\"".$des6."\",\"".$des7."\",\"".$des8."\",\"".$des9.'");'."\r\n";
wr("order");wr("maxreport");wr("imgsize");wr("reportauto");wr("validate");wr("nopic");wr("notification");wr("allowupload");wr ("allowurl");
wr("uploadurl");wr("uploadpath");wr("uploadsize");if ($chmodoption =="yes") wr("chmod");wr("commentson");wr("samplecomments");
wr("usertable");wr("imagetable");wr("mailtable");wr("admintable");wr("commenttable");wr("template");wr("votephp");wr("gophp");
wr("loginphp");wr("topphp");wr("profilephp");wr("mailphp");wr("reportphp");wr("modphp");wr("signupphp");wr("processphp");wr("userphp");wr("faqphp");
fwrite ($fp, "include ('lang.php');\r\n");
$configphp .= "include ('lang.php');\r\n";

fwrite ($fp, "include ('extras.php');\r\n?".">");
$configphp .= "include ('extras.php');\r\n?&gt;";

flock ($fp,1); //unlock config.php
fclose ($fp);
if ($chmod=="yes") @chmod ("config.php", 0777);   // some servers will require this line
$configphp=ereg_replace("\"","&quot;",$configphp);
$letknown .= 'ImageVote Settings Updated<br><br>Your config.php file:<br><textarea name="msg1" cols="50" rows="20" wrap="OFF">'.$configphp.'</textarea><br>If your server cannot update your config.php automatically, cut and paste the above into your config.php file and upload to your server.';
$do="donesetup";
}  // end setup modification

if ($info == "newsetup" && $user !="dbusername") { print "<br> config.php has been setup.  Now run <a href=\"newinstall.php\">newinstall.php</a>"; exit;}

if ($action == "removeimg") {  // remove image
$result=mysql_query("SELECT url FROM $imagetable where id = '$submitid'") or die(mysql_error());
$delurl=mysql_result($result,0,"url");
mysql_query("DELETE FROM $imagetable where id = '$submitid'") or die(mysql_error());
$file = basename($delurl);
$delurl=parse_url($delurl);
$serverurl=parse_url($uploadurl);
if (($serverurl[host]) == ($delurl[host])) @unlink("$uploadpath$file");
mysql_query("DELETE FROM $commenttable where name = '$submitid'") or die(mysql_error());
   $letknown = "<b>Image removed</b><br>";
}

if ($action == "deleteuser") {  // remove user
mysql_query("DELETE FROM $usertable where name = '$deluser'") or die(mysql_error());
mysql_query("DELETE FROM $commenttable where fromuser = '$deluser'") or die(mysql_error());
mysql_query("DELETE FROM $mailtable where fromuser = '$deluser'") or die(mysql_error());
mysql_query("DELETE FROM $mailtable where name = '$deluser'") or die(mysql_error());
$result = mysql_query("SELECT id, url FROM $imagetable where name = '$deluser'");
$numpics = mysql_num_rows($result); $i=0;
while ($i < $numpics) {
$delurl=mysql_result($result,$i,"url");
$delimgid=mysql_result($result,$i,"id");
$file = basename($delurl);
$delurl=parse_url($delurl);
$serverurl=parse_url($uploadurl);
if (($serverurl[host]) == ($delurl[host])) @unlink("$uploadpath$file");
mysql_query("DELETE FROM $commenttable where name = '$imgid'") or die(mysql_error());
$i=$i++;
}
mysql_query("DELETE FROM $imagetable where name = '$deluser'") or die(mysql_error());

$letknown = "<b>User account removed</b><br>";
}

if ($action == "delmod") {  // remove user
mysql_query("DELETE FROM $admintable where username = '$deluser'") or die(mysql_error());
$letknown = "<b>Moderator removed</b><br>";
}

if ($action == "updateimg" || $action == "resetimg") {   // update image
   $result=mysql_query("SELECT url FROM $imagetable where id = '$submitid'") or die(mysql_error());
   $delurl=mysql_result($result,0,"url");
if ($submiturl != $delurl) {
   $file = basename($delurl);
   $delurl=parse_url($delurl);
   $serverurl=parse_url($uploadurl);
if (($serverurl[host]) == ($delurl[host])) {$letknown = "<b>Cannot change URL of uploaded image.  Use remove image.</b><br><br>";}
   else mysql_query("UPDATE $imagetable SET url = '$submiturl' where id = '$submitid'") or die(mysql_error());
}
   mysql_query("UPDATE $imagetable SET category = '$updatecat', description = '$submitdescription',  notifypub = '$submitnotifypub', status = '$submitstatus' where id = '$submitid'") or die(mysql_error());
   $letknown .= "Info updated<br>";
}
if ($action == "resetimg") {
  mysql_query("UPDATE $imagetable SET one='0',two='0',three='0',four='0',five='0',six='0',seven='7',eight='8',nine='9',ten='0',total='0',rate='0',average='0.0',voter1='0',voter2='0',voter3='0',voter4='0',voter5='0' where id = '$submitid'") or die(mysql_error());
  $letknown = "Image vote totals reset to Zero";
}

if ($action == "updateuser") {
 mysql_query("UPDATE $usertable SET age = '$submitage', homepage = '$submithomepage', email = '$submitemail', notifypriv = '$submitnotifypriv' where name = '$lookupuser'") or die(mysql_error());
 $letknown .= "User Info updated<br>";
}

if ($action == "updatemod") {
 mysql_query("UPDATE $admintable SET name = '$submitname', username = '$submitusername', password = '$submitpass', email = '$submitemail', access = '$submitaccess' where username = '$submitusername'") or die(mysql_error());
 $letknown .= "Moderator info updated<br>";
}

if ($action == "addmod") {
 mysql_query("INSERT INTO $admintable (name, username, password, email, access) VALUES ('$submitname', '$submitusername', '$submitpass', '$submitemail', '$submitaccess')") or die(mysql_error());
 $letknown .= "Moderator info updated<br>";
}


if ($action == "sendmail") {
$listquery = "SELECT * FROM $usertable WHERE email != '' and email != 'null@null'";
$listresult = mysql_query($listquery);

$message = stripslashes($message);
$numsent = 0;
if (!isset($sender)) $sender = $admin;
while($listrow = mysql_fetch_array($listresult))
{

/* $idresult=mysql_query("SELECT average FROM $imagetable WHERE name = '$listrow[name]' LIMIT 1") or die(mysql_error());
$nopic = mysql_num_rows($idresult);
if ($nopic > 0) $average=mysql_result($idresult,0,"average");
else $average = "[No active images]";*/

$message2=str_replace ("<username>", $listrow[name], $message);
$message2= str_replace ("<password>", $listrow[password], $message2);
// $message2= str_replace ("<rating>", $average, $message2);

  $msg = "$message2";
  $msg .= "\n\n";

  $mailheaders = "From: $sender \n";
  $mailheaders .= "Reply-To: $replyto\n\n";

mail($listrow[email], $subject, $msg, $mailheaders);
$numsent++;

}
$letknown =  "E-mail has been sent to $numsent users (with valid e-mail address)";
}

?>

<html>
<head>
<script LANGUAGE="JavaScript">
function scrollScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=yes, width=420,height=400');
}
</script>
<script LANGUAGE="JavaScript">
function fullScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=400,height=400');
}
</script>
<!-- #BeginEditable "doctitle" -->
<title>Image Vote Administration Panel</title>
<!-- #EndEditable --> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table border="1" cellspacing="0" cellpadding="2" align="center" bordercolor="#000000" bgcolor="#D7E2EE" width="640">
  <tr> 
    <td colspan="2" valign="top" bgcolor="#000066"> 
      <div align="center"><b><font color="#FFFFFF"><?=$sitename?> Administration</font></b></div>
    </td>
  </tr>
  <tr valign="top" align="center"> 
    <td colspan="2"> <?=$letknown?><br>
<? if (!isset($do)){ ?>
      <div align="center">
        <table width="450" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr> 
            <td bgcolor="#000066"> 
              <div align="center"><b><font color="#FFFFFF">Main Menu</font></b></div>
            </td>
          </tr>
          <tr> 
            <td> 
<? if ($modon == 1 && $impossible == "turned off") { ?>
              <p align="center"><a href="<?=$PHP_SELF?>?do=setup"><font size="2">Image Vote Setup</font></a></p> 
                <?  } ?>
              <p align="center"><font size="2"><a href="<? print "$modphp"?>">Moderate 
                Images</a></font></p>
              <p align="center"><font size="2"><a href="<?=$PHP_SELF?>?do=stats">View
                Site Statistics</a></font></p>
              <p align="center"><font size="2"><a href="<?=$PHP_SELF?>?do=browseuser">Browse
                User Accounts</a></font></p>
              <p align="center"><font size="2"><a href="<?=$PHP_SELF?>?do=browseimg">Browse
                Images</a></font></p>
              <p align="center"><font size="2"><a href="<?=$PHP_SELF?>?do=massmail">Mass
                E-mail Users</a></font></p>
                 <p align="center"><font size="2"><a href="<?=$PHP_SELF?>?do=viewmod">View / Add Moderators</a></font></p>
              <p align="center"><font size="2"><a href="<?=$votephp?>?f=logout">Admin Logout</a></font></p>
              </td>
          </tr>
        </table>
   <br>
        <table width="400" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066"> 
            <td>
              <div align="center"><b><font color="#FFFFFF">View / Edit User</font></b></div>
            </td>
          </tr>
          <tr valign="top"> 
            <td>
              <form name="viewuser" action="<?=$PHP_SELF?>" method="POST">
                <p align="center"> <font size="2">
                  <input type="hidden" name="do" value="edituser">
                  Username: 
                  <input type="text" name="lookupuser" size="20" maxlength="20">
                  <input type="submit" name="Submit" value="Lookup">
                  </font></p>
                </form>
            </td>
          </tr>
        </table>
        <p>
        <table width="400" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066"> 
            <td> 
              <div align="center"><b><font color="#FFFFFF">View / Edit Image</font></b></div>
            </td>
          </tr>
          <tr valign="top"> 
            <td>
              <form name="viewimage" action="<?=$PHP_SELF?>" method="POST">
                <p align="center"> <font size="2">
                  <input type="hidden" name="do" value="editimg">
                 Image ID #: 
                  <input type="text" name="lookupimg" size="6" maxlength="5">
                  <input type="submit" name="Submit" value="Lookup">
                  </font></p>
                </form>
              </td>
          </tr>
        </table>
        <?}



        if ($do == "stats") {


$result=mysql_query("SELECT name FROM $usertable");
$numusers = mysql_num_rows($result);
$result=mysql_query("SELECT id FROM $imagetable");
$numimg = mysql_num_rows($result);
$result=mysql_query("SELECT sum(total) as numvotes, sum(rate) as rates FROM $imagetable");
$numvotes = mysql_result($result,0,"numvotes");
$rates = mysql_result($result,0,"rates");
$totalav = $rates/$numvotes;
$totalav = sprintf ('%0.2f', $totalav);
$numvotes=number_format($numvotes);
$result=mysql_query("SELECT id FROM $mailtable");
$nummail = mysql_num_rows($result);
$result=mysql_query("SELECT id FROM $commenttable");
$numcom = mysql_num_rows($result);




            ?>
        <table width="550" align="center" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066"> 
            <td> 
              <div align="center"><b><font color="#FFFFFF">Site Statistics </font></b></div>
            </td>
          </tr>
          <tr valign="top"> 
            <td> 
              <table width="525" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td bgcolor="#BBD1FF" width="274"> 
                    <div align="right"><font size="2"><b>Registered Users:</b></font></div>
                  </td>
                  <td bgcolor="#FFFFFF" width="251"><font size="2"><b><?=$numusers?></b></font></td>
                </tr>
                <tr> 
                  <td bgcolor="#BBD1FF" width="274"> 
                    <div align="right"><font size="2"><b>Images In Database:</b></font></div>
                  </td>
                  <td bgcolor="#FFFFFF" width="251"><font size="2"><b><?=$numimg?></b></font></td>
                </tr>
                <?
foreach ($categories as $a) {
$result=mysql_query("SELECT id FROM $imagetable WHERE category='$a'");
$catnum = mysql_num_rows($result);
print "<tr><td bgcolor=\"#BBD1FF\" width=\"274\"><div align=\"right\"><b><font size=\"2\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">";
print "Images In &quot;$a&quot; Category:</font></b></div></td><td bgcolor=\"#FFFFFF\" width=\"251\"><b>$catnum</b></td></tr>";
}                       ?> 
                <tr bgcolor="#CCCCCC"> 
                  <td colspan="2"> 
                    <hr>
                  </td>
                </tr>
                <tr> 
                  <td bgcolor="#BBD1FF" width="274"> 
                    <div align="right"><font size="2"><b>Total Number of Votes:</b></font></div>
                  </td>
                  <td bgcolor="#FFFFFF" width="251"><font size="2"><b><?=$numvotes?></b></font></td>
                </tr>
                <tr> 
                  <td bgcolor="#BBD1FF" width="274"> 
                    <div align="right"><font size="2"><b>Average Rating:</b></font></div>
                  </td>
                  <td bgcolor="#FFFFFF" width="251"><font size="2"><b><?=$totalav?></b></font></td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td colspan="2"> 
                    <hr>
                  </td>
                </tr>
                <tr> 
                  <td bgcolor="#BBD1FF" width="274"> 
                    <div align="right"><font size="2"><b>Total Public Messages:</b></font></div>
                  </td>
                  <td bgcolor="#FFFFFF" width="251"><font size="2"><b><?=$numcom?></b></font></td>
                </tr>
                <tr> 
                  <td bgcolor="#BBD1FF" width="274"> 
                    <div align="right"><font size="2"><b>Total Private Messages:</b></font></div>
                  </td>
                  <td bgcolor="#FFFFFF" width="251"><font size="2"><b><?=$nummail?></b></font></td>
                </tr>
                <tr> 
                  <td bgcolor="#BBD1FF" width="274"><b>&nbsp;</b></td>
                  <td bgcolor="#FFFFFF" width="251">&nbsp;</td>
                </tr>
              </table>

              </td>
          </tr>
        </table>   
        <?}
        if ($do=="editimg") {
$result=mysql_query("SELECT name FROM $imagetable WHERE id='$lookupimg'");
$rows = mysql_num_rows($result);
if ($rows < 1) errormsg("Invalid Image ID");
$lookupuser=mysql_result($result,0,"name");

$result=mysql_query("SELECT name FROM $usertable WHERE name='$lookupuser'");
$rows = mysql_num_rows($result);
if ($rows < 1) $nouser=1;
$do = "edituser";
        }
if ($do=="edituser") {
            // get user info
if ($lookupuser == "[no user]") $nouser = 1;
if ($nouser != 1){
$result=mysql_query("SELECT * FROM $usertable WHERE name='$lookupuser'");
$rows = mysql_num_rows($result);
if ($rows < 1) errormsg("Invalid user name");
$passw=mysql_result($result,0,"password");
$name=mysql_result($result,0,"name");
$email=mysql_result($result,0,"email");
if ($email == "null@null") $email = "";
$age=mysql_result($result,0,"age");
$category=mysql_result($result,0,"category");
$homepage=mysql_result($result,0,"homepage");
$notifypriv=mysql_result($result,0,"notifypriv");
}
else {$lookupuser="[no user]";}

            ?> <br>
        <table width="500" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066"> 
            <td> 
              <div align="center"><b><font color="#FFFFFF">View / Edit User</font></b></div>
            </td>
          </tr>
          <tr valign="top"> 
            <td> 
              <form method="post" action="<?=$PHP_SELF?>" method="POST">
                
              <table width="100%" border="0" cellspacing="2" cellpadding="2">
                <tr> 
                  <td width="33%"> 
                    <div align="right"><font size="2"><b>Username:</b></font></div>
                  </td>
                  <td colspan="3" width="67%"><b><?=$lookupuser?> </b></td>
                </tr>
                <tr> 
                  <td width="33%"> 
                    <div align="right"><font size="2"><b>Password:</b></font></div>
                  </td>
                  <td colspan="3" width="67%"><b><?=$passw?> &nbsp;&nbsp;&nbsp;&nbsp;<font size="1">&nbsp;<a href="<?=$PHP_SELF?>?action=deleteuser&deluser=<?=$lookupuser?>&do=browseuser&nouser=1">Delete 
                    User</a></font></b></td>
                </tr>
                <tr> 
                  <td width="33%"> 
                    <div align="right"><font size="2"><b>E-mail:</b></font></div>
                  </td>
                  <td colspan="3" width="67%"> 
                    <input type="text" name="submitemail" size="40" value="<?=$email?>">
                  </td>
                </tr>
                <tr> 
                  <td width="33%"> 
                    <div align="right"><font size="2"><b>Age:</b></font></div>
                  </td>
                  <td colspan="3" width="67%"> 
                    <input type="text" name="submitage" size="5" value="<?=$age?>">
                  </td>
                </tr>
                <tr> 
                  <td width="33%"> 
                    <div align="right"><font size="2"><b>Homepage:</b></font></div>
                  </td>
                  <td colspan="3" width="67%"> 
                    <input type="text" name="submithomepage" size="43" value="<?=$homepage?>" maxlength="60">
                  </td>
                </tr>
                   <tr>
                    <td width="33%" valign="top">
                      <div align="right"><b><small><strong>Private Message Notification</strong></small></b></div>
                    </td>

                  <td colspan="3" width="67%"  valign="top">
                  <input type=checkbox name="submitnotifypriv" value="1" <? if ($notifypriv == "1") print "checked"; ?>>
                  </td>
                  </tr>

                <tr>
                 <td colspan="4" align="center">
                    <input type="hidden" name="do" value="edituser">
                    <input type="hidden" name="lookupuser" value="<?=$lookupuser?>">
                    <input type="hidden" name="action" value="updateuser">
                    <input type="submit" value="Update Info">
                  </td>
                </tr>
              </table>
              </form>
                
              <p><?
if ($nouser !=1) $imgresult=mysql_query("SELECT * FROM $imagetable WHERE name='$lookupuser'");
else $imgresult=mysql_query("SELECT * FROM $imagetable WHERE id='$lookupimg'");
$numimages = mysql_num_rows($imgresult);
if ($numimages == 0) print "User has no current pictures.<br>";
else {
$count=0;

while ($count <  $numimages) {
$url=mysql_result($imgresult,$count,"url");
$id=mysql_result($imgresult,$count,"id");
$c=mysql_result($imgresult,$count,"category");
$description=mysql_result($imgresult,$count,"description");
$notifypub=mysql_result($imgresult,$count,"notifypub");
$average=mysql_result($imgresult,$count,"average");
$total=mysql_result($imgresult,$count,"total");
$status=mysql_result($imgresult,$count,"status");
print "<table border=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\">\n";
print "                  <tr>\n";
print "                    <td colspan=\"2\" align=\"center\" valign=\"top\"> \n";
print "                      <form name=\"form1\" action=\"$PHP_SELF\" method=\"POST\">\n";

print "                        <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n";
print "                          <tr> \n";
print "                            <td rowspan=\"6\" width=\"27%\"> \n";
print "                              <div align=\"center\"><a href=\"javascript:void(0);\" onClick=\"scrollScreen('$profilephp?u=$lookupuser&id=$id');\"> <img src=\"$url\" width=\"150\" alt=\"View Profile\"></a><br>\n";
print "                              </div>\n";
print "                            </td>\n";
print "                            <td width=\"23%\" valign=\"top\"> \n";
print "                              <div align=\"right\"><font size=\"2\">Img Id:</font></div>\n";
print "                            </td>\n";
print "                            <td width=\"50%\" valign=\"top\"><font size=\"2\">$id&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"scrollScreen('$profilephp?u=$lookupuser&id=$id');\">View Public Comments</a></font></td>\n";
print "                          </tr>\n";
print "                          <tr> \n";
print "                            <td width=\"23%\"> \n";
print "                              <div align=\"right\"><font size=\"2\">Photo Status:</font></div>\n";
print "                            </td>\n";
print "                            <td width=\"50%\"><font size=\"2\"><b>";
print "<select name=\"submitstatus\" size=\"1\">";
print "<option value=\"rejected\">rejected</option>";
print "<option value=\"active\">active</option>";
print "                       <option value=\"$status\" selected>$status</option></select>";
print "                            </b></font></td>\n";
print "                          </tr>\n";
print "                          <tr> \n";
print "                            <td width=\"23%\"> \n";
print "                              <div align=\"right\"><font size=\"2\">Rating:</font></div>\n";
print "                            </td>\n";
print "                            <td width=\"50%\"><font size=\"2\"><b>$average ($total votes)</b></font></td>\n";
print "                          </tr>\n";
print "                          <tr> \n";
print "                            <td colspan=\"4\"> <font size=\"2\">Image URL:<br>\n";
print "                              <input type=\"text\" name=\"submiturl\" size=\"50\" value=\"$url\" maxlength=\"60\">\n";
print "                              <br>\n";
print "                              Photo Description:<br>\n";
print "                              <input type=\"text\" name=\"submitdescription\" size=\"50\" value=\"$description\" maxlength=\"60\">\n";
print "                              <br>\n";
print " Category: <select name=\"updatecat\" size=\"1\">";
print " <option value=\"$c\">$c</option>";
foreach ($categories as $a) print "<option value=\"$a\">$a</option>";
print "                          </select><br>";
print "                          <input type=checkbox name=\"submitnotifypub\" ";
if ($notifypub == "1") print "checked ";
print " value=\"1\">Public Comments Notification<br>\n";
print "                              <select name=\"action\">\n";
print "                                <option value=\"updateimg\" selected>Update Image</option>\n";
print "                                <option value=\"removeimg\">Remove Image</option>\n";
print "                                <option value=\"resetimg\">Reset Votes To 0</option>\n";
print "                              </select>\n";
print "                              <input type=\"hidden\" name=\"submitid\" value=\"$id\">\n";
print "                              <input type=\"hidden\" name=\"do\" value=\"edituser\">\n";
print "                              <input type=\"hidden\" name=\"lookupimg\" value=\"$id\">\n";
print "                              <input type=\"hidden\" name=\"lookupuser\" value=\"$lookupuser\">\n";
print "                              <input type=\"submit\" name=\"Submit\" value=\"Do It\">\n";
print "                              <br>\n";
print "                              </font></td>\n";
print "                          </tr>\n";
print "                        </table>\n";
print "                      </form>\n";
print "                    </td>\n";
print "                  </tr>\n";
print "                </table>\n";
print "              <hr width=\"500\">\n";
$count++;
}}

   ?> </p>
            
</td>
          </tr>
        </table>
        <?}
if ($do == "browseuser") {
    if (!isset($letter)) $letter = "all";
    if (!isset($whatcat)) $whatcat = "all";
     ?>

        <table width="500" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066">
            <td>
              <div align="center"><b><font color="#FFFFFF">Browse Users </font></b></div>
            </td>
          </tr>
          <tr valign="top">
            <td> 
          
              <form method="post" action="<?=$PHP_SELF?>">
                <div align="center"> 
                  <p>Category: 
                    <select name="whatcat">
                      <option value=<?=$whatcat?>><?=$whatcat?></option>
                      <option value="all">all</option>
                      <?                  foreach ($categories as $a) print "<option value=\"$a\">$a</option>";?> 
                    </select>
                    Username: 
                    <select name="letter">
                      <option value="<?=$letter?>"><?=$letter?></option>
                      <option value="all">all</option>
                      <option value="a"># / a</option>
                      <option value="b">b</option>
                      <option value="c">c</option>
                      <option value="d">d</option>
                      <option value="e">e</option>
                      <option value="f">f</option>
                      <option value="g">g</option>
                      <option value="h">h</option>
                      <option value="i">i</option>
                      <option value="j">j</option>
                      <option value="k">k</option>
                      <option value="l">l</option>
                      <option value="m">m</option>
                      <option value="n">n</option>
                      <option value="o">o</option>
                      <option value="p">p</option>
                      <option value="q">q</option>
                      <option value="r">r</option>
                      <option value="s">s</option>
                      <option value="t">t</option>
                      <option value="u">u</option>
                      <option value="v">v</option>
                      <option value="w">w</option>
                      <option value="x">x</option>
                      <option value="y">y</option>
                      <option value="z">z</option>
                    </select>
                    <br>
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="do" value="browseuser">
                    <input type="submit" name="Submit3" value="Go">
                  </p>
                  </div>
              </form>
          
              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr bgcolor="#000066"> 
                  <td width="33%"><font color="#FFFFFF" size="2">Username</font></td>
                  <td width="21%"><font color="#FFFFFF" size="2">Category</font></td>
                  <td width="12%"><font color="#FFFFFF" size="2">Age</font></td>
                  <td width="17%"><font color="#FFFFFF" size="2">Info 1</font></td>
                  <td width="17%"><font color="#FFFFFF" size="2">Verified</font></td>
                </tr>
                <?
if (!isset($per_page)) $per_page = 35;
$sql_text = ("select name,category,age,validate from $usertable");
if ($whatcat != "all") $sql_text .= " WHERE category = '$whatcat'";
if ($letter != "all" && $whatcat == "all") $sql_text .= " WHERE name LIKE '$letter%' order by name";
if ($letter != "all" && $whatcat != "all") $sql_text .= " AND name LIKE '$letter%' order by name";

// Set page #, if no page isspecified, assume page 1
if (!$page)  $page = 1;
$prev_page = $page++;
$next_page = $page--;

$query = mysql_query($sql_text);

// Set up specified page
$page_start = ($per_page * $page) - $per_page;
$num_rows = mysql_num_rows($query);

if ($num_rows <= $per_page) $num_pages = 1;
elseif (($num_rows % $per_page) == 0) $num_pages = ($num_rows / $per_page);
else    $num_pages = ($num_rows / $per_page) + 1;

$num_pages = (int) $num_pages;

if (($page > $num_pages) || ($page < 0)) errormsg("You have specified an invalid page number");

$sql_text .= " LIMIT $page_start, $per_page";
$query = mysql_query($sql_text);


while ($result = mysql_fetch_array($query)) {
print "<tr bgcolor=\"#FFFFFF\"><td width=\"33%\"><font size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\"><a href=\"$PHP_SELF?do=edituser&lookupuser=$result[name]\">$result[name]</font></td>";
print "<td width=\"21%\"><font size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">$result[category]</font></td>";
print "<td width=\"12%\"><font size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">$result[age]</font></td>";
print "<td width=\"17%\"><font size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">$result[info1]</font></td>";
print "<td width=\"17%\"><font size=\"1\" face=\"MS Sans Serif, Tahoma, Verdana, Arial\">$result[validate]</font></td>";
print "</tr>";

}


                 ?> 
              </table>
              <center>
<? if ($prev_page) echo "<a href=\"$PHP_SELF?do=browseuser&whatcat=$whatcat&letter=$letter&page=$prev_page\">Prev</a>";

   for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $page) echo " <a href=\"$PHP_SELF?do=browseuser&whatcat=$whatcat&letter=$letter&page=$i\">$i</a> ";
   else echo " $i ";
   if ($i == 15) echo "<br>";
}

// Next
if ($page != $num_pages) echo "<a href=\"$PHP_SELF?do=browseuser&whatcat=$whatcat&letter=$letter&page=$next_page\">Next</a>"; ?>
</center>
  </td>
          </tr>
        </table>


        <?}  if ($do == "viewmod") {
    ?> 
        <table width="640" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066"> 
            <td> 
              <div align="center"><b><font color="#FFFFFF">View / Add Moderators</font></b></div>
            </td>
          </tr>
          <tr valign="top"> 
            <td> 

<?
$result=mysql_query("SELECT * FROM $admintable");
$rows = mysql_num_rows($result);
for ($i=0;$i < $rows;$i++) {
$modusername=mysql_result($result,$i,"username");
$modpass=mysql_result($result,$i,"password");
$modname=mysql_result($result,$i,"name");
$modemail=mysql_result($result,$i,"email");
$modaccess=mysql_result($result,$i,"access");
print " <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
print "<form method=\"post\" action=\"$PHP_SELF\"><tr bgcolor=\"#FFFFFF\"><td colspan=\"2\" width=\"39%\"><font size=\"2\"> Login:";
print "<input type=\"text\" name=\"submitusername\" size=\"20\" value=\"$modusername\"></font></td>";
print "<td colspan=\"2\"><font size=\"2\"> Password:<input type=\"password\" name=\"submitpass\" value=\"$modpass\" size=\"15\">";
print "</font></td><td colspan=\"2\"><font size=\"2\"> Access:<select name=\"submitaccess\">";
print "<option value=\"moderator\">moderator</option><option value=\"admin\">admin</option>";
print "<option value=\"$modaccess\" selected>$modaccess</option></select></font></td></tr>";
print "<tr bgcolor=\"#FFFFFF\"><td colspan=\"2\"><font size=\"2\"> Real Name:<input type=\"text\" name=\"submitname\" size=\"20\" value=\"$modname\">";
print "</font></td><td colspan=\"2\"><font size=\"2\"> E-Mail:";
print "<input type=\"text\" name=\"submitemail\" size=\"20\" value=\"$modemail\">";
print "                      </font></td>";
print "                    <td colspan=\"2\"> <font size=\"2\">     ";
print "                      <input type=\"submit\" value=\"Update Record\" name=\"submit\">";
print "                      <input type=\"hidden\" name=\"action\" value=\"updatemod\">      ";
print "                      <input type=\"hidden\" name=\"do\" value=\"viewmod\">";
print "                      <font size=\"1\"><a href=\"$PHP_SELF?do=viewmod&amp;action=delmod&amp;deluser=$modusername\">Remove</a></font></font></td>";
print "                  </tr>";
print "                </form>              </table><br>";
} ?>

              <center>
                <form method="post" action="<?=$PHP_SELF?>">
                  <div align="center"> 
                    <p><font size="2">Add Moderator / Admin:</font></p>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tr bgcolor="#000066"> 
                        <td width="22%"><font color="#FFFFFF" size="2">Name</font></td>
                        <td width="17%"><font color="#FFFFFF" size="2">Username</font></td>
                        <td width="16%"><font color="#FFFFFF" size="2">Password</font></td>
                        <td width="22%"><font color="#FFFFFF" size="2">E-Mail</font></td>
                        <td colspan="2" width="23%"><font color="#FFFFFF" size="2">Access 
                          Level</font></td>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <td width="22%"> 
                          <input type="text" name="submitname" size="20">
                        </td>
                        <td width="17%"> 
                          <input type="text" name="submitusername" size="15">
                        </td>
                        <td width="16%"> 
                          <input type="text" name="submitpass" size="15">
                        </td>
                        <td width="22%"> 
                          <input type="text" name="submitemail" size="25">
                        </td>
                        <td colspan="2" width="23%"> 
                          <select name="submitaccess">
                            <option value="moderator" selected>moderator</option>
                            <option value="admin">admin</option>
                          </select>
                          <input type="submit" value="Add" name="submit">
                        </td>
                      </tr>
                    </table>
                    <center>
                      <input type="hidden" name="action" value="addmod">
                      <input type="hidden" name="do" value="viewmod">
                      <input type="submit" value="Go">
                    </center>
                    </div>
                </form>
                </center>
            </td>
          </tr>
        </table>
        <br>  <?}if ($do == "browseimg") {
      if (!isset($orderby)) $orderby = "id";
      if (!isset($whatcat)) $whatcat = "all"; ?>
        <table width="500" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066">
            <td>
              <div align="center"><b><font color="#FFFFFF">Browse Image Records</font></b></div>
            </td>
          </tr>
          <tr valign="top">
            <td>

              <form method="post" action="<?=$PHP_SELF?>">
                <div align="center">
                  <p>Category: 
                    <select name="whatcat">
                      <option value=<?=$whatcat?>><?=$whatcat?></option>
                      <option value="all">all</option>
                      <?                  foreach ($categories as $a) print "<option value=\"$a\">$a</option>";?> 
                    </select>
                    Order By: 
                    <select name="orderby">
                      <option value="<?=$orderby?>"><?=$orderby?></option>
                      <option value="goodrate">Rating (Desending)</option>
                      <option value="badrate">Rating (Assending)</option>
                      <option value="total">Total Votes</option>
                      <option value="id">Image ID</option>
                    </select>
                    <br>
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="do" value="browseimg">
                    <input type="submit" value="Go">
                  </p>
                  </div>
              </form>

              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr bgcolor="#000066"> 
                  <td width="33%"><font color="#FFFFFF" size="2">ID</font></td>
                  <td width="21%"><font color="#FFFFFF" size="2">Username</font></td>
                  <td width="12%"><font color="#FFFFFF" size="2">Category</font></td>
                  <td width="17%"><font color="#FFFFFF" size="2">Rating</font></td>
                  <td width="17%"><font color="#FFFFFF" size="2">Votes</font></td>
                </tr>
                <?
if (!isset($per_page)) $per_page = 35;
$sql_text = ("select id,name,category,average,total,reported from $imagetable");
if ($whatcat != "all") $sql_text .= " WHERE category = '$whatcat'";
if ($orderby  == "goodrate") $sql_text .= " ORDER BY average DESC";
if ($orderby  == "badrate") $sql_text .= " ORDER BY average";
if ($orderby  == "total") $sql_text .= " ORDER BY total DESC";
if ($orderby  == "id") $sql_text .= " ORDER BY id";
// Set page #, if no page isspecified, assume page 1
if (!$page)  $page = 1;
$prev_page = $page++;
$next_page = $page--;

$query = mysql_query($sql_text);

// Set up specified page
$page_start = ($per_page * $page) - $per_page;
$num_rows = mysql_num_rows($query);

if ($num_rows <= $per_page) $num_pages = 1;
elseif (($num_rows % $per_page) == 0) $num_pages = ($num_rows / $per_page);
else    $num_pages = ($num_rows / $per_page) + 1;

$num_pages = (int) $num_pages;

if (($page > $num_pages) || ($page < 0)) errormsg("You have specified an invalid page number");

$sql_text .= " LIMIT $page_start, $per_page";
$query = mysql_query($sql_text);


while ($result = mysql_fetch_array($query)) {
print "<tr bgcolor=\"#FFFFFF\"><td width=\"33%\"><font size=\"2\"><a href=\"$PHP_SELF?do=editimg&lookupimg=$result[id]\">$result[id]</font></td>";
print "<td width=\"21%\"><font size=\"2\"><a href=\"$PHP_SELF?do=edituser&lookupuser=$result[name]\">$result[name]</a></font></td>";
print "<td width=\"12%\"><font size=\"2\">$result[category]</font></td>";
print "<td width=\"17%\"><font size=\"2\">$result[average]</font></td>";
print "<td width=\"17%\"><font size=\"2\">$result[total]</font></td>";
print "</tr>";

}


                 ?> 
              </table>
              <center>
<? if ($prev_page) echo "<a href=\"$PHP_SELF?do=browseimg&whatcat=$whatcat&orderby=$orderby&page=$prev_page\">Prev</a>";

   for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $page) echo " <a href=\"$PHP_SELF?do=browseimg&whatcat=$whatcat&orderby=$orderby&page=$i\">$i</a> ";
   else echo " $i ";
   if ($i == 15) echo "<br>";
}

// Next
if ($page != $num_pages) echo "<a href=\"$PHP_SELF?do=browseimg&whatcat=$whatcat&orderby=$orderby&page=$next_page\">Next</a>"; ?>
</center>
  </td>
          </tr>
        </table>





        <?}      if ($do == "setup") {?><p><br>
        </p><table width="500" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066"> 
            <td> 
              <div align="center"><b><font color="#FFFFFF">Image Vote Setup</font></b></div>
            </td>
          </tr>
          <tr valign="top"> 
            <td> 
              <form method="post" action="<?=$PHP_SELF?>">
                <div align="center">
                  <table width="450" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td colspan="2" bgcolor="#000066"> 
                        <div align="center"><b><font color="#FFFFFF">MySQL Database 
                          Information </font></b></div>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="216"> 
                        <div align="right"><font size="2">MySQL Database Name:</font></div>
                      </td>
                      <td width="234"> 
                        <input type="text" name="database" value="<?=$database?>" size="30">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="216"> 
                        <div align="right"><font size="2">MySQL Username :</font></div>
                      </td>
                      <td width="234"> 
                        <input type="text" name="submituser" value="<?=$user?>" size="30">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="216"> 
                        <div align="right"><font size="2">MySQL Password:</font></div>
                      </td>
                      <td width="234"> 
                        <input type="password" name="submitpass" value="<?=$pass?>" size="30">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="216"> 
                        <div align="right"><font size="2">Hostname: <br>
                          (leave as localhost for most servers)</font></div>
                      </td>
                      <td width="234"> 
                        <input type="text" name="host" value="<?=$host?>" size="30">
                      </td>
                    </tr>
                  </table>
                 <br>
                  <table width="450" border="0" cellspacing="0" cellpadding="2">
                    <tr bgcolor="#000066"> 
                      <td colspan="2"> 
                        <div align="center"><b><font color="#FFFFFF">Your Website 
                          Information </font></b> </div>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Site Name: </font></div>
                      </td>
                      <td width="233"> 
                        <input type="text" name="sitename" value="<?print stripslashes($sitename);?>" size="40">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">URL To ImageVote Directory: 
                          <br>
                          (include trailing slash)</font></div>
                      </td>
                      <td width="233"> 
                        <input type="text" name="siteurl" value="<?=$siteurl?>" size="40">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Admin E-mail Address: 
                          </font></div>
                      </td>
                      <td width="233"> 
                        <input type="text" name="admin" value="<?=$admin?>" size="40">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Votes Needed To Show 
                          Rating: </font></div>
                      </td>
                      <td width="233"> 
                        <input type="text" name="votesneeded" value="<?=$votesneeded?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Display categories as: 
                          </font></div>
                      </td>
                      <td width="233"> 
                        <select name="pickcat">
                          <option value="radio">Radio Buttons</option>
                          <option value="list">Pull Down List</option>
                          <option value="<?=$pickcat?>" selected><?=$pickcat?></option>
                        </select>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Order pictures by: </font></div>
                      </td>
                      <td width="233"> 
                        <select name="order">
                          <option value="random">Random Order</option>
                          <option value="loop">Ordered Loop</option>
                          <option value="<?=$order?>" selected><?=$order?></option>
                        </select>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Picture is Made Inactive 
                          After Being Reported How Many Times?:</font></div>
                      </td>
                      <td width="233"> 
                        <input type="text" name="maxreport" size="3" maxlength="3" value="<?=$maxreport?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Resize big images to 
                          what width?:</font></div>
                      </td>
                      <td width="233"> 
                        <input type="text" name="imgsize" size="3" maxlength="3" value="<?=$imgsize?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Auto-detect and report 
                          broken images?:</font></div>
                      </td>
                      <td width="233"> 
                        <select name="reportauto">
                          <option value="yes">yes</option>
                          <option value="no">no</option>
                          <option value="<?=$reportauto?>" selected><?=$reportauto?></option>
                        </select>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Require e-mail validation?: 
                          </font></div>
                      </td>
                      <td width="233"> 
                        <select name="validate">
                          <option value="yes">yes</option>
                          <option value="no">no</option>
                          <option value="<?=$validate?>" selected><?=$validate?></option>
                        </select>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Allow users to signup 
                          without submitting picture?:</font></div>
                      </td>
                      <td width="233"> 
                        <select name="nopic">
                          <option value="yes">yes</option>
                          <option value="no">no</option>
                          <option value="<?=$nopic?>" selected><?=$nopic?></option>
                        </select>
                      </td>
                    </tr>

                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Send notification email to users on signup?:</font></div>
                      </td>
                      <td width="233"> 
                        <select name="notification">
                          <option value="yes">yes</option>
                          <option value="no">no</option>
                          <option value="<?=$notification?>" selected><?=$notification?></option>
                        </select>
                      </td>
                    </tr>

                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Allow public comments 
                          on pictures?:</font></div>
                      </td>
                      <td width="233"> 
                        <select name="commentson">
                          <option value="0">Not Allowed</option>
                          <option value="1">Yes, Moderated</option>
                          <option value="2">Yes, Unmoderated</option>
                          <option value="<?=$commentson?>" selected><? if ($commentson > 0) print "yes";?></option>
                        </select>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="209"> 
                        <div align="right"><font size="2">Number of comments to 
                          display on voting page?:</font></div>
                      </td>
                      <td width="233"> 
                        <select name="samplecomments">
                          <option value="<?=$samplecomments?>" selected><?=$samplecomments?></option>
                          <option value="0">0</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10</option>
                          <option value="15">15</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                  <br>
                  <table width="450" border="0" cellspacing="0" cellpadding="2">
                    <tr bgcolor="#000066"> 
                      <td> 
                        <div align="center"><b><font color="#FFFFFF">Categories</font></b></div>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td> 
                        <div align="center"> 
                          <input type="text" name="listcategories" value="<?
                          foreach ($categories as $cate){
                          if ($cate == ($categories[count($categories)-1])) print "$cate";
                          else print "$cate,";
                          }


                          ?>" size="50">
                          <br>
                          <font size="2">Separated by commas (ex: men,women,dogs,cats,babies)</font></div>
                      </td>
                    </tr>
                  </table>
                  <br>
                  <table width="450" border="0" cellspacing="0" cellpadding="2">
                    <tr bgcolor="#000066"> 
                      <td colspan="2"> 
                        <div align="center"><b><font color="#FFFFFF">Image submission 
                          options</font></b></div>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="199"> 
                        <div align="right"><font size="2">Allow users to provide 
                          a remote URL for their pictures?: </font></div>
                      </td>
                      <td width="243"> <font size="2"> 
                          <select name="allowurl">
                          <option value="1">yes</option>
                          <option value="0">no</option>
                          <option value="<?=$allowurl?>" selected><? if ($allowurl != 0) print "yes"; else print "no";?></option>
                        </select>
                        </font></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="199"> 
                        <div align="right"><font size="2">Allow users to upload 
                          pictures: </font></div>
                      </td>
                      <td width="243"> <font size="2"> 
                        <select name="allowupload">
                          <option value="1">yes</option>
                          <option value="0">no</option>
                          <option value="<?=$allowupload?>" selected><? if ($allowupload != 0) print "yes"; else print "no";?></option>
                        </select>
                        </font></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="199"> 
                        <div align="right"><font size="2"> URL to upload directory: 
                          <br>
                          (with trailing slash):</font></div>
                      </td>
                      <td width="243"> <font size="2"> 
                        <input type="text" name="uploadurl" value="<?=$uploadurl?>" size="40">
                        </font></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="199"> 
                        <div align="right"><font size="2">Server path to upload 
                          directory: (with trailing slash)</font></div>
                      </td>
                      <td width="243"> <font size="2"> 
                        <input type="text" name="uploadpath" value="<?=$uploadpath?>" size="40">
                        </font></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="199"> 
                        <div align="right"><font size="2">Maximum file size for 
                          image uploads: </font></div>
                      </td>
                      <td width="243"> <font size="2"> 
                        <input type="text" name="uploadsize" size="4" maxlength="4" value="<?=$uploadsize?>">
                        <br>
                        in kilobytes (100=100k) </font></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td colspan="2"> <font size="2"> 
                        <input type="checkbox" name="chmodoption" value="yes"<? if ($chmod=="yes") print " checked";?>>
                        Check here if your server needs help setting permissions 
                        on uploaded files. (Most servers will not require this)</font></td>
                    </tr>
                  </table>
                  <br>
                  <table width="300" border="0" cellspacing="0" cellpadding="2">
                    <tr bgcolor="#000066"> 
                      <td> 
                        <div align="center"><b><font color="#FFFFFF">Descriptions 
                          for Ratings <br>
                          (can be left blank)</font></b></div>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td>
                        <div align="center">1 
                          <input type="text" name="des0" value="<?print stripslashes($des[0]);?>">
                          <br>
                          2 
                          <input type="text" name="des1" value="<?print stripslashes($des[1]);?>">
                          <br>
                          3 
                          <input type="text" name="des2" value="<?print stripslashes($des[2]);?>">
                          <br>
                          4 
                          <input type="text" name="des3" value="<?print stripslashes($des[3]);?>">
                          <br>
                          5 
                          <input type="text" name="des4" value="<?print stripslashes($des[4]);?>">
                          <br>
                          6 
                          <input type="text" name="des5" value="<?print stripslashes($des[5]);?>">
                          <br>
                          7 
                          <input type="text" name="des6" value="<?print stripslashes($des[6]);?>">
                          <br>
                          8 
                          <input type="text" name="des7" value="<?print stripslashes($des[7]);?>">
                          <br>
                          9 
                          <input type="text" name="des8" value="<?print stripslashes($des[8]);?>">
                          <br>
                          10 
                          <input type="text" name="des9" value="<?print stripslashes($des[9]);?>">
                        </div>
                      </td>
                    </tr>
                  </table>
                  <br>
                  <table width="450" border="0" cellspacing="0" cellpadding="1">
                    <tr bgcolor="#000066"> 
                      <td colspan="2"> 
                        <div align="center"><b><font color="#FFFFFF">MySQL Table 
                          Names<br>
                          <font size="2">(Do not change these variables unless 
                          needed)</font></font></b></div>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="197"> 
                        <div align="right"><font size="2">User Table:</font></div>
                      </td>
                      <td width="253"> 
                        <input type="text" name="usertable" value="<?=$usertable?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="197"> 
                        <div align="right"><font size="2">Image Table:</font></div>
                      </td>
                      <td width="253"> 
                        <input type="text" name="imagetable" value="<?=$imagetable?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="197"> 
                        <div align="right"><font size="2">Private Mail Table:</font></div>
                      </td>
                      <td width="253"> 
                        <input type="text" name="mailtable" value="<?=$mailtable?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="197"> 
                        <div align="right"><font size="2">Public Comment Table:</font></div>
                      </td>
                      <td width="253"> 
                        <input type="text" name="commenttable" value="<? if (!isset ($commenttable)) $commenttable="commenttable"; print $commenttable;?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="197"> 
                        <div align="right"><font size="2">Admin Table:</font></div>
                      </td>
                      <td width="253"> 
                        <input type="text" name="admintable" value="<?=$admintable?>">
                      </td>
                    </tr>
                  </table>
                  <br>
                  <table width="450" border="0" cellspacing="0" cellpadding="2">
                    <tr bgcolor="#000066"> 
                      <td colspan="2"> 
                        <div align="center"><b><font color="#FFFFFF">PHP File 
                          Names<br>
                          <font size="2">(Do not change these variables unless 
                          needed)</font></font></b></div>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Voting template:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="template" value="<?=$template?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Function template:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="gophp" value="<?if (!isset($gophp)) $gophp="go.php"; print $gophp;?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Voting functions:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="votephp" value="<?=$votephp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Top 10:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="topphp" value="<?=$topphp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Profile:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="profilephp" value="<?=$profilephp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Mail:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="mailphp" value="<?=$mailphp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Report:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="reportphp" value="<?=$reportphp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Moderator:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="modphp" value="<?=$modphp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Signup:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="signupphp" value="<?=$signupphp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Process:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="processphp" value="<?=$processphp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">User:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="userphp" value="<?=$userphp?>">
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td width="234"> 
                        <div align="right"><font size="2">Login:</font></div>
                      </td>
                      <td width="216"> 
                        <input type="text" name="loginphp" value="<?=$loginphp?>">
                      </td>
                    </tr>
                            <tr bgcolor="#FFFFFF">
                      <td width="234">
                        <div align="right"><font size="2">FAQ:</font></div>
                      </td>
                      <td width="216">
                        <input type="text" name="faqphp" value="<?=$faqphp?>">
                      </td>
                    </tr>
                  </table>
                  <p>
                    <input type="hidden" name="action" value="modifysetup">
                     <input type="hidden" name="oldb" value="<?=$pass?>">
                    <input type="hidden" name="do" value="setup">
<? if ($user=="dbusername"){ ?> <input type="hidden" name="info" value="newsetup"> <?} ?>
                    <input type="submit" value="Modify Settings">  
                  </p>
                  </div>
              </form>
              
            </td>
          </tr>
        </table>
        <br>
<? } if ($do == "massmail") {  ?>
<table width="400" border="2" cellspacing="0" cellpadding="5" bgcolor="#E1E1E1" bordercolor="#000000">
          <tr valign="top" bgcolor="#000066"> 
            <td> 
              <div align="center"><b>Send Mass Mail</b></div>
            </td>
          </tr>
          <tr valign="top"> 
            <td> 
              <form action="<?=$PHP_SELF?>" method="POST">
                <p align="center"> <font size="2">
                  <input type="hidden" name="action" value="sendmail">
                  Sender: 
                  <input type="text" name="sender" size="40" maxlength="75" value="<?=$admin?>">
                  </font></p>
                <p align="center"><font size="2"> Subject: 
                  <input type="text" name="subject" size="40" maxlength="75">
                  </font></p>
                <p align="center"><font size="2"> You can use these variables 
                  to insert data<br>
                  for each user into your e-mail message:<br>
                  <b>&lt;username&gt; &lt;password&gt;</b><br>
                  Message: <br>
                  <textarea name="message" cols="50" rows="20" wrap="VIRTUAL">Dear &lt;username&gt;,

Type message here.


</textarea>
                  </font></p>
                <p align="center"><font size="2"> Be patient, mailing may take 
                  a minute or two.<br>
                  <input type="submit" name="Submit22" value="Send Mail">
                  </font></p>
              </form>
            </td>
          </tr>
        </table>
        <p>&nbsp;</p> <?} if (!isset($do)) { ?>
        <p><b>Image Vote v<?=$ver?></b><br>
          (c) 2001 Pro PHP<br>
          <br>
          <!--CyKuH [WTN]--></p>
          <?} else {?>
                 
        <p align="center"><a href="<?=$PHP_SELF?>">Return To Admin Main Menu</a></p>
                
<?}     mysql_close;         ?>
      </div>
    </td>
  </tr>
</table>

<p>&nbsp; </p>
</body>
</html>
