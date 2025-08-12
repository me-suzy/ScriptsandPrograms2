<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>{title}</title>
<link rel="stylesheet" href="{baseurl}/template/css/{css}" type="text/css">
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
<form method="POST" action="editconfirm.php?SID={session}&uid={uid}&ref={ref}">
	</td>
    <tr>
      <td width="22%">{realname}:</td>
      <td width="78%"><input class="formbox" type="text" name="name" value="{nameval}" size="50"></td>
    </tr>
    <tr>
      <td width="22%">{login}:</td>
      <td width="78%"><input class="formbox" type="text" name="ulogin" value="{loginval}" size="50"></td></td>
    </tr>
    <tr>
      <td width="22%">{pass}:</td>
      <td width="78%"><input class="formbox" type="text" name="newpass" value="{passval}"size="50"></td>
    </tr>
    <tr>
      <td width="22%">{email}:</td>
      <td width="78%"><input class="formbox" type="text" name="email" value="{emailval}" size="50"> {emaillink}
	  </td>
    </tr>
			<tr>
      <td width="22%">{category}:</td>
      <td width="78%">
			<select class="formbox" name="category">
			{cat_list}
			</select>
			</td>
	    <tr>
      <td width="22%">{exposures}:</td>
      <td width="78%"><input class="formbox" type="text" name="exposures" size="20" value={exposuresval}></td>
    </tr>
	    <tr>
      <td width="22%">{credits}:</td>
      <td width="78%"><input class="formbox" type="text" name="credits" size="20" value={creditsval}> {saleshist}</td>
    </tr>
	    <tr>
      <td width="22%">{clicks}:</td>
      <td width="78%"><input class="formbox" type="text" name="clicks" size="20" value={clicksval}></td>
    </tr>
	    <tr>
      <td width="22%">{siteclick}:</td>
      <td width="78%"><input class="formbox" type="text" name="siteclicks" size="20" value={siteclicksval}></td>
    </tr>
	<tr>
      <td width="22%">{raw}:</td> 
      <td width="78%"><textarea class="formbox" name="rawform" cols="50" rows="5" value="{rawval}"></textarea></td>
    </tr>
		    <tr>
      <td width="25%" valign="middle">{status}:</td>
      <td width="78%" valign="middle">
  <input class="formbox" type=radio checked name="approved" value="Approved">{approved}&nbsp;&nbsp;&nbsp;
  <input class="formbox" type=radio name="approved" class="radio">{notapproved}
  </tr>
				    <tr>
      <td width="25%" valign="middle">{defaultacct}:</td>
      <td width="78%" valign="middle">{defaultopt}

	</td>
	  </tr>
		<tr>
      <td width="25%" valign="middle">{sendletter}:</td>
      <td width="78%" valign="middle">{newsletteropt}
</td>
      </tr>
	  <td colspan=2 align=center>			  
<br><br>
	<input class="formbox"  type="submit" value=" {validate_button} " name="submit">
      <input class="formbox"  type="reset" value="{reset_button}"></form>
	  <form method="POST" action="deleteacct.php?SID={session}&uid={uid}&ref={ref}">
	  <input class="formbox" type="submit" value="{delete_button}" name="submit"></form>
</td>
</tr>
<tr>
<td>
<td colspan=2 align=center><div class="head">{banners}</div><br>
{totalbanners}<br>
<a href="banners.php?SID={session}&uid={uid}">{editbanners}</a>
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
