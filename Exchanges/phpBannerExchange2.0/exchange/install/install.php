<?
////////////////////////////////////////////////////////
//                 phpBannerExchange                  //
//                   by: Darkrose                     //
//              (darkrose@eschew.net)                 //
//                                                    //
// You can redistribute this software under the terms //
// of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of  //
// the License, or (at your option) any later         //
// version.                                           //
//                                                    //
// You should have received a copy of the GNU General //
// Public License along with this program; if not,    //
// write to the Free Software Foundation, Inc., 59    //
// Temple Place, Suite 330, Boston, MA 02111-1307 USA //
//                                                    //
//     Copyright 2004 by eschew.net Productions.      //
//   Please keep this copyright information intact.   //
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
// All: Header

include("../lang/install.php");
include("../css.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><? echo "$LANG_title"; ?></title>
<link rel="stylesheet" href="<? echo "../template/css/$css"; ?>" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" 
  marginheight="0" >
<div id="content">
<div class="main">
<center><table border="0" cellpadding="1" width="650" align="center" cellspacing="0">
<tr>
<td>
<table class="tablehead" cellpadding="5" border="0" width="100%" cellspacing="0">
<tr>
<td colspan="2"><center><div class="head">
      <? echo "$LANG_title"; ?></center></div></td>
</tr>
<td class="tablebody" colspan="2">
<div class="mainbody">
<table border="0" cellpadding="1" cellspacing="1" style="border-collapse: collapse" class="windowbg" width="90%">
  <tr>
  <?
////////////////////////////////////////////////////////
// All: Marker: Page 1

if(!$_REQUEST[install]){
	echo "$LANG_install_verbage<p>";

	//check for an existing version of the software.
	@include("../config.php");
	if($BE_build){
		echo "$LANG_install_version_found";
	}else{
		echo "$LANG_install_version_donno<p><a href=\"install.php?install=1&page=2\">$LANG_install_install</a> - $LANG_install_instdesc<p><a href=\"install.php?install=2&page=2\">$LANG_install_upgrade</a> - $LANG_install_upgdesc</a><p><a href=\"install.php?install=3&page=2\">$LANG_install_rcupgrade</a> - $LANG_install_rcupgdesc";
	}
}

////////////////////////////////////////////////////////
// All: Marker: Page 2

if($_REQUEST[page]=='2'){
	@include("../config.php");
	$basepath = realpath("../");

	if($showimage=='Y'){
		$showimage_code.="<input type=\"radio\" value=\"Y\" checked name=\"showimage_input\">$LANG_yes <input type=\"radio\" name=\"showimage_input\" value=\"N\">$LANG_no";
	}else{
		 $showimage_code.="<input type=\"radio\" value=\"Y\" name=\"showimage_input\">$LANG_yes <input type=\"radio\" checked name=\"showimage_input\" value=\"N\">$LANG_no";
	}

		if($showtext=='Y'){
		$showtext_code.="<input type=\"radio\" value=\"Y\" checked name=\"showtext_input\">$LANG_yes <input type=\"radio\" name=\"showtext_input\" value=\"N\">$LANG_no";
	}else{
		 $showtext_code.="<input type=\"radio\" value=\"Y\" name=\"showtext_input\">$LANG_yes <input type=\"radio\" checked name=\"showtext_input\" value=\"N\">$LANG_no";
	}

	if($reqbanapproval=='Y'){
		$reqbanapproval_code.="<input type=\"radio\" value=\"Y\" checked name=\"reqbanapproval_input\">$LANG_yes <input type=\"radio\" name=\"reqbanapproval_input\" value=\"N\">$LANG_no";
	}else{
		 $reqbanapproval_code.="<input type=\"radio\" value=\"Y\" name=\"reqbanapproval_input\">$LANG_yes <input type=\"radio\" checked name=\"reqbanapproval_input\" value=\"N\">$LANG_no";
	}

		if($allow_upload=='Y'){ 
			$upload_code.="<input type=\"radio\" value=\"Y\" checked name=\"allow_upload_input\">$LANG_yes <input type=\"radio\" name=\"allow_upload_input\" value=\"N\">$LANG_no</td>";
		}else{
			$upload_code.="<input type=\"radio\" value=\"Y\" name=\"allow_upload_input\">$LANG_yes <input type=\"radio\" checked name=\"allow_upload_input\" value=\"N\">$LANG_no</td>";
	}

if($anticheat==''){
			$anticheat_code="<option selected value=\"\">None</option><option value=\"cookies\">Cookies</option><option value=\"DB\">Database</option>";
		}
if($anticheat=="cookies"){
			$anticheat_code="<option selected value=\"cookies\">Cookies</option><option value=\"\">None</option><option value=\"DB\">Database</option>";
}
if($anticheat=="DB"){
			$anticheat_code="<option selected value=\"DB\">Database</option><option value=\"cookies\">Cookies</option><option value=\"\">None</option>";
}

		if($referral_program=='Y'){
			$referral_code="<input type=\"radio\" value=\"Y\" checked name=\"referral_input\">$LANG_yes <input type=\"radio\" name=\"referral_input\" value=\"N\">$LANG_no</td>";
		}else{
			$referral_code="<input type=\"radio\" value=\"Y\" name=\"referral_input\">$LANG_yes <input type=\"radio\" checked name=\"referral_input\" value=\"N\">$LANG_no</td>";
		}

	if($date_format=="1"){
			$dateformat_code="<option selected value=\"0\">dd/mm/yy</option><option value=\"1\">mm/dd/yy</option>";
	}else{
			$dateformat_code="<option selected value=\"1\">mm/dd/yy</option><option value=\"0\">dd/mm/yy</option>";
	}

	if($sellcredits=='1'){
		$sellcredits_code="<input type=\"radio\" value=\"1\" checked name=\"sellcredits_input\">$LANG_yes <input type=\"radio\" name=\"sellcredits_input\" value=\"0\">$LANG_no";
	}else{
		$sellcredits_code="<input type=\"radio\" value=\"1\" name=\"sellcredits_input\">$LANG_yes <input type=\"radio\" checked name=\"sellcredits_input\" value=\"0\">$LANG_no";
	}

	if($sendemail=='Y'){
		$sendemail_code="<input type=\"radio\" value=\"Y\" checked name=\"sendemail_input\">$LANG_yes <input type=\"radio\" name=\"sendemail_input\" value=\"N\">$LANG_no";
	}else{
		$sendemail_code="<input type=\"radio\" value=\"Y\" name=\"sendemail_input\">$LANG_yes <input type=\"radio\" checked name=\"sendemail_input\" value=\"N\">$LANG_no";
	}

	if($usemd5=='Y'){
		$usemd5_code="<input type=\"radio\" value=\"Y\" checked name=\"md5_input\">$LANG_yes <input type=\"radio\" name=\"md5_input\" value=\"N\">$LANG_no";
	}else{
		$usemd5_code="<input type=\"radio\" value=\"Y\" name=\"md5_input\">$LANG_yes <input type=\"radio\" checked name=\"md5_input\" value=\"N\">$LANG_no";
	}

	if($use_gzhandler=='1'){
		$usegzhandler_code="<input type=\"radio\" value=\"1\" checked name=\"use_gzhandler_input\">$LANG_yes <input type=\"radio\" name=\"use_gzhandler_input\" value=\"0\">$LANG_no";
	}else{
		$usegzhandler_code="<input type=\"radio\" value=\"1\" name=\"use_gzhandler_input\">$LANG_yes <input type=\"radio\" checked name=\"use_gzhandler_input\" value=\"0\">$LANG_no";
	}

	if($log_clicks=='Y'){
		$logclicks_code="<input type=\"radio\" value=\"Y\" checked name=\"log_clicks_input\">$LANG_yes <input type=\"radio\" name=\"log_clicks_input\" value=\"N\">$LANG_no";
	}else{
		$logclicks_code="<input type=\"radio\" value=\"Y\" name=\"log_clicks_input\">$LANG_yes <input type=\"radio\" checked name=\"log_clicks_input\" value=\"N\">$LANG_no";
	}

	if($use_dbrand=='1'){
		$use_dbrand_code="<input type=\"radio\" value=\"1\" checked name=\"use_dbrand_input\">$LANG_yes <input type=\"radio\" name=\"use_dbrand_input\" value=\"0\">$LANG_no";
	}else{
		$use_dbrand_code="<input type=\"radio\" value=\"1\" name=\"use_dbrand_input\">$LANG_yes <input type=\"radio\" checked name=\"use_dbrand_input\" value=\"0\">$LANG_no";
	}

//adding a space so it will show up right on the form..
$upload_path="$upload_path ";
$basepath="$basepath ";

?>
		<form method="POST" action="install.php?install=<? echo "$_REQUEST[install]"; ?>&page=3"><table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="90%">
    <tr>
      <td colspan=2><? echo "$LANG_varedit_dirs"; ?><p>
	  <? if($_REQUEST[install]=='2'){
	echo "$LANG_install_oldvarupgrade";
}
?>
&nbsp;</td>
    </tr>
	    <tr><td class="tablehead" colspan="2"><center><div class="varhead"><? echo "$LANG_varedit_dbhead"; ?></div></center></td></tr>
		<tr>
      <td width="148" height="23" class="tablebody"><? echo "$LANG_varedit_dbhost"; ?>:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="dbhost_input" value="<? echo "$dbhost"; ?>" size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23" class="tablebody"><? echo "$LANG_varedit_dblogin"; ?>:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="dbuser_input" value="<? echo "$dbuser"; ?>" size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23" class="tablebody"><? echo "$LANG_varedit_dbpass"; ?>:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="dbpass_input" value="<? echo "$dbpass"; ?>"  size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23" class="tablebody"><? echo "$LANG_varedit_dbname"; ?>:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="dbname_input"  value="<? echo "$dbname"; ?>" size="28"></td>
    </tr>
	<tr><td class="tablehead" colspan="2"><center><div class="varhead"><? echo "$LANG_varedit_pathing"; ?></center></td></tr>
    <tr>
      <td width="148" height="23" class="tablebody"><? echo "$LANG_varedit_baseurl"; ?>:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="baseurl_input"  value="<? echo "$baseurl" ?>" size="50"><br>(<? echo "$LANG_varedit_baseurl_note"; ?>)</td>
    </tr>
	<tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_basepath"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="basepath_input"  value="<? echo "$basepath"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_exchangename"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="exchangename_input"  value="<? echo "$exchangename"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="23" class="tablebody"><? echo "$LANG_varedit_sitename"; ?>:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="sitename_input"  value="<? echo "$sitename"; ?>" size="50"> </td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_adminname"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="adminname_input"  value="<? echo "$adminname"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_adminemail"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="ownermail_input"  value="<? echo "$ownermail"; ?>" size="50"></td>
    </tr>
    <tr>
<tr><td class="tablehead" colspan="2"><center><div class="varhead"><? echo "$LANG_varedit_bannerhead"; ?></div></center></td></tr>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_width"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="bannerwidth_input"  value="<? echo "$bannerwidth"; ?>" size="4"> <? echo "$LANG_varedit_pixels"; ?></td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_height"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="bannerheight_input"  value="<? echo "$bannerwidth"; ?>" size="4"> <? echo "$LANG_varedit_pixels"; ?></td>
    </tr>
		      <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_defrat"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="steexp_input" value="<? echo "$steexp"; ?>" size="4">:<input class="formbox" type="text" name="banexp_input"  value="<? echo "$banexp"; ?>" size="4"> <? echo "$LANG_varedit_defrat_msg"; ?></td>
    </tr>
	  <tr>
      <td width="148" height="19" class="tablebody"><? echo "$LANG_varedit_showimage"; ?>:</td>
      <td width="417" height="19" class="tablebody">
<? echo "$showimage_code"; ?>
	  </td>
    </tr>
		<tr>
      <td width="148" height="19" class="tablebody"><? echo "$LANG_varedit_imgpos"; ?>:</td>
      <td width="417" height="19" class="tablebody">
		 <input type="radio" value="L" checked name="imagepos_input" class="formbox" ><? echo "$LANG_left"; ?>
		 <input type="radio" value="R" name="imagepos_input" class="formbox" ><? echo "$LANG_right"; ?>
		 <input type="radio" value="T" name="imagepos_input" class="formbox" ><? echo "$LANG_top"; ?>
		 <input type="radio" value="B" name="imagepos_input" class="formbox" ><? echo "$LANG_bottom"; ?>
	  </td>
    </tr>
    <tr>
      <td width="148" height="23" class="tablebody"><? echo "$LANG_varedit_imageurl"; ?>:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="imageurl_input" value="<? echo "$imageurl"; ?>" size="28"> (<? echo "$LANG_varedit_imageurl_msg"; ?>)</td>
    </tr>
	<tr>
      <td width="148" height="19" class="tablebody"><? echo "$LANG_varedit_showtext"; ?></td>
      <td width="417" height="19" class="tablebody">
		 <? echo "$showtext_code"; ?> || <? echo "$LANG_varedit_exchangetext"; ?>: <input type="text" class="formbox" name="exchangetext_input"  value="<? echo "$exchangetext"; ?>" size="30">
	  </td>
    </tr>
		<tr>
      <td width="148" height="19" class="tablebody"><? echo "$LANG_varedit_reqbanapproval"; ?>:</td>
      <td width="417" height="19" class="tablebody">
<? echo "$reqbanapproval_code"; ?>
	  </td>
    </tr>
    <tr>
      <td width="148" height="18" class="tablebody"><? echo "$LANG_varedit_upload"; ?>:</td>
      <td width="417" height="18" class="tablebody">
<? echo "$upload_code"; ?>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_maxsize"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="max_filesize_input"  value="<? echo "$max_filesize"; ?>" size="8"></td>
    </tr>
	    <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_uploadpath"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input type="text" class="formbox" name="upload_path_input"  value="<? echo "$upload_path"; ?>" size="50"></td>
    </tr>
		<tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_upurl"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="banner_dir_url_input"  value="<? echo "$banner_dir_url"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody"><? echo "$LANG_varedit_maxbanners"; ?>:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="maxbanners_input"  value="<? echo "$maxbanners"; ?>" size="8"></td>
    </tr>

	
<tr><td class="tablehead" colspan="2"><center><div class="varhead"><? echo "$LANG_varedit_anticheathead"; ?></div></center></td></tr>
		<tr>
      <td width="148" height="22"><? echo "$LANG_varedit_anticheat"; ?>:</td>
<td><select class="formbox" name="anticheat_input">
<? echo "$anticheat_code"; ?>
</select>
</td>
</tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_duration"; ?>:</td>
      <td width="417" height="22"><input class="formbox" type="text" name="cookielength_input"  value="<? echo "$expiretime"; ?>" size="4"> <? echo "$LANG_varedit_duration_msg"; ?></td>
    </tr>
		<tr><td class="tablehead" colspan="2"><center><div class="varhead"><? echo "$LANG_varedit_refncredits"; ?></div></center></td></tr>
	<tr>
      <td width="148" height="18"><? echo "$LANG_varedit_referral"; ?>:</td>
      <td width="417" height="18">
<? echo "$referral_code"; ?>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_bounty"; ?>:</td>
      <td width="417" height="22"><input class="formbox" type="text" name="bounty_input"  value="<? echo "$referral_bounty"; ?>" size="8"></td>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_starting_credits"; ?>:</td>
      <td width="417" height="22"><input class="formbox" type="text" name="startcredits_input"  value="<? echo "$startcredits"; ?>" size="4"></td>
    </tr>
		<tr>
      <td width="148" height="19"><? echo "$LANG_varedit_sellcredits"; ?>:</td>
      <td width="417" height="19">
<? echo "$sellcredits_code"; ?>
	  </td>
    </tr>
<tr><td class="tablehead" colspan="2"><center><div class="varhead"><? echo "$LANG_varedit_misc"; ?></div></center></td></tr>
	    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_topnum"; ?>:</td>
      <td width="417" height="22"><input class="formbox" type="text" name="topnum_input"  value="<? echo "$topnum"; ?>" size="4"> <? echo "$LANG_varedit_topnum_other"; ?>.</td>
    </tr>
	
    <tr>
      <td width="148" height="18"><? echo "$LANG_varedit_sendemail"; ?>:</td>
      <td width="417" height="18">
<? echo "$sendemail_code"; ?></td>
    </tr>
    <tr>
      <td width="148" height="18"><? echo "$LANG_varedit_usemd5"; ?>:</td>
      <td width="417" height="18">
<? echo "$usemd5_code"; ?></td>
    </tr>
	<tr>
      <td width="148" height="19"><? echo "$LANG_varedit_usegzhandler"; ?>:</td>
      <td width="417" height="19">
<? echo "$usegzhandler_code"; ?>
	  </td>
    </tr>

		<tr>
      <td width="148" height="19"><? echo "$LANG_varedit_logclicks"; ?></td>
      <td width="417" height="19">
<? echo "$logclicks_code"; ?>
	  </td>
    </tr>

	<tr>
	      <td width="148" height="19"><? echo "$LANG_varedit_usedbrand"; ?></td>
      <td width="417" height="19">
<? echo "$use_dbrand_code"; ?> || <? echo "$LANG_varedit_usedbrand_warn"; ?>
	  </td>
    </tr>
	<tr>
	      <td width="148" height="22"><? echo "$LANG_varedit_dateformat"; ?>:</td>
<td><select class="formbox" name="date_format_input">
<? echo "$dateformat_code"; ?>
</select>
	    <tr>
	<input type="hidden" name="service_input" value="paypal">
      <td width="148" height="19"><input class="button" type="submit" value="<? echo "$LANG_varedit_submit"; ?>" name="submit">  &nbsp;<input class="button" type="reset" value=" <? echo "$LANG_varedit_reset"; ?>" name="reset"></td>
      <td width="417" height="19">&nbsp;</td>
	  </tr>
  </table>
</form>
<? } 

////////////////////////////////////////////////////////
// All: Marker: Page 3

if($_REQUEST[page]=='3'){

$basepath_input=ereg_replace(" ", "", "$_REQUEST[basepath_input]");
$upload_path_input=ereg_replace(" ", "", "$_REQUEST[upload_path_input]");

	$BE_build="013105";
		
		// Write out the config file.
		$config_data = '<?php'."\n\n";
		$config_data .= "//\n// phpBannerExchange auto-generated config file\n// Change this file at your own risk!\n//\n\n";
		$config_data .= "// DO NOT CHANGE THIS OR YOUR HANDS WILL FALL OFF\n";
		$config_data .= "// (and it could screw up future installation processes)\n";
		$config_data .= '$BE_build = "' . $BE_build .'";' . "\n\n";
		$config_data .= '$dbhost = "' . $_REQUEST[dbhost_input] . '";' . "\n";
		$config_data .= '$dbuser = "' . $_REQUEST[dbuser_input] . '";' . "\n";
		$config_data .= '$dbpass = "' . $_REQUEST[dbpass_input] . '";' . "\n";
		$config_data .= '$dbname = "' . $_REQUEST[dbname_input] . '";' . "\n\n";

		$config_data .= '$baseurl = "' . $_REQUEST[baseurl_input] . '";' . "\n";
		$config_data .= '$basepath = "' . $basepath_input . '";' . "\n";
		$config_data .= '$exchangename = "' . $_REQUEST[exchangename_input] . '";' . "\n";
		$config_data .= '$sitename = "' . $_REQUEST[sitename_input] . '";' . "\n";
		$config_data .= '$adminname = "' . $_REQUEST[adminname_input] . '";' . "\n";
		$config_data .= '$ownermail = "' . $_REQUEST[ownermail_input] . '";' . "\n\n";

		$config_data .= '$bannerwidth = "' . $_REQUEST[bannerwidth_input] . '";' . "\n";
		$config_data .= '$bannerheight = "' . $_REQUEST[bannerheight_input] . '";' . "\n";
		$config_data .= '$steexp = "' . $_REQUEST[steexp_input] . '";' . "\n";
		$config_data .= '$banexp = "' . $_REQUEST[banexp_input] . '";' . "\n";
		$config_data .= '$showimage = "' . $_REQUEST[showimage_input] . '";' . "\n";
		$config_data .= '$imagepos = "' . $_REQUEST[imagepos_input] . '";' . "\n";
		$config_data .= '$imageurl = "' . $_REQUEST[imageurl_input] . '";' . "\n";
		$config_data .= '$showtext = "' . $_REQUEST[showtext_input] . '";' . "\n";
		$config_data .= '$exchangetext = "' . $_REQUEST[exchangetext_input] . '";' . "\n";
		$config_data .= '$reqbanapproval = "' . $_REQUEST[reqbanapproval_input] . '";' . "\n";
		$config_data .= '$allow_upload = "' . $_REQUEST[allow_upload_input] . '";' . "\n";
		$config_data .= '$max_filesize = "' . $_REQUEST[max_filesize_input] . '";' . "\n";
		$config_data .= '$upload_path = "' . $upload_path_input . '";' . "\n";
		$config_data .= '$banner_dir_url = "' . $_REQUEST[banner_dir_url_input] . '";' . "\n";
		$config_data .= '$maxbanners  = "' . $_REQUEST[maxbanners_input] . '";' . "\n\n";

		$config_data .= '$anticheat = "' . $_REQUEST[anticheat_input] . '";' . "\n";
		$config_data .= '$expiretime = "' . $_REQUEST[cookielength_input] . '";' . "\n";

		$config_data .= '$referral_program = "' . $_REQUEST[referral_input] . '";' . "\n";
		$config_data .= '$referral_bounty = "' . $_REQUEST[bounty_input] . '";' . "\n";
		$config_data .= '$startcredits = "' . $_REQUEST[startcredits_input] . '";' . "\n";
		$config_data .= '$sellcredits = "' . $_REQUEST[sellcredits_input] . '";' . "\n";
		$config_data .= '$commerce_service = "' . $_REQUEST[service_input] . '";' . "\n\n";

		$config_data .= '$topnum = "' . $_REQUEST[topnum_input] . '";' . "\n";
		$config_data .= '$sendemail = "' . $_REQUEST[sendemail_input] . '";' . "\n";
		$config_data .= '$usemd5 = "' . $_REQUEST[md5_input] . '";' . "\n";
		$config_data .= '$use_gzhandler = "' . $_REQUEST[use_gzhandler_input] . '";' . "\n";
		$config_data .= '$log_clicks = "' . $_REQUEST[log_clicks_input] . '";' . "\n";
		$config_data .= '$use_dbrand = "' . $_REQUEST[use_dbrand_input] . '";' . "\n";
		$config_data .= '$date_format = "' . $_REQUEST[date_format_input] . '";' . "\n\n";
		$config_data .= "// Magic Quotes compatibility.. \n";
		$config_data .= '$exchangename = stripslashes($exchangename);' ."\n";
		$config_data .= '$sitename = stripslashes($sitename);' . "\n\n";
		$config_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!

	@umask(0011);
	$fp=fopen('../config.php', 'wb');

	if(!@fputs($fp, $config_data, strlen($config_data))){
		//try again, this time, let's try chmodding..
		if(!@chmod("$basepath_input/config.php", 0777)){
			echo "$LANG_fput_chmod<p>";
		}

		echo "$LANG_fput_error_config<p>";
	}else{
		fclose($fp);
		echo "$LANG_fput_success<p>";
		echo "<a href=\"install.php?install=$_REQUEST[install]&page=4\">$LANG_continue</a>";
	}
}
////////////////////////////////////////////////////////
// All: Marker: Page 4

if($_REQUEST[page]=='4'){
	if($_REQUEST[install]=='1' or $_REQUEST[install]=='2'){
		
		include("../config.php");

		if(!$db=@mysql_connect("$dbhost","$dbuser","$dbpass")){
			echo "$LANG_db_noconnect";
			exit();
		}

		if(!@mysql_select_db($dbname,$db)){
			echo "$LANG_db_problem";
			exit();
		}

		mysql_query("DROP TABLE IF EXISTS `banneradmin`");
		mysql_query("CREATE TABLE `banneradmin` (`id` int(11) NOT NULL auto_increment,`adminuser` varchar(15) NOT NULL default '',`adminpass` varchar(255) NOT NULL default '',PRIMARY KEY  (`id`),UNIQUE KEY `id` (`id`,`adminuser`))");

		mysql_query("DROP TABLE IF EXISTS `bannercats`");
		mysql_query("CREATE TABLE `bannercats` (`id` int(7) NOT NULL auto_increment,`catname` varchar(50) NOT NULL default '',PRIMARY KEY  (`id`))");

		mysql_query("DROP TABLE IF EXISTS `bannerclicklog`");
		mysql_query("CREATE TABLE `bannerclicklog` (`id` int(11) NOT NULL auto_increment,`siteid` int(11) NOT NULL default '0',`clickedtosite` int(11) NOT NULL default '0',`bannerid` int(11) NOT NULL default '0',`ip` varchar(255) NOT NULL default '',`page` int(11) NOT NULL default '0',`time` int(11) NOT NULL default '0',PRIMARY KEY  (`id`))");

		mysql_query("DROP TABLE IF EXISTS `bannercommerce`");
		mysql_query("CREATE TABLE `bannercommerce` (`productid` int(11) NOT NULL auto_increment,`productname` text NOT NULL,`credits` decimal(14,0) NOT NULL default '0',`price` decimal(12,2) NOT NULL default '0.00',`purchased` int(11) NOT NULL default '0',UNIQUE KEY `productid` (`productid`))");

		mysql_query("DROP TABLE IF EXISTS `bannerconfig`");
		mysql_query("CREATE TABLE `bannerconfig` (`name` varchar(255) NOT NULL default '',`data` longtext NOT NULL,PRIMARY KEY  (`name`))");

		mysql_query("DROP TABLE IF EXISTS `bannerlogs`");
		mysql_query("CREATE TABLE `bannerlogs` (`uid` int(11) NOT NULL default '0',`ipaddr` text NOT NULL,`page` int(11) NOT NULL default '0',`timestamp` text NOT NULL)");
		
		mysql_query("DROP TABLE IF EXISTS `bannerfaq`");
		mysql_query("CREATE TABLE `bannerfaq` (`id` int(11) NOT NULL AUTO_INCREMENT ,`question` longtext NOT NULL ,`answer` longtext NOT NULL,UNIQUE KEY `id` (`id`))");

		mysql_query("DROP TABLE IF EXISTS `bannerlogs`");
		mysql_query("CREATE TABLE `bannerlogs` (`uid` int(11) NOT NULL default '0',`ipaddr` text NOT NULL,`page` int(11) NOT NULL default '0',`timestamp` text NOT NULL)");

		mysql_query("DROP TABLE IF EXISTS `bannerpromologs`");
		mysql_query("CREATE TABLE `bannerpromologs` (`id` int(11) NOT NULL auto_increment,`uid` int(11) NOT NULL default '0',`promoid` int(11) NOT NULL default '0',`usedate` int(11) NOT NULL default '0',PRIMARY KEY  (`id`))");

		mysql_query("DROP TABLE IF EXISTS `bannerpromos`");
		mysql_query("CREATE TABLE `bannerpromos` (`promoid` int(11) NOT NULL auto_increment,`promoname` varchar(255) NOT NULL default '',`promocode` varchar(255) NOT NULL default '',`promotype` int(11) NOT NULL default '0',`promonotes` text,`promovals` decimal(11,2) NOT NULL default '0.00',`promocredits` int(11) NOT NULL default '0',`promoreuse` tinyint(4) NOT NULL default '0',`promoreuseint` int(11) NOT NULL default '0',`promousertype` tinyint(4) NOT NULL default '0',`ptimestamp` int(11) NOT NULL default '0',`promostatus` tinyint(4) NOT NULL default '0', PRIMARY KEY  (`promoid`))");

		mysql_query("DROP TABLE IF EXISTS `bannerrefs`");
		mysql_query("CREATE TABLE `bannerrefs` (`id` int(11) NOT NULL auto_increment,`uid` int(11) NOT NULL default '0',`refid` tinyint(4) NOT NULL default '0',`given` tinyint(4) NOT NULL default '0',PRIMARY KEY  (`id`))");

		mysql_query("DROP TABLE IF EXISTS `bannersales`");
		mysql_query("CREATE TABLE `bannersales` (`invoice` int(11) NOT NULL default '0',`uid` int(11) NOT NULL default '0',`item_number` int(11) NOT NULL default '0',`payment_status` text NOT NULL,`payment_gross` text NOT NULL,`payer_email` varchar(200) NOT NULL default '',`timestamp` int(14) NOT NULL default '0')");

		mysql_query("DROP TABLE IF EXISTS `bannerstats`");
		mysql_query("CREATE TABLE `bannerstats` (`uid` int(11) NOT NULL default '0',`category` int(11) NOT NULL default '0',`exposures` int(11) NOT NULL default '0',`credits` int(11) NOT NULL default '0',`clicks` int(11) NOT NULL default '0',`siteclicks` int(11) NOT NULL default '0',`approved` tinyint(4) NOT NULL default '0',`defaultacct` tinyint(4) NOT NULL default '0',`histexposures` int(11) NOT NULL default '0',`raw` tinyint(4) NOT NULL default '0',`startdate` int(11) NOT NULL default '0',PRIMARY KEY  (`uid`))");

		mysql_query("DROP TABLE IF EXISTS `bannerurls`");
		mysql_query("CREATE TABLE `bannerurls` (`id` int(11) NOT NULL auto_increment,`bannerurl` varchar(200) NOT NULL default '',`targeturl` varchar(255) NOT NULL default '',`clicks` tinyint(4) NOT NULL default '0',`views` int(11) NOT NULL default '0',`uid` int(11) NOT NULL default '0',`pos` int(11) NOT NULL default '0',PRIMARY KEY  (`id`))");

		mysql_query("DROP TABLE IF EXISTS `banneruser`");
		mysql_query("CREATE TABLE `banneruser` (`id` int(11) NOT NULL auto_increment,`login` varchar(20) NOT NULL default '',`pass` varchar(255) NOT NULL default '',`name` varchar(200) NOT NULL default '',`email` varchar(100) NOT NULL default '',`newsletter` tinyint(4) NOT NULL default '0',PRIMARY KEY  (`id`),UNIQUE KEY `id` (`id`,`login`))");
		
		mysql_query("INSERT INTO `bannercats` (`id`, `catname`) VALUES (1, 'Default')");

		mysql_query("INSERT INTO `bannerurls` (`id`, `bannerurl`, `targeturl`, `clicks`, `views`, `uid`, `pos`) VALUES (1, 'http://www.eschew.net/scripts/exchange.gif', 'http://www.eschew.net/scripts/', 0, 0, 0, 0)");

		mysql_query("INSERT INTO `bannerconfig` (`name`, `data`) VALUES ('cou', 'All members of {exchange_name} are required to agree to the following Terms and Conditions. Anyone determined by {exchange_name} administrators to have violated these terms and conditions is subject to being banned from {exchange_name} without any obligation by {site_name} to redeem any earned credits.<p><b>Members agree to include the full unmodified HTML code provided by us for displaying banner ads on their web site, in as many or few pages as they wish. We reserves the right to verify the correctness of its HTML code through any means.</b><p>Members acknowledge that {exchange_name} is a free service. At any time, this free service can be revoked for any reason the adminstrators see fit.<p>A member may not artificially inflate traffic counts to his/her site using a device, program, or other means. A member may not insert more than one exchange HTML code on any page. A member may not include the banner exchange HTML code on any pages that automatically reload or go to another page without interaction from the user (i.e., client pull or server push) or on a page which is inaccessible to the general surfing population including but not limited to pop-up windows and hidden frames. A member may not place his/her banner exchange HTML code on pages that are unrelated to the site being advertised. Anyone found in violation of these policies will be banned from {exchange_name} and will forfeit any credits pending on his/her account.<p>Members acknowledge and agree that their web site information (name, URL, traffic counts, etc.) may be utilized by {site_name}. Possible uses include (but are not limited to) lists of the busiest sites, lists of member sites, etc.<p>All members agree to utilize the services of this banner exchange program at their own risk. {exchange_name}, its administrators, {site_name}e and it\'s partners cannot be held liable for any damage or loss of information that may occur from the use of the services of this banner exchange program.<p>Although we will make a reasonable effort to provide a high standard of quality for our services, we make no guarantees of any kind regarding the dependability, accuracy, or timeliness of the services.<p><b>Anyone found in deliberate violation of these terms and conditions is subject to being banned from {exchange_name} and forfeit any credits pending on his/her account.</b><p>You agree to defend, indemnify and hold {exchange_name} and its sponsors ({site_name}) harmless from and against any and all claims, losses, liability costs and expenses (including but not limited to attorneys\' fees) arising from your violation of this Agreement or any third-party\'s rights, including but not limited to infringement of any copyright, violation of any proprietary right and invasion of any privacy rights. This obligation will survive any termination of this Agreement.<p>')");

		mysql_query("INSERT INTO `bannerconfig` (`name`, `data`) VALUES ('rules', 'This is the default ruleset.<br />\r\n<br />\r\nYou should edit these rules to suit your exchange. Rules can be things like no porn, check with your host to make sure you can serve images without being hit with an anti-leech tool, and the like.<br />\r\n<br />\r\n<b>You may edit these rules by clicking the Edit Rules option in the phpBannerExchange 2.0 Administration Control Panel.</b>')");
		mysql_query("INSERT INTO `bannerconfig` (`name`, `data`) VALUES ('exchangestate', '0')");


		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (1, 'What is a Banner Exchange?', 'A Banner Exchange is a great way to get traffic to your site. In return for showing other banners on your site, you earn the right to show your banner on another site in the exchange. For each time you show another banner on your site, you earn credits, which are redeemed for exposure on another site in the exchange.')");
		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (2, 'How does a Banner Exchange work?', 'When you show other sites banners on your site, you earn credits. Credits are automatically redeemed for exposures on another site in the exchange.')");
		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (3, 'How long does it take to set up an account?', 'Accounts can be set up in seconds! Click the <b>Sign Up</b> link to the left and sign up for an account. Once you are signed up, you can begin earning exposures immediately, but the Exchange Administrators must first validate your account before you can begin redeeming exposures.')");
		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (4, 'How do I earn credits?', 'Credits are earned by showing other banners on your site! Once you have created an account and logged in to your Control Panel, you will have an option to <b>Get HTML</b>. Use this option to get the HTML code you need to put in your web page to start earning credits!')");
		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (5, 'What is an exchange ratio?', 'An exchange ratio describes how many exposures of other banners on your site to earn a number of exposures on another site. <br />\r\n<br />\r\nFor example, a 3:1 ratio means that for every 3 exposures of an exchange banner on your site, you earn 1 exposure on another site. A 1:1 ratio means for every banner you show on your site, you get an exposure elsewhere.')");
		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (6, 'What is a Click-thru ratio?', 'This ratio indicates an average of how many banners are displayed on your site before someone clicks on a banner. ')");
		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (7, 'How do categories work?', 'Categories are a great way to insure visitors on your site get banners that are appropriate for the content. Each account can define one category that best describes its content. Most websites these days really would fall under multiple categories, so choose the best one for your site.<br />\r\n<br />\r\nYou can also display banners for only a specific category on your page. The <b>Get HTML</b> page in your Control Panel contains details for doing this.')");
		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (8, 'Why do I have to wait for my banner to be approved?', 'This is so we can insure your site meets the content standards of the Exchange. This insures that all sites in the exchange meet the scope of the exchange.')");
		mysql_query("INSERT INTO `bannerfaq` (`id`, `question`, `answer`) VALUES (9, 'I lost my password, what can I do?', 'Use the <b>Lost Password?</b> link to recover your password.')");

		echo "$LANG_tables_created";
	}

	if($_REQUEST[install]=='3'){
		include("../config.php");

		if(!$db=@mysql_connect("$dbhost","$dbuser","$dbpass")){
			echo "$LANG_db_noconnect";
			exit();
		}

		if(!@mysql_select_db($dbname,$db)){
			echo "$LANG_db_problem";
			exit();
		}

		echo "$LANG_upgrade_db<p>";
		mysql_query("ALTER TABLE `bannercommerce` CHANGE `price` `price` DECIMAL( 12, 2 ) DEFAULT '0.00' NOT NULL");
		mysql_query("ALTER TABLE `bannercommerce` CHANGE `credits` `credits` DECIMAL( 12, 2 ) DEFAULT '0.00' NOT NULL");
		mysql_query("ALTER TABLE `bannersales` ADD `timestamp` INT( 14 ) NOT NULL");

		mysql_query("CREATE TABLE `bannerpromos` (`promoid` INT NOT NULL ,`promoname` VARCHAR( 255 ) NOT NULL ,`promocode` VARCHAR( 255 ) NOT NULL ,`promotype` INT NOT NULL ,`promovalue` TEXT NOT NULL ,`promocredits` INT NOT NULL ,`promostart` INT NOT NULL ,`promoend` INT NOT NULL ,`promostatus` INT NOT NULL ,PRIMARY KEY ( `promoid` ))");

		mysql_query("CREATE TABLE `bannerpromologs` (`id` INT NOT NULL ,`uid` INT NOT NULL ,`promoid` INT NOT NULL ,`usedate` INT NOT NULL,PRIMARY KEY ( `id` ))");

		echo "$LANG_upgrade_done";
	}
	echo "<p><a href=\"install.php?install=$_REQUEST[install]&page=5\">$LANG_continue</a>";
}

////////////////////////////////////////////////////////
// All: Marker: Page 5

if($_REQUEST[page]=='5'){
	if($_REQUEST[install]=='1' or $_REQUEST[install]=='2'){
		echo "$LANG_admin_add_instructions";
		echo "<p><form method=\"POST\" action=\"install.php?install=1&page=6\"></td><tr><td width=\"22%\">$LANG_admin_login:</td><td width=\"78%\"><input class=\"formbox\" type=\"text\" name=\"newlogin\" size=\"50\"></td></tr><td width=\"22%\">$LANG_admin_pass:</td><td width=\"78%\"><input class=\"formbox\" type=\"password\" name=\"pass1\" size=\"50\"></td></tr><tr><td width=\"22%\">$LANG_admin_pass ($LANG_again):</td><td width=\"78%\"><input class=\"formbox\" type=\"password\" name=\"pass2\" size=\"50\"></td></td></tr><tr><td colspan=2 align=center><br><br><input class=\"button\" type=\"submit\" value=\" $LANG_varedit_submit \" name=\"submit\">&nbsp; &nbsp;<input class=\"button\" type=\"reset\" value=\"$LANG_varedit_reset\"></form></td></tr></table>";
	}

	if($_REQUEST[install] == '3'){
		echo "$LANG_install_complete";
	}
}
////////////////////////////////////////////////////////
// All: Marker: Page 5

if($_REQUEST[page]=='6'){
	include("../config.php");

	if(!$db=@mysql_connect("$dbhost","$dbuser","$dbpass")){
		echo "$LANG_db_noconnect";
		exit();
	}

	if(!@mysql_select_db($dbname,$db)){
		echo "$LANG_db_problem";
		exit();
	}
	$error='0';

	if($_REQUEST[pass1] != $_REQUEST[pass2]){
		echo "$LANG_password_mismatch";
		$error=1;
	}		
	if($error=='0'){
		$pass=$_REQUEST[pass1];
		$login=$_REQUEST[newlogin];
		if($usemd5 == Y){
			$encpass = md5($pass);		
			$insert=mysql_query("insert into banneradmin values ('','$login','$encpass')");
		}else{		
			$insert=mysql_query("insert into banneradmin values ('','$login','$pass')");	
		}	
	
		if($_REQUEST[install]=='1'){
			echo "$LANG_install_complete";
		}

		if($_REQUEST[install]=='2'){
			echo "$LANG_install_oldverupg";
		}
	}
}
	?>