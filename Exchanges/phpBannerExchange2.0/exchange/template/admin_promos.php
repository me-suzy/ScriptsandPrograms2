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
	<td class="tablehead"><b>{name}</b></td>
	<td class="tablehead"><b>{codehead}</b></td>
	<td class="tablehead"><b>{type}</b></td>
	<td class="tablehead"><b>{credits}</b></td>
	<td class="tablehead"><b>{timestamp}</b></td>
	<td class="tablehead"><b>{options}</b></td></tr>
{msg}
</td>
</tr>
</table><p>
<center>[ <a href="promos.php?SID={session}&status=2">{listall}</a> | <a href="promos.php?SID={session}&status=1">{listact}</a> | <a href="promos.php?SID={session}&status=0">{listdel}</a> ]</center>
<p><b>{errhead}</b>
{err}<p>
<center><table border="0" cellpadding="2" cellspacing="2" style="border-collapse: collapse" width="75%" align="center"><form action="{addedit}" method="post">
<tr><td class="tablehead" colspan="2" align="center"><b>{additem}</b></tr>
<td class="tablebody">{name}</td><td class="tablebody"><input class="formbox" type="text" size="46" name="productname" value="{productname}"></td></tr>
<td class="tablebody">{codehead}</td><td class="tablebody"><input class="formbox" type="text" size="46" name="codehead" value="{codenew}"></td></tr>
<td class="tablebody">{type}</td><td class="tablebody"><select class="formbox" name="type">
{promo_options}
	</select> {value}: <input class="formbox" type="text" size="10" name="pricebox" value="{newval}">
	</td></tr>
<td class="tablebody">{credits}</td><td class="tablebody"><input class="formbox" type="text" size="46" name="newcredits" value="{newcredits}"></td></tr>

<td class="tablebody" colspan="2"><input class="formbox" type="checkbox" {reuse_val} name="newreuse"> {reuse}.
<br>{reuseint}: <input class="formbox" type="text" size="3" name="newreuseint" value="{reuseint_val}"> {days}.</td></tr>

<td class="tablebody">{usertype}</td><td class="tablebody"><select class="formbox" name="newusertype">{user_types}</select></td></tr>

<tr><td class="tablebody"></td><td class="tablebody"><input class="button" type="submit" value="{submit}" name="submit">
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