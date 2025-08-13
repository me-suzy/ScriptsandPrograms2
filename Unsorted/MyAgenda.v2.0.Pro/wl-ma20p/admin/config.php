<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("header.php");
?>
<?php
if (match_referer() && IsSet($HTTP_POST_VARS)) {
	$frm = $HTTP_POST_VARS;
	$errormsg = validate_form($frm);
	if(empty($errormsg)) {
		$sQL = "UPDATE ".$CFG->Tbl_Pfix."_CONFIGS SET
					PROG_NAME = '".trim($frm[PROG_NAME])."',
					PROG_URL	='".trim($frm[PROG_URL])."',
					PROG_EMAIL =	'".trim($frm[PROG_EMAIL])."',
					PROG_LANG =	'".$frm[PROG_LANG]."',
					WEEK_START =	'".$frm[WEEK_START]."',
					TIME_OFFSET =	'".$frm[TIME_OFFSET]."',
					USER_TIMEOUT =	'".$frm[USER_TIMEOUT]."'
					";
		if(!empty($frm[ADMIN_USERNAME])) {
			$sQL .= ", ADMIN_USERNAME =	'".trim($frm[ADMIN_USERNAME])."' ";
		}
		if(!empty($frm[ADMIN_PASSWORD])) {
			$sQL .= ", ADMIN_PASSWORD =	'".trim($frm[ADMIN_PASSWORD])."' ";
		}
		$sQL = mysql_query($sQL) or die (mysql_error());
		if(mysql_affected_rows() != 0) {
			sleep(2);
			$noticemsg = "Program configuration updated";
		}else{
			$errormsg = "Nothing Updated";
		}
	}
}


	if ($langdir = @opendir($CFG->PROG_PATH . "/language")) {
		$lang_form = "<Select name=\"PROG_LANG\">\n";
		while (($file = @readdir($langdir)) !== false) {
			if ( $file != "default.inc.php" && $file !=  "." && $file != "..") {
				$val = @explode(".", $file);
				$PROG_LANG = empty($frm[PROG_LANG]) ? $row[PROG_LANG] : $frm[PROG_LANG];
				$lang_form .= "<Option value=\"$val[0]\" ".($PROG_LANG==$val[0] ? "Selected" : "").">$val[0]\n";
			}
		}
		$lang_form .= "</Select>\n";
		@closedir($langdir);
	}else{
		$errormsg = "Cannot access to <u>language</u> directory. It should be placed into language directory which is in the root of the myAgenda installation";
	}
?>
<img src="../images/spacer.gif" width="1" height="2" border="0" alt=""><br>
<table border=0 cellspacing=0 cellpadding=1 width="600" bgcolor="#333333" align="center">
 <tr>
	<td>

	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		  <table border=0 cellpadding=1 cellspacing=1 width="100%">
			  <tr>
				 <td bgcolor="#f3f3f3" align="center">
<?php
	$sQL = mysql_query("SELECT * FROM ".$CFG->Tbl_Pfix."_CONFIGS") or die(mysql_error());
	if(mysql_num_rows($sQL) != 0) {
		$row = mysql_fetch_array($sQL);
	}

?>
<form action="<?=$ME;?>" name="myform" method="post">
                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
	<td colspan="3"><font color="#FF0000" class="text"><b><?=$LANGUAGE['str_Config'];?></b></font></td>
</tr><tr>
	<td colspan="3"><font color="#FF0000"><b><?=$errormsg;?><?=$noticemsg;?></b></font></td>
</tr>
						  
						  <tr>
                              <td width="30%" >Program Name</td>
 							  <td align="center">:</td>
                              <td width="70%"><input type="text" class="txt" class="txt" name="PROG_NAME" value="<?=(empty($frm[PROG_NAME]) ? $row[PROG_NAME] : $frm[PROG_NAME]);?>" size="40"></td>
                            </tr><tr>
                              <td >Program URL</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="PROG_URL" value="<?=(empty($frm[PROG_URL]) ? $row[PROG_URL] : $frm[PROG_URL]);?>" size="40"> <font class="small">( No ending slash)</font></td>
                            </tr><tr>
                              <td >Program E-Mail</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="PROG_EMAIL" value="<?=(empty($frm[PROG_EMAIL]) ? $row[PROG_EMAIL] : $frm[PROG_EMAIL]);?>" size="40"></td>
                            </tr><tr>
                              <td >Program Language</td>
 							  <td align="center">:</td>
                              <td ><?=$lang_form;?></td>
                            </tr><tr>
                              <td >Week Start:</td>
 							  <td align="center">:</td>
							  <?php
							  $WEEK_SELECT = empty($frm[WEEK_START]) ? $row[WEEK_START] : $frm[WEEK_START];
							  ?>
                              <td ><select name="WEEK_START">
							  <Option value="0" <?=($WEEK_SELECT=='0' ? "Selected" : "");?>>Sunday
							  <Option value="1" <?=($WEEK_SELECT=='1' ? "Selected" : "");?>>Monday
							</select></td>
                            </tr><tr>
                              <td >Time Offset</td>
 							  <td align="center">:</td>
                              <td valign="top">
							  <?php
							  $TIME_OFFSET = empty($frm[TIME_OFFSET]) ? $row[TIME_OFFSET] : $frm[TIME_OFFSET];
							  ?>							
							  <table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
								<td><select name="TIME_OFFSET">
							  <Option value="0" <?=($TIME_OFFSET=="0" ? "Selected" : "");?>>0
							  <Option value="-39600" <?=($TIME_OFFSET=="-39600" ? "Selected" : "");?>>-11
							  <Option value="-36000" <?=($TIME_OFFSET=="-36000" ? "Selected" : "");?>>-10
							  <Option value="-32400" <?=($TIME_OFFSET=="-32400" ? "Selected" : "");?>>-9
							  <Option value="-28800" <?=($TIME_OFFSET=="-28800" ? "Selected" : "");?>>-8
							  <Option value="-25200" <?=($TIME_OFFSET=="-25200" ? "Selected" : "");?>>-7
							  <Option value="-21600" <?=($TIME_OFFSET=="-21600" ? "Selected" : "");?>>-6
							  <Option value="-18000" <?=($TIME_OFFSET=="-18000" ? "Selected" : "");?>>-5
							  <Option value="-14400" <?=($TIME_OFFSET=="-14400" ? "Selected" : "");?>>-4
							  <Option value="-10800" <?=($TIME_OFFSET=="-10800" ? "Selected" : "");?>>-3
							  <Option value="-7200"  <?=($TIME_OFFSET== "-7200" ? "Selected" : "");?>>-2
							  <Option value="-3600"  <?=($TIME_OFFSET== "-3600" ? "Selected" : "");?>>-1
							  <Option value="+3600"  <?=($TIME_OFFSET==  "+3600" ? "Selected" : "");?>>1
							  <Option value="+7200"  <?=($TIME_OFFSET==  "+7200" ? "Selected" : "");?>>2
							  <Option value="+10800" <?=($TIME_OFFSET== "+10800" ? "Selected" : "");?>>3
							  <Option value="+14400" <?=($TIME_OFFSET== "+14400" ? "Selected" : "");?>>4
							  <Option value="+18000" <?=($TIME_OFFSET== "+18000" ? "Selected" : "");?>>5
							  <Option value="+21600" <?=($TIME_OFFSET== "+21600" ? "Selected" : "");?>>6
							  <Option value="+25200" <?=($TIME_OFFSET== "+25200" ? "Selected" : "");?>>7
							  <Option value="+28800" <?=($TIME_OFFSET== "+28800" ? "Selected" : "");?>>8
							  <Option value="+32400" <?=($TIME_OFFSET== "+32400" ? "Selected" : "");?>>9
							  <Option value="+36000" <?=($TIME_OFFSET== "+36000" ? "Selected" : "");?>>10
							  <Option value="+39600" <?=($TIME_OFFSET== "+39600" ? "Selected" : "");?>>11
							</select></td>
	<td class="small">Use this if your server time is different than your country<BR>(your server date time is: <?=date("d-m-Y H:i:s");?>)<BR></td>
</tr>
</table>

							  </td>
                            </tr><tr>
                              <td >User Timeout</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="USER_TIMEOUT" value="<?=(empty($frm[USER_TIMEOUT]) ? $row[USER_TIMEOUT] : $frm[USER_TIMEOUT]);?>" size="40" maxlength="4" onKeyPress="event.returnValue=IsDigit()"> <font class="small">(As Seconds)</font></td>
                            </tr><tr>
                              <td >Admin Username</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="ADMIN_USERNAME" value="<?=$frm[ADMIN_USERNAME];?>" size="40" maxlength="20"></td>
                            </tr><tr>
                              <td >Admin Password</td>
 							  <td align="center">:</td>
                              <td ><input type="password" name="ADMIN_PASSWORD" size="40" maxlength="20"></td>
                            </tr><tr>
                              <td >Admin Password (Confirm)</td>
 							  <td align="center">:</td>
                              <td ><input type="password" name="ADMIN_PASSWORD2" size="40" maxlength="20"></td>
                            </tr><tr>
                              <td >&nbsp;</td>
 							  <td>&nbsp;</td>
                              <td>Please enter your current password to make changes<br>
							  <input type="password" name="CURRENTPASSWORD" size="40" maxlength="20"></td>
                            </tr><tr>
                              <td >&nbsp;</td>
                              <td >&nbsp;</td>
                              <td align="center"><input type="submit" value="<?=$LANGUAGE['strSubmit'];?>"></td>
                            </tr>
                          </table>

</form>
				 </td>
			  </tr>
			 </table>
		</td>
	 </tr>
	</table>

	</td>
 </tr>
</table>
<img src="../images/spacer.gif" width="1" height="2" border="0" alt=""><br>
<?php
function validate_form(&$frm) {
	global $LANGUAGE, $CFG;
	$msg = "";
	if(	strlen($frm[PROG_NAME]) < 2 ) {
		$msg .= "<li>Enter Program Name</li>";
	}
	if( empty($frm[PROG_URL]) || substr($frm[PROG_URL],0,7) != "http://" ){
		$msg .= "<li>Enter Program URL. Your program url should contain http://</li>";
	}
	if(!email_check($frm[PROG_EMAIL])) {
		$msg .= "<li>Enter Program Email</li>";
	}
	if($frm[USER_TIMEOUT]<600) {
		$msg .= "<li>You should give at least 10 minutes (600) to user timeout</li>";
	}
	if(!empty($frm[ADMIN_USERNAME])) {
		if(	(strlen($frm[ADMIN_USERNAME]) < 4) || (strrpos($frm[ADMIN_USERNAME],' ') > 0) ) {
			$msg .= "<li>Your username should be at least 4 chars and shouldn't contain space char.</li>";
		}
	}
	if(!empty($frm[ADMIN_PASSWORD])) {
		if(	(strlen($frm[ADMIN_PASSWORD]) < 4) || (strrpos($frm[ADMIN_PASSWORD],' ') > 0) ) {
			$msg .= "<li>Your password should be at least 4 chars and shouldn't contain space char.</li>";
		}
		if(	$frm[ADMIN_PASSWORD] != $frm[ADMIN_PASSWORD2] ) {
			$msg .= "<li>Your passwords doesn't match!</li>";
		}
	}
	$sQL = mysql_query("SELECT ADMIN_PASSWORD FROM ".$CFG->Tbl_Pfix."_CONFIGS WHERE ADMIN_PASSWORD = '".$frm[CURRENTPASSWORD]."'") or die(mysql_error());
	if(mysql_num_rows($sQL) == 0) {
		$msg .= "<li>Your current password is wrong!</li>";
	}
	return $msg;
}
?>
<?php
	include("footer.php");
?>