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
    <td align="center" colspan=2><a class="littleheadplain"><b>{stats_snapshot}</b></td>
  </tr>
  <tr>
    <td align="center" colspan=2>
    <table cellpadding="0" cellspacing="0" style="border-collapse: collapse"  width="100%">
      <tr>
    <td class="tablehead" align="center">{valusers}</td>
    <td class="tablehead" align="center">{totexp}</td>
    <td class="tablehead" align="center">{loosecred}</td>
    <td class="tablehead" align="center">{totalban}</td>
      </tr>
      <tr>
    <td align="center" class="tablebodycenter"><b>{val_totusers}</b></td>
    <td align="center" class="tablebodycenter"><b>{val_totexp}</b></td>
    <td align="center" class="tablebodycenter"><b>{val_totloosecred}</b></td>
    <td align="center" class="tablebodycenter"><b>{val_totviewexp}</b></td>
      </tr>
      <tr>
	    <td class="tablehead" align="center">{pendusr}</td>
    <td class="tablehead" align="center">{totclicks}</td>
    <td class="tablehead" align="center">{totsicl}</td>
    <td class="tablehead" align="center">{overrat}</td>
      </tr>
      <tr>
    <td align="center" class="tablebodycenter"><b>{val_pendusers}</b></td>
    <td align="center" class="tablebodycenter"><b>{val_totclicks}</b></td>
    <td align="center" class="tablebodycenter"><b>{val_totsiteclicks}</b></td>
    <td align="center" class="tablebodycenter"><b>{val_ratio}:1</b></td>
      </tr>
    </table>
  <p>
  {statusmessage}
  <p><b>{security_warning}</b>
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
