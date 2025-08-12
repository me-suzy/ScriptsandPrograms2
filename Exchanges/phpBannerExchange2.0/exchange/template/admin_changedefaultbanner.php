<!D<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
{msg}
<p>
<img src="{defaultbanner}"><p>
{defaulturl}: <a href="{defaulturl_data}">{defaulturl_data}</a>
<p>
<form method="POST" action="changedefaultbanner.php?SID={session}">
	</td>
       <tr>
      <td colspan="2"><center><b>{shorttitle}</b></center></td></tr>
	  <tr>
      <td width="22%" class="mainbody">{bannername}:</td>
      <td width="78%"><input class="formbox" type="text" name="newbanurl" size="50" value="{defaultbanner}"></td>
    </tr>
    <tr>
      <td width="22%" class="mainbody">{defaulturl}:</td>
      <td width="78%"><input class="formbox" type="text" name="newtargeturl" size="50" value="{defaulturl_data}"></td></td>
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