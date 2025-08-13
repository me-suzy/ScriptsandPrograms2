<?php
//Read in config file
$admin = 1;
$thisfile = "addcategory";
$configfile = "../includes/config.php";
include($configfile);
include("../includes/cats_lib.php");
include("../includes/links_lib.php");
include("../includes/user_lib.php");

//to select default perms
$cat_user=get_user_id($HTTP_POST_VARS["cat_user"]);

#!!! added to solve the problem with the no default user during addition of the category

if(!$action)
{	if(!$cat_user) 
		$cat_user=$ses["user_id"]; 
}
#above 



if($add_cat)
{	
	if($add_link_cat!=$id)
	{
		if(ereg(",$add_link_cat,",$cat_list)<1 && ereg(",$add_link_cat$",$cat_list)<1 && ereg("^$add_link_cat,",$cat_list)<1)
		{	
			$cat_list.="$add_link_cat,";
		}
	}

}
elseif($selcat)
{
	$cur_cat=$add_link_cat;
}
else
{	global $HTTP_POST_VARS;
	if (($HTTP_POST_VARS)&&is_array($HTTP_POST_VARS))
	{
		reset($HTTP_POST_VARS);
		while (list ($key, $value) = each($HTTP_POST_VARS)) 
		{	if(strpos($key,"eletethiscat")==1) //remove
			{	$remove=1;
				$cat_id=substr($key,13);
				$cats=split(",",$cat_list);
				if($cat_id=="0")
				{
					$cat_id="0";
				}
				$cat_list="";
				for($i=0;$i<count($cats);$i++)
				{	if($cats[$i] != $cat_id)
						$cat_list.="$cats[$i],";
				}
				$cat_list=substr($cat_list,0,strlen($cat_list)-1);
			}
		}
	}

	if($remove!=1)
	{
		if($id && $action!="editcat")
			getcat($id);
		elseif(!$first) //defalt settings
		{	$reg_cat_perm=1;
			$all_cat_perm=1;
			$cat_vis=1;
			$rs=&$conn->Execute("SELECT cat_user FROM inl_cats WHERE cat_id=$cat"); //get parent user
			if($rs && !$rs->EOF)
				$cat_user=$rs->fields[0];
		}
		
		if ($action == "addcat")
		{	validatecat();
		    if ($error == "0")
			{	addcat($cat);
				$attach1=ereg_replace("\|","&",$attach);
		        inl_header("http://$server$filepath" ."admin/navigate.php?having=$having&$attach1"."cat=$cat");
			}
		}
		elseif ($action == "editcat") {
			validatecat();
			if ($error == "0") {
			    editcat($id);
				$attach1=ereg_replace("\|","&",$attach);
				inl_header("http://$server$filepath" . "admin/navigate.php?having=$having&$attach1"."cat=$cat");
			}
		}
	}
}	
if (get_magic_quotes_gpc())
{	
	$cat_name=stripslashes($cat_name);
	$cat_desc=stripslashes($cat_desc);
	$cat_image=stripslashes($cat_image);
	$meta_desc=stripslashes($meta_desc);
	$meta_keywords=stripslashes($meta_keywords);
	$cust1=stripslashes($cust1);
	$cust2=stripslashes($cust2);
	$cust3=stripslashes($cust3);
	$cust4=stripslashes($cust4);
	$cust5=stripslashes($cust5);
	$cust6=stripslashes($cust6);
}
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">

</HEAD>
<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
	<TD rowspan="2" width="0"><IMG src="images/icon1-.gif" width="32" height="32"></TD>
	<TD class="title" width="100%"><?php echo $la_nav1; ?></TD>
	<TD rowspan="2" width="0"><A href="help/6.htm#addcat"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?
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
<FORM name="addcat" method="post" action="addcategory.php?
	<?php
		if($sid && $session_get)
			echo "sid=$sid&";
		echo "having=$having&&attach=$attach";
	?>">
  <TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
	<TD class="tabletitle" bgcolor="#666666"><?php if($id){echo $la_title_edit_category;}else{echo $la_title_add_category;} ?></TD>
  </TR>
  <TR> 
	<TD bgcolor="#F6F6F6"> 
	  	<TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
		<TR bgcolor="#DEDEDE"> 
				<TD valign="top" colspan="3"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"><?php echo $la_enable_html;?> <input type="checkbox" name="html_enable" value="yes"><br>
			<img src="images/mark.gif" width="16" height="16" align="absmiddle"><?php echo $la_warning_html_enable;?></span></TD>
			</TR>
		<TR> 
		    <TD valign="top"><span class="text">
              <?php if ($error == 1) { echo "<font color=\"red\">";} ?><?php echo $la_name; ?>
              </span></TD>
		  <TD> 
			<INPUT type="text" name="cat_name" class="text" size="30" value="<?php echo $cat_name;?>">
		  </TD>
		 <td><SPAN class="text"><?php if ($error == 1) { echo "<font color=\"red\">$la_invalid_entry</font>";} ?>&nbsp;</SPAN></TD>
		</TR>
		<TR bgcolor="#DEDEDE"> 
		  <TD valign="top"><SPAN class="text"><?php if ($error == 2) { echo "<font color=\"red\">";} ?><?php echo $la_description; ?></SPAN></TD>
		  <TD> 
			<TEXTAREA name="cat_desc" cols="30" rows="5" class="text"><?php echo $cat_desc; ?></TEXTAREA>
		  </TD>
		 <td><SPAN class="text"><?php if ($error == 2) { echo "<font color=\"red\">$la_invalid_entry</font>";} ?>&nbsp;</SPAN></TD>
		</TR>
		<TR> 
		    <TD valign="top" class="text">
              <?php if (($error == 3) || ($error == 4) || ($error == 5)) { echo "<font color=\"red\">";} ?><?php echo $la_date_created; ?>
            </TD>
		  <TD> 
			<INPUT type="text" name="cat_month" class="text" size="5" value="<?php if($cat_month){echo $cat_month;}else{echo $month;} ?>">
			- 
			<INPUT type="text" name="cat_day" class="text" size="5" value="<?php if($cat_day){echo $cat_day;}else{echo $day;} ?>">
			- 
			<INPUT type="text" name="cat_year" class="text" size="7" value="<?php if($cat_year){echo $cat_year;}else{echo $year;} ?>">
			<SPAN class="small"><?php echo $la_date_format1; ?></SPAN></TD>
		<td><SPAN class="text"><?php if(($error == 3) || ($error == 4) || ($error == 5)) { echo "<font color=\"red\">$la_invalid_entry</font>";} ?>&nbsp;</SPAN></TD>
		</TR>
		<TR bgcolor="#DEDEDE">
            <TD valign="top" class="text" bgcolor="#DEDEDE"><?php echo $la_cat_owner; ?></TD>
            <TD bgcolor="#DEDEDE"> 
              <input type="text" value="<?php if ($HTTP_POST_VARS["cat_user"]) echo $HTTP_POST_VARS["cat_user"]; else echo get_user_name($cat_user); ?>" name="cat_user" class="text" size='30'>
            </TD><td bgcolor="#DEDEDE" class="text"><?php if ($error == 6) { echo "<font color=\"red\">$la_invalid_entry</font>";}  ?>&nbsp;</SPAN></td>
          </TR>
		<TR>
            <TD valign="top" class="text" bgcolor="#F6F6F6"><?php echo $la_change_all_subcat_users; ?></TD>
            <TD bgcolor="#F6F6F6"> 
              <INPUT type="checkbox" name="all_users" class="text" value="1">
            </TD><td bgcolor="#F6F6F6" class="text">&nbsp;</td>
         </TR>
		<TR>
            <TD valign="top" class="text" bgcolor="#F6F6F6"><?php echo $la_keep_subcat_editors; ?></TD>
            <TD bgcolor="#F6F6F6"> 
              <INPUT type="checkbox" name="keep_editors" class="text" value="1">
            </TD><td bgcolor="#F6F6F6" class="text">&nbsp;</td>
         </TR>

		<TR>
		  <TD valign="top" class="text" bgcolor="#DEDEDE"><?php echo $la_editor_pick; ?></TD>
		  <TD bgcolor="#DEDEDE"> 
			<INPUT type="checkbox" name="cat_pick" class="text"<?if ($cat_pick == "on" || $cat_pick==1) {echo "checked";}?>>
		  </TD><td bgcolor="#DEDEDE" class="text">&nbsp;</td>
		</TR>


		<TR bgcolor="#F6F6F6"> 
		<TD valign="top" class="text" bgcolor="#F6F6F6" colspan="3">
		<?php echo $la_cat_permissions; ?>:</TD>
		</TR>

		
		<TR bgcolor="#F6F6F6"> 
		<TD valign="top" class="text" bgcolor="#F6F6F6">
		<?php echo $la_registered_users; ?>:</TD>
		
		  <TD bgcolor="#F6F6F6" colspan="2"> 
			<SELECT name="reg_cat_perm" class="text">
			  <OPTION value="1" <?php if($reg_cat_perm==1) echo "selected";?>
					><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0" <?php if($reg_cat_perm==0) echo "selected";?>
					><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2" <?php if($reg_cat_perm==2) echo "selected";?>
					><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
		  </TD>
		</TR>

		<TR bgcolor="#F6F6F6"> 
		<TD valign="top" class="text" bgcolor="#F6F6F6">
		<?php echo $la_notregistered_visitors; ?>:</TD>
		
		  <TD bgcolor="#F6F6F6" colspan="2"> 
			<SELECT name="all_cat_perm" class="text">
			  <OPTION value="1" <?php if($all_cat_perm==1) echo "selected";?>
					><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0" <?php if($all_cat_perm==0) echo "selected";?>
					><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2" <?php if($all_cat_perm==2) echo "selected";?>
					><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
		  </TD>
		</TR>
		<?php if($id) :?>
		<tr bgcolor="#F6F6F6" colspan="2">
		  
            <td class="text"><?php echo $la_apply_cat_perm_sub; ?></td>
            <td class="text"> 
              <input type="checkbox" name="apply_cat_perm" value="1">
            </td>
          </tr>
		<?php endif ?>
		<TR bgcolor="#DEDEDE"> 
		  <TD valign="top" class="text"><?php echo $la_visible; ?></TD>
		  <TD> 
			<INPUT type="checkbox" name="cat_vis" class="text" <?php if($cat_vis=="on" || $cat_vis==1){echo " checked";}?>>
		  </TD><td class="text">&nbsp;</td>
		</TR>
		<TR bgcolor="#F6F6F6"> 
		  <TD valign="top"><SPAN class="text"><?php echo $la_category_graphic; ?></SPAN></TD>
		  <TD> 
			<INPUT type="text" name="cat_image" class="text" size="30" value="<?php echo $cat_image; ?>">
		  </TD><td class="text">&nbsp;</td>
		</TR>
		<TR bgcolor="#F6F6F6"> 
		  <TD valign="top"><SPAN class="text"><?php echo $la_meta_keywords; ?></SPAN></TD>
		  <TD> 
			<INPUT type="text" name="meta_keywords" class="text" size="30" value="<?php echo $meta_keywords; ?>">
		  </TD><td class="text">&nbsp;</td>
		</TR>
		<TR bgcolor="#DEDEDE"> 
		  <TD valign="top"><SPAN class="text"><?php echo $la_meta_desc; ?></SPAN></TD>
		  <TD> 
			<TEXTAREA name="meta_desc" cols="30" rows="5" class="text"><?php echo $meta_desc; ?></TEXTAREA>
		  </TD>
		 <td><SPAN class="text">&nbsp;</SPAN></TD>
		</TR>
		<?php
			$cats=split(",",$cat_list);
			end($cats); 
			$last_i = key($cats); 
			for($i=0;$i<$last_i;$i++)
			{	$cat_id="";
				$cat_name="";
				if($cats[$i]=="Home")
				{	$cat_id=0;
					$cat_name=$la_nav_home;
				}
				elseif($cats[$i] == "0")
				{
					$cat_name="Home";
					$cat_id = "0";
				}
				elseif($cats[$i])
				{	
					$query="select cat_id from inl_cats where cat_id=$cats[$i] and cat_pend=0";
					$rs = &$conn->Execute($query);
					if ($rs && !$rs->EOF)
					{	
						$cat_name=linkpath($rs->fields[0]);
						$cat_id=$rs->fields[0];
					}
				}
				else
					continue;

				echo "<TR bgcolor='#F6F6F6'>
						<TD valign='top'><span class='text'>$la_related_to</span></TD>
						<TD class='text'><b>$cat_name</b></TD>
						<td class='error'> 
							<input type='submit' name='deletethiscat$cat_id' value='$la_remove' class='button'>
						</td>
					 </TR>";
			}
			?>
		<TR bgcolor="#DEDEDE"> 
				<TD valign="top" class="text"><?php echo $la_choose_rel_cat;?></TD>
				<TD class="text">
					<?php 
					if ( $cur_cat==0)
					{	
						$parent_id=0;
						$cur_cat_title = $la_nav_home;
						echo "<b>",linkpath($cur_cat), "$la_navbar_seperator </b>";
						echo "<SELECT name=\"add_link_cat\">";
						
						echo "<option value=\"0\">&lt;&lt;----</option>";
					    
						echo print_drop_cats(0,$cat_list);
						echo "</SELECT>";
					}
					else if ( $cur_cat>0)
					{
						$rs = $conn->Execute("SELECT cat_name,cat_sub FROM inl_cats WHERE cat_id='$cur_cat'");
						$cur_cat_title = $rs -> fields["cat_name"];
						$parent_id = $rs -> fields["cat_sub"];
						echo "<b>",linkpath($cur_cat), "$la_navbar_seperator </b>";
						echo "<SELECT name=\"add_link_cat\">
						<option value=\"$parent_id\">&lt;&lt;----</option>";
					    echo print_drop_cats($cur_cat,$cat_list);
						echo "</SELECT>";

					}
					?>
				
				</TD>
				<TD class="error"> 
					<INPUT type="submit" name="selcat" value="<?php echo $la_select;?>" class="button">
					<INPUT type="submit" name="add_cat" value="<?php echo $la_relate_cat;?>" class="button">
			</TR>
		<TR bgcolor="#F6F6F6"> 
		  <TD valign="top"><SPAN class="text"><?php if($cc1)echo $cc1;else echo $la_custom_cat1; ?></SPAN></TD>
		  <TD> 
			<INPUT type="text" name="cust1" class="text" size="30" value="<?php echo $cust1; ?>">
		  </TD><td class="text">&nbsp;</td>
		</TR>
		<TR bgcolor="#DEDEDE"> 
		  <TD valign="top"><SPAN class="text"><?php if($cc2)echo $cc2;else echo $la_custom_cat2; ?></SPAN></TD>
		  <TD> 
			<INPUT type="text" name="cust2" class="text" size="30" value="<?php echo $cust2; ?>">
		  </TD><td class="text">&nbsp;</td>
		</TR>
		<TR bgcolor="#F6F6F6"> 
		  <TD valign="top"><SPAN class="text"><?php if($cc3)echo $cc3;else echo $la_custom_cat3; ?></SPAN></TD>
		  <TD> 
			<INPUT type="text" name="cust3" class="text" size="30" value="<?php echo $cust3; ?>">
		  </TD><td class="text">&nbsp;</td>
		</TR>
		<TR bgcolor="#DEDEDE"> 
		  <TD valign="top"><SPAN class="text"><?php if($cc4)echo $cc4;else echo $la_custom_cat4; ?></SPAN></TD>
		  <TD> 
			<INPUT type="text" name="cust4" class="text" size="30" value="<?php echo $cust4; ?>">
		  </TD><td class="text">&nbsp;</td>
		</TR>
		<TR bgcolor="#F6F6F6"> 
		  <TD valign="top"><SPAN class="text"><?php if($cc5)echo $cc5;else echo $la_custom_cat5; ?></SPAN></TD>
		  <TD> 
			<INPUT type="text" name="cust5" class="text" size="30" value="<?php echo $cust5; ?>">
		  </TD><td class="text">&nbsp;</td>
		</TR>
	
		<TR bgcolor="#DEDEDE"> 
		  <TD valign="top"><SPAN class="text"><?php if($cc6)echo $cc6;else echo $la_custom_cat6; ?></SPAN></TD>
		  <TD> 
			<INPUT type="text" name="cust6" class="text" size="30" value="<?php echo $cust6; ?>">
		  </TD><td class="text">&nbsp;</td>
		</TR>
	  </TABLE>
	</TD>
  </TR>
</TABLE>
<P>
  <input type="hidden" name="action" value="<? if($id){echo "editcat";}else{echo "addcat";}?>">
	<input type="hidden" name="cat" value="<?php echo $cat; ?>">
	<input type="hidden" name="cat_cust" value="<?php echo $cat_cust; ?>">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<input type="hidden" name="cat_list" value="<?php echo $cat_list; ?>">
	<input type="hidden" name="cur_cat" value="<?php echo $cur_cat; ?>">
	<input type="hidden" name="first" value="1">
  <INPUT type="submit" name="Submit" value="<?php if($id){echo $la_button_edit_cat;}else{echo $la_button_add_cat;} ?>" class="button">
  <INPUT type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
  <INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
</P>
</FORM>
</BODY>
</HTML>
