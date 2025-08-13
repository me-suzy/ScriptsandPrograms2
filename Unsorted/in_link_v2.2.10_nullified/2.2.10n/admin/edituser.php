<?php
//Read in config file
$thisfile = "user";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
$attach1=$attach;
if($attach){$attach=ereg_replace("\|","&",$attach);}
include("../includes/admin_users_lib.php");
include("../includes/admin_search_lib.php");
include("../includes/hierarchy_lib.php");

if(!$pend){$pend="0";}
$form_action="edituser.php";
if($submit==$la_button_edit){getuser($id); $tit=$la_button_edit_user; $subut=$la_button_update_user;}
elseif($submit==$la_button_update_user){
	$tit=$la_button_edit_user; $subut=$la_button_update_user;
	$valid=1;
	if(strlen($user_name)<3){$err["user_name"]=$la_invalid_entry; $valid=0;}

	if((strlen($user_pass_t)<3 || ereg(" ",$user_pass_t)>0) && $user_pass_t)
	{
		$err["user_pass"]=$la_invalid_entry; 
		$valid=0;
	}
	
	if($user_pass_t!=$re_pass){$err["re_pass"]=$la_pass_not_match; $valid=0;}
	if(strlen($first)<1){$err["first"]=$la_invalid_entry; $valid=0;}
	if(strlen($last)<1){$err["last"]=$la_invalid_entry; $valid=0;}
	if($uday<1 || $uday>31 || $uday<1 || $umonth<1 || $umonth>12 || $umonth<1 || $uyear<1 || $uyear>4000 || $uyear<1){$err["udate"]=$la_invalid_entry; $valid=0;}
	if(strlen($email)<5 || ereg("@",$email)<1 || ereg("\.",$email)<1){$err["email"]=$la_invalid_entry; $valid=0;}
	if($valid==1)
	{	if(edituser($id,$user_name,$user_pass_t, $email, $first, $last,$umonth ,$uday, $uyear, $user_perm_t, $user_status, $cust1, $cust2, $cust3, $cust4, $cust5, $cust6))
		{
			inl_header("users.php?pend=$pend$attach");
		}
	}		
	
}
elseif($submit==$la_button_new_user){$tit=$la_button_new_user; $subut=$la_button_add_user; $umonth=$month; $uday=$day; $uyear=$year;}
elseif($submit==$la_button_add_user){
	$tit=$la_button_new_user; $subut=$la_button_add_user;
	$valid=1;
	if(strlen($user_name)<3){$err["user_name"]=$la_invalid_entry; $valid=0;}
	if(strlen($user_pass_t)<3){$err["user_pass"]=$la_invalid_entry; $valid=0;}
	if(strlen($re_pass)<1 || $user_pass_t!=$re_pass){$err["re_pass"]=$la_pass_not_match; $valid=0;}
	if(strlen($first)<1){$err["first"]=$la_invalid_entry; $valid=0;}
	if(strlen($last)<1){$err["last"]=$la_invalid_entry; $valid=0;}
	if($uday<1 || $uday>31 || $uday<1 || $umonth<1 || $umonth>12 || $umonth<1 || $uyear<1 || $uyear>4000 || $uyear<1){$err["udate"]=$la_invalid_entry; $valid=0;}
	if(strlen($email)<5 || ereg("@",$email)<1 || ereg("\.",$email)<1){$err["email"]=$la_invalid_entry; $valid=0;}
	if($valid==1){
		if(adduser($user_name,$user_pass_t, $email, $first, $last,$umonth ,$uday, $uyear, $user_perm_t, $user_status, $cust1, $cust2, $cust3, $cust4, $cust5, $cust6))
		{
			inl_header("users.php?pend=$pend$attach");
		}
	} else {
	$user_name=inl_escape($user_name);
	$user_pass=md5($user_pass);
	$re_pass=inl_escape($re_pass);
	$email=inl_escape($email);
	$first=inl_escape($first);
	$last=inl_escape($last);
	$cust1=inl_escape($cust1);
	$cust2=inl_escape($cust2);
	$cust3=inl_escape($cust3);
	$cust4=inl_escape($cust4);
	$cust5=inl_escape($cust5);
	$cust6=inl_escape($cust6);
	}
	
}
elseif($submitYes==$la_yes)
{	deleteuser($deleteid); 
	inl_header("users.php?pend=$pend$attach");
}
elseif($submitNo==$la_no)
	inl_header("users.php?pend=$pend$attach");
	

?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<LINK rel="stylesheet" href="admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
      <td rowSpan="2" width="0"><img src="images/icon9-.gif" width="32" height="32"></td>
      <td class="title" width="100%"><?php echo $la_nav3;?></td>
    <TD rowspan="2" width="0"><A href="help/6.htm#adduser"><IMG src="images/but1.gif" width="30" height="32" border="0"></A><A href="confirm.php?<?php
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
  <TR> 
    <TD class="tabletitle" bgcolor="#666666"><?php echo $tit; ?></TD>
  </TR>
  <TR> 
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
         <FORM name="edituser" method="post" action="<?php 
			if($sid && $session_get)
				$att_sid="sid=$sid&";
			echo "$form_action?$att_sid"."attach=$attach1"; 
			?>">
		<input type="hidden" name="pend" value="<?php echo $pend; ?>">
		 <TR> 
            <TD valign="top"><SPAN class="<?php if(strlen($err["user_name"])>1){echo "error";}else{echo "text";} ?>"><?php echo $la_user_name; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="user_name" class="text" size="30" value="<?php if(strlen($user_name)>0){echo $user_name;} ?>">
            </TD>
			<td class="error">&nbsp;<?php if(strlen($err["user_name"])>1){echo $err["user_name"];} ?></td>
          </TR>
          <TR> 
            <TD valign="top" bgcolor="DEDEDE"><SPAN class="<?php if(strlen($err["user_pass"])>1){echo "error";}else{echo "text";} ?>"><?php echo $la_password; ?></SPAN></TD>
            <TD bgcolor="DEDEDE"> 
              <INPUT type="password" name="user_pass_t" class="text" size="30" value=""> <span class="small"><?php echo $la_atleast_3,$la_to_keep_leave_blank;?></span>
            </TD>
				<td bgcolor="DEDEDE" class="error">&nbsp<?php if(strlen($err["user_pass"])>1){echo $err["user_pass"];} ?></td>
          </TR>
          <TR> 
            <TD valign="top"><SPAN class="<?php if(strlen($err["re_pass"])>1){echo "error";}else{echo "text";} ?>"><?php echo $la_re_password; ?></SPAN></TD>
            <TD> 
              <INPUT type="password" name="re_pass" class="text" size="30" value=""> <span class="small"><?php echo $la_atleast_3,$la_to_keep_leave_blank;?></span>
            </TD>
		<td class="error">&nbsp;<?php if(strlen($err["re_pass"])>1){echo $err["re_pass"];} ?></td>	
          </TR>
          <TR> 
            <TD valign="top" class="<?php if(strlen($err["first"])>1){echo "error";}else{echo "text";} ?>" bgcolor="DEDEDE"><?php echo $la_first_name; ?></TD>
            <TD bgcolor="DEDEDE"> 
              <INPUT type="text" name="first" class="text" size="30" value="<?php if(strlen($first)>0){echo $first;} ?>">
            </TD>
		<td bgcolor="DEDEDE" class="error">&nbsp;<?php if(strlen($err["first"])>1){echo $err["first"];} ?></td>
          </TR>
          <TR> 
            <TD valign="top" class="<?php if(strlen($err["last"])>1){echo "error";}else{echo "text";} ?>"><?php echo $la_last_name; ?></TD>
            <TD> 
              <INPUT type="text" name="last" class="text" size="30" value="<?php if(strlen($last)>0){echo $last;} ?>">
            </TD>
				<td class="error">&nbsp;<?php if(strlen($err["last"])>1){echo $err["last"];} ?></td>

          </TR>
          <TR> 
            <TD valign="top" class="<?php if(strlen($err["email"])>1){echo "error";}else{echo "text";} ?>" bgcolor="DEDEDE"><?php echo $la_email; ?></TD>
            <TD bgcolor="DEDEDE"> 
              <INPUT type="text" name="email" class="text" size="30" value="<?php if(strlen($email)>0){echo $email;} ?>">
            </TD>
			<td bgcolor="DEDEDE" class="error"><?php if(strlen($err["email"])>1){echo $err["email"];} ?></td>

          </TR>
          <TR> 
            <TD valign="top" class="<?php if(strlen($err["udate"])>1){echo "error";}else{echo "text";} ?>"><?php echo $la_date; ?></TD>
            <TD> 
              <INPUT type="text" name="umonth" class="text" maxlength="2" size="5" value="<?php if(strlen($umonth)>0){echo $umonth;} ?>">
              - 
              <INPUT type="text" name="uday" class="text" maxlength="2" size="5" value="<?php if(strlen($uday)>0){echo $uday;} ?>">
              - 
              <INPUT type="text" name="uyear" class="text" maxlength="4" size="7" value="<?php if(strlen($uyear)>0){echo $uyear;} ?>">
              <span class="small">
              <?php echo $la_date_format1; ?>              </span></TD>
				<td class="error">
				<?php 
						if(strlen($err["udate"])>1){echo $err["udate"];} 
 				?>
				</td>



          </TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="#DEDEDE"><?php echo $la_permissions; ?></TD>
            <TD bgcolor="#DEDEDE"> 
              <select size="1" name="user_perm_t" class="text">
                <option value="3" <?php if($user_perm_t==3){echo "selected";}?>><?php echo $la_user; ?></option>
                <option value="2" <?php if($user_perm_t==2){echo "selected";}?>><?php echo $la_admin; ?></option>
				<option value="4" <?php if($user_perm_t==4){echo "selected";}?>><?php echo $la_mailing_list; ?></option>
				<option value="5" <?php if($user_perm_t==5){echo "selected";}?>><?php echo $la_editor; ?></option>
              </select>
            </TD>
		<td bgcolor="#DEDEDE">&nbsp;</td>
          </TR>

          <TR> 
            <TD valign="top" class="text"><?php echo $la_user_enabled; ?></TD>
            <TD> 
              <INPUT type="checkbox" name="user_status" class="text" size="3" value="1" <?php if($user_status==1 || $submit=="$la_button_new_user"){echo "checked";} ?>>
            </TD>
				<td class="error">&nbsp;</td>

          </TR>
		          <TR> 
            <TD valign="top" class="text" bgcolor="#DEDEDE"><?php if(strlen($uc1)>0){echo $uc1;}else{echo $la_custom_user1;} ?></TD>
            <TD bgcolor="#DEDEDE"> 
              <INPUT type="text" name="cust1" class="text" size="30" value="<?php if(strlen($cust1)>0){echo $cust1;} ?>">
            </TD>
				<td bgcolor="#DEDEDE" class="error">&nbsp;</td>

          </TR>
          <TR> 
            <TD valign="top" class="text"><?php if(strlen($uc2)>0){echo $uc2;}else{echo $la_custom_user2;} ?></TD>
            <TD> 
              <INPUT type="text" name="cust2" class="text" size="30" value="<?php if(strlen($cust2)>0){echo $cust2;} ?>">
            </TD>
				<td class="error">&nbsp;</td>

          </TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="#DEDEDE"><?php if(strlen($uc3)>0){echo $uc3;}else{echo $la_custom_user3;} ?></TD>
            <TD bgcolor="#DEDEDE"> 
              <INPUT type="text" name="cust3" class="text" size="30" value="<?php if(strlen($cust3)>0){echo $cust3;} ?>">
            </TD>
				<td class="error" bgcolor="#DEDEDE">&nbsp;</td>

          </TR>
          <TR> 
            <TD valign="top" class="text"><?php if(strlen($uc4)>0){echo $uc4;}else{echo $la_custom_user4;} ?></TD>
            <TD> 
               <INPUT type="text" name="cust4" class="text" size="30" value="<?php if(strlen($cust4)>0){echo $cust4;} ?>">
            </TD>
				<td class="error">&nbsp;</td>

          </TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="#DEDEDE"><?php if(strlen($uc5)>0){echo $uc5;}else{echo $la_custom_user5;} ?></TD>
            <TD bgcolor="#DEDEDE"> 
               <INPUT type="text" name="cust5" class="text" size="30" value="<?php if(strlen($cust5)>0){echo $cust5;} ?>">
            </TD>
				<td class="error" bgcolor="#DEDEDE">&nbsp;</td>

          </TR>
          <TR> 
            <TD valign="top" class="text"><?php if(strlen($uc6)>0){echo $uc6;}else{echo $la_custom_user6;} ?></TD>
            <TD> 
               <INPUT type="text" name="cust6" class="text" size="30" value="<?php if(strlen($cust6)>0){echo $cust6;} ?>">
            </TD>
				<td class="error">&nbsp;</td>

          </TR>

		<TR> 
            <TD valign="top" class="text" colspan="3" bgcolor="#DEDEDE">
		<input type="hidden" name="id" value="<?php echo $id;?>">
		<input type="submit" name="submit" value="<?php echo $subut; ?>" class="button">
		<input type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
		<input type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
		</td>
		</tr>
		</FORM>
        </TABLE>

     
    </TD>
  </TR>
</TABLE>
</BODY>
</HTML>