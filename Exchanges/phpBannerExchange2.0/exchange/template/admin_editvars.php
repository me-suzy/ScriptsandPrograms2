<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>{title}</title>
<link rel="stylesheet" href="{base_url}/template/css/{css}" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" 
  marginheight="0" >
<div id="content">
<div class="main">
<table border="0" cellpadding="1" width="650" cellspacing="0">
<tr>
<td>
<table cellpadding="5" border="1" width="100%" cellspacing="0">
<tr>
<td colspan="2" class="tablehead"><center><div class="head">{title}</center></div></td>
</tr>
<td class="tablebody" colspan="2">
<div class="mainbody">
<table border="0" cellpadding="1" cellspacing="1" style="border-collapse: collapse"  width="90%">
  <tr>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="90%" >
<tr>

		<form method="POST" action="processvars.php?SID={session}"><table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="90%">
    <tr>
      <td colspan=2>{msg}<br>
&nbsp;</td>
    </tr>
	    <tr><td class="tablehead" colspan="2"><center><div class="varhead">{dbhead}</div></center></td></tr>
		<tr>
      <td width="148" height="23" class="tablebody">{dbhost}:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="dbhost_input" value="{dbhost_data}" size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23" class="tablebody">{dblogin}:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="dbuser_input" value="{dbuser_data}" size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23" class="tablebody">{dbpass}:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="dbpass_input" value="{dbpass_data}"  size="28"></td>
    </tr>
	    <tr>
      <td width="148" height="23" class="tablebody">{dbname}:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="dbname_input"  value="{dbname_data}" size="28"></td>
    </tr>
	<tr><td class="tablehead" colspan="2"><center><div class="varhead">{pathing_head}</center></td></tr>
    <tr>
      <td width="148" height="23" class="tablebody">{baseurl}:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="baseurl_input"  value="{baseurl_data}" size="50"><br>({baseurl_note})</td>
    </tr>
	<tr>
      <td width="148" height="22" class="tablebody">{basepath}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="basepath_input"  value="{basepath_data}" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody">{exchangename}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="exchangename_input"  value="{exchangename_data}" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="23" class="tablebody">{sitename}:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="sitename_input"  value="{sitename_data}" size="50"> </td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody">{adminname}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="adminname_input"  value="{adminname_data}" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody">{adminemail}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="ownermail_input"  value="{ownermail_data}" size="50"></td>
    </tr>
    <tr>
<tr><td class="tablehead" colspan="2"><center><div class="varhead">{bannershead}</div></center></td></tr>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody">{width}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="bannerwidth_input"  value="{width_data}" size="4"> {pixels}</td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody">{height}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="bannerheight_input"  value="{height_data}" size="4"> {pixels}</td>
    </tr>
		      <tr>
      <td width="148" height="22" class="tablebody">{defrat}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="steexp_input" value="{steexp_data}" size="4">:<input class="formbox" type="text" name="banexp_input"  value="{banexp_data}" size="4"> {defrat_msg}</td>
    </tr>
	  <tr>
      <td width="148" height="19" class="tablebody">{showimage}</td>
      <td width="417" height="19" class="tablebody">
{showimage_code}
	  </td>
    </tr>
		<tr>
      <td width="148" height="19" class="tablebody"> </td>
      <td width="417" height="19" class="tablebody">{imagepos_code}
	  </td>
    </tr>
    <tr>
      <td width="148" height="23" class="tablebody">{imageurl}:</td>
      <td width="417" height="23" class="tablebody"><input class="formbox" type="text" name="imageurl_input" value="{imageurl_data}" size="28"> ({imageurl_msg})</td>
    </tr>
	<tr>
      <td width="148" height="19" class="tablebody">{showtext}</td>
      <td width="417" height="19" class="tablebody">
		 {showtext_value} || {exchangetext}: <input type="text" class="formbox" name="exchangetext_input"  value="{exchangetext_value}" size="30">
	  </td>
    </tr>
		<tr>
      <td width="148" height="19" class="tablebody">{reqbanapproval}</td>
      <td width="417" height="19" class="tablebody">
{reqbanapproval_data}
	  </td>
    </tr>
    <tr>
      <td width="148" height="18" class="tablebody">{upload}</td>
      <td width="417" height="18" class="tablebody">
{upload_data}
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody">{maxsize}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="max_filesize_input"  value="{max_filesize_data}" size="8"></td>
    </tr>
	    <tr>
      <td width="148" height="22" class="tablebody">{upload_path}:</td>
      <td width="417" height="22" class="tablebody"><input type="text" class="formbox" name="upload_path_input"  value="{upload_path_data}" size="50"></td>
    </tr>
		<tr>
      <td width="148" height="22" class="tablebody">{banner_dir_url}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="banner_dir_url_input"  value="{banner_dir_url_data}" size="50"></td>
    </tr>
    <tr>
      <td width="148" height="22" class="tablebody">{maxbanners}:</td>
      <td width="417" height="22" class="tablebody"><input class="formbox" type="text" name="maxbanners_input"  value="{maxbanners_data}" size="8"></td>
    </tr>

	
<tr><td class="tablehead" colspan="2"><center><div class="varhead">{anticheat_head}</div></center></td></tr>
		<tr>
      <td width="148" height="22">{anticheat}:</td>
<td><select class="formbox" name="anticheat_input">
{anticheat_code}
</select>
</td>
</tr>
    <tr>
      <td width="148" height="22">{expiretime}:</td>
      <td width="417" height="22"><input class="formbox" type="text" name="cookielength_input"  value="{expiretime_data}" size="4"> {expiretime_msg}</td>
    </tr>
		<tr><td class="tablehead" colspan="2"><center><div class="varhead">{refncred_head}</div></center></td></tr>
	<tr>
      <td width="148" height="18">{referral}:</td>
      <td width="417" height="18">
{referral_code}
    </tr>
    <tr>
      <td width="148" height="22">{referral_bounty}:</td>
      <td width="417" height="22"><input class="formbox" type="text" name="bounty_input"  value="{referral_bounty_data}" size="8"></td>
    </tr>
    <tr>
      <td width="148" height="22">{startcredits}:</td>
      <td width="417" height="22"><input class="formbox" type="text" name="startcredits_input"  value="{startcredits_data}" size="4"></td>
    </tr>
		<tr>
      <td width="148" height="19">{sellcredits}:</td>
      <td width="417" height="19">
{sellcredits_code}
	  </td>
    </tr>
<tr><td class="tablehead" colspan="2"><center><div class="varhead">{misc_head}</div></center></td></tr>
	    <tr>
      <td width="148" height="22">{topnum}:</td>
      <td width="417" height="22"><input class="formbox" type="text" name="topnum_input"  value="{topnum_data}" size="4"> {topnum_other}.</td>
    </tr>
	
    <tr>
      <td width="148" height="18">{sendemail}:</td>
      <td width="417" height="18">
{sendemail_code}</td>
    </tr>
    <tr>
      <td width="148" height="18">{usemd5}</td>
      <td width="417" height="18">
{usemd5_code}</td>
    </tr>
	<tr>
      <td width="148" height="19">{usegzhandler}</td>
      <td width="417" height="19">
{usegzhandler_code}
	  </td>
    </tr>

		<tr>
      <td width="148" height="19">{logclicks}</td>
      <td width="417" height="19">
{logclicks_code}
	  </td>
    </tr>

	<tr>
	      <td width="148" height="19">{usedbrand}</td>
      <td width="417" height="19">
{use_dbrand_code} || {use_dbrand_warn}
	  </td>
    </tr>
 <td width="148" height="22">{dateformat}:</td>
<td><select class="formbox" name="date_format_input">{dateformatcode}
</td></tr>
	    <tr>
	<input type="hidden" name="service_input" value="paypal">
      <td width="148" height="19"><input class="button" type="submit" value="{submit}" name="submit"><input class="button" type="reset" value="{reset}" name="reset"></td>
      <td width="417" height="19">&nbsp;</td>
	  </tr>
  </table>
</form>
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
<div class="footer">
{footer}
</div>
</div>
{menu}
