<?php
//Read in config file
$thisfile = "conf_output";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
include("../includes/admin_conf_lib.php");
if ($action == "update") 
{	if(validateconf())
		update_conf_output();
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
    <TD rowspan="2" width="0"><A href="help/6.htm#output"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?
	<?php
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
	$nav_names_admin=array($la_title_output,$la_title_email,$la_title_data_structure,$la_title_user_settings,$la_title_security,$la_title_system, $la_title_search);
	$nav_links_admin[$la_title_output]="conf_output.php$att_sid";
  	$nav_links_admin[$la_title_email]="conf_email.php$att_sid";
   	$nav_links_admin[$la_title_data_structure]="conf_data.php$att_sid";
    	$nav_links_admin[$la_title_user_settings]="conf_users.php$att_sid";
     	$nav_links_admin[$la_title_security]="conf_security.php$att_sid";
      	$nav_links_admin[$la_title_system]="conf_system.php$att_sid";
		$nav_links_admin[$la_title_search]="conf_search.php$att_sid";
	echo display_admin_nav($la_title_output, $nav_names_admin, $nav_links_admin);
?>
  <TR> 
    <TD class="tabletitle" bgcolor="#666666"><?php echo $la_title_output; ?></TD>
  </TR>
  <TR> 
    <TD bgcolor="#F6F6F6"> 
      <FORM name="conf_output" method="post" action="conf_output.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		?>">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
		  <TR valign="middle"> 
			<TD colspan="2" class="textTitle" bgcolor="#999999"> 
			  <?php echo $la_categories; ?>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#F6F6F6"> 
			  <?php echo $la_order_output; ?>
			</TD>
			<TD class="text" bgcolor="#F6F6F6"> 
			  <SELECT name="cat_order_t" class="text">
				<OPTION value="cat_name"<?php if ($cat_order == "cat_name") { echo " selected";}?>>
				<?php echo $la_drop_name ?>
				</OPTION>
				<OPTION value="cat_date"<?php if ($cat_order == "cat_date") { echo " selected";}?>>
				<?php echo $la_drop_date ?>
				</OPTION>
				<OPTION value="cat_desc"<?php if ($cat_order == "cat_desc") { echo " selected";}?>>
				<?php echo $la_drop_desc ?>
				</OPTION>
				<OPTION value="cat_user"<?php if ($cat_order == "cat_user") { echo " selected";}?>>
				<?php echo $la_drop_user ?>
				</OPTION>
				<OPTION value="cat_sub"<?php if ($cat_order == "cat_sub") { echo " selected";}?>>
				<?php echo $la_drop_numsubs ?>
				</OPTION>
				<OPTION value="cat_perm"<?php if ($cat_order == "cat_perm") { echo " selected";}?>>
				<?php echo $la_drop_perm ?>
				</OPTION>
				<OPTION value="cat_vis"<?php if ($cat_order == "cat_vis") { echo " selected";}?>>
				<?php echo $la_drop_vis ?>
				</OPTION>
				<OPTION value="cat_links"<?php if ($cat_order == "cat_links") { echo " selected";}?>>
				<?php echo $la_drop_numlinks ?>
				</OPTION>
				<OPTION value="cat_pick"<?php if ($cat_order == "cat_pick") { echo " selected";}?>>
				<?php echo $la_drop_editors_pick ?>
				</OPTION>
				<OPTION value="cat_image"<?php if ($cat_order == "cat_image") { echo " selected";}?>>
				<?php echo $la_drop_image ?>
				</OPTION>
			  </SELECT>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#DEDEDE"> 
			  <?php echo $la_sorting; ?>
			</TD>
			<TD bgcolor="#DEDEDE"> 
			  <SELECT name="cat_sort_t" class="text">
				<OPTION value="asc"<?php if ($cat_sort == "asc") { echo " selected";}?>>
				<?php echo $la_drop_ascending ?>
				</OPTION>
				<OPTION value="desc"<?php if ($cat_sort == "desc") { echo " selected";}?>>
				<?php echo $la_drop_descending ?>
				</OPTION>
			  </SELECT>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="<?php if($err_conf["cols"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#F6F6F6"> 
			  <?php echo $la_break_output_into_number_of_columns; ?>
			</TD>
			<TD class="<?php if($err_conf["cols"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#F6F6F6"> 
			  <INPUT type="text" name="cols_t" class="text" size="5" value="<?php echo $cols; ?>">
			  <SPAN class="<?php if($err_conf["cols"]==1){echo "error";}else{echo "text";} ?>"></SPAN> 
			  <SPAN class="small"> 
			  <?php echo $la_min_one; ?>
			  </SPAN> </TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="<?php if($err_conf["cols"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#DEDEDE"> 
			  <?php echo $la_break_output_into_number_of_rel_columns; ?>
			</TD>
			<TD class="<?php if($err_conf["rcols"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#DEDEDE"> 
			  <INPUT type="text" name="rcols_t" class="text" size="5" value="<?php echo $rcols; ?>">
			  <SPAN class="<?php if($err_conf["rcols"]==1){echo "error";}else{echo "text";} ?>"></SPAN> 
			  <SPAN class="small"> 
			  <?php echo $la_min_one; ?>
			  </SPAN></TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="<?php if($err_conf["cat_new"]==1){echo "error";}else{echo "text";} ?>"> 
			  <?php echo $la_number_of_days_category_is_new; ?>
			</TD>
			<TD class="<?php if($err_conf["cat_new"]==1){echo "error";}else{echo "text";} ?>"> 
			  <INPUT type="text" name="cat_new_t" class="text" size="5" value="<?php echo $cat_new; ?>">
			  <SPAN class="<?php if($err_conf["cat_new"]==1){echo "error";}else{echo "text";} ?>"></SPAN> 
			  <SPAN class="small">
			  <?php echo $la_min_zero; ?>
			  </SPAN></TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#DEDEDE"> 
			  <?php echo $la_meta_keywords; ?>
			</TD>
			<TD class="text" bgcolor="#DEDEDE"> 
			  <INPUT type="text" name="default_meta_keywords_t" class="text" size="15" value="<?php echo $default_meta_keywords; ?>">
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text"> 
			  <?php echo $la_meta_desc; ?>
			</TD>
			<TD class="text"> 
			  <TEXTAREA name="default_meta_desc_t" cols="30" rows="5" class="text" value=""><?php echo $default_meta_desc; ?></TEXTAREA>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD colspan="2" class="textTitle" bgcolor="#999999"> 
			  <?php echo $la_sub_preview_links; ?>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#F6F6F6">
			  <?php echo $la_order_output; ?>
			</TD>
			<TD class="text" bgcolor="#F6F6F6"> 
			  <SELECT name="subcat_order_t" class="text">
				<OPTION value="cat_name"<?php if ($subcat_order == "cat_name") { echo " selected";}?>>
				<?php echo $la_drop_name ?>
				</OPTION>
				<OPTION value="cat_date"<?php if ($subcat_order == "cat_date") { echo " selected";}?>>
				<?php echo $la_drop_date ?>
				</OPTION>
				<OPTION value="cat_desc"<?php if ($subcat_order == "cat_desc") { echo " selected";}?>>
				<?php echo $la_drop_desc ?>
				</OPTION>
				<OPTION value="cat_user"<?php if ($subcat_order == "cat_user") { echo " selected";}?>>
				<?php echo $la_drop_user ?>
				</OPTION>
				<OPTION value="cat_sub"<?php if ($subcat_order == "cat_sub") { echo " selected";}?>>
				<?php echo $la_drop_numsubs ?>
				</OPTION>
				<OPTION value="cat_perm"<?php if ($subcat_order == "cat_perm") { echo " selected";}?>>
				<?php echo $la_drop_perm ?>
				</OPTION>
				<OPTION value="cat_vis"<?php if ($subcat_order == "cat_vis") { echo " selected";}?>>
				<?php echo $la_drop_vis ?>
				</OPTION>
				<OPTION value="cat_links"<?php if ($subcat_order == "cat_links") { echo " selected";}?>>
				<?php echo $la_drop_numlinks ?>
				</OPTION>
				<OPTION value="cat_pick"<?php if ($subcat_order == "cat_pick") { echo " selected";}?>>
				<?php echo $la_drop_editors_pick ?>
				</OPTION>
				<OPTION value="cat_image"<?php if ($subcat_order == "cat_image") { echo " selected";}?>>
				<?php echo $la_drop_image ?>
				</OPTION>
			  </SELECT>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#DEDEDE"> 
			  <?php echo $la_sorting; ?>
			</TD>
			<TD bgcolor="#DEDEDE"> 
			  <SELECT name="subcat_sort_t" class="text">
				<OPTION value="asc"<?php if ($subcat_sort == "asc") { echo " selected";}?>>
				<?php echo $la_drop_ascending ?>
				</OPTION>
				<OPTION value="desc"<?php if ($subcat_sort == "desc") { echo " selected";}?>>
				<?php echo $la_drop_descending ?>
				</OPTION>
			  </SELECT>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="textTitle" colspan="2" bgcolor="#999999"> 
			  <?php echo $la_links; ?>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#F6F6F6"> 
			  <?php echo $la_order_output; ?>
			</TD>
			<TD bgcolor="#F6F6F6"> 
			  <SELECT name="link_order_t" class="text">
				<OPTION value="link_name"<?php if ($link_order == "link_name") { echo " selected";}?>>
				<?php echo $la_drop_name ?>
				</OPTION>
				<OPTION value="link_date"<?php if ($link_order == "link_date") { echo " selected";}?>>
				<?php echo $la_drop_date ?>
				</OPTION>
				<OPTION value="link_desc"<?php if ($link_order == "link_desc") { echo " selected";}?>>
				<?php echo $la_drop_desc ?>
				</OPTION>
				<OPTION value="link_url"<?php if ($link_order == "link_url") { echo " selected";}?>>
				<?php echo $la_drop_url ?>
				</OPTION>
				<OPTION value="link_rating"<?php if ($link_order == "link_rating") { echo " selected";}?>>
				<?php echo $la_drop_rating ?>
				</OPTION>
				<OPTION value="link_votes"<?php if ($link_order == "link_votes") { echo " selected";}?>>
				<?php echo $la_drop_votes ?>
				</OPTION>
				<OPTION value="link_hits"<?php if ($link_order == "link_hits") { echo " selected";}?>>
				<?php echo $la_drop_hits ?>
				</OPTION>
				<OPTION value="link_user"<?php if ($link_order == "link_user") { echo " selected";}?>>
				<?php echo $la_drop_user ?>
				</OPTION>
				<OPTION value="link_vis"<?php if ($link_order == "link_vis") { echo " selected";}?>>
				<?php echo $la_drop_vis ?>
				</OPTION>
				<OPTION value="link_pick"<?php if ($link_order == "link_pick") { echo " selected";}?>>
				<?php echo $la_drop_editors_pick ?>
				</OPTION>
				<OPTION value="link_image"<?php if ($link_order == "link_image") { echo " selected";}?>>
				<?php echo $la_drop_image ?>
				</OPTION>
			  </SELECT>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#DEDEDE"> 
			  <?php echo $la_sorting; ?>
			</TD>
			<TD bgcolor="#DEDEDE"> 
			  <SELECT name="link_sort_t" class="text">
				<OPTION value="asc"<?php if ($link_sort == "asc") { echo " selected";}?>> 
				<?php echo $la_drop_ascending ?>
				</OPTION>
				<OPTION value="desc"<?php if ($link_sort == "desc") { echo " selected";}?>> 
				<?php echo $la_drop_descending ?>
				</OPTION>
			  </SELECT>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="<?php if($err_conf["lim"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#F6F6F6"> 
			  <?php echo $la_number_of_resulting_links_per_page;?>
			</TD>
			<TD class="<?php if($err_conf["lim"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#F6F6F6"> 
			  <INPUT type="text" name="lim_t" class="text" size="5" value="<?php echo $lim; ?>">
			  <SPAN class="<?php if($err_conf["lim"]==1){echo "error";}else{echo "text";} ?>"></SPAN> 
			  <SPAN class="small">
			  <?php echo $la_min_one; ?>
			  </SPAN></TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="<?php if($err_conf["link_pop"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#DEDEDE"> 
			  <?php echo $la_percent_cutoff_for_link_to_be_popular;?>
			</TD>
			<TD class="<?php if($err_conf["link_pop"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#DEDEDE"> 
			  <INPUT type="text" name="link_pop_t" class="text" size="5" value="<?php echo $link_pop; ?>">
			  <SPAN class="<?php if($err_conf["link_pop"]==1){echo "error";}else{echo "text";} ?>"></SPAN> 
			  <SPAN class="small">
			  <?php echo $la_min_zero; ?>
			  </SPAN></TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="<?php if($err_conf["link_top"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#F6F6F6"> 
			  <?php echo $la_percent_cutoff_for_link_to_be_top;?>
			</TD>
			<TD class="<?php if($err_conf["link_top"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#F6F6F6"> 
			  <INPUT type="text" name="link_top_t" class="text" size="5" value="<?php echo $link_top; ?>">
			  <SPAN class="<?php if($err_conf["link_top"]==1){echo "error";}else{echo "text";} ?>"></SPAN> 
			  <SPAN class="small">
			  <?php echo $la_min_zero; ?>
			  </SPAN></TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="<?php if($err_conf["link_new"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#DEDEDE"> 
			  <?php echo $la_number_of_days_link_is_new;?>
			</TD>
			<TD class="<?php if($err_conf["link_new"]==1){echo "error";}else{echo "text";} ?>" bgcolor="#DEDEDE"> 
			  <INPUT type="text" name="link_new_t" class="text" size="5" value="<?php echo $link_new; ?>">
			  <SPAN class="<?php if($err_conf["link_new"]==1){echo "error";}else{echo "text";} ?>"></SPAN> 
			  <SPAN class="small">
			  <?php echo $la_min_zero; ?>
			  </SPAN></TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#F6F6F6"> 
			  <?php echo $la_force_pick?>
			</TD>
			<TD class="text" bgcolor="#F6F6F6"> 
			  <?php //$force_pick=0;?>
			  <INPUT type="checkbox" name="force_pick_t" value="1" <?php if($force_pick==1){echo "checked";}else{echo "";} ?>>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#DEDEDE"> 
			  <?php echo $la_pick_template?>
			</TD>
			<TD class="text" bgcolor="#DEDEDE"> 
			  <INPUT type="checkbox" name="use_pick_tpl_t" value="1" <?php if($use_pick_tpl==1){echo "checked";}else{echo "";} ?>>
			  <?php //$use_pick_tpl=0;?>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#F6F6F6"> 
			  <?php echo $la_show_url?>
			</TD>
			<TD class="text" bgcolor="#F6F6F6"> 
			  <INPUT type="checkbox" name="show_status_url_t" value="1" <?php if($show_status_url==1){echo "checked";}else{echo "";} ?>>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#DEDEDE"> 
			  <?php echo $la_multiple_search_instances?>
			</TD>
			<TD class="text" bgcolor="#DEDEDE"> 
			  <INPUT type="checkbox" name="multiple_search_instances_t" value="1" <?php if($multiple_search_instances==1){echo "checked";}else{echo "";} ?>>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="textTitle" colspan="2" bgcolor="#999999"> 
			  <?php echo $la_reviews; ?>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#F6F6F6"> 
			  <?php echo $la_order_output; ?>
			</TD>
			<TD bgcolor="#F6F6F6"> 
			  <SELECT name="review_order_t" class="text">
				<OPTION value="rev_date"<?php if ($review_order == "rev_date") { echo " selected";}?>>
				<?php echo $la_drop_date ?>
				</OPTION>
				<OPTION value="rev_text"<?php if ($review_order == "rev_text") { echo " selected";}?>>
				<?php echo $la_drop_desc ?>
				</OPTION>
				<OPTION value="rev_user"<?php if ($review_order == "rev_user") { echo " selected";}?>>
				<?php echo $la_drop_user ?>
				</OPTION>
			  </SELECT>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#DEDEDE"> 
			  <?php echo $la_sorting; ?>
			</TD>
			<TD bgcolor="#DEDEDE"> 
			  <SELECT name="review_sort_t" class="text">
				<OPTION value="asc"<?php if ($review_sort == "asc") { echo " selected";}?>> 
				<?php echo $la_drop_ascending ?>
				</OPTION>
				<OPTION value="desc"<?php if ($review_sort == "desc") { echo " selected";}?>> 
				<?php echo $la_drop_descending ?>
				</OPTION>
			  </SELECT>
			</TD>
		  </TR>
		  <TR valign="middle"> 
			<TD class="text" bgcolor="#F6F6F6"> <BR>
			  <INPUT type="hidden" name="action" value="update">
			  <INPUT type="submit" name="Submit" value="<?php echo $la_button_update; ?>" class="button">
			  <INPUT type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
			  <INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
			</TD>
			<TD class="text" bgcolor="#F6F6F6">&nbsp;</TD>
		  </TR>
		</TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>
</BODY>
</HTML>