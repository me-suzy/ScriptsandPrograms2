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

// Image manipulation feature.  Only works on JPGs and on servers that support GD
$addwhat=0; // Add what to uploaded Images?  See next line
// (0=nothing!, 1 = Logo (tag.jpg), 2 = text string
$logoh = "12"; // logo height if adding a logo (tag.jpg)

require "config.php";
langprocess();
langlogin();
langmail();

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
    
function userexists($username)
{
	$sql = "select name from $GLOBALS[usertable] where name='$username'";
    mysql_connect($GLOBALS[host],$GLOBALS[user],$GLOBALS[pass]);
    @mysql_select_db($GLOBALS[database]) or die( "Unable to select database userexisits");
	$query = mysql_query($sql);
	$rows = mysql_num_rows($query);
        mysql_close ();
	if ($rows > 0)
		return 1;
	return 0;
}

function sitexists($url)
{
	$sql = "select url from $GLOBALS[imagetable] where url='$url'";
    mysql_connect($GLOBALS[host],$GLOBALS[user],$GLOBALS[pass]);
    @mysql_select_db($GLOBALS[database]) or die( "Unable to select database sitexists");
	$query = mysql_query($sql);
	$rows = mysql_num_rows($query);
        mysql_close ();
	if ($rows > 0)
		return 1;
	return 0;
}

function is_uploaded_fil($filename) {  // function for PHP versions < 4.0.3
  
  if (!$tmp_file = get_cfg_var('upload_tmp_dir')) {
        $tmp_file = dirname(tempnam('', ''));
    }
    $tmp_file .= '/' . basename($filename);
    return (ereg_replace('/+', '/', $tmp_file) == $filename);
}


$username = strtolower($username);
if(!$username) { $message .= NOUSERNAME."<br>";}
if(!$password) { $message .= NOPASS."<br>"; }
if(!$email == "" && (!strstr($email,"@") || !strstr($email,".")))  $message .= ENTEREMAIL."<BR>";

if ($submitpic != "no" ) {if((strlen($url) <= 8) && !$userpic)  $message .= NOURL."<br>";}

// if(!$age)  $message .= "Your age was not entered.<br>";
if(!$category)  $message .= NOCAT."<br>";
if ($submitpic != "no") {if(!$describe)  $message .= NODESCRIP."<br>";}
if(!$self)  $self=5;
if (userexists($username)) $message .= USEREXISTS."<br>";
if (sitexists($url) && !$userpic && $submitpic != "no") $message .= IMGEXISTS."<br>";
if (isset($aself)) $message .= "<br><ID#>";
$resize = "no";
if (!isset($submitpic)) $submitpic ="yes";

if ($submitpic == "yes") {

if (strlen($url) <= 8 && $allowupload != 0) {  // begin file upload routine
$source = $HTTP_POST_FILES['userpic']['tmp_name'];
$dest = '';

if (($source != 'none') && ($source != '' )) {

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


$imagesize = @getimagesize($dest);

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
if ( $source_size > ($uploadsize * 1024) ) $message .= FILETOOBIG;
if ( filesize($dest) > ($uploadsize * 1024) ) { unlink($dest); $message .= FILETOOBIG; }

}  // end file upload routine
}

if ($message) { errormsg ($message); exit; }

$status = WAITING;

if ($validate == "yes") {
srand ((double) microtime() * 1000000);
$valcode = "";$i=0;
while($i<8)  {$valcode .= chr((rand()%26)+97); $i++; }
}
else $valcode = "ok";


mysql_connect($host,$user,$pass);
@mysql_select_db($database) or die( "Unable to select database");
mysql_query("INSERT INTO $usertable (name, password, age, category, homepage, self, email, notifypriv, validate, joindate) VALUES('$username','$password','$age','$category','$homepage','$self','$email','$notifypriv','$valcode', CURRENT_TIMESTAMP)") or die(mysql_error());

for ($i=1; $i < 21; $i++)
 { $marker = "info".$i; $markdat = $$marker;
if (strlen($markdat) > 0) mysql_query("UPDATE $usertable SET $marker = '$markdat' where name = '$username'") or die(mysql_error());
 }

if ($submitpic != "no") {mysql_query("INSERT INTO $imagetable (name, url, category, description, notifypub, self, total, rate, average, resize, status, reason)
                         VALUES('$username','$url','$category','$describe','$notifypub','$self','1','$self','$self','$resize','$status','new')") or die(mysql_error());

$newid = mysql_insert_id();


}
mysql_close ();

header ("Set-Cookie: logged=$username; expires=Friday, 16-Jan-2037 00:00:00 GMT; path=/;");
?>
<html>
<head>
<title><?=$sitetitle?></title>
</head>
<body bgcolor="#ffffff" text="#000000" link="#006699" alink="#000000" vlink="#000000" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" rightmargin="0">
<br>
<table border=0 cellpadding=0 cellspacing=0 width="500" align="center">
  <center>
    <tr bgcolor="#375288"> 
      <td> 
        <table border=0 cellspacing=1 cellpadding=4 width="100%" align="center">
          <tr> 
            <td valign="top" colspan="2" bgcolor="#f7f7f7"> 
              <div align="center" class="topper"> 
                <p><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><b><? echo PICSREV;?></b></font></p>
              </div>
              <p><font face="Arial"><big><font face="Arial, Helvetica, sans-serif" size="2"><? echo WELCOMETO."<br>".THANKU2;?></font></big></font></p>
              <p><font face="Arial, Helvetica, sans-serif" size="2"><? echo HEREINFO; ?></font></p>
              <p><font face="Arial, Helvetica, sans-serif" size="2"><? echo USERNAME." ".$username; ?></font></p>
              <p><font face="Arial, Helvetica, sans-serif" size="2"><? echo PASSSWORD." ".$password; ?></font></p>
              <form method="POST" action="<?=$loginphp?>">
                <font face="Arial, Helvetica, sans-serif" size="2"> 
                <input type="hidden" name="loginpw" value="<?=$password?>">
                <input type="hidden" name="loginuser" value="<?=$username?>">
                <input type="hidden" name="go" value="vote">
                </font> 
                <p> <font face="Arial, Helvetica, sans-serif" size="2"> 
                  <input type="submit" value="<? echo LOGINNOW;?>">
                  </font></p>
              </form>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </center></table>
<center>
  <table border="0" width="760" cellpadding="5" cellspacing="0">
    <tr bgcolor="#ffffff"> 
      <td valign="center" align="center"> <font size="1" face="Verdana, Arial" color="#000000"><? echo POWEREDBY; ?><a href="http://www.imagevote.com/">Image Vote</a></font><br>
        <br>
      </td>
    </tr>
  </table>
</center>
</body>
</html>
<?
if ($notification == "yes" && $validate == "no") {
$recipient .= "$username <$email>";
$headers .= "From: $sitename <$admin>\n";
if ($email != "" && $email != "null@null") mail($recipient, $subjectmail, $notifmail, $headers);
}

if ($validate == "yes") {
$vcodemail = ereg_replace ("VCODE", $valcode, $vcodemail);
$vcodemail = ereg_replace ("USERNAME", $username, $vcodemail);
$vcodemail .= $notifmail;
$recipient .= "$username <$email>";
$headers .= "From: $sitename <$admin>\n";
if ($email != "" && $email != "null@null") mail($recipient, $subjectmail, $vcodemail, $headers);
}

//  Image Vote(c) 2002 ProPHP.Com
?>
