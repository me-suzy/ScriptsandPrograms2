<?php
include("../const.inc.php");
if (!(is_writeable("../const.inc.php"))){
print "$admin_constisnotwriteable";
exit;
}
?>
<html>
<head>
<title><?php print "$admin_install"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$admin_charset"; ?>">
<link rel="stylesheet" href="style/style.css" type="text/css">
</head>

<body bgcolor="#FFFFFF">
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<form action="installfinish.php" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top"> 
      <hr width="90%" size="1" noshade>
      <table width="90%" border="0" cellspacing="0" cellpadding="4" height="300">
        <tr> 
          <td align="center"> 
            <p><?php print "$admin_databasesetting"; ?></p>
            <table width="300" border="0" cellspacing="1" cellpadding="4" bgcolor="#F2F2F2">
              <tr bgcolor="#FFFFFF"> 
                <td width="83"><?php print "$admin_databasename"; ?> :</td>
                <td width="198"><input type="text" name="installdbname" value="dingyetest"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_databaseuser"; ?> :</td>
                <td><input type="text" name="installdbuser" value="root"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_databasepass"; ?> :</td>
                <td><input type="text" name="installdbpass"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_databasehost"; ?> :</td>
                <td><input type="text" name="installdbhost" value="localhost"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td colspan="2"><br><p><?php print "$admin_setadminpassword"; ?></p></td>                
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_username"; ?> :</td>
                <td><input type="text" name="installusername" value="dingye"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_password"; ?> :</td>
                <td><input type="text" name="installpassword" value="test"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_adminemail"; ?> :</td>
                <td><input type="text" name="installadminemail"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td>&nbsp;</td>
                <td><input type="submit" name="Submit" value="<?php print "$admin_next"; ?>"></td>
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
