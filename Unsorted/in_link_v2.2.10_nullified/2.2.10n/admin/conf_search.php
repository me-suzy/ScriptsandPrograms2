<?php
//Read in config file
$thisfile = "conf_search";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
include("../includes/admin_conf_lib.php");
$do_search_update1 = true;
$do_search_update2 = true;
$form_search_error_message1 = "";
$form_search_error_message2 = "";

if ($action == "update")
{
	if ((!$do_link_name_f) && (!$do_link_desc_f) && (!$do_link_url_f) && (!$do_link_image_f) && (!$do_link_cust1_f) && (!$do_link_cust2_f) && (!$do_link_cust3_f) && (!$do_link_cust4_f) && (!$do_link_cust5_f) && (!$do_link_cust6_f))
	{	
		$form_search_error_message1 = $la_field_select_error;
		$do_search_update1 = false;
	}
	if ((!$do_cat_name_f) && (!$do_cat_desc_f) && (!$do_cat_image_f) && (!$do_cat_cust1_f) && (!$do_cat_cust2_f) && (!$do_cat_cust3_f) && (!$do_cat_cust4_f) && (!$do_cat_cust5_f) && (!$do_cat_cust6_f))
	{
		$form_search_error_message2 = $la_field_select_error;
		$do_search_update2 = false;
	}
	if ($do_search_update1 && $do_search_update2)
	{
		update_conf_search();
		$extended_search = $extended_search_f;
		$high_lighting_tag1 = $high_lighting_tag1_f;
		$high_lighting_tag2 = $high_lighting_tag2_f;
		$do_link_name = $do_link_name_f;
		$do_link_desc = $do_link_desc_f;
		$do_link_url = $do_link_url_f;
		$do_link_image = $do_link_image_f;
		$do_link_cust1 = $do_link_cust1_f;
		$do_link_cust2 = $do_link_cust2_f;
		$do_link_cust3 = $do_link_cust3_f;
		$do_link_cust4 = $do_link_cust4_f;
		$do_link_cust5 = $do_link_cust5_f;
		$do_link_cust6 = $do_link_cust6_f;
		
		$do_cat_name = $do_cat_name_f;
		$do_cat_desc = $do_cat_desc_f;
		$do_cat_image = $do_cat_image_f;
		$do_cat_cust1 = $do_cat_cust1_f;
		$do_cat_cust2 = $do_cat_cust2_f;
		$do_cat_cust3 = $do_cat_cust3_f;
		$do_cat_cust4 = $do_cat_cust4_f;
		$do_cat_cust5 = $do_cat_cust5_f;
		$do_cat_cust6 = $do_cat_cust6_f;
	}
}
elseif($action=="keywords")
{
	if($submit==$la_remove)
		for($i=0;$i<count($invalid_keywords);$i++)
			$conn->Execute("DELETE FROM inl_keywords where keyword_id=".$invalid_keywords[$i]);
	elseif($submit==$la_add)
		$conn->Execute("INSERT INTO inl_keywords (keyword_id,keyword) values ('','".addslashes($new_keyword)."')");
}
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="images/icon4-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav6; ?></TD>
    <TD rowspan="2" width="0"><A href="help/6.htm#email"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><IMG src="images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
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
	echo display_admin_nav($la_title_search, $nav_names_admin, $nav_links_admin);
?>

  <TR> 
    <TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_search; ?></TD>
  </TR>
  <TR> 
    <TD bgcolor="#F6F6F6"> 
    
        
      <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr valign="middle" bgcolor="#999999"> 
            <td colspan="3" class="textTitle"> 
              <?php echo $la_general ?>
            </td>
          </tr>
		<FORM name="form1" method="post" action="conf_search.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		?>">
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"><SPAN class="text"> 
              <?php echo $la_extended_search;?>
              :</SPAN></TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="extended_search_f" value="1" <?php if($extended_search==1){echo "checked";}else{echo "";}?>>
            </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"><SPAN class="text"> 
              <?php echo $la_search_highlighting; ?>
              </SPAN></TD>
            <TD class="text"> <SPAN class="text"> 
              <?php echo $la_search_highlighting_open; ?>
              :</SPAN> 
              <INPUT type="text" class="text" size="40" maxlength="100" name="high_lighting_tag1_f" value="<?php if(strlen($high_lighting_tag1)>0){echo $high_lighting_tag1;}?>">
              <BR>
              <SPAN class="text"> 
              <?php echo $la_search_highlighting_close; ?>
              :</SPAN> 
              <INPUT type="text" class="text" size="40" maxlength="100" name="high_lighting_tag2_f" value="<?php if(strlen($high_lighting_tag2)>0) echo $high_lighting_tag2; else echo "";?>">
            </TD>
          </TR>
          <TR bgcolor="#999999" valign="middle"> 
            <TD class="textTitle" colspan="3"> 
              <?php echo $la_simple_link_search_on; ?>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_name; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_name_f" value="1" <?php if(($do_link_name==1) || ($do_search_update1 == false)){echo "checked";}else{echo "";} ?>>
              <SPAN class="error"> 
              <?php echo "&nbsp;&nbsp;&nbsp;".$form_search_error_message1;?>
              </SPAN> </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_desc; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_desc_f" value="1" <?php if($do_link_desc==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_url; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_url_f" value="1" <?php if($do_link_url==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_image; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_image_f" value="1" <?php if($do_link_image==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_cust1; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_cust1_f" value="1" <?php if($do_link_cust1==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_cust2; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_cust2_f" value="1" <?php if($do_link_cust2==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_cust3; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_cust3_f" value="1" <?php if($do_link_cust3==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_cust4; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_cust4_f" value="1" <?php if($do_link_cust4==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_cust5; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_cust5_f" value="1" <?php if($do_link_cust5==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_link_cust6; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_link_cust6_f" value="1" <?php if($do_link_cust6==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#999999" valign="middle"> 
            <TD colspan="3" class="textTitle"> 
              <?php echo $la_simple_cat_search_on; ?>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_name; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_name_f" value="1"
			<?php if(($do_cat_name==1) || ($do_search_update1 == false)){echo "checked";}else{echo "";} ?>>
              <SPAN class="error"> 
              <?php if (strlen($form_search_error_message2)>0) {echo "&nbsp;&nbsp;&nbsp;".$form_search_error_message2;} else {echo "";}?>
              </SPAN> </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_desc; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_desc_f" value="1" <?php if($do_cat_desc==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_image; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_image_f" value="1" <?php if($do_cat_image==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_cust1; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_cust1_f" value="1" <?php if($do_cat_cust1==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_cust2; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_cust2_f" value="1" <?php if($do_cat_cust2==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_cust3; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_cust3_f" value="1" <?php if($do_cat_cust3==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_cust4; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_cust4_f" value="1" <?php if($do_cat_cust4==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#DEDEDE" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_cust5; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_cust5_f" value="1" <?php if($do_cat_cust5==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6" valign="middle"> 
            <TD class="text" colspan="2"> 
              <?php echo $la_simple_search_cat_cust6; ?>
            </TD>
            <TD class="text"> 
              <INPUT type="checkbox" name="do_cat_cust6_f" value="1" <?php if($do_cat_cust6==1){echo "checked";}else{echo "";} ?>>
            </TD>
          </TR>
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="3" class="textTitle">&nbsp;</td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="3">&nbsp; 
              <INPUT type="hidden" name="action" value="update">
              <INPUT type="submit" name="Submit" value="<?php echo $la_button_update; ?>" class="button">
              <INPUT type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
              <INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
            </td>
          </tr>
        </FORM>
        <tr bgcolor="#999999" valign="middle"> 
          <td colspan="3" class="textTitle"> 
            <?php echo $la_invalid_search_keywords; ?>
          </td>
        </tr>
        <FORM name="form2" method="post" action="conf_search.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		?>">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php echo $la_keywords; ?>
            </td>
            <td class="text" align="left" valign="middle"> 
              <select name="invalid_keywords[]" size="5" multiple class="text">
			  <?php
				$query="Select * from inl_keywords order by keyword asc";
				$rs=&$conn->Execute($query);
				if ($rs && !$rs->EOF)
				{	
					do
					{	$key_data = $rs->fields;
						echo "<option value=\"".$key_data["keyword_id"]."\">".$key_data["keyword"]."</option>\n";
						$rs->MoveNext();
					} while ($rs && !$rs->EOF);
				}
			  ?>
              </select>
            </td>
            <td class="text" align="left" valign="middle"> <br>
              <input type="submit" name="submit" value="<?php echo $la_remove; ?>" class="button">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"> 
              <?php echo $la_new_keyword; ?>
            </td>
            <td class="text"> 
              <input type="text" name="new_keyword" class="text" size="20">
            </td>
            <td class="text"> 
              <input type="submit" name="submit" value="<?php echo $la_add; ?>" class="button">
            </td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="3" class="textTitle">&nbsp;</td>
          </tr>
		  <input type="hidden" name="action" value="keywords">
        </form>
      </TABLE>

    </TD>
  </TR>
</TABLE>
<P>&nbsp;</P>
</BODY>
</HTML>
