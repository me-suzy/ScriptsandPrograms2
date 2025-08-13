<?php
//Read in config file
$thisfile = "conf_users";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
include("../includes/admin_conf_lib.php");
if ($action == "update") {
update_conf_users();
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
    <td class="title" width="100%"><?php echo $la_nav3; ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#permissions"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
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
	echo display_admin_nav($la_title_user_settings, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_user_settings; ?></td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="conf_users.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">

          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2"><?php echo $la_users; ?></td>
          </tr>
            <tr bgcolor="#F6F6F6" valign="middle">
		  
            <td class="text"><?php echo $la_new_users; ?></td>
            <td class="text"> 
              <SELECT name="user_perm_t" class="text">
			  <OPTION value="1"<?php if($user_perm==1) echo " selected";?>><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"<?php if($user_perm==0) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"<?php if($user_perm==2) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			  <OPTION value="3"<?php if($user_perm==3) echo " selected";?>><?php echo $la_drop_email_confirmation; ?></OPTION>
			</SELECT>
            </td>
          </tr>


          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2"><?php echo $la_rootcat; ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle">
		  
            <td class="text"><?php echo $la_registered_users; ?></td>
            <td class="text"> 
			<SELECT name="root_link_reg" class="text">
			  <OPTION value="1"<?php if($root_link_perm<6 && $root_link_perm>2) echo " selected";?>><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"<?php if($root_link_perm<3) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"<?php if($root_link_perm>5) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
            </td>
          </tr>

          <tr bgcolor="#DEDEDE" valign="middle">
		  
            <td class="text"><?php echo $la_notregistered_visitors; ?></td>
            <td class="text"> 
              <SELECT name="root_link_all" class="text">
			  <OPTION value="1"<?php if($root_link_perm%3==1) echo " selected";?>><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"<?php if($root_link_perm%3==0) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"<?php if($root_link_perm%3==2) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
            </td>
          </tr>
         
		  <tr bgcolor="#F6F6F6" valign="middle">
		  
            <td class="text"><?php echo $la_apply_cat_perm; ?></td>
            <td class="text"> 
              <input type="checkbox" name="apply_cat_perm" value="1">
            </td>
          </tr>

          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2"><?php echo $la_suggest_cat; ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle">
		  
            <td class="text"><?php echo $la_registered_users; ?></td>
            <td class="text"> 
			<SELECT name="suggest_cat_reg" class="text">
			  <OPTION value="1"<?php if($suggest_cat_perm<6 && $suggest_cat_perm>2) echo " selected";?>><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"<?php if($suggest_cat_perm<3) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"<?php if($suggest_cat_perm>5) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
            </td>
          </tr>

          <tr bgcolor="#DEDEDE" valign="middle">
		  
            <td class="text"><?php echo $la_notregistered_visitors; ?></td>
            <td class="text"> 
              <SELECT name="suggest_cat_all" class="text">
			  <OPTION value="1"<?php if($suggest_cat_perm%3==1) echo " selected";?>><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"<?php if($suggest_cat_perm%3==0) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"<?php if($suggest_cat_perm%3==2) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
            </td>
          </tr>
         
          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2"><?php echo $la_reviews; ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle">
		  
            <td class="text"><?php echo $la_registered_users; ?></td>
            <td class="text"> 
			<SELECT name="review_reg" class="text">
			  <OPTION value="1"<?php if($review_perm<6 && $review_perm>2) echo " selected";?>><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"<?php if($review_perm<3) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"<?php if($review_perm>5) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
            </td>
          </tr>

          <tr bgcolor="#DEDEDE" valign="middle">
		  
            <td class="text"><?php echo $la_notregistered_visitors; ?></td>
            <td class="text"> 
              <SELECT name="review_all" class="text">
			  <OPTION value="1"<?php if($review_perm%3==1) echo " selected";?>><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"<?php if($review_perm%3==0) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"<?php if($review_perm%3==2) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
            </td>
          </tr>
         
		  <tr bgcolor="#F6F6F6" valign="middle">
		  
            <td class="text"><?php echo $la_not_allow_reviews; ?></td>
            <td class="text"> 
               <input type="text" name="review_expiration_t" size="5" class="text" value="<?php echo $review_expiration;?>">
            </td>
          </tr>


          <tr bgcolor="#999999" valign="middle"> 
            <td class="textTitle" colspan="2"><?php echo $la_votes; ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle">
		  
            <td class="text"><?php echo $la_registered_users; ?></td>
            <td class="text"> 
			<SELECT name="rate_reg" class="text">
			   <OPTION value="0"<?php if($rate_perm<2) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="1"<?php if($rate_perm>1) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
            </td>
          </tr>

          <tr bgcolor="#DEDEDE" valign="middle">
		  
            <td class="text"><?php echo $la_notregistered_visitors; ?></td>
            <td class="text"> 
              <SELECT name="rate_all" class="text">
			  <OPTION value="0"<?php if($rate_perm%2==0) echo " selected";?>><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="1"<?php if($rate_perm%2==1) echo " selected";?>><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
            </td>
          </tr>
         
		  <tr bgcolor="#F6F6F6" valign="middle">
		  
            <td class="text"><?php echo $la_not_allow_votes; ?></td>
            <td class="text"> 
              <input type="text" name="rating_expiration_t" size="5" class="text" value="<?php echo $rating_expiration;?>">
            </td>
          </tr>





        </table>
        <br>
		<input type="hidden" name="action" value="update">
        <input type="submit" name="Submit" value="<?php echo $la_button_update ?>" class="button">
        <input type="reset" name="Submit2" value="<?php echo $la_button_reset ?>" class="button">
        <input type="button" name="Submit3" value="<?php echo $la_button_cancel ?>" class="button" onClick="history.back();">
        <br>
      </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
