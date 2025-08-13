<?php
//Read in config file
$admin=1;
$thisfile = "addlink";

include("../includes/config.php");
include("../includes/user_lib.php");
include("../includes/links_lib.php");
include("../includes/cats_lib.php");

$form_input_add_link_user=get_user_id($HTTP_POST_VARS["form_input_add_link_user"]);

if ($action==$la_button_add_link)
{	$error=validatelink($cat_list);
    if ($error == "0") //good
		inl_header(add_new_link($cat_list));
	else{
		$l_title_add_link=$la_title_add_link;
		$l_button_add_link=$la_button_add_link;
		if($error==10)
			$cat_error=$la_invalid_entry;
	}
}

if(!$action)
{	$l_title_add_link=$la_title_add_link;
	$l_button_add_link=$la_button_add_link;
	if(!$form_input_add_link_user) 
		$form_input_add_link_user=$ses["user_id"]; 

}

if($editlink)
{	get_link($id);
	$editlink2="yes";
}
if($action==$la_button_edit_link)
{	$error=validatelink($cat_list);
	if ($error=="0")
	{	//if($ses["destin"]=="duplicates")
		if(ereg("duplicatelinks",$ses["destin"])>0)
		{	
			save_link($id,$cat_list);
			inl_header($ses["destin"]);
		}
		elseif(ereg("query_ids",$ses["destin"])>0)
		{	save_link($id,$cat_list);
			inl_header("linksvalidate.php?display=Display&".$ses["destin"]);
		}
		else
			inl_header(save_link($id,$cat_list));
	}
    else
	{	$action="savelink";
		$l_title_add_link=$la_title_edit_link;
		$l_button_add_link=$la_button_edit_link;	
		if($error==10)
			$cat_error=$la_invalid_entry;
	}
}
if($editlink2)
{	$l_title_add_link=$la_title_edit_link;
	$l_button_add_link=$la_button_edit_link;
}

if($addcat)
{	if(ereg("^_",$add_link_cat)<1)
	{

		if($add_link_cat==0) //fix the root naming issue
			$add_link_cat="Home";
		if(ereg(",$add_link_cat,",$cat_list)<1 
			&& ereg(",$add_link_cat$",$cat_list)<1
			&& ereg("^$add_link_cat,",$cat_list)<1)
		{	
			$cat_list.="$add_link_cat,";
		
		}
	}
}
if($selcat)
{
	if(ereg("^_",$add_link_cat)>0)
		$add_link_cat=ereg_replace("^_","",$add_link_cat);
	$cur_cat=$add_link_cat;
}
//deleting
{	global $HTTP_POST_VARS;
	if (($HTTP_POST_VARS)&&is_array($HTTP_POST_VARS))
	{
	reset($HTTP_POST_VARS);
	while (list ($key, $value) = each($HTTP_POST_VARS)) 
	{	if(strpos($key,"eletethiscat")==1) //remove
		{	//13
			$cat_id=substr($key,13);
			$cats=split(",",$cat_list);
			if($cat_id=="0")
				$cat_id="Home";
			$cat_list="";
			for($i=0;$i<count($cats);$i++)
			{	if($cats[$i] != $cat_id)
					$cat_list.="$cats[$i],";
			}
			$cat_list=substr($cat_list,0,strlen($cat_list)-1);
		}
	}
	}
}

if ($form_input_add_link_votes == "") {$form_input_add_link_votes = 0;}
if ($form_input_add_link_hits == "") {$form_input_add_link_hits = 0;}
if ($form_input_add_link_rating == "") {$form_input_add_link_rating = "0.00";}
if ($form_input_add_link_month == "") {$form_input_add_link_month = $month;}
if ($form_input_add_link_day == "") {$form_input_add_link_day = $day;}
if ($form_input_add_link_year == "") {$form_input_add_link_year = $year;}
if ($form_input_add_link_hour == "") {$form_input_add_link_hour = date("H",time());}
if ($form_input_add_link_minute == "") {$form_input_add_link_minute = date("i",time());}
if ($form_input_add_link_second == "") {$form_input_add_link_second = date("s",time());}
if (get_magic_quotes_gpc())
{	
	$form_input_add_link_name=stripslashes($form_input_add_link_name);
	$form_input_add_link_desc=stripslashes($form_input_add_link_desc);
	$form_input_add_link_url=stripslashes($form_input_add_link_url);
	$form_input_add_link_image=stripslashes($form_input_add_link_image);
	$form_input_add_link_cust1=stripslashes($form_input_add_link_cust1);
	$form_input_add_link_cust2=stripslashes($form_input_add_link_cust2);
	$form_input_add_link_cust3=stripslashes($form_input_add_link_cust3);
	$form_input_add_link_cust4=stripslashes($form_input_add_link_cust4);
	$form_input_add_link_cust5=stripslashes($form_input_add_link_cust5);
	$form_input_add_link_cust6=stripslashes($form_input_add_link_cust6);
}
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle;?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="images/icon1-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav1; ?></TD>
    <TD rowspan="2" width="0"><A href="help/6.htm#addlink"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><IMG src="images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>
<FORM name="addlink" method="post" action="addlink.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
	?>">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
    <TD class="tabletitle" bgcolor="#666666">
	<?php echo $l_title_add_link;?>
	</TD>
  </TR>
  <TR>
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
		<TR bgcolor="#DEDEDE"> 
				<TD valign="top" colspan="3"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"><?php echo $la_enable_html;?> <input type="checkbox" name="html_enable" value="yes"><br>
			<img src="images/mark.gif" width="16" height="16" align="absmiddle"><?php echo $la_warning_html_enable;?></span></TD>
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
				elseif($cats[$i])
				{	$query="select cat_id from inl_cats where cat_id=$cats[$i] and cat_pend=0";
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
						<TD valign='top'><span class='text'>$la_submitting_to</span></TD>
						<TD class='text'><b>$cat_name</b></TD>
						<td class='error'> 
							<input type='submit' name='deletethiscat$cat_id' value='$la_remove' class='button'>
						</td>
					 </TR>";
			}
			if($cat_error)
			{	echo "<TR bgcolor='#F6F6F6'>
						<TD valign='top' colspan=\"3\"><span class='error'>$cat_error - $la_category_missing</span>
						</TD>
					 </TR>";
				$cat_error="";
			}
			?>
		<TR bgcolor="#DEDEDE"> 
				<TD valign="top" class="text"><?php echo $la_additional_cats;?></TD>
				<TD class="text">
					<?php 

					if ( $cur_cat <0)
					{	
						$cur_cat_title = $la_nav_home;
						echo "<SELECT name=\"add_link_cat\">
								<option value=\"0\">$la_nav_home</option>
								</SELECT>";
					}
					else if ( $cur_cat==0)
					{	
						$parent_id=0;
						$cur_cat_title = $la_nav_home;
						echo "<b>",linkpath($cur_cat), "$la_navbar_seperator </b>";
						echo "<SELECT name=\"add_link_cat\">
						<option value=\"_-1\">&lt;&lt;----</option>";
					    echo print_drop_cats($cur_cat,$cat_list);
						echo "</SELECT>";
					}
					else if ( $cur_cat>0)
					{
						$rs = $conn->Execute("SELECT cat_name,cat_sub FROM inl_cats WHERE cat_id='$cur_cat'");
						$cur_cat_title = $rs -> fields["cat_name"];
						$parent_id = $rs -> fields["cat_sub"];
						echo "<b>",linkpath($cur_cat), "$la_navbar_seperator </b>";
						echo "<SELECT name=\"add_link_cat\">
						<option value=\"_$parent_id\">&lt;&lt;----</option>";
					    echo print_drop_cats($cur_cat,$cat_list);
						echo "</SELECT>";

					}
					?>
				
				</TD>
				<TD class="error"> 
					<INPUT type="submit" name="selcat" value="<?php echo $la_select;?>" class="button">
					<INPUT type="submit" name="addcat" value="<?php echo $la_add_cat;?>" class="button">
			</TR>
			<TR> 
            <TD valign="top"><SPAN class="text"><?php if ($error == 1) { echo "<font color=\"red\">";} ?><?php echo $la_name; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="form_input_add_link_name" class="text" size="30" value="<?php echo $form_input_add_link_name; ?>">
            </TD>
			<td><SPAN class="text"><?php if ($error == 1) { echo "<font color=\"red\">$la_invalid_entry</font>";} ?>&nbsp;</SPAN></TD>
          </TR>
          <TR> 
            <TD valign="top" bgcolor="#DEDEDE"><SPAN class="text"><?php if ($error == 6) { echo "<font color=\"red\">";} ?><?php echo $la_url; ?></SPAN></TD>
            <TD bgcolor="DEDEDE"> 
              <INPUT type="text" name="form_input_add_link_url" class="text" size="30" value="<?php echo $form_input_add_link_url; ?>">
            </TD>
		<td bgcolor="DEDEDE"><SPAN class="text"><?php if ($error == 6) { echo "<font color=\"red\">$la_invalid_entry</font>";}  ?>&nbsp;</SPAN></TD>
          </TR>
          <TR> 
            <TD valign="top"><SPAN class="text"><?php if ($error == 2) { echo "<font color=\"red\">";} ?><?php echo $la_description; ?></SPAN></TD>
            <TD> 
              <TEXTAREA name="form_input_add_link_desc" cols="30" rows="5" class="text"><?php echo $form_input_add_link_desc; ?></TEXTAREA>
            </TD>
		<td><SPAN class="text"><?php if ($error == 2){ echo "<font color=\"red\">$la_invalid_entry</font>";}  ?>&nbsp;</SPAN></TD>
          </TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="DEDEDE"><?php if ($error == 9) { echo "<font color=\"red\">";} ?><?php echo $la_rating; ?></TD>
            <TD bgcolor="DEDEDE"> 
              <INPUT type="text" name="form_input_add_link_rating" class="text" size="5" value="<?php echo $form_input_add_link_rating; ?>">
              <SPAN class="small"><?php echo $la_min_zero_max_ten; ?></SPAN></TD>
			<td bgcolor="DEDEDE"><SPAN class="text"><?php if ($error == 9) { echo "<font color=\"red\">$la_invalid_entry</font>";}  ?>&nbsp;</SPAN></TD>
          </TR>
          <TR> 
            <TD valign="top" class="text"><?php if ($error == 7) { echo "<font color=\"red\">";} ?><?php echo $la_votes; ?></TD>
            <TD> 
              <INPUT type="text" name="form_input_add_link_votes" class="text" size="5" value="<?php echo $form_input_add_link_votes; ?>">
              <span class="small">
              <?php echo $la_min_zero; ?>
              </span></TD>
			<td><SPAN class="text"><?php if ($error == 7) { echo "<font color=\"red\">$la_invalid_entry</font>";}  ?>&nbsp;</SPAN></TD>
          </TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="DEDEDE"><?php if ($error == 8) { echo "<font color=\"red\">";} ?><?php echo $la_hits; ?></TD>
            <TD bgcolor="DEDEDE"> 
              <INPUT type="text" name="form_input_add_link_hits" class="text" size="5" value="<?php echo $form_input_add_link_hits; ?>">
              <span class="small">
              <?php echo $la_min_zero; ?>
              </span></TD>
			 <td bgcolor="DEDEDE"><SPAN class="text"><?php if ($error == 8) { echo "<font color=\"red\">$la_invalid_entry</font>";}  ?>&nbsp;</SPAN></TD>
          </TR>
          <TR> 
            <TD valign="top" class="text"><?php if (($error == 3) || ($error == 4) || ($error == 5)) { echo "<font color=\"red\">";} ?><?php echo $la_date_created;?></TD>
            <TD> 
              <b><INPUT type="text" name="form_input_add_link_month" class="text" size="2" value="<?php echo $form_input_add_link_month; ?>">
              - 
              <INPUT type="text" name="form_input_add_link_day" class="text" size="2" value="<?php echo $form_input_add_link_day; ?>">
              - 
              <INPUT type="text" name="form_input_add_link_year" class="text" size="4" value="<?php echo $form_input_add_link_year; ?>"> , &nbsp;
             
			  <INPUT type="text" name="form_input_add_link_hour" class="text" size="2" value="<?php echo $form_input_add_link_hour; ?>">:
              <INPUT type="text" name="form_input_add_link_minute" class="text" size="2" value="<?php echo $form_input_add_link_minute; ?>">:
              <INPUT type="text" name="form_input_add_link_second" class="text" size="2" value="<?php echo $form_input_add_link_second; ?>"></b><br>
              <span class="small">(mm-dd-YYYY, hh:mm:ss)
              </span></TD>
			<td><SPAN class="text"><?php if (($error == 3) || ($error == 4) || ($error == 5))  { echo "<font color=\"red\">$la_invalid_entry</font>";}  ?>&nbsp;</SPAN></TD>
          </TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="#DEDEDE"><?php echo $la_editor_pick;?></TD>
            <TD bgcolor="#DEDEDE"> 
              <INPUT type="checkbox" name="form_input_add_link_pick" <?php if ($form_input_add_link_pick == "on" || $form_input_add_link_pick == 1) {echo "checked";}?>>
            </TD><td bgcolor="DEDEDE" class="text">&nbsp;</td>
          </TR>
          <TR bgcolor="#F6F6F6">
            <TD valign="top" class="text" bgcolor="#F6F6F6"><?php echo $la_link_owner; ?></TD>
            <TD bgcolor="#F6F6F6"> 
              <input type="text" value="<?php if ($HTTP_POST_VARS["form_input_add_link_user"]) echo $HTTP_POST_VARS["form_input_add_link_user"]; else echo get_user_name($form_input_add_link_user); ?>" name="form_input_add_link_user" class="text" size='30'>
            </TD><td class="text"><?php if ($error == 11) { echo "<font color=\"red\">$la_invalid_entry</font>";}  ?>&nbsp;</SPAN></td>
          </TR>
          <TR bgcolor="#DEDEDE">
            <TD valign="top" class="text">
              <?php echo $la_visible; ?>
            </TD>
            <TD>
              <INPUT type="checkbox" name="form_input_add_link_vis" <?php if ($form_input_add_link_vis == "on" || $form_input_add_link_vis == 1 || $l_button_add_link==$la_button_add_link) {echo "checked";}?>>
            </TD><td class="text">&nbsp;</td>
          </TR>
          <TR bgcolor="#F6F6F6">
            <TD valign="top"><SPAN class="text"><?php echo $la_link_graphic; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="form_input_add_link_image" class="text" size="30" value="<?php echo $form_input_add_link_image; ?>">
            </TD><td class="text">&nbsp;</td>
          </TR>
          <TR bgcolor="#DEDEDE">
            <TD valign="top"><SPAN class="text"><?php if($lc1)echo $lc1;else echo $la_custom_link1; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="form_input_add_link_cust1" class="text" size="30" value="<?php echo $form_input_add_link_cust1; ?>">
            </TD><td class="text">&nbsp;</td>
          </TR>
          <TR bgcolor="#F6F6F6">
            <TD valign="top"><SPAN class="text"><?php if($lc2)echo $lc2;else echo $la_custom_link2; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="form_input_add_link_cust2" class="text" size="30" value="<?php echo $form_input_add_link_cust2; ?>">
            </TD><td class="text">&nbsp;</td>
          </TR>
          <TR bgcolor="#DEDEDE">
            <TD valign="top"><SPAN class="text"><?php if($lc3)echo $lc3;else echo $la_custom_link3; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="form_input_add_link_cust3" class="text" size="30" value="<?php echo $form_input_add_link_cust3; ?>">
            </TD><td class="text">&nbsp;</td>
          </TR>
          <TR bgcolor="#F6F6F6">
            <TD valign="top"><SPAN class="text"><?php if($lc4)echo $lc4;else echo $la_custom_link4; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="form_input_add_link_cust4" class="text" size="30" value="<?php echo $form_input_add_link_cust4; ?>">
            </TD><td class="text">&nbsp;</td>
          </TR>
          <TR bgcolor="#DEDEDE">
            <TD valign="top"><SPAN class="text"><?php if($lc5)echo $lc5;else echo $la_custom_link5; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="form_input_add_link_cust5" class="text" size="30" value="<?php echo $form_input_add_link_cust5; ?>">
            </TD><td class="text">&nbsp;</td>
          </TR>
          <TR bgcolor="#F6F6F6">
            <TD valign="top"><SPAN class="text"><?php if($lc6)echo $lc6;else echo $la_custom_link6; ?></SPAN></TD>
            <TD>
              <INPUT type="text" name="form_input_add_link_cust6" class="text" size="30" value="<?php echo $form_input_add_link_cust6; ?>">
            </TD><td class="text">&nbsp;</td>
          </TR>
        </TABLE>

     
    </TD>
  </TR>
</TABLE>
<P>

<input type="hidden" name="cat" value="<?php echo $cat; ?>">
<?php if($editlink2)
		echo "\n<input type='hidden' name='editlink2' value='yes'>";
?>
<input type="hidden" name="cur_cat" value="<?php echo $cur_cat; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="cat_list" value="<?php echo $cat_list; ?>">
<input type="hidden" name="attach" value="<?php echo $attach; ?>">
<input type="hidden" name="having" value="<?php echo $having; ?>">
<input type="submit" <?php echo "name='action' value='$l_button_add_link'"; ?> class="button">
<input type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
<input type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
</P>
</FORM>
</BODY>
</HTML>
