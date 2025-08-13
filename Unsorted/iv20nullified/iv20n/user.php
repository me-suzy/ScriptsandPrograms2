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

// Image manipulation feature.  Only works on JPGs
$addwhat=0; // Add what to uploaded images?  See next line
// (0=nothing!, 1 = Logo (tag.jpg))
$logoh = "12"; // logo height if adding a logo (tag.jpg)

if (!isset($maximages)) $maximages = 5;

function addlogo()
{
global $dest,$string,$logoh,$addwhat;

if ($addwhat > 0) {

$im = imagecreatefromjpeg($dest);
$imwidth = imagesx($im);
$imheight = imagesy($im);
$txtpl = ($imwidth-7.5*strlen($string))/2;
$tag = imagecreatefromjpeg("./tag.jpg");
imagefilledrectangle ($im, 0, ($imheight-$logoh), $imwidth, $imheight, 0);
if ($addwhat==1) ImageCopy ($im, $tag, $txtpl, ($imheight-$logoh), 0, 0, 126, $logoh);
else ImageString($im,5,$txtpl,($imheight-($logoh-5)),$string,1);
ImageJpeg($im, $dest, 95);
}
}

if (!isset($go)) require ("config.php");
langprocess();
langlogin();
languser();
langmail();
langindex();
langmod();
langprofile();
langtop();

mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");
if (isset($logged)) $userresult = @mysql_query("SELECT * from $usertable where name = '$logged'");
elseif (isset($un)) $userresult = @mysql_query("SELECT * from $usertable where name = '$un'");
if (isset($userresult)) { $userin = 0; $user_id = mysql_fetch_array($userresult); }


function is_uploaded_fil($filename) {
    if (!$tmp_file = get_cfg_var('upload_tmp_dir')) {
        $tmp_file = dirname(tempnam('', ''));
    }
    $tmp_file .= '/' . basename($filename);
    return (ereg_replace('/+', '/', $tmp_file) == $filename);
}

function dispatch_vcode($username,$vcodemail,$vcodesubject) {
global $usertable,$sitename,$admin;
$result = mysql_query("SELECT email, validate from $usertable where name = '$username'");
$email = mysql_result($result,0,"email");
$vcode = mysql_result($result,0,"validate");
$vcodemail = ereg_replace ("VCODE", $vcode, $vcodemail);
$vcodemail = ereg_replace ("USERNAME", $username, $vcodemail);
$recipient .= "$username <$email>";
$headers .= "From: $sitename <$admin>\n";
if ($email != "" && $email != "null@null") mail($recipient, $vcodesubject, $vcodemail, $headers);
}

function logout() {
header ("Set-Cookie: logged=$user_id; expires=Wed, 2-Jan-1987 00:00:00 GMT; path=/;");
}
if (isset($aself)) errormsg("<br><ID#>");
$cookieinfo = $HTTP_COOKIE_VARS[logged];

if ($action == "vm") {
if ($svc == $user_id["validate"]) {
@mysql_query("UPDATE $usertable set validate = 'ok' where name = '$logged'");
$user_id["validate"] = "ok";
$letknown = ACCTVALD;
}
else $letknown = "Incorrect validation code.";

}

if ($action == "resendval" || isset($resendval)) {
if (strlen($un) < 1) $letknown = NOUSERNAME;
else {
	
dispatch_vcode($un,$vcodemail,$vcodesubject);
$letknown = VALCODESENT;
}
if ($then=="e") { 
	
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

print $letknown;
print " </p>";
print "          </td>\n";
print "        </tr>\n";
print "      </table>\n";
print "</td></tr>";
print "</table>\n";
print "</body>\n";
print "</html>\n";

exit; 
	
	
	}
}

// e-mail validation required 
if ($user_id["validate"] != "ok" && $validate != "no" && isset($logged)) {
if (!isset($go)) {
	?>
<html>
<head>
<title><?=$sitename?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" text="#000000">
<table border="0" width="99%" align="center" bgcolor="#375288" height="396">
<tr align="center">
<td valign="top"> 
<? } ?>
<table border=0 cellspacing=1 cellpadding=4 align="center" width="99%" height="396">
  <tr bgcolor="#FFFFFF"> 
          <td valign="top"> 
          <div align="center">
           <p><h3><?=$letknown?></h3></p><p><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo MUSTVAL;?></font></b></p>
           <form method="post" action="<?=$gophp?>">
           <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <b>
           <input type="hidden" name="go" value="userphp">
		<input type="hidden" name="action" value="vm">
                </b> </font> 
              <table width="210" border="0" cellspacing="2" cellpadding="4" align="center">
                <tr> 
                  <td valign="top" width="74" bgcolor="#EFEFEF"> 
                 <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo USERNAME; ?></font></div>
                  </td>
                    <td colspan="3" valign="top" width="154"> 
                      <div align="left"> 
                        <input type="text" name="un" value="<?=$logged?>" size="15" maxlength="20">
                      </div>
                   </td>
                  </tr>
                  <tr>
                   <td valign="top" height="21" width="74" bgcolor="#EFEFEF"> 
                      <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo VALCODE;?></font></div>
                    </td>
                   <td valign="top" height="21" colspan="3" width="154"> 
                      <div align="left"> 
                    <input type="text" name="svc" size="15" maxlength="40">
                       <br>
                        <input type="submit" value="<? echo LOGINNOW;?>">
                       <br>
                     </div>
                   </td>
                  </tr>
                </table>
      <p><input type="submit" name="resendval" value="<? echo RESENDVAL; ?>"></p>    
	</form>
                   <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><i><? echo DONTHAVEACCT; ?></i><br>
          <a href="<?=$gophp?>?go=signupphp"><? echo SUBMITPIC; ?></a></font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="<?=$votephp?>"><? echo ORGOVOTE;?></a></font></p>
 </div>
<? if (!isset($go)) { ?>
      </td>
      </tr>
    </table>
</table>
</body>
</html>
<? }
exit;
}


// log in user if not already logged in
if (!isset ($logged)) {
if (!isset($go)) {
	?>
<html>
<head>
<title><?=$sitename?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" text="#000000">
<table border="0" width="99%" align="center" bgcolor="#375288" height="396">
<tr align="center">
<td valign="top"> 
<? } ?>
<table border=0 cellspacing=1 cellpadding=4 align="center" width="99%" height="396">
  <tr bgcolor="#FFFFFF"> 
          <td valign="top"> 
          <div align="center">
           <p><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo MUSTLOGIN;?></font></b></p>
           <form method="post" action="<?=$loginphp?>">
           <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <b>
           <input type="hidden" name="go" value="user">
           <input type="hidden" name="goto" value="<?=$to?>">
                </b> </font> 
              <table width="210" border="0" cellspacing="2" cellpadding="4" align="center">
                <tr> 
                  <td valign="top" width="74" bgcolor="#EFEFEF"> 
                 <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo USERNAME; ?></font></div>
                  </td>
                    <td colspan="3" valign="top" width="154"> 
                      <div align="left"> 
                        <input type="text" name="loginuser" size="15" maxlength="20">
                      </div>
                   </td>
                  </tr>
                  <tr>
                   <td valign="top" height="21" width="74" bgcolor="#EFEFEF"> 
                      <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><? echo PASSWORD;?></font></div>
                    </td>
                   <td valign="top" height="21" colspan="3" width="154"> 
                      <div align="left"> 
                    <input type="text" name="loginpw" size="15" maxlength="40">
                       <br>
                        <input type="submit" value="<? echo LOGINNOW;?>">
                       <br>
                     </div>
                   </td>
                  </tr>
                </table>
         </form>
          <p>&nbsp;</p>
           <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><i><? echo DONTHAVEACCT; ?></i><br>
          <a href="<?=$gophp?>?go=signupphp"><? echo SUBMITPIC; ?></a></font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="<?=$votephp?>"><? echo ORGOVOTE;?></a></font></p>
 </div>
<? if (!isset($go)) { ?>
      </td>
      </tr>
    </table>
</table>
</body>
</html>
<? }
exit;
}

// once authorized
@mysql_query("UPDATE $usertable set lastlogin = CURRENT_TIMESTAMP where name = '$logged'");

if ($action == "remove") {  // remove image
$result=mysql_query("SELECT url FROM $imagetable where id = '$submitid'") or die(mysql_error());
$delurl=mysql_result($result,0,"url");
mysql_query("DELETE FROM $imagetable where id = '$submitid'") or die(mysql_error());
mysql_query("DELETE FROM $commenttable where name = '$submitid'") or die(mysql_error());
$file = basename($delurl);
$delurl=parse_url($delurl);
$serverurl=parse_url($uploadurl);
if (($serverurl[host]) == ($delurl[host])) unlink("$uploadpath$file");
   $letknown = "<b>".IMGREMOVED."</b><br>";
}


if ($action == "updatepic") {   // update image
   $result=mysql_query("SELECT url FROM $imagetable where id = '$submitid'") or die(mysql_error());
   $delurl=mysql_result($result,0,"url");
if ($submiturl != $delurl) {
   $file = basename($delurl);
   $delurl=parse_url($delurl);
   $serverurl=parse_url($uploadurl);
   if (($serverurl[host]) == ($delurl[host])) {$letknown = "<b>".CANTCHANGE."</b><br><br>";}
       else mysql_query("UPDATE $imagetable SET url = '$submiturl' where id = '$submitid'") or die(mysql_error());
}
   mysql_query("UPDATE $imagetable SET category = '$updatecat',description = '$submitdescription', notifypub = '$submitnotifypub', status = 'waiting',  reason = 'updated' where id = '$submitid'") or die(mysql_error());
   $letknown .= INFOUPDATED;
}

if ($action == "dm") {  // delete all mail
mysql_query("delete from $mailtable where name = '$logged'") or die(mysql_error());
}

if ($action == "updateinfo") {
   if(!$submitemail == "" && (!strstr($submitemail,"@") || !strstr($submitemail,".")))  $letknown .= "<font size=3 color=red>".ENTEREMAIL."</font><br>";
else { mysql_query("UPDATE $usertable SET age = '$submitage', homepage = '$submithomepage', email = '$submitemail', notifypriv = '$submitnotifypriv' where name = '$cookieinfo'") or die(mysql_error());

if (($submitemail != $user_id["email"]) && $validate == "yes") {
srand ((double) microtime() * 1000000);
$newcode = "";$i=0;
while($i<8)  { $newcode .= chr((rand()%26)+97); $i++; }
@mysql_query("UPDATE $usertable SET validate = '$newcode' where name = '$cookieinfo'");
dispatch_vcode($cookieinfo,$vcodemail,$vcodesubject);
$letknown .= VALCODESENT."<br>";
}

for ($i=1; $i < 21; $i++)
 { $marker = "info".$i; $markdat = $$marker;
if (strlen($markdat) > 1) mysql_query("UPDATE $usertable SET $marker = '$markdat' where name = '$cookieinfo'") or die(mysql_error());
 }
   $letknown .= INFOUPDATED;}
}
// get user info
$result=mysql_query("SELECT * FROM $usertable WHERE name='$cookieinfo' limit 1");
$rows = mysql_num_rows($result);
if ($rows < 1) errormsg(INVALIDUSER);
$user_id=mysql_fetch_array($result);

$username=$user_id["name"];
$pw=$user_id["password"];
$name=$user_id["name"];
$email=$user_id["email"];
if ($email == "null@null") $email = "";
$age=$user_id["age"];
$category=$user_id["category"];
$homepage=$user_id["homepage"];
$self=$user_id["self"];
$currentpw=$user_id["password"];
$notifypriv=$user_id["notifypriv"];

foreach ( $extras as $marker ) {  // display values
 $extra[$marker][value]=$user_id["$marker"];
   }


// get mails for user
$mailresult=mysql_query("SELECT * FROM $mailtable WHERE name='$cookieinfo'");
$nomail = mysql_num_rows($mailresult);

// get image infos
$imgresult=mysql_query("SELECT * FROM $imagetable WHERE name='$cookieinfo'");
$numimages = mysql_num_rows($imgresult);

if ($action == "addpic")
{
if ($numimages >= $maximages && $admin != $email) $message .= MAXIMGS;
// if ($numimages >= 5) $message .= MAXIMGS;

if (strlen($newurl) <= 8) {  // begin file upload routine
$source = $HTTP_POST_FILES['userpic']['tmp_name'];
$dest = '';

if ( ($source != 'none') && ($source != '' )) {

$newfile = uniqid('img').'';
$dest = $uploadpath.$newfile;
if (ereg( "[4-9]\.[0-9]\.[3-9].*", phpversion() ) || ereg( "[4-9]\.[1-9]\.[0-9].*", phpversion() )) {
        if ( $dest != '' ) {
             if ( move_uploaded_file( $source, $dest ) ) {$url = $uploadurl.$newfile;}
             else $message .=  FILENOTSTORED."<BR>";
if (isset($chmod)) chmod ($dest, 0755);   // some servers will require this line

        }
   }
         else {if ( $dest != '' ) {
              if (is_uploaded_fil($source)) { copy($source, $dest); $url = $uploadurl.$newfile;}
if (isset($chmod)) chmod ($dest, 0755);   // some servers will require this line
              }}

          } else $message =  FILETOOBIG;


$imagesize = getimagesize($dest);
switch ( $imagesize[2] ) {
           case 1:
                rename($dest, $dest.".gif");
                $url.= ".gif";
                break;
            case 2:
                rename($dest, $dest.".jpg");
                $url.= ".jpg";
                $dest .= ".jpg";
                addlogo();
                $jpg = 1;
                break;
            case 3:
                rename($dest, $dest.".png");
                $url.= ".png";
	           break;
		   default:
               $message = INVALIDIMG;
		   		@unlink($dest);
                break;
               }
              if ( $imagesize[0] > $imgsize) $resize = "yes";
if ( $source_size > ($uploadsize * 1124) ) $message .= FILETOOBIG;
if ( filesize($dest) > ($uploadsize * 1024) ) { unlink($dest); $message .= FILETOOBIG; }
$newurl=$url;
}   // end file upload routine


$query = mysql_query("select url from $imagetable where url='$newurl'");
$rows = mysql_num_rows($query);
if ($rows > 0) $message .= IMGEXISTS."<br>";
if((strlen($newurl) <= 8) && !$userpic) $message .= NOURL."<br>";
if(!$newdescribe) $message .= NODESCRIP."<br>";
if ($message) { errormsg ($message); exit; }

if (isset($newself)) { $total = 1; $rate = $newself;}
else {$total = 0; $rate = 0;}
$resize = "no";
$average = $rate;
$status = WAITING;
mysql_query("INSERT INTO $imagetable (name, url, category, description, notifypub, self, one, two, three, four, five, six, seven, eight, nine, ten, total, rate, average, resize, status, reason)
                         VALUES('$cookieinfo','$newurl','$newcat','$newdescribe','$newnotifypub','$newself','0','0','0','0','0','0','0','0','0','0','$total','$rate','$average','$resize','$status','new')") or die(mysql_error());
$newid = mysql_insert_id();

if (!isset($go)) {
print "<html>\n";
print "<head>\n";
print "<title>$sitetitle</title>\n";
print "</head>\n";
print "<body bgcolor=\"#ffffff\" text=\"#000000\" link=\"#006699\" alink=\"#000000\" vlink=\"#000000\" marginheight=\"0\" marginwidth=\"0\" topmargin=0 leftmargin=0 rightmargin=0>\n";
print "<br>\n";
print "<table border=0 cellpadding=0 cellspacing=0 width=\"500\" align=\"center\">\n";
print "  <center>\n";
print "    <tr bgcolor=\"#375288\"> \n";
print "      <td> \n";
}
print "        <table border=0 cellspacing=1 cellpadding=4 width=\"100%\" align=\"center\">\n";
print "          <tr> \n";
print "            <td valign=\"top\" colspan=\"2\" bgcolor=\"#f7f7f7\"> \n";
print "              <div align=\"center\" class=\"topper\"> \n";
print "                <p><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><b><font size=\"3\">".PICSREV."</font></b></font>\n";
print "              </div>\n";
print "              <form method=\"POST\" action=\"$gophp\">\n";
print "                <font face=\"Arial, Helvetica, sans-serif\" size=\"2\"> \n";
print "                <input type=\"hidden\" name=\"loginuser\" value=\"$username\">\n";
print "                <input type=\"hidden\" name=\"go\" value=\"userphp\">\n";
print "                </font> \n";
print "                <p> <font face=\"Arial, Helvetica, sans-serif\" size=\"2\"> \n";
print "                  <input type=\"submit\" value=\"".RETURNTOMEM."\">\n";
print "                  </font></p>\n";
print "              </form>\n";
print "            </td>\n";
print "          </tr>\n";
print "        </table>\n";
print "      </td>\n";
print "    </tr>\n";
print "  </center></table>\n";
print "<center>\n";
print "  <table border=\"0\" width=\"760\" cellpadding=\"5\" cellspacing=\"0\">\n";
print "    <tr bgcolor=\"#ffffff\"> \n";
print "      <td valign=\"center\" align=\"center\"> <font size=\"1\" face=\"Verdana, Arial\" color=\"#000000\">".POWEREDBY." <a href=\"http://www.imagevote.com/\">Image Vote</a></font><br>\n";
if (!isset($go)){
print "        <br>\n";
print "      </td>\n";
print "    </tr>\n";
print "  </table>\n";
print "</center>\n";
print "</body>\n";
print "</html>\n";
}
exit;

}

// done with database?  better close it
mysql_close ();
?>
<? if (!isset($go)) { ?>

<html>

<head>

<title><?=$sitename?></title>
<STYLE>
A:visited         {TEXT-DECORATION: underline}
A:hover         {BACKGROUND-COLOR: #C7D8EA; COLOR: #110474}
A:link                {TEXT-DECORATION: underline}
A:active         {TEXT-DECORATION: underline, overline}
BODY                 {CURSOR: default; FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial; FONT-SIZE: 10px}
UL                {CURSOR: default; FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
LI                 {CURSOR: default; FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
P                {CURSOR: default; FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
TD                 {FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
TR                 {FONT-FAMILY: MS Sans Serif, Tahoma, Verdana, Arial, sans-serif; FONT-SIZE: 10px}
</STYLE>

</head>

<body bgcolor="#ffffff" text="#000000" link="#006699" alink="#000000" vlink="#000000" marginheight="0" marginwidth="0" topmargin=0 leftmargin=0 rightmargin=0>

<br>
<table border=0 cellpadding=0 cellspacing=0 width="700" align="center">
  <tr bgcolor="#375288">
      <td>
<?} ?>

<script LANGUAGE="JavaScript">
function fullScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=350,height=400');
}
</script>
<script LANGUAGE="JavaScript">
function scrollScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=yes, width=420,height=400');
}
</script>
      <table border=0 cellspacing=1 cellpadding=4 width="100%" align="center">
        <tr>
          <td valign="top" colspan="2" bgcolor="#f7f7f7">
<div align="center"><h3><?=$letknown?></h3><br><b><? print MEMBERSAREAFOR." $cookieinfo"; ?></b><br><a href="<?=$votephp?>"><? print RETURNHOME; ?>!</a> </div>
            <div align="center">
<form method="post" action="<?=$gophp?>" method="POST">
                <table width="100%" border="0" cellspacing="2" cellpadding="2">
       <tr>
                    <td width="33%">
                      <div align="right"><b><small><strong><? print EMAIL; ?></strong></small></b></div>
                    </td>

                  <td colspan="3" width="67%">
                    <input type="text" name="submitemail" size="40" value="<?=$email?>">
                  </td>
                  </tr>
                     <tr>
                    <td width="33%">
                      <div align="right"><b><small><strong><? print AGE; ?>:</strong></small></b></div>
                    </td>

                  <td colspan="3" width="67%">
                    <input type="text" name="submitage" size="5" value="<?=$age?>">
                  </td>
                  </tr>
              
				  
		
            <?
  foreach ( $extras as $marker ) {  // display values
?>
	<tr>
                    <td width="33%">
                      <div align="right"><b><small><strong><? print $extra[$marker][name]; ?>:</strong></small></b></div>
                    </td>
 <td colspan="3" width="67%">


	<? if (!is_array($extra[$marker][type])) { ?>
   <input type="text" name="<?=$marker?>" size="30" value="<? print $extra[$marker][value];?>">
	<? } else { 
	print "<select name=\"$marker\">";
    print "<option value=\"".$extra[$marker][value]."\" selected>".$extra[$marker][value]."</option>";
	while (list($key, $value) = each ($extra[$marker][type])) {
	if ($value != $extra[$marker][value]) print "<option value=\"$value\">$value</option>";
	}
	print "</select>";
    } 
    print "</td></tr>"; 
              
} ?>

                  <tr>
                    <td width="33%">
                      <div align="right"><b><small><strong><? print HOMEPAGE; ?></strong></small></b></div>
                    </td>

                  <td colspan="3" width="67%">
                    <input type="text" name="submithomepage" size="43" value="<?=$homepage?>" maxlength="60">
                  </td>
                  </tr>
                  <tr>
                    <td width="33%" valign="top">
                      <div align="right"><b><small><strong><? print NOTIFYPRIV; ?></strong></small></b></div>
                    </td>

                  <td colspan="3" width="67%"  valign="top">
                  <input type=checkbox name="submitnotifypriv" value="1"<? if ($notifypriv == "1") print " checked"; ?>>
                  </td>
                  </tr>

                  <tr>
                   <td colspan="4" align="center">
                    <input type="hidden" name="go" value="userphp">
                    <input type="hidden" name="action" value="updateinfo">
                    <input type="submit" name="add2">
                  </td>
                  </tr>
                </table>
              </form>
              <hr width="500">
              <b><? print PRIVMSG; ?></b><br>

              <table width="550" border="0" cellspacing="0" cellpadding="0">
                <tr bgcolor="#375395">
                  <td width="58"><b><font color="#FFFFFF"><? print STATUS;?></font></b></td>
                  <td width="96"><b><font color="#FFFFFF"><? print FROM;?></font></b></td>
                  <td width="274"><b><font color="#FFFFFF"><? print SUBJECT;?></font></b></td>
                  <td width="122"><b><font color="#FFFFFF"><? print MSGDATE;?></font></b></td>
                </tr>
                <?
if ($nomail == 0) print NOMSG;
else {
$count=0;
while ($count <  $nomail) {
$id=mysql_result($mailresult,$count,"id");
$fromuser=mysql_result($mailresult,$count,"fromuser");
$subject=mysql_result($mailresult,$count,"subject");
$datestamp=mysql_result($mailresult,$count,"datestamp");
$body=mysql_result($mailresult,$count,"body");
$status=mysql_result($mailresult,$count,"status");
print "  <tr> ";
print "    <td width=\"58\"><font size=\"2\">$status</font></td>";
print "    <td width=\"96\"><font size=\"2\"><a href=\"javascript:void(0);\" onClick=\"scrollScreen('$profilephp?u=$fromuser')\">$fromuser</a></font></td> ";
print "    <td width=\"274\"><font size=\"2\"><a href=\"javascript:void(0);\" onClick=\"fullScreen('$mailphp?id=$id');\">$subject</a></font></td> ";
print "    <td width=\"122\"><font size=\"2\">$datestamp</font></td>";
print "  </tr>  ";
$count++;
}
}
?>
              </table>
<a href="<? echo $gophp."?go=userphp";?>"><? print REFRESHMAIL; ?></a>&nbsp;&nbsp;<a href="<? echo $gophp."?go=userphp&action=dm";?>"><? print DELMAIL; ?></a>
              <hr width="500">
            </div>
            <center>
<?
if ($numimages == 0) print NOCURPIC."<br>";
else {
$count=0;
// if ($action != "remove") $numimages++;
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
print "                              <div align=\"center\"> <img src=\"$url\" width=\"175\"><br>\n";
print "                              </div>\n";
print "                            </td>\n";
print "                            <td width=\"23%\" valign=\"top\"> \n";
print "                              <div align=\"right\"><font size=\"2\">".IMGID."</font></div>\n";
print "                            </td>\n";
print "                            <td width=\"50%\" valign=\"top\"><font size=\"2\">$id</font></td>\n";
print "                          </tr>\n";
print "                          <tr> \n";
print "                            <td width=\"23%\"> \n";
print "                              <div align=\"right\"><font size=\"2\">".STATUS."</font></div>\n";
print "                            </td>\n";
print "                            <td width=\"50%\"><font size=\"2\"><b>$status</b></font></td>\n";
print "                          </tr>\n";
print "                          <tr> \n";
print "                            <td width=\"23%\"> \n";
print "                              <div align=\"right\"><font size=\"2\">".RATING."</font></div>\n";
print "                            </td>\n";
print "                            <td width=\"50%\"><font size=\"2\"><b>$average ($total ".V.")</b>&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"scrollScreen('$profilephp?id=$id&u=$cookieinfo')\">Viewer Comments</a></font></td>\n";
print "                          </tr>\n";
print "                          <tr> \n";
print "                            <td colspan=\"4\"> <font size=\"2\">".IMGURL."<br>\n";
print "                              <input type=\"text\" name=\"submiturl\" size=\"50\" value=\"$url\">\n";
print "                              <br>\n";
print "                              ".DESCRIBE."<br>\n";
print "                              <input type=\"text\" name=\"submitdescription\" size=\"50\" value=\"$description\" maxlength=\"60\">\n";
print "                              <br>\n";
print CATEGORY." <select name=\"updatecat\" size=\"1\">";
print " <option value=\"$c\">$c</option>";
foreach ($categories as $a) print "<option value=\"$a\">$a</option>";
print "                          </select><br>";
print "                          <input type=checkbox name=\"submitnotifypub\" ";
if ($notifypub == "1") print "checked ";
print " value=\"1\">".NOTIFYPUB."<br>\n";
print "                              <select name=\"action\">\n";
print "                                <option value=\"updatepic\" selected>".UPDATEIMG."</option>\n";
print "                                <option value=\"remove\">".REMOVEIMG."</option>\n";
print "                              </select>\n";
print "                              <input type=\"hidden\" name=\"submitid\" value=\"$id\">\n";
print "                              <input type=\"hidden\" name=\"go\" value=\"userphp\">\n";
print "                              <input type=\"submit\" value=\"".DOIT."\">\n";
print "                              <br>\n";
print "                              <font size=\"1\">".MODNOTE."</font><br>\n";
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

       ?>



              <table border="0" width="100%" bgcolor="#FFFFFF">
                <tr>
                  <td colspan="2" align="center" valign="top">
                    <form name="form1" ENCTYPE="multipart/form-data" action="<?=$userphp?>" method="post">
                      <table border="0" width="80%">
                        <tr>
                          <td colspan="2">
                            <div align="center"><? print UPTOFIVE;?></div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2" bgcolor="#375395"><strong><font color="#FFFFFF"><? print ADDANOTHER;?></font></strong></td>
                        </tr>
                        <? if ($allowurl != "0") {?>
                        <tr>
                          <td width="30%"><small><strong><? print IMGURL;?></strong></small></td>
                          <td width="70%">
                            <input type="text" name="newurl" size="43" value="http://"><br>
							(ie: http://www.yourwebhost.com/yourdir/yourpic.jpg) 
                          </td>
                        </tr>
                    <? }?>
                      <? if ($allowupload != "0") {?>

                      <tr>
                        <td width="33%"><b><? print ORUPLOAD; ?></b></td>
                        <td width="67%">
                          <input type="FILE" name="userpic" size="30">
                        </td>
                      </tr>
                      <? }?>
					  <tr>
                        <td width="33%"><b><? print CATEGORY; ?></b></td>
                        <td width="67%">
<select name="newcat" size="1"><option value="<?=$category?>"><?=$category?></option>
<? foreach ($categories as $a) { if ($a != $category) print "<option value=\"$a\">$a</option>"; } ?>
 </select><br>

                        </td>
                      </tr>
                        <tr>
                          <td colspan="2"><strong><small><? print DESCRIBE; ?>
                            </small></strong></td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <input type="text" name="newdescribe" size="59" value="<? print SAMPLEDES;?>">
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                          <input type=checkbox name="newnotifypub" checked value="1"><strong><small><? print NOTIFYPUB;?></small></strong>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <hr>
                          </td>
                        </tr>
                        <tr bgcolor="#375395">
                          <td colspan="2"><strong><font color="#FFFFFF"><? print RATING;?></font></strong></td>
                        </tr>
                        <tr>
                          <td colspan="2"><small><strong><? print WHATRATE;?></strong></small></td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <div align="left">
                              <table border="0" width="96%" bgcolor="#000000">
                                <tr>
                                  <td width="100%">
                                    <table border="0" width="100%" cellspacing="0" cellpadding="0" height="20">
                                      <tr bgcolor="#CCCCFF">
                                        <td width="100%" height="20">
                                          <div align="center">
                                            <p><strong><small>1
                                              <input type="radio" value="1" name="newself">
                                              2
                                              <input type="radio" value="2" name="newself">
                                              3
                                              <input type="radio" value="3" name="newself">
                                              4
                                              <input type="radio" value="4" name="newself">
                                              5
                                              <input type="radio" value="5" name="newself">
                                              6
                                              <input type="radio" value="6" name="newself">
                                              7
                                              <input type="radio" value="7" name="newself">
                                              8
                                              <input type="radio" value="8" name="newself">
                                              9
                                              <input type="radio" value="9" name="newself">
                                              10
                                              <input type="radio" value="10" name="newself">
                                              </small></strong>
                                          </div>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </td>
                        </tr>
                        <tr align="center">
                          <td colspan="2">
                            <input type="hidden" name="action" value="addpic">
                            <input type="submit" value="<? print ADDPIC; ?>" name="add">
                          </td>
                        </tr>
                        <tr align="center">
                          <td width="30%"></td>
                          <td width="70%"></td>
                        </tr>
                                            </table>

                      </form>
                    <br>
                    <a href="<?=$votephp?>"><? print RETURNHOME; ?></a></td>
                </tr>
              </table>
                     </center>
            </td>
        </tr>
      </table>
<? if (!$go) { ?>
</td></tr>
</table>

<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr>
      <td valign="center" align="center"> &copy; <?=$sitename?>
        <br>
      </td>
    </tr>
  </table>
  </center>
</body>
</html>
<? }?>
