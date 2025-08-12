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
	<td class="tablehead"><b>{invoice}</b></td>
	<td class="tablehead"><b>{date}</b></td>
	<td class="tablehead"><b>{user}</b></td>
	<td class="tablehead"><b>{item}</b></td>
	<td class="tablehead"><b>{status}</b></td>
	<td class="tablehead"><b>{payment}</b></td>
	<td class="tablehead"><b>{email}</b></td></tr>
{data}
</td>
</tr>
</table>
<table border="0" width="100%">
<tr valign="top"><td>{prev}</td><td>{next}</td><td width="520" align="right"><form action="commerce_display.php?SID={session}&pos={pos}&filter={filter}" method="post">{showamt}: <input class="formbox" type="text" size="2" name="amt" value="{amt}"> <input class="button" type="submit" value="{submit}" name="submit"></form></td></tr></table>
<p>
<center><div class="littleheadplain">{filter_head}</center></div>
<center><a href="commerce_display.php?SID={session}">{clearfilters}</a></center><p>
<form method="post" action="commerce_display.php?SID={session}&pos={pos}&amt={amt}">
{uidsearch}: <input class="formbox" type="text" name="user" size="4" value="{uid}"> <input class="button" type="submit" value="{go}" name="searchuid"></form>

{orderfilter}:<br>
<a href="commerce_display.php?SID={session}&pos={pos}&amt={amt}&status=Canceled_Reversal">Canceled_Reversal</a> |  <a href="commerce_display.php?SID={session}&pos={pos}&amt={amt}&status=Completed">Completed</a> | <a href="commerce_display.php?SID={session}&pos={pos}&amt={amt}&status=Denied">Denied</a> | <a href="commerce_display.php?SID={session}&pos={pos}&amt={amt}&status=Failed">Failed</a> | <a href="commerce_display.php?SID={session}&pos={pos}&amt={amt}&status=Pending">Pending</a> | <a href="commerce_display.php?SID={session}&pos={pos}&amt={amt}&status=Refunded">Refunded</a> | <a href="commerce_display.php?SID={session}&pos={pos}&amt={amt}&status=Reversed">Reversed</a>

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