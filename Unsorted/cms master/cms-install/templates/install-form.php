<script>
function verify_form() {
    var error_msg = "";
    if (document.getElementById("db_host").value=="") {
	error_msg = error_msg + "<?php echo $Lang_install->error_db_host_empty ?>" + "\n";
    }
    if (document.getElementById("db_name").value=="") {
	error_msg = error_msg + "<?php echo $Lang_install->error_db_name_empty ?>" + "\n";
    }
    if (document.getElementById("db_user").value=="") {
	error_msg = error_msg + "<?php echo $Lang_install->error_db_user_empty ?>" + "\n";
    }
    if (document.getElementById("admin_email").value=="") {
	error_msg = error_msg + "<?php echo $Lang_install->error_admin_email_empty ?>" + "\n";
    }
    if (document.getElementById("admin_pass").value=="") {
	error_msg = error_msg + "<?php echo $Lang_install->error_admin_pass_empty ?>" + "\n";
    } else {
	if (document.getElementById("admin_pass").value!=document.getElementById("admin_retype_pass").value) {
	    error_msg = error_msg + "<?php echo $Lang_install->error_password_incorrect ?>" + "\n";
	}
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
<?php echo $Lang_install->header_options_form ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<form name=options action=index.php method=post onSubmit="return verify_form();">
<input type=hidden name=mode value="install">
<tr valign=top>
<td bgcolor=#f3f3f3 width=130><?php echo $Lang_install->field_db_server ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=db_host name=db_host value="localhost" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_install->field_db_name ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=db_name name=db_name value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_install->field_db_user ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=db_user name=db_user value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_install->field_db_pass ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=db_pass name=db_pass value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3>&nbsp;</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_install->field_admin_pass ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=admin_pass name=admin_pass value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_install->field_admin_retype_pass ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=admin_retype_pass name=admin_retype_pass value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3>&nbsp;</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_install->field_admin_email ?>:</td>
<td bgcolor=#f3f3f3><input type=text id=admin_email name=admin_email value="" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3><input type=submit value="<?php echo $Lang_install->button_install ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
</form>
</table>
</td></tr>
</table>
</td></tr>
</table>
<p>
<?php echo $Lang_install->notes_options_form ?>
</p>
<!-- END CONTENT -->
