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
<form method="POST" action="emailgo.php?SID={session}">
	<input type="hidden" name="cat" value="{cat}">
	<input type="hidden" name="override" value="{override}">
	<input type="hidden" name="subject" value="{subject}">
	<input type="hidden" name="body" value="{body}">
	</td>
       <tr>
      <td width="22%">{subjecttext}:</td>
      <td width="78%">{subject}</td></tr>

      <td width="22%" valign="top">{bodytext}:</td>
      <td width="78%">{body}</td>
    </tr>
    <tr>
  <td colspan=2 align=center>
<br><br>
	<input class="button" type="submit" value=" {submit} " name="submit">
      <input class="button" type="reset" value="{reset}"></form>
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