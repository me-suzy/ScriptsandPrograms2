<?php
//Read in config file
$thisfile = "search_advanced";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
$onlycats=0; $onlylinks==0;
$action="search_result.php";
if($pend && $table=="cats"){ $onlycats=1;}
elseif($pend && $table=="links"){ $onlylinks=1;}
if($onlycats==0 && $onlylinks==0){$onlylinks=1; $onlycats=1;}
?>

<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">

<?php if($user){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon<?php if($pend==1){echo 2;}else{echo 1;}?>-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php if($pend==1){echo $la_nav2;}else{echo $la_nav1;} ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#navigate"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="./images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_advanced_search ?></td>
  </tr>
  <tr>
    <td bgcolor="#DEDEDE">
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
	<tr bgcolor="#999999" class="tabletitle"> 
            <td valign="top" class="text" colspan="2">
              <?php echo $la_users ?>
            </td>
          </tr><form name="form" method="post" action="users.php<?php 
			if($sid && $session_get)
				echo "?sid=$sid";
			?>">
		<input type="hidden" name="pend" value="<?php if($pend){echo $pend;}else{echo "0";} ?>">
          <tr valign="top"> 
            <td class="text" bgcolor="#DEDEDE">
              <?php echo $la_user_name; ?>
            </td>
            <td class="text"  bgcolor="#DEDEDE"> 
              <input type="text" name="user_name" class="text" size="20">
            </td>
          </tr>
                    <tr valign="top"> 
            <td class="text" bgcolor="#F6F6F6">
              <?php echo $la_last_name; ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              <input type="text" name="last" class="text" size="20">
            </td>
          </tr>
                    <tr valign="top"> 
            <td class="text"  bgcolor="#DEDEDE">
              <?php echo $la_first_name; ?>
            </td>
            <td class="text"  bgcolor="#DEDEDE"> 
              <input type="text" name="first" class="text" size="20">
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#F6F6F6">
              <?php echo $la_email; ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              <input type="text" name="email" class="text" size="30">
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#DEDEDE">
              <?php echo $la_date_created ?>
            </td>
            <td class="text" bgcolor="#DEDEDE"> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td class="text"> 
                    <?php echo $la_from ?>
                  </td>
                  <td> 
                    <input type="text" name="fmonth" class="text" size="2" value="" maxlength="2">
                    - 
                    <input type="text" name="fday" class="text" size="2" value="" maxlength="2">
                    - 
                    <input type="text" name="fyear" class="text" size="4" value="" maxlength="4">
                    <span class="small"><?php echo $la_date_format1 ?></span>
                  </td>
                </tr>
                <tr> 
                  <td class="text">
                    <?php echo $la_to ?>
                  </td>
                  <td> 
                    <input type="text" name="lmonth" class="text" size="2" value="">
                    - 
                    <input type="text" name="lday" class="text" size="2" value="" maxlength="2">
                    - 
                    <input type="text" name="lyear" class="text" size="4" value="" maxlength="4">
                    <span class="small"> 
                    <?php echo $la_date_format1 ?>
                    </span></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#F6F6F6">
              <?php echo $la_permissions ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
           
                 <select size="1" name="user_perm" class="text">
	            <option value=""><?php echo $la_all; ?></option>
			<option value="3"><?php echo $la_user; ?></option>
                <option value="2"><?php echo $la_admin; ?></option>
              </select>
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#DEDEDE"><?php echo $la_status ?>
            </td>
            <td class="text" bgcolor="#DEDEDE"> 
            <input type="radio" value="1" name="status">Enabled&nbsp;&nbsp;&nbsp;
            <input type="radio" value="0" name="status"> Disabled 
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#F6F6F6">
              <?php if(strlen($uc1)>0){echo $uc1;}else{echo $la_custom_user1;} ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              <input type="text" name="ucust1" class="text" size="30">
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#DEDEDE">
              <?php if(strlen($uc2)>0){echo $uc2;}else{echo $la_custom_user2;} ?>
            </td>
            <td class="text" bgcolor="#DEDEDE"> 
              <input type="text" name="ucust2" class="text" size="30">
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#F6F6F6">
              <?php if(strlen($uc3)>0){echo $uc3;}else{echo $la_custom_user3;} ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              <input type="text" name="ucust3" class="text" size="30">
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#DEDEDE">
              <?php if(strlen($uc4)>0){echo $uc4;}else{echo $la_custom_user4;} ?>
            </td>
            <td class="text" bgcolor="#DEDEDE"> 
              <input type="text" name="ucust4" class="text" size="30">
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text" bgcolor="#F6F6F6">
              <?php if(strlen($uc5)>0){echo $uc5;}else{echo $la_custom_user5;} ?>
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              <input type="text" name="ucust5" class="text" size="30">
            </td>
          </tr>
			<tr valign="top"> 
            <td class="text" bgcolor="#DEDEDE">
              <?php if(strlen($uc6)>0){echo $uc6;}else{echo $la_custom_user6;} ?>
            </td>
            <td class="text" bgcolor="#DEDEDE"> 
              <input type="text" name="ucust6" class="text" size="30">
            </td>
          </tr>
			<tr valign="top"> 
            <td class="text" bgcolor="#F6F6F6">
              &nbsp;
            </td>
            <td class="text" bgcolor="#F6F6F6"> 
              &nbsp;
            </td>
          </tr>
         <tr bgcolor="#999999"> 
            <td valign="top" class="text" colspan="2"> <b>
              <?php echo $la_general ?>
              </b> </td>
          </tr>
                    <tr valign="middle" bgcolor="#DEDEDE"> 
            <td class="text"><?php echo $la_boolean_type_search;?></td>
            <td class="text"> 
              <select name="sep" class="text">
				<option value="or" selected><?php echo $la_drop_or_default; ?></option>    
				<option value="and"><?php echo $la_drop_and; ?></option>		
              </select>
            </td>
        </tr>
          <tr valign="middle" bgcolor="#F0F0F0"> 
            <td class="text">
              <?php echo $la_return_number_results ?>
            </td>
            <td class="text"> 
              <select name="result" class="text" size="1">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50" selected>50</option>
                <option value="100">100</option>
                <option value="500">500</option>
                <option value="" selected><?php echo $la_drop_as_many_as_found ?></option>
              </select>
            </td>
          </tr>
          <tr valign="middle"> 
            <td class="text" colspan="2"> 
              <input type="submit" name="submit" value="<?php echo $la_button_search ?>" class="button">
              <input type="reset" name="Submit2" value="<?php echo $la_button_reset ?>" class="button">
              <input type="button" name="Submit3" value="<?php echo $la_button_cancel ?>" class="button" onClick="history.back();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
</table>
</body>
</html>

<?php }elseif($catlinks){?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon<?php if($pend==1){echo 2;}else{echo 1;}?>-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php if($pend==1){echo $la_nav2;}else{echo $la_nav1;} ?></td>
    <td rowspan="2" width="0"><a href="help/manual.pdf"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  <tr> 
    <td width="100%"><img src="./images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_advanced_search ?></td>
  </tr><form name="form" method="post" action="<?php echo $action;
		if($sid && $session_get)
			echo "?sid=$sid";
	?>">
<?php if($onlycats==1){ ?>  
<tr>
    <td bgcolor="#DEDEDE">
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr valign="middle" bgcolor="#999999"> 
          <td class="textTitle" colspan="2"><b><?php echo $la_categories ?></b></td>
        </tr>
          <tr valign="top"> 
            <td class="text"> 
              <?php echo $la_name ?>
            </td>
            <td class="text"> 
              <input type="text" name="cat_name" class="text" size="20">
            </td>
          </tr>
          <tr valign="top" bgcolor="#F0F0F0"> 
            <td class="text">
              <?php echo $la_description ?>
            </td>
            <td class="text"> 
              <input type="text" name="cat_desc" class="text" size="20">
            </td>
          </tr>
          <tr valign="top" bgcolor="#DEDEDE"> 
            <td class="text">
              <?php echo $la_date_created ?>
            </td>
            <td class="text"> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td class="text"> 
                    <?php echo $la_from ?>
                  </td>
                  <td> 
                    <input type="text" name="fmonth" class="text" size="5" value="">
                    - 
                    <input type="text" name="fday" class="text" size="5" value="">
                    - 
                    <input type="text" name="fyear" class="text" size="7" value="">
                    <span class="small"><?php echo $la_date_format1 ?></span>
                  </td>
                </tr>
                <tr> 
                  <td class="text">
                    <?php echo $la_to ?>
                  </td>
                  <td> 
                    <input type="text" name="lmonth" class="text" size="5" value="">
                    - 
                    <input type="text" name="lday" class="text" size="5" value="">
                    - 
                    <input type="text" name="lyear" class="text" size="7" value="">
                    <span class="small"> 
                    <?php echo $la_date_format1 ?>
                    </span></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr valign="top" bgcolor="#F0F0F0"> 
            <td class="text">
              <?php echo $la_editor_pick ?>
            </td>
            <td class="text"> 
             <?php echo $la_yes ?>&nbsp; <input type="radio" value="1" name="cat_pick">&nbsp;
             <?php echo $la_no ?>&nbsp; <input type="radio" value="0" name="cat_pick">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">
              <?php if(strlen($cc1)>0){echo $cc1;}else{echo $la_custom_cat1;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="ccust1" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="top"> 
            <td class="text">
              <?php if(strlen($cc2)>0){echo $cc2;}else{echo $la_custom_cat2;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="ccust2" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">
              <?php if(strlen($cc3)>0){echo $cc3;}else{echo $la_custom_cat3;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="ccust3" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="top"> 
            <td class="text">
               <?php if(strlen($cc4)>0){echo $cc4;}else{echo $la_custom_cat4;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="ccust4" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">
              <?php if(strlen($cc5)>0){echo $cc5;}else{echo $la_custom_cat5;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="ccust5" class="text" size="30">
            </td>
          </tr>
                    <tr bgcolor="#F6F6F6" valign="top"> 
            <td class="text">
              <?php if(strlen($cc6)>0){echo $cc6;}else{echo $la_custom_cat6;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="ccust6" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">
              <?php echo $la_visible ?>
            </td>
            <td class="text"> 
                           <?php echo $la_yes ?>&nbsp; <input type="radio" value="1" name="cat_vis">&nbsp;
             <?php echo $la_no ?>&nbsp; <input type="radio" value="0" name="cat_vis">
            </td>
          </tr>
          <tr  bgcolor="#F6F6F6" valign="top"> 
            <td class="text">&nbsp;</td>
            <td class="text">&nbsp; </td>
          </tr>
	</table></td></tr>
<?php }if($onlylinks==1){?>          
<tr>
    <td bgcolor="#DEDEDE">
      <table width="100%" border="0" cellspacing="0" cellpadding="4">      
          <tr bgcolor="#999999" class="tabletitle"> 
            <td valign="top" class="textTitle" colspan="2">
              <?php echo $la_links ?>
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text">
              <?php echo $la_name ?>
            </td>
            <td class="text"> 
              <input type="text" name="link_name" class="text" size="20">
            </td>
          </tr>
          <tr valign="top" bgcolor="#F0F0F0"> 
            <td class="text">
              <?php echo $la_description ?>
            </td>
            <td class="text"> 
              <input type="text" name="link_desc" class="text" size="20">
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text">
              <?php echo $la_rating ?>
            </td>
            <td class="text">
              <?php echo $la_from ?>
              <input type="text" name="link_rating_f" class="text" size="5">
              <?php echo $la_to ?>
              <input type="text" name="link_rating_l" class="text" size="5">
            </td>
          </tr>
          <tr valign="top"> 
            <td class="text">
              <?php echo $la_votes ?>
            </td>
            <td class="text">
              <?php echo $la_from ?>
              <input type="text" name="link_votes_f" class="text" size="5">
              <?php echo $la_to ?>
              <input type="text" name="link_votes_l" class="text" size="5">
            </td>
          </tr>
          <tr valign="top" bgcolor="#F0F0F0"> 
            <td class="text">
              <?php echo $la_hits ?>
            </td>
            <td class="text">
              <?php echo $la_from ?>
              <input type="text" name="link_hits_f" class="text" size="5">
              <?php echo $la_to ?>
              <input type="text" name="link_hits_l" class="text" size="5">
            </td>
          </tr>
          <tr valign="top" bgcolor="#DEDEDE"> 
            <td class="text">
              <?php echo $la_date_created ?>
            </td>
            <td class="text"> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td class="text"> 
                    <?php echo $la_from ?>
                  </td>
                  <td> 
                    <input type="text" name="fmonthl" class="text" size="2" value="" maxlength="2">
                    - 
                    <input type="text" name="fdayl" class="text" size="2" value="" maxlength="2">
                    - 
                    <input type="text" name="fyearl" class="text" size="4" value="" maxlength="4">
                    <span class="small"><?php echo $la_date_format1 ?></span>
                  </td>
                </tr>
                <tr> 
                  <td class="text">
                    <?php echo $la_to ?>
                  </td>
                  <td> 
                    <input type="text" name="lmonthl" class="text" size="2" value="">
                    - 
                    <input type="text" name="ldayl" class="text" size="2" value="" maxlength="2">
                    - 
                    <input type="text" name="lyearl" class="text" size="4" value="" maxlength="4">
                    <span class="small"> 
                    <?php echo $la_date_format1 ?>
                    </span></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr valign="top" bgcolor="#F0F0F0"> 
            <td class="text">
              <?php echo $la_editor_pick ?>
            </td>
            <td class="text"> 
                           <?php echo $la_yes ?>&nbsp; <input type="radio" value="1" name="link_pick">&nbsp;
             <?php echo $la_no ?>&nbsp; <input type="radio" value="0" name="link_pick">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">
               <?php if(strlen($lc1)>0){echo $lc1;}else{echo $la_custom_link1;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="lcust1" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#F0F0F0" valign="top"> 
            <td class="text">
              <?php if(strlen($lc2)>0){echo $lc2;}else{echo $la_custom_link2;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="lcust2" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">
               <?php if(strlen($lc3)>0){echo $lc3;}else{echo $la_custom_link3;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="lcust3" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#F0F0F0" valign="top"> 
            <td class="text">
              <?php if(strlen($lc4)>0){echo $lc4;}else{echo $la_custom_link4;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="lcust4" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">
              <?php if(strlen($lc5)>0){echo $lc5;}else{echo $la_custom_link5;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="lcust5" class="text" size="30">
            </td>
          </tr>
                    <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">
             <?php if(strlen($lc6)>0){echo $lc6;}else{echo $la_custom_link6;} ?>
            </td>
            <td class="text"> 
              <input type="text" name="lcust6" class="text" size="30">
            </td>
          </tr>
          <tr bgcolor="#F0F0F0" valign="top"> 
            <td class="text">
              <?php echo $la_visible ?>
            </td>
            <td class="text"> 
                                         <?php echo $la_yes ?>&nbsp; <input type="radio" value="1" name="link_vis">&nbsp;
             <?php echo $la_no ?>&nbsp; <input type="radio" value="0" name="link_vis">
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="top"> 
            <td class="text">&nbsp;</td>
            <td class="text">&nbsp;</td>
          </tr>
	</table>
    </td>
  </tr>

<?php } ?>
	      
    
  <tr><td bgcolor="#DEDEDE"><table width="100%" border="0" cellspacing="0" cellpadding="4">
         <tr bgcolor="#999999"> 
            <td valign="top" class="textTitle" colspan="2"> <b>
              <?php echo $la_general ?>
              </b> </td>
          </tr>
             <tr valign="middle" bgcolor="#DEDEDE"> 
            <td class="text"><?php echo $la_boolean_type_search;?></td>
            <td class="text"> 
              <select name="sep" class="text">
                <option value="or" selected><?php echo $la_drop_or_default; ?></option>
                <option value="and"><?php echo $la_drop_and; ?></option>
              </select>
            </td>
        </tr>
          <tr valign="middle" bgcolor="#F0F0F0"> 
            <td class="text">
              <?php echo $la_return_number_results ?>
            </td>
            <td class="text"> 
	<input type="hidden" name="pend" value="<?php if($pend){echo $pend;}else{echo "0";} ?>">
              <select name="result" class="text" size="1">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50" selected>50</option>
                <option value="100">100</option>
                <option value="500">500</option>
                <option value="" selected><?php echo $la_drop_as_many_as_found ?></option>
              </select>
            </td>
          </tr>
          <tr valign="middle"> 
            <td class="text" colspan="2"> 
			<input type="hidden" name="pend" value="<?php if($pend){echo 1;}else{echo 0;}?>">
              <?php if($onlycats==1){echo "<input type='submit' name='submit' value='$la_button_search_cats' class='button'>&nbsp;&nbsp;";} ?>
              <?php if($onlylinks==1){echo "<input type='submit' name='submit' value='$la_button_search_links' class='button'>&nbsp;&nbsp;";} ?>
              <input type="reset" name="Submit2" value="<?php echo $la_button_reset ?>" class="button">&nbsp;&nbsp;
              <input type="button" name="Submit3" value="<?php echo $la_button_cancel ?>" class="button" onClick="history.back();">
            </td>
          </tr>
      </table>
    </td>
  </tr>
</form>
</table>
</body>
</html>

<?php } ?>