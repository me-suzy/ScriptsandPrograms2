<script>
function verify_form() {
    var error_msg = "";
    
    if (document.getElementById("password").value!=document.getElementById("retype_password").value && document.getElementById("password").value!="") {
	error_msg = error_msg + "<?php echo $Lang_system->error_password_incorrect ?>" + "\n";
    }
    
    if (error_msg=="") {
	return true;
    } else {
	alert(error_msg);
	return false;
    }
}
</script>

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
<?php include("$CFG->dir_admin_templates/system-menu.php") ?>
<!-- END MENU 2 -->

</td>
</tr>
</table>
</td>
<td width=20 valign=top><img src=images/trans.gif width=20 height=1 border=0></td>
<td width=450 valign=top>
<img src=images/trans.gif width=450 height=10 border=0>

<h2>
<?php echo $Lang_system->header_edit_user ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<form name=user_form action=system.php method=post onSubmit="return verify_form();">
<input type=hidden name=login value="<?php echo $frm["login"] ?>">
<input type=hidden name=mode value="<?php echo $frm["mode"] ?>">

<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_system->field_login ?>:</td>
<td bgcolor=#f3f3f3><?php echo $frm["login"] ?></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3>&nbsp;</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_system->field_email ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=email name=email value="<?php echo $frm["email"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3>&nbsp;</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_system->field_password ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=password name=password value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_system->field_retype_password ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=retype_password name=retype_password value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3><input type=submit value="<?php echo $frm["button"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
</form>
</table>
</td></tr>
</table>
</td></tr>
</table>
<p>
<?php echo $Lang_system->notes_edit_user ?>
</p>
<!-- END CONTENT -->
