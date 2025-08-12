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
<p>
{instructions}<p>
<form method="post" action="editcss.php?SID={session}">
<select class="formbox" name="csspick">
{defaultoption}
{filelist}
</select> <input class="button" type="submit" name="submit" value="{submit}"><p>
</form>

<hr>
{instructions1}<p>

<form method="post" action="editcss.php?SID={session}">
<select class="formbox" name="css_var">
{editoption}
{edit_list}
</select> <input class="button" type="submit" name="edit_load" value="{loadbutton}">
</form>

<form method="post" action="css_change.php?SID={session}&css_var={css_var}">
<textarea class="formbox" wrap="off" rows="20" name="editresult" cols="75">{css_dump}</textarea><p>
<center><input class="button" type="submit" name="edit_do" value=" {submit} " name="submit_change">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input class="button" type="reset" value=" {reset} " name="reset"><p></center></form>
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