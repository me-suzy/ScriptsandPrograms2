<?php
session_start();

if (!empty($logincheck)){
$sql = "select username from newsadmin where username='$checkuser' and password='$checkpass'";
$results = $db->select($sql);
	if (empty($results)) {
	print "$admin_loginfail";
	exit;
	}else{	
	session_register("loginuser");
	$loginuser = $checkuser;
	}
}
?>
<?php
if (!(session_is_registered("loginuser"))){
?>
<html>
<head>
<title><?php print "$admin_login"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$admin_charset"; ?>">
<link rel="stylesheet" href="style/style.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<form action="<?php print "$PHP_SELF"; ?>" method="POST">
<?
if (count($HTTP_POST_VARS)) {
       while (list($key, $val) = each($HTTP_POST_VARS)) {
       print "<input type=\"hidden\" name=\"$key\" value=\"$val\">\n";
      }
}

if (count($HTTP_GET_VARS)) {
       while (list($key, $val) = each($HTTP_GET_VARS)) {
       print "<input type=\"hidden\" name=\"$key\" value=\"$val\">\n";
      }
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top"> 
      <hr width="90%" size="1" noshade>
      <table width="90%" border="0" cellspacing="0" cellpadding="4" height="300">
        <tr> 
          <td align="center"> 
            <p><?php print "$admin_login"; ?></p>
            <table width="300" border="0" cellspacing="1" cellpadding="4" bgcolor="#F2F2F2">
              <tr bgcolor="#FFFFFF"> 
                <td width="83"><?php print "$admin_username"; ?> :</td>
                <td width="198"><input type="text" name="checkuser"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_password"; ?> :</td>
                <td><input type="password" name="checkpass"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td>&nbsp;</td>
                <td><input type="submit" name="logincheck" value="<?php print "$admin_ok"; ?>"></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" height="40">&nbsp;</td>
  </tr>
</table>
</form>
<?php
include("bottom.php3");
?>
</body>
</html>
<?php
exit;
}
?>