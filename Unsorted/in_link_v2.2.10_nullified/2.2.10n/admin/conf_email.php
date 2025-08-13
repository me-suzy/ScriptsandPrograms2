<?php
//Read in config file
$thisfile = "conf_email";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
include("../includes/admin_conf_lib.php");
if ($action == "update") 
	update_conf_email();

adminemail();
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
    <td rowspan="2" width="0"><a href="help/6.htm#email"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
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
	echo display_admin_nav($la_title_email, $nav_names_admin, $nav_links_admin);
?>

  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_email; ?></td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="conf_email.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><span class="text">
              <?php echo $la_administrator_email_address; ?>
              </span></td>
            <td class="text"> 
              <input type="text" name="admin_email" class="text" value="<?php echo $admin_email; ?>">
            </td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2">
              <?php echo $la_administrator_notices; ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_a_new_user_is_registered; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="admin_new_user" value="1"<?if (read_email_setting($email_perm,"admin_new_user") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="admin_new_user" value="0"<?if (read_email_setting($email_perm,"admin_new_user") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_a_new_link_is_added; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="admin_new_link" value="1"<?if (read_email_setting($email_perm,"admin_new_link") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="admin_new_link" value="0"<?if (read_email_setting($email_perm,"admin_new_link") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_an_old_link_is_modified; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="admin_edit_link" value="1"<?if (read_email_setting($email_perm,"admin_edit_link") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="admin_edit_link" value="0"<?if (read_email_setting($email_perm,"admin_edit_link") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_a_new_category_is_suggested; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="admin_new_cat" value="1"<?if (read_email_setting($email_perm,"admin_new_cat") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="admin_new_cat" value="0"<?if (read_email_setting($email_perm,"admin_new_cat") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_a_new_review_added; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="admin_new_review" value="1"<?if (read_email_setting($email_perm,"admin_new_review") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="admin_new_review" value="0"<?if (read_email_setting($email_perm,"admin_new_review") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="2" class="textTitle">
              <?php echo $la_user_notices; ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_a_new_user_is_registered; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="new_user_r" value="1"<?if (read_email_setting($email_perm,"new_user_r") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="new_user_r" value="0"<?if (read_email_setting($email_perm,"new_user_r") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_a_new_user_is_approved; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="new_user_a" value="1"<?if (read_email_setting($email_perm,"new_user_a") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="new_user_a" value="0"<?if (read_email_setting($email_perm,"new_user_a") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_a_new_user_is_denied; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="new_user_d" value="1"<?if (read_email_setting($email_perm,"new_user_d") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="new_user_d" value="0"<?if (read_email_setting($email_perm,"new_user_d") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_a_link_is_added; ?>
            </td>
            <td class="text"> 
              <input type="radio" name="new_link" value="1"<?if (read_email_setting($email_perm,"new_link") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="new_link" value="0"<?if (read_email_setting($email_perm,"new_link") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_a_link_is_approved; ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              <input type="radio" name="new_link_a" value="1"<?if (read_email_setting($email_perm,"new_link_a") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="new_link_a" value="0"<?if (read_email_setting($email_perm,"new_link_a") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_a_link_is_denied; ?>
            </td>
            <td class="text" bgcolor="#DEDEDE"> 
              <input type="radio" name="new_link_d" value="1"<?if (read_email_setting($email_perm,"new_link_d") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="new_link_d" value="0"<?if (read_email_setting($email_perm,"new_link_d") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text" bgcolor="#F6F6F6"> 
              <?php echo $la_a_mod_is_added; ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              <input type="radio" name="edit_link" value="1"<?if (read_email_setting($email_perm,"edit_link") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="edit_link" value="0"<?if (read_email_setting($email_perm,"edit_link") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" bgcolor="#DEDEDE"> 
              <?php echo $la_a_mod_is_approved; ?>
            </td>
            <td class="text" bgcolor="#DEDEDE"> 
              <input type="radio" name="edit_link_a" value="1"<?if (read_email_setting($email_perm,"edit_link_a") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="edit_link_a" value="0"<?if (read_email_setting($email_perm,"edit_link_a") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text" bgcolor="#F6F6F6"> 
              <?php echo $la_a_mod_is_denied; ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              <input type="radio" name="edit_link_d" value="1"<?if (read_email_setting($email_perm,"edit_link_d") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="edit_link_d" value="0"<?if (read_email_setting($email_perm,"edit_link_d") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle">
            <td class="text" bgcolor="#DEDEDE">
              <?php echo $la_a_new_review_added; ?>
            </td>
            <td class="text" bgcolor="#DEDEDE">
              <input type="radio" name="add_review_owner" value="1"<?if (read_email_setting($email_perm,"add_review_owner") == "1") {echo " checked";}?>>
              <?php echo $la_yes; ?>
              <input type="radio" name="add_review_owner" value="0"<?if (read_email_setting($email_perm,"add_review_owner") == "0") {echo " checked";}?>>
              <?php echo $la_no; ?>
            </td>
          </tr>
        </table>
        <br>
		<input type="hidden" name="action" value="update">
        <input type="submit" name="Submit" value="<?php echo $la_button_update; ?>" class="button">
        <input type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
        <input type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
        <br>
      </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
