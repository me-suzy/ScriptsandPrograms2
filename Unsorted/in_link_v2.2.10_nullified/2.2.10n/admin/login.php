<?php
//Read in config file
$thisfile = "login"; 
$admin=1;
$configfile = "../includes/config.php";
include($configfile);

if($submit)
{	$in=login($login,$psw);
	if($in==1)
	{	inl_header ("index.php");
		//echo "Logged in: $sid";
	}

}
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" href="admin.css" type="text/css">
<META http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FFFFFF" text="#000000" onLoad="form1.login.focus();">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr>
    <td valign="middle" align="center"> 
      <div align="center">
        <p><img src="images/logo.gif" width="176" height="64" border="0"></p><!--CyKuH [WTN]-->
        <form name="form1" method="post" action="login.php?target=_top">
          <table width="200" border="0" cellspacing="0" cellpadding="4" class="tableborder">
            <tr> 
              <td colspan="2" class="tabletitle" bgcolor="#333333">Login</td>
            </tr>
            <tr bgcolor="#F0F0F0"> 
              <td class="text"><?php echo $la_user_name; ?></td>
              <td> 
                <input type="text" name="login" class="text">
              </td>
            </tr>
            <tr bgcolor="#F0F0F0"> 
              <td class="text">
                <?php echo $la_password; ?>
              </td>
              <td> 
                <input type="password" name="psw" class="text">
              </td>
            </tr>
            <tr bgcolor="#F0F0F0"> 
              <td colspan="2"> 
                <div align="left">
                  <input type="submit" name="submit" value="<?php echo $la_button_login; ?>" class="button">
				  <input type="reset" name="Cancel" value="<?php echo $la_button_cancel; ?>" class="button">
                </div>
              </td>
            </tr>
          </table>
        </form>
        <p class="error">
		<?php	switch($in)
				{	case -4: case -1:
						echo $la_login_incorrect;
						break;
					case -2:
						echo $la_failed_session."<br>".$conn->ErrorMsg();
						break;
					case -3:
						echo $la_error_db."<br>".$conn->ErrorMsg();
						break;				
				}
		?>&nbsp;</p>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
