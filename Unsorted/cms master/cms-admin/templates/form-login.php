<html>
<head>
<title><?php echo $Lang->title ?></title>
<link rel=StyleSheet href="style.css" type="text/css">
</head>

<body bgcolor=#ffffff text=#000000 link=#c80000 vlink=#c80000 alink=#c0c0c0 leftmargin=20 topmargin=20 marginwidth=20 marginheight=20>

<center>
<table width=700 cellpadding=0 cellspacing=0 border=0>
<tr>
<td width=200>
<img src=images/logo.gif>
</td>
<td width=500 align=right valign=bottom>

<!-- START MENU 1 -->
&nbsp;
<!-- END MENU 1 -->

</td>
</tr>
<tr><td colspan=2><img src=images/trans.gif width=700 height=1 border=0></td></tr>
</table>
</center>

<center>
<table width=700 cellpadding=0 cellspacing=0 border=0>
<tr><td width=700 bgcolor=#c80000><img src=images/trans.gif width=700 height=16 border=0></td></tr>
<tr><td width=700 bgcolor=#363636><img src=images/trans.gif width=700 height=38 border=0></td></tr>
<tr><td width=700><img src=images/trans.gif width=700 height=1 border=0></td></tr>
</table>
</center>

<center>
<table width=700 cellpadding=0 cellspacing=0 border=0>
<tr>
<td width=210 valign=top>
<table width=210 cellpadding=0 cellspacing=0 border=0>
<tr>
<td bgcolor=#e9e9e9><img src=images/trans.gif width=70 height=51 border=0></td>
<td bgcolor=#d1d1d1><img src=images/trans.gif width=70 height=51 border=0></td>
<td bgcolor=#e9e9e9><img src=images/trans.gif width=70 height=51 border=0></td>
</tr>
<tr>
<td bgcolor=#b6b6b6><img src=images/trans.gif width=70 height=51 border=0></td>
<td bgcolor=#e9e9e9><img src=images/trans.gif width=70 height=51 border=0></td>
<td bgcolor=#ffffff><img src=images/trans.gif width=70 height=51 border=0></td>
</tr>
<tr>
<td valign=top>
<table cellpadding=0 cellspacing=0 border=0><tr><td bgcolor=#d1d1d1><img src=images/trans.gif width=70 height=51 border=0></td></tr></table>
</td>
<td bgcolor=#ffffff valign=top colspan=2>

<!-- START MENU 2 -->
&nbsp;
<!-- END MENU 2 -->

</td>
</tr>
</table>
</td>
<td width=20 valign=top><img src=images/trans.gif width=20 height=1 border=0></td>
<td width=450 valign=top>
<img src=images/trans.gif width=450 height=10 border=0>

<h2>
<?php echo $Lang_auth->page_header ?>
</h2>

<?php $Base->msg_show("<br><center><font color=#ff0000><b>MSG</b></font></center></br>") ?>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<form name=formlogin method=post action=login.php>
<tr>
<td bgcolor=#f3f3f3 width=60><?php echo $Lang_auth->field_login ?> : </td>
<td bgcolor=#f3f3f3><input type=text name=login value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr>
<td bgcolor=#f3f3f3><?php echo $Lang_auth->field_password ?> : </td>
<td bgcolor=#f3f3f3><input type=password name=password value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3><input type=submit  value="<?php echo $Lang_auth->button_login ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
</form>
</table>
</td></tr>
</table>
</td></tr>
</table>
<!-- END CONTENT -->

<br>
<hr size=1 color=#e2e2e2 width=450>
</td>
<td width=20 valign=top><img src=images/trans.gif width=20 height=1 border=0></td>
</tr>
</table>
</center>


</body>
</html>
