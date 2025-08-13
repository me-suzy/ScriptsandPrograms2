<?php
//Read in config file
$thisfile = "conf_system";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
include("../includes/admin_conf_lib.php");
if ($action == "update") {
update_conf_system();
}
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<META http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon4-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav6 ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#system"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
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
	echo display_admin_nav($la_title_system, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666"> 
      <?php echo $la_title_system ?>
    </td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="conf_system.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr valign="middle" bgcolor="#999999"> 
            <td colspan="2" class="textTitle"> 
              <?php echo $la_general ?>
            </td>
          </tr>
          <tr valign="middle"> 
            <td class="text"> 
              <?php echo $la_site_name ?>
            </td>
            <td class="text"> 
              <input type="text" name="sitenamen" class="text" size="30" value="<?php echo $sitename; ?>">
            </td>
          </tr>
          <tr valign="middle"> 
            <td bgcolor="#DEDEDE" class="text"> 
              <?php echo $la_site_address ?>
              <span class="hint"><br>
              <img src="images/mark.gif" width="16" height="16" align="absmiddle"> 
              <?php echo $la_to_chang_field_contact_inlink ?>
              </span></td>
            <td bgcolor="#DEDEDE" class="text"> 
              <div align="left"><b> 
                <?php echo $server; ?>
                </b></div>
            </td>
          </tr>
          <tr valign="middle" bgcolor="#999999"> 
            <td colspan="2" class="textTitle"> 
              <?php echo $la_database ?>
            </td>
          </tr>
          <tr valign="middle" bgcolor="#F6F6F6"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"> 
              <?php echo $la_dont_screw_up_inlink ?>
              </span></td>
          </tr>
          <tr valign="middle"> 
            <td class="text"> 
              <?php echo $la_sql_server ?>
            </td>
            <td class="text"> 
              <input type="text" name="sql_servern" class="text" size="30" value="<?php echo $sql_server;?>">
            </td>
          </tr>
          <tr valign="middle"> 
            <td bgcolor="#DEDEDE" class="text"><span class="text"> 
              <?php echo $la_sql_user_name ?>
              </span></td>
            <td bgcolor="#DEDEDE" class="text"> 
              <input type="text" name="sql_usern" class="text" size="30" value="<?php echo $sql_user;?>">
            </td>
          </tr>
          <tr valign="middle"> 
            <td bgcolor="#F6F6F6" class="text"><span class="text"> 
              <?php echo $la_sql_password ?>
              </span></td>
            <td class="text"> 
              <input type="password" name="sql_passn" class="text" size="30" value="<?php echo $sql_pass;?>">
            </td>
          </tr>
          <tr valign="middle"> 
            <td class="text" bgcolor="DEDEDE"><span class="text"> 
              <?php echo $la_sql_database ?>
              </span></td>
            <td bgcolor="DEDEDE" class="text"> 
              <input type="text" name="sql_dbn" class="text" size="30" value="<?php echo $sql_db;?>">
            </td>
          </tr>
          <tr valign="middle"> 
            <td class="text" bgcolor="#F6F6F6"><span class="text"> 
              <?php echo $la_sql_type ?>
              </span></td>
            <td bgcolor="#F6F6F6" class="text"> 
              <select name="sql_typen" class="text">
                <option value="mysql"<?php if ($sql_type == "mysql"){echo " selected";} ?>>MySQL</option>
                <option value="postgres7"<?php if ($sql_type == "postgres7"){echo " selected";} ?>>PostgreSQL 
                7.0</option>
                <option value="mssql"<?php if ($sql_type == "mssql"){echo " selected";} ?>>Microsoft 
                SQL</option>
              </select>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"><span class="text">
              <?php echo $la_presistent_conection;?>
              </span></td>
            <td class="text">
              <input type="checkbox" name="pconnect_t" value="checkbox" <?php if($pconnect) echo "checked";?>>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"> 
              <?php echo $la_warning_pconnect;?>
              </span></td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2"> 
              <?php echo $la_session_control ?>
            </td>
          </tr>
          <tr valign="middle" bgcolor="#F6F6F6"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"> 
              <?php echo $la_dont_screw_up_inlink ?>
              </span> 
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"> 
              <?php echo $la_session_handeling ?>
            </td>
            <td class="text"> 
              <input type="radio" value="1" <?php if(!$session_get && $session_cookie)echo "checked";?> name="session_control">
              <?php echo $la_session_cookies; ?>
              <input type="radio" value="2" <?php if($session_get && !$session_cookie)echo "checked";?> name="session_control">
              <?php echo $la_session_get; ?>
              <input type="radio" value="3" <?php if($session_get && $session_cookie)echo "checked";?> name="session_control">
              <?php echo $la_session_both; ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php echo $la_session_timeout; ?>
            </td>
            <td class="text"> 
              <input type="text" name="ses_expirationn" class="text" size="2" value="<?php echo $ses_expiration; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text" colspan="2">&nbsp;</td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2"> 
              <?php echo $la_paths ?>
            </td>
          </tr>
          <tr valign="middle" bgcolor="#F6F6F6"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"> 
              <?php echo $la_dont_screw_up_inlink ?>
              </span></td>
          </tr>
          <tr valign="middle"> 
            <td class="text" bgcolor="DEDEDE"><span class="text"> 
              <?php echo $la_inlink_server_path ?>
              </span></td>
            <td bgcolor="DEDEDE" class="text"> 
              <input type="text" name="filedirn" class="text" size="40" value="<?php echo $filedir; ?>">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><span class="text"> 
              <?php echo $la_url_path ?>
              </span></td>
            <td class="text"> 
              <input type="text" name="filepathn" class="text" size="40" value="<?php echo $filepath; ?>">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text" colspan="2">&nbsp;</td>
          </tr>
	  <tr valign="middle" bgcolor="#999999"> 
            <td colspan="2" class="textTitle">
              <?php echo $cykuh_header_reg ?>
            </td>
          </tr>
	  <tr valign="middle" bgcolor="#F6F6F6"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">
              <?php echo $cykuh_reg_info ?>
              </span></td>
          </tr>
          <td bgcolor="#DEDEDE" class="text">
              <?php echo $cykuh_first_name ?>
            </td>
            <td bgcolor="#DEDEDE" class="text"> 
              <input type="text" name="first_namen" class="text" value="<?php echo $first_name; ?>">
            </td>
          </tr>
          <tr valign="middle">
	    <td class="text">
              <?php echo $cykuh_last_name ?>
	    </td>
              <td class="text">
		<input type="text" name="last_namen" class="text" value="<?php echo $last_name; ?>">
            </td>
          </tr>
	  <tr valign="middle"> 
	  <td bgcolor="#DEDEDE" class="text"> 
              <?php echo $cykuh_reg_domain ?>
	    </td>
              <td bgcolor="#DEDEDE" class="text"> 
		<input type="text" name="servern" class="text" size="30" value="<?php echo $server; ?>">
            </td>
          </tr>
	 
          
            <tr valign="middle"> 
            <td class="text" colspan="2"><?php echo $cykuh_null_info; ?></td>
            
          </tr>
        </table>
		<input type="hidden" name="action" value="update">
        <input type="submit" name="Submit" value="<?php echo $la_button_update ?>" class="button">
        <input type="button" name="Submit3" value="<?php echo $la_button_cancel ?>" class="button" onClick="history.back();">
        <br>
      </form>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
