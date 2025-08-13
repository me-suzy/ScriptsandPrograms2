<?php
//Read in config file
$thisfile = "conf_data";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
include("../includes/admin_conf_lib.php");
if ($Submit2 == $la_button_update) 
	updatecustom();

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
    <td rowspan="2" width="0"><a href="help/6.htm#datastruc"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
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

	$nav_names_admin=array($la_title_output,$la_title_email,$la_title_data_structure,$la_title_user_settings,$la_title_security,$la_title_system, $la_title_search);
	$nav_links_admin[$la_title_output]="conf_output.php$att_sid";
  	$nav_links_admin[$la_title_email]="conf_email.php$att_sid";
   	$nav_links_admin[$la_title_data_structure]="conf_data.php$att_sid";
    $nav_links_admin[$la_title_user_settings]="conf_users.php$att_sid";
    $nav_links_admin[$la_title_security]="conf_security.php$att_sid";
    $nav_links_admin[$la_title_system]="conf_system.php$att_sid";
	$nav_links_admin[$la_title_search]="conf_search.php$att_sid";
	echo display_admin_nav($la_title_data_structure, $nav_names_admin, $nav_links_admin);
?>
  <tr>
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_data_structure; ?></td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="conf_data.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2"><?php echo $la_categories_custom_fields; ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_categories_custom_fields1; ?></td>
            <td class="text"> 
              <input type="text" name="cc1n" class="text" value="<?php echo $cc1; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"><?php echo $la_categories_custom_fields2; ?></td>
            <td class="text"> 
              <input type="text" name="cc2n" class="text" value="<?php echo $cc2; ?>">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_categories_custom_fields3; ?></td>
            <td class="text"> 
              <input type="text" name="cc3n" class="text" value="<?php echo $cc3; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"><?php echo $la_categories_custom_fields4; ?></td>
            <td class="text"> 
              <input type="text" name="cc4n" class="text" value="<?php echo $cc4; ?>">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_categories_custom_fields5; ?></td>
            <td class="text"> 
              <input type="text" name="cc5n" class="text" value="<?php echo $cc5; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle">
            <td class="text"><?php echo $la_categories_custom_fields6; ?></td>
            <td class="text">
              <input type="text" name="cc6n" class="text" value="<?php echo $cc6; ?>">
            </td>
          </tr>
          <tr bgcolor="#CCCCCC" valign="middle">
            <td colspan="2" class="textTitle" bgcolor="#999999"><?php echo $la_links_custom_fields; ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle">
            <td class="text"><?php echo $la_links_custom_fields1; ?></td>
            <td class="text">
              <input type="text" name="lc1n" class="text" value="<?php echo $lc1; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle">
            <td class="text"><?php echo $la_links_custom_fields2; ?></td>
            <td class="text">
              <input type="text" name="lc2n" class="text" value="<?php echo $lc2; ?>">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle">
            <td class="text"><?php echo $la_links_custom_fields3; ?></td>
            <td class="text">
              <input type="text" name="lc3n" class="text" value="<?php echo $lc3; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle">
            <td class="text"><?php echo $la_links_custom_fields4; ?></td>
            <td class="text">
              <input type="text" name="lc4n" class="text" value="<?php echo $lc4; ?>">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle">
            <td class="text"><?php echo $la_links_custom_fields5; ?></td>
            <td class="text">
              <input type="text" name="lc5n" class="text" value="<?php echo $lc5; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle">
            <td class="text"><?php echo $la_links_custom_fields6; ?></td>
            <td class="text">
              <input type="text" name="lc6n" class="text" value="<?php echo $lc6; ?>">
            </td>
          </tr>
          <tr bgcolor="#CCCCCC" valign="middle">
            <td colspan="2" class="textTitle" bgcolor="#999999"><?php echo $la_users_custom_fields; ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_users_custom_fields1; ?></td>
            <td class="text"> 
              <input type="text" name="uc1n" class="text" value="<?php echo $uc1; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"><?php echo $la_users_custom_fields2; ?></td>
            <td class="text"> 
              <input type="text" name="uc2n" class="text" value="<?php echo $uc2; ?>">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_users_custom_fields3; ?></td>
            <td class="text"> 
              <input type="text" name="uc3n" class="text" value="<?php echo $uc3; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"><?php echo $la_users_custom_fields4; ?></td>
            <td class="text"> 
              <input type="text" name="uc4n" class="text" value="<?php echo $uc4; ?>">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_users_custom_fields5; ?></td>
            <td class="text"> 
              <input type="text" name="uc5n" class="text" value="<?php echo $uc5; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle">
            <td class="text"><?php echo $la_users_custom_fields6; ?></td>
            <td class="text">
              <input type="text" name="uc6n" class="text" value="<?php echo $uc6; ?>">
            </td>
          </tr>
           </table>
        <br>
        <input type="hidden" name="action" value="update">
        <input type="submit" name="Submit2" value="<?php echo $la_button_update; ?>" class="button">
        <input type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
        <input type="button" name="Submit32" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
      </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
