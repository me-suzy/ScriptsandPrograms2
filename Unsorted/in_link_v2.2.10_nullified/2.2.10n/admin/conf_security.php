<?php
//Read in config file
$thisfile = "conf_security";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
include("../includes/admin_conf_lib.php");
if ($action == "update") {
updaterootpass($pass_new, $pass_new2, $pass_old);
if ($error == "1") {
	$error = $la_error_pass_match;
} elseif ($error == "2") {
	$error = $la_error_pass_wrong;
} else {
	$error = $la_pass_success;
}
}
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon4-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav6; ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#security"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
<?php
	if($sid && $session_get)
		$att_sid="?sid=$sid";

	$nav_names_admin=array($la_title_output,$la_title_email,$la_title_data_structure,$la_title_user_settings,$la_title_security,$la_title_system, $la_title_search);	$nav_links_admin[$la_title_output]="conf_output.php$att_sid";
 	$nav_links_admin[$la_title_email]="conf_email.php$att_sid";
   	$nav_links_admin[$la_title_data_structure]="conf_data.php$att_sid";
    	$nav_links_admin[$la_title_user_settings]="conf_users.php$att_sid";
     	$nav_links_admin[$la_title_security]="conf_security.php$att_sid";
      	$nav_links_admin[$la_title_system]="conf_system.php$att_sid";
		$nav_links_admin[$la_title_search]="conf_search.php$att_sid";
	echo display_admin_nav($la_title_security, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_security; ?></td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="conf_security.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">
<?php echo $la_root_password_change_warning; ?></span><br><span class="error"><?php echo $error;?></span></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><span class="text"><?php echo $la_old_root_password; ?></span></td>
            <td class="text"> 
              <input type="password" name="pass_old" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"><?php echo $la_new_root_password; ?></td>
            <td class="text"> 
              <input type="password" name="pass_new" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_retype_password; ?></td>
            <td class="text"> 
              <input type="password" name="pass_new2" class="text" size="30">
            </td>
          </tr>
        </table>
        <br>
		<input type="hidden" name="action" value="update">
              <input type="submit" name="Submit" value="<?php echo $la_button_update; ?>" class="button">
              <input type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
      </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
