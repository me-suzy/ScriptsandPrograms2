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

<td class="caption" align="center" colspan=4><a class="littleheadplain"><b>{header}</b></td></tr><tr><td><br><center>
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="50%" >
<tr class="tablehead"><td width=20% class="tablehead"><b>{catname}</b></td>
<td width=10% class="tablehead"><b>{sites}</b></td>
<td width=10% class="tablehead"><b>{edit}</b></td>
<td width=10% class="tablehead"><b>{delete}</b></td></tr>
{catstable}
<tr><td colspan=4><center>{totalcats}</center>
</td></tr></table><br></td></tr>
<tr><td class="littleheadplain" align="center" colspan="4"><b>{addcat}</b></td></tr>
<form action="addcat.php?SID={session}" method= "post">
<tr><td colspan=4><center>{addcat}: &nbsp;&nbsp;<input class="formbox" type="text" size="40" name="catname">&nbsp;&nbsp;<input class="button" type="submit" value=" Add Category "></center>

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
