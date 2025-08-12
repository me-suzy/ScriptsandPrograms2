<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="no-cache">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="cache-Control" content="no-cache"> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>{title1}</title>
<link rel="stylesheet" href="{baseurl1}/template/css/{css}" type="text/css">
<body leftmargin="0" topmargin="0" marginwidth="0" 
  marginheight="0" >
<div id="content">
<div class="main">
<table border="0" cellpadding="1" width="650" cellspacing="0">
<tr>
<td>
<table cellpadding="5" border="1" width="100%" cellspacing="0">
<tr>
<td colspan="2" class="tablehead"><center><div class="head">{title1}</center></div></td>
</tr>
<td class="tablebody" colspan="2">
<div class="mainbody">
<table border="0" cellpadding="1" cellspacing="1" style="border-collapse: collapse"  width="90%">
  <tr>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="90%" >
<tr>
{msg1}<p>
<b>{warning1}</b>
<p>{filewrite}<p>
<form method="post" action="templates.php?SID={session1}&template={template_name1}">
{template1}:
<select class="formbox" name="template">
{list1}
</select>
<input class="button" type="submit" value=" {submit1} " name="submit">
</form><p>
<center><b>{template_name1}</b><br>
<form method="post" action="templates_change.php?SID={session1}&template={template_var1}">
<textarea class="formbox" wrap="off" rows="25" name="editresult" cols="75">{template_data1}</textarea><p>
<center><input class="button" type="submit" value=" {submit1} " name="submit_change">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input class="button" type="reset" value=" {reset1} " name="reset"><p></center></form>
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
<div class="footer">
{footer1}
</div>
</div>
{menu1}