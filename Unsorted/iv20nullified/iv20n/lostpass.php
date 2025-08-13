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

function userexists($email)
{
	$sql = "select name from $GLOBALS[usertable] where email='$email'";
    mysql_connect($GLOBALS[host],$GLOBALS[user],$GLOBALS[pass]);
    @mysql_select_db($GLOBALS[database]) or die( "Unable to select database userexists");
	$query = mysql_query($sql);
	$rows = mysql_num_rows($query);
        mysql_close ();
	if ($rows > 0)
		return 1;
	return 0;
}

function acctexists($user)
{
    $user=strtolower($user);
	$sql = "select name from $GLOBALS[usertable] where name='$user'";
    mysql_connect($GLOBALS[host],$GLOBALS[user],$GLOBALS[pass]);
    @mysql_select_db($GLOBALS[database]) or die( "Unable to select database acctexists");
	$query = mysql_query($sql);
	$rows = mysql_num_rows($query);
        mysql_close ();
	if ($rows > 0)
		return 1;
	return 0;
}

require("config.php");
langlostpass();
langerrors();
$message = '';

if (strlen($username)>=2) {
if (acctexists($username) == 0 ) {langerrors();errormsg(NOACCT);}

mysql_connect($host,$user,$pass);

@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT * FROM $usertable WHERE name='$username'";
$result=mysql_query($query);
mysql_close ();

$email=mysql_result($result,0,"email");
$pw=mysql_result($result,0,"password");
langlostpass();

$recipient .= "$name <$email>";
$headers .= "From: $sitename <$admin>\n";
$headers .= "X-Mailer: PHP\n"; // mailer
$headers .= "X-Priority: 1\n"; // Urgent message!

if ($email == "" || $email == "null@null") errormsg(ACCTNOMAIL);
else mail($recipient, $subject, $newmail, $headers);

}

if (isset($email) && !isset($headers)) {

if (userexists($email) == 0 ) errormsg(NOACCT);

mysql_connect($host,$user,$pass);

@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT * FROM $usertable WHERE email='$email'";
$result=mysql_query($query);
mysql_close ();

$username=mysql_result($result,0,"name");
$pw=mysql_result($result,0,"password");
langlostpass();
$recipient .= "$name <$email>";
$headers .= "From: $sitename <$admin>\n";
$headers .= "X-Mailer: PHP\n"; // mailer
$headers .= "X-Priority: 1\n"; // Urgent message!
mail($recipient, $subject, $newmail, $headers);
}

if ($message) { errormsg ($message); exit; }
if (!$go) {?>

<html>
<head>
<title><? print LOSTPASS; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<br><table width="640" border="0" cellspacing="2" cellpadding="2" align="center">
  <tr bgcolor="#660000"> 
    <td> 
    <?} ?>
      <table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#DFDFDF">
        <tr bgcolor="#CDCDF1"> 
        
           <td><br>
<?
if (!$email) {
?>
<form method="post" action="<?=$PHP_SELF?>">
<table width="500" border="0" cellspacing="2" cellpadding="2" align="center" bgcolor="#000000">
<tr><td bgcolor="#3030A9"><div align="center"><font color="#FFFFFF"><? print LOSTPASS; ?></font></div>
</td></tr>
<tr bgcolor="#FFFFFF"><td><p align="center"><? print ENTEREMAIL; ?><input type="text" name="email">
<br><? print ORUSERNAME; ?> <input type="text" name="username"><br><br><input type="submit"></p></td></tr>

</table><br></form>
<?
}
else { print BEENEMAILED;}
?>
         <br><br> </td>
        </tr>
      </table>

<? if (!$go) {?>
    </td>
  </tr>
</table>
</body>
</html>         <?}?>
