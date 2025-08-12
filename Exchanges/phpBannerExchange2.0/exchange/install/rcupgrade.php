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

include("../lang/install.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><? echo "$LANG_upgr_title"; ?></title>
<link rel="stylesheet" href="../template/style.css" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" 
  marginheight="0" >
<div id="content">
<div class="main">
<center><table border="0" cellpadding="1" width="650" align="center" cellspacing="0">
<tr>
<td>
<table class="tablehead" cellpadding="5" border="1" width="100%" cellspacing="0">
<tr>
<td colspan="2"><center><div class="head">
      <? echo "$LANG_upgr_title"; ?></center></div></td>
</tr>
<td class="tablebody" colspan="2">
<div class="mainbody">
<table border="0" cellpadding="1" cellspacing="1" style="border-collapse: collapse" class="windowbg" width="90%">
  <tr>
    <td align="center"><a class="heading"><b><? echo "$LANG_upgr_title"; ?></b></td>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="90%" class=windowbg>
<tr>
<?
if (!isset($upgrade)){
?>
<html>
	<head>
	<title><? echo "$LANG_upgr_title"; ?></title>
</head>
<body>
<? echo "$LANG_rcupgrade_verbage"; 
echo "<br><A href=\"$PHP_SELF?upgrade=1\">$LANG_cont</a>";
}
elseif ($upgrade==1){
	echo "<h3>Define Variables</h3>";
		@include("../config.php");
		?>
		<form method="POST" action="<? echo"$PHP_SELF?upgrade=2";?>"><table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="90%">
    <tr>
      <td colspan=2><? echo "$LANG_varedit_dirs"; ?><br>
&nbsp;</td>
    </tr>
	    <tr>
      <td width="148" height="23"><? echo "$LANG_varedit_dbhost"; ?>:</td>
      <td width="417" height="23"><input type="text" name="dbhost_input" value="<? echo "$dbhost"; ?>" size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23"><? echo "$LANG_varedit_dblogin"; ?>:</td>
      <td width="417" height="23"><input type="text" name="dbuser_input" value="<? echo "$dbuser"; ?>" size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23"><? echo "$LANG_varedit_dbpass"; ?>:</td>
      <td width="417" height="23"><input type="text" name="dbpass_input" value="<? echo "$dbpass"; ?>"  size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23"><? echo "$LANG_varedit_dbname"; ?>:</td>
      <td width="417" height="23"><input type="text" name="dbname_input"  value="<? echo "$dbname"; ?>" size="28"></td>
    </tr>
    <tr>
      <td width="148" height="23"><? echo "$LANG_varedit_baseurl"; ?>:</td>
      <td width="417" height="23"><input type="text" name="baseurl_input"  value="<? echo "$baseurl"; ?>" size="50"><br>(<? echo "$LANG_varedit_baseurl_note"; ?>)</td>
    </tr>
	<tr>
	<? $basepath=realpath("../"); ?>
      <td width="148" height="22"><? echo "$LANG_varedit_basepath"; ?>:</td>
      <td width="417" height="22"><input type="text" name="basepath_input"  value="<? echo "$basepath"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_exchangename"; ?>:</td>
      <td width="417" height="22"><input type="text" name="exchangename_input"  value="<? echo "$exchangename"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="23"><? echo "$LANG_varedit_sitename"; ?>:</td>
      <td width="417" height="23"><input type="text" name="sitename_input"  value="<? echo "$sitename"; ?>" size="50"> </td>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_adminname"; ?>:</td>
      <td width="417" height="22"><input type="text" name="adminname_input"  value="<? echo "$adminname"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_adminemail"; ?>:</td>
      <td width="417" height="22"><input type="text" name="ownermail_input"  value="<? echo "$ownermail"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="19">&nbsp;</td>
      <td width="417" height="19">&nbsp;</td>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_width"; ?>:</td>
      <td width="417" height="22"><input type="text" name="bannerwidth_input"  value="<? echo "$bannerwidth"; ?>" size="4"> <? echo "$LANG_varedit_pixels"; ?></td>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_height"; ?>:</td>
      <td width="417" height="22"><input type="text" name="bannerheight_input"  value="<? echo "$bannerheight"; ?>" size="4"> <? echo "$LANG_varedit_pixels"; ?></td>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "Starting Credits"; ?>:</td>
      <td width="417" height="22"><input type="text" name="startcredits_input"  value="<? echo "$startcredits"; ?>" size="4"></td>
    </tr>
		<tr>
      <td width="148" height="22"><? echo "$LANG_varedit_cookies"; ?>:</td>
		  <td width="417" height="19">
		  <? if($usecookies=='Y'){ ?>
		 <input type="radio" value="Y" checked name="usecookies_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" name="usecookies_input" value="N"><? echo "$LANG_no"; ?>
	  <? }else{ ?>
		 <input type="radio" value="Y" name="usecookies_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" checked name="usecookies_input" value="N"><? echo "$LANG_no"; ?>
		 <? } ?>
    <tr>
	      <td width="148" height="22"><? echo "$LANG_varedit_usedb"; ?>:</td>
		  <td width="417" height="19">
		  <? if($usedb=='Y'){ ?>
		 <input type="radio" value="Y" checked name="usedb_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" name="usedb_input" value="N"><? echo "$LANG_no"; ?>
	  <? }else{ ?>
		 <input type="radio" value="Y" name="usedb_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" checked name="usedb_input" value="N"><? echo "$LANG_no"; ?>
		 <? } ?>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_duration"; ?>:</td>
      <td width="417" height="22"><input type="text" name="cookielength_input"  value="<? echo "$expiretime"; ?>" size="4"> <? echo "$LANG_varedit_duration_msg"; ?></td>
    </tr>
	    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_topnum"; ?>:</td>
      <td width="417" height="22"><input type="text" name="topnum_input"  value="<? echo "$topnum"; ?>" size="4"> <? echo "$LANG_varedit_topnum_other"; ?>.</td>
    </tr>
    <tr>
		      <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_defrat"; ?>:</td>
      <td width="417" height="22"><input type="text" name="steexp_input" value="<? echo "$steexp"; ?>" size="4">:<input type="text" name="banexp_input"  value="<? echo "$banexp"; ?>" size="4"> <? echo "$LANG_varedit_defrat_msg"; ?></td>
    </tr>
	<tr>
      <td width="148" height="19"><? echo "$LANG_varedit_showtext"; ?></td>
      <td width="417" height="19">
		 <input type="radio" value="Y" checked name="showtext_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" value="N" name="showtext_input"><? echo "$LANG_no"; ?>
	  </td>
    </tr>
	<tr>
	      <td width="148" height="22"><? echo "$LANG_varedit_exchangetext"; ?>:</td>
      <td width="417" height="22"><input type="text" name="exchangetext_input"  value="<? echo "$exchangetext"; ?>" size="50"></td>
	  </tr>
	  <tr>
      <td width="148" height="19"><? echo "$LANG_varedit_showimage"; ?></td>
      <td width="417" height="19">
	  <? if($showimage=='Y'){ ?>
		 <input type="radio" value="Y" checked name="showimage_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" name="showimage_input" value="N"><? echo "$LANG_no"; ?>
	  <? }else{ ?>
		 <input type="radio" value="Y" name="showimage_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" checked name="showimage_input" value="N"><? echo "$LANG_no"; ?>
		 <? } ?>
	  </td>
    </tr>
		<tr>
      <td width="148" height="19"><? echo "$LANG_varedit_imgpos"; ?></td>
      <td width="417" height="19">
		 <input type="radio" value="L" checked name="imagepos_input"><? echo "$LANG_left"; ?>
		 <input type="radio" value="R" name="imagepos_input"><? echo "$LANG_right"; ?>
		 <input type="radio" value="T" name="imagepos_input"><? echo "$LANG_top"; ?>
		 <input type="radio" value="B" name="imagepos_input"><? echo "$LANG_bottom"; ?>
	  </td>
    </tr>
    <tr>
      <td width="148" height="23"><? echo "$LANG_varedit_imageurl"; ?>:</td>
      <td width="417" height="23"><input type="text" name="imageurl_input" size="28"> (<? echo "$LANG_varedit_imageurl_msg"; ?>)</td>
    </tr>
	<tr>
      <td width="148" height="19"><? echo "$LANG_varedit_showtext"; ?></td>
      <td width="417" height="19">
		 <input type="radio" value="Y" checked name="showtext_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" value="N" name="imagepos_input"><? echo "$LANG_no"; ?>
	  </td>
    </tr>
	<tr>
	      <td width="148" height="22"><? echo "$LANG_varedit_exchangetext"; ?>:</td>
      <td width="417" height="22"><input type="text" name="exchangetext_input"  value="<? echo "$exchangetext"; ?>" size="50"></td>
	  </tr>
    <tr>
      <td width="148" height="18"><? echo "$LANG_varedit_sendemail"; ?>:</td>
      <td width="417" height="18">
	  <? if($sendemail=='Y'){ ?>
      <input type="radio" value="Y" checked name="sendemail_input"><? echo "$LANG_yes"; ?>
      <input type="radio" name="sendemail_input" value="N"><? echo "$LANG_no"; ?></td>
	   <? }else{ ?>
	  <input type="radio" value="Y" name="sendemail_input"><? echo "$LANG_yes"; ?>
      <input type="radio" checked name="sendemail_input" value="N"><? echo "$LANG_no"; ?></td>
	  <? } ?>
    </tr>
    <tr>
      <td width="148" height="18"><? echo "$LANG_varedit_usemd5"; ?></td>
      <td width="417" height="18">
	  <? if($usemd5=='Y'){ ?>
      <input type="radio" value="Y" checked name="md5_input"><? echo "$LANG_yes"; ?>
      <input type="radio" name="md5_input" value="N"><? echo "$LANG_no"; ?></td>
	  <? }else{ ?>
      <input type="radio" value="Y" name="md5_input"><? echo "$LANG_yes"; ?>
      <input type="radio" checked name="md5_input" value="N"><? echo "$LANG_no"; ?></td>
	  <? } ?>
    </tr>
	<tr>
      <td width="148" height="18"><? echo "$LANG_varedit_referral"; ?>:</td>
      <td width="417" height="18">
	  <? if($referral_program=='Y'){ ?>
      <input type="radio" value="Y" checked name="referral_input"><? echo "$LANG_yes"; ?>
      <input type="radio" name="referral_input" value="N"><? echo "$LANG_no"; ?></td>
	   <? }else{ ?>
	  <input type="radio" value="Y" name="referral_input"><? echo "$LANG_yes"; ?>
      <input type="radio" checked name="referral_input" value="N"><? echo "$LANG_no"; ?></td>
	  <? } ?>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_bounty"; ?>:</td>
      <td width="417" height="22"><input type="text" name="bounty_input"  value="<? echo "$referral_bounty"; ?>" size="8"></td>
    </tr>
    <tr>
      <td width="148" height="18"><? echo "$LANG_varedit_upload"; ?></td>
      <td width="417" height="18">
	  <? if($allow_upload=='Y'){ ?>
      <input type="radio" value="Y" checked name="allow_upload_input"><? echo "$LANG_yes"; ?>
      <input type="radio" name="sllow_upload_input" value="N"><? echo "$LANG_no"; ?></td>
	  <? }else{ ?>
      <input type="radio" value="Y" name="allow_upload_input"><? echo "$LANG_yes"; ?>
      <input type="radio" checked name="allow_upload_input" value="N"><? echo "$LANG_no"; ?></td>
	  <? } ?>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_maxsize"; ?>:</td>
      <td width="417" height="22"><input type="text" name="max_filesize_input"  value="<? echo "$max_filesize"; ?>" size="8"></td>
    </tr>
	    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_uploadpath"; ?>:</td>
      <td width="417" height="22"><input type="text" name="upload_path_input"  value="<? echo "$upload_path"; ?>" size="50"></td>
    </tr>
		<tr>
      <td width="148" height="22"><? echo "$LANG_varedit_upurl"; ?>:</td>
      <td width="417" height="22"><input type="text" name="banner_dir_url_input"  value="<? echo "$banner_dir_url"; ?>" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22"><? echo "$LANG_varedit_maxbanners"; ?>:</td>
      <td width="417" height="22"><input type="text" name="maxbanners_input"  value="<? echo "$max_filesize"; ?>" size="8"></td>
    </tr>
	<tr>
      <td width="148" height="19"><? echo "$LANG_varedit_sellcredits"; ?></td>
      <td width="417" height="19">
	  <? if($sellcredits=='1'){ ?>
		 <input type="radio" value="1" checked name="sellcredits_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" name="sellcredits_input" value="0"><? echo "$LANG_no"; ?>
	  <? }else{ ?>
		 <input type="radio" value="1" name="sellcredits_input"><? echo "$LANG_yes"; ?>
		 <input type="radio" checked name="sellcredits_input" value="0"><? echo "$LANG_no"; ?>
		 <? } ?>
	  </td>
    </tr>
    <tr>	<input type="hidden" name="service_input" value="paypal">
      <td width="148" height="19"><input type="submit" value="<? echo "$LANG_varedit_submit"; ?>" name="submit"><input type="reset" value="<? echo "$LANG_varedit_reset"; ?>" name="reset"></td>
      <td width="417" height="19">&nbsp;</td>
    </tr>
  </table>
</form>
		</td>
		</tr>
		</table>
<?
}

elseif ($upgrade==2){
		// Write out the config file.
		$config_data = '<?php'."\n\n";
		$config_data .= "//\n// phpBannerExchange auto-generated config file\n// Change this file at your own risk!\n//\n\n";
		$config_data .= '$dbhost = "' . $dbhost_input . '";' . "\n";
		$config_data .= '$dbuser = "' . $dbuser_input . '";' . "\n";
		$config_data .= '$dbpass = "' . $dbpass_input . '";' . "\n";
		$config_data .= '$dbname = "' . $dbname_input . '";' . "\n\n";
		$config_data .= '$baseurl = "' . $baseurl_input . '";' . "\n";
		$config_data .= '$basepath = "' . $basepath_input . '";' . "\n";
		$config_data .= '$exchangename = "' . $exchangename_input . '";' . "\n";
		$config_data .= '$sitename = "' . $sitename_input . '";' . "\n";
		$config_data .= '$adminname = "' . $adminname_input . '";' . "\n";
		$config_data .= '$ownermail = "' . $ownermail_input . '";' . "\n\n";
		$config_data .= '$bannerwidth = "' . $bannerwidth_input . '";' . "\n";
		$config_data .= '$bannerheight = "' . $bannerheight_input . '";' . "\n";
		$config_data .= '$steexp = "' . $steexp_input . '";' . "\n";
		$config_data .= '$banexp = "' . $banexp_input . '";' . "\n";
		$config_data .= '$startcredits = "' . $startcredits_input . '";' . "\n";
		$config_data .= '$showimage = "' . $showimage_input . '";' . "\n";
		$config_data .= '$imagepos = "' . $imagepos_input . '";' . "\n";
		$config_data .= '$imageurl = "' . $imageurl_input . '";' . "\n";
		$config_data .= '$showtext = "' . $showtext_input . '";' . "\n";
		$config_data .= '$exchangetext = "' . $exchangetext_input . '";' . "\n";
		$config_data .= '$sendemail = "' . $sendemail_input . '";' . "\n";
		$config_data .= '$usemd5 = "' . $md5_input . '";' . "\n\n";
		$config_data .= '$expiretime = "' . $cookielength_input . '";' . "\n";
		$config_data .= '$usecookies = "' . $usecookies_input . '";' . "\n";
		$config_data .= '$usedb = "' . $usedb_input . '";' . "\n";
		$config_data .= '$topnum = "' . $topnum_input . '";' . "\n\n";
		$config_data .= '$referral_program = "' . $referral_input . '";' . "\n";
		$config_data .= '$referral_bounty = "' . $bounty_input . '";' . "\n\n";
		$config_data .= '$allow_upload = "' . $allow_upload_input . '";' . "\n";
		$config_data .= '$max_filesize = "' . $max_filesize_input . '";' . "\n";
		$config_data .= '$upload_path = "' . $upload_path_input . '";' . "\n";
		$config_data .= '$banner_dir_url = "' . $banner_dir_url_input . '";' . "\n";
		$config_data .= '$sellcredits = "' . $sellcredits_input . '";' . "\n";
		$config_data .= '$commerce_service = "' . $service_input . '"' . "\n\n";
		$config_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!

	@umask(0111);
	$fp=fopen('../config.php', 'wb');
	$result = fputs($fp, $config_data, strlen($config_data));
	fclose($fp);


		echo "$LANG_vars_insert<p>";
		echo "$LANG_varedit_dbhost: <b>$dbhost_input</b><br>";
		echo "$LANG_varedit_dblogin: <b>$dbuser_input</b><br>";
		echo "$LANG_varedit_dbpass: <b>$dbpass_input</b><br>";
		echo "$LANG_varedit_dbname: <b>$dbname_input</b><br>";
		echo "$LANG_varedit_baseurl: <b>$baseurl_input</b><br>";
		echo "$LANG_varedit_basepath: <b>$basepath_input</b><br>";
		echo "$LANG_varedit_exchangename: <b>$exchangename_input</b><br>";
		echo "$LANG_varedit_sitename: <b>$sitename_input</b><br>";
		echo "$LANG_varedit_adminname: <b>$adminname_input</b><br>";
		echo "$LANG_varedit_adminemail: <b>$ownermail_input</b><br>";
		echo "$LANG_varedit_width: <b>$bannerwidth_input</b><br>";
		echo "$LANG_varedit_height: <b>$bannerheight_input</b><br>";
		echo "$LANG_varedit_cookies: <b>$startcredits_input</b><br>";
		echo "$LANG_varedit_usedb: <b>$usedb_input</b><br>";
		echo "$LANG_varedit_duration: <b>$cookielength_input</b><br>";
		echo "$LANG_varedit_defrat: <b>$steexp_input:$banexp_input</b><br>";		     
		echo "$LANG_varedit_showimage: <b>$showimage_input</b><br>";
		echo "$LANG_varedit_imgpos: <b>$imagepos_input</b><br>";
        echo "$LANG_varedit_imageurl: <b>$imageurl_input</b><br>";
		echo "$LANG_varedit_showtext: <b>$showtext_input</b><br>";
		echo "$LANG_varedit_exchangetext: <b>$exchangetext_input</b><br>";
		echo "$LANG_varedit_sendemail: <b>$sendemail_input</b><br>";
		echo "$$LANG_varedit_usemd5: <b>$md5_input</b><br>";
		echo "$LANG_varedit_topnum: <b>$topnum_input</b><br>";
		echo "$LANG_varedit_referral: <b>$referral_input</b><br>";
		echo "$LANG_varedit_bounty: <b>$bounty_input</b><br>";
		echo "$LANG_varedit_upload: <b>$allow_upload_input</b><br>";
		echo "$LANG_varedit_maxsize: <b>$max_filesize_input</b><br>";
		echo "$LANG_varedit_uploadpath: <b>$upload_path_input</b><br>";
		echo "$LANG_varedit_upurl: <b>$banner_dir_url_input</b><br>";
		echo "$LANG_varedit_maxbanners: <b>$maxbanners_input</b><br>";
		echo "$LANG_varedit_sellcredits: <b>$sellcredits_input</b><p>";
echo "<br><A href=\"$PHP_SELF?upgrade=3\">$LANG_cont</a>";
}

elseif ($upgrade==3){
include("../config.php");
	$db=mysql_connect("$dbhost","$dbuser","$dbpass");
	mysql_select_db($dbname,$db);

	echo "<p>$LANG_db_firstpart <b>bannercommerce</b> $LANG_db_secondpart $dbname<br>";
$create_bannercommerce="CREATE TABLE bannercommerce (
productid int(11) NOT NULL auto_increment,
productname text NOT NULL,
credits int(11) NOT NULL default '0',
price int(11) NOT NULL default '0',
purchased int(11) NOT NULL default '0',
UNIQUE KEY productid (productid)
)";

mysql_query($create_bannercommerce);

echo "<p>$LANG_db_firstpart <b>bannersales</b> $LANG_db_secondpart $dbname<br>";

$create_bannersales="CREATE TABLE bannersales (
invoice int(11) NOT NULL default '0',
uid int(11) NOT NULL default '0',
item_number int(11) NOT NULL default '0',
payment_status text NOT NULL,
payment_gross text NOT NULL,
payer_email varchar(200) NOT NULL default ''
)";

mysql_query($create_bannersales);

echo "phpBannerExchange has been successfully updated! You may now log in to the <a href=\"../admin/index.php\">Admin Control Panel</a><p>It is strongly advised that you delete the install directory immediately!";
}
?>