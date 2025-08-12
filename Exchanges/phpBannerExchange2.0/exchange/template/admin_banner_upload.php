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
	<table border="0" cellpadding="4" cellspacing="4" style="border-collapse: collapse" width="100%">
		<tr>
		<td colspan="2">
		{banner_display}
		<p>
<center><form enctype="multipart/form-data" action="uploadbanner.php?SID={session}&uid={uid}" method="post">
<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000">
<table cellpadding="0" cellspacing="0" width="40%">
    <tr>
      <td width="100%" colspan="2"><center><a class="heading"><b>{add_headerbutton}</b></a></td>
    </tr>
    <tr>
      <td width="84%"><CENTER>
		<input name="userfile" type="file"><BR>
			<input type="submit" value="  {add_headerbutton}  ">
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
