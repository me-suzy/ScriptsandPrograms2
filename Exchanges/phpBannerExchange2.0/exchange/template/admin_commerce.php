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
<table border="1" cellpadding="2" cellspacing="2" style="border-collapse: collapse" width="100%" >
<tr>
	<td class="tablehead"><b>{id}</b></td>
	<td class="tablehead"><b>{name}</b></td>
	<td class="tablehead"><b>{credits}</b></td>
	<td class="tablehead"><b>{price}</b></td>
	<td class="tablehead"><b>{purchased}</b></td>
	<td class="tablehead" colspan="2"><b>{options}</b></td></tr>
{msg}
</td>
</tr>
</table>
<center><a href="commerce_display.php?SID={session}">{search}</a>
<p>
<center><table border="0" cellpadding="2" cellspacing="2" style="border-collapse: collapse" width="75%" align="center"><form action="commerce.php?SID={session}" method="post">
<tr><td class="tablehead" colspan="2" align="center"><b>{additem}</b></tr>
<td class="tablebody">{name}</td><td class="tablebody">&nbsp;&nbsp;<input class="formbox" type="text" size="50" name="productname"></td></tr>
<td class="tablebody">{credits}</td><td class="tablebody">&nbsp;&nbsp;<input class="formbox" type="text" size="50" name="credits"></td></tr>
<td class="tablebody">{price}</td><td class="tablebody">{sign}<input class="formbox" type="text" size="50" name="price">{int_sign}</td></tr>
<td class="tablebody"></td><td class="tablebody"><input class="button" type="submit" value="{submit}" name="submit">
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