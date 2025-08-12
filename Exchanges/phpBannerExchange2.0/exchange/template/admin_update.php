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
{instructions}<p>
<b><a href="do_update.php?SID={session}&option=1" target=_blank>{opt1}</a></b> - {opt1desc}<p>
<b><a href="do_update.php?SID={session}&option=2" target=_blank>{opt2}</a></b> - {opt2desc}<p>
<b><a href="do_update.php?SID={session}&option=3" target=_blank>{opt3}</a></b> - {opt3desc}<p>
  <p>
</td>
</tr>
</table>
</div>
</td>
</tr>
</table>
<div class="footer">
{footer}
</div>
</div>
{menu}
