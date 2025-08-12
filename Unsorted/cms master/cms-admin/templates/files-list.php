<script>
function verify_form() {
    if(document.file_form.file.value=="") {
	alert("<?php echo $Lang_files->error_file_empty ?>");
	return false;
    } else {
	return true;
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
<?php echo $Lang_files->header_files_list ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<form name=file_form onSubmit="return verify_form();" action=files.php method=post enctype="multipart/form-data">
<input type=hidden name=mode value=upload_file>
<tr>
<td bgcolor=#c80000 colspan=2><font color=#ffffff><b><?php echo $Lang_files->table_file_add ?></b></font></td>
</tr>
<tr>
<td bgcolor=#f3f3f3 width=340><input type=file name=file style="width:100%; border-width:1; border-color:#363636"></td>
<td bgcolor=#f3f3f3><input type=submit value="<?php echo $Lang_files->button_upload ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
</form>
</table>
</td></tr>
</table>
</td></tr>
</table>

<br>

<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>

<?php
if ($count_files==0) {
?>
	<tr>
	<td bgcolor=#f3f3f3 align=center><br><?php echo $Lang_files->msg_no_files ?><br><br></td>

<?php
} else {
?>
<tr>
<td bgcolor=#c80000 width=220><font color=#ffffff><b><?php echo $Lang_files->table_file_name ?></b></font></td>
<td bgcolor=#c80000 width=50 align=center><font color=#ffffff><b><?php echo $Lang_files->table_file_size ?></b></font></td>
<td bgcolor=#c80000 width=70 align=center><font color=#ffffff><b><?php echo $Lang_files->table_file_date ?></b></font></td>
<td bgcolor=#c80000 align=center><font color=#ffffff><b><?php echo $Lang_files->table_file_cmd ?></b></font></td>
</tr>
<?php
    $n = 0;
    while($files[$n]) {
	$file_name = $files[$n]["name"];
	$file_size = $files[$n]["size"];
	$cmd_view = "<a href=/cms-files/$file_name target=_new title=\"$Lang_files->cmd_file_view '$file_name'\"><img src=images/cmd_view.gif width=16 height=16 border=0 alt=\"$Lang_files->cmd_file_view '$file_name'\"></a>";
	$cmd_del = "<a href=# onClick=\"if(confirm('$Lang_files->msg_confirm_delete_file \'$file_name\'?')) { location='files.php?mode=delete_file&name=$file_name'; } else { return false; }\" title=\"$Lang_files->cmd_file_del '$file_name'\"><img src=images/cmd_del.gif width=16 height=16 border=0 alt=\"$Lang_files->cmd_file_del '$file_name'\"></a>";
?>
	<tr>
	<td bgcolor=#f3f3f3><?php echo $files[$n]["name"] ?></td>
	<td bgcolor=#f3f3f3 align=center><?php echo $files[$n]["size"] ?></td>
	<td bgcolor=#f3f3f3 align=center><?php echo $files[$n]["date"] ?></td>
	<td bgcolor=#f3f3f3 align=center><?php echo $cmd_view ?> <?php echo $cmd_del ?></td>
	</tr>
<?php
	$n++;
    }
}
?>

</table>
</td></tr>
</table>
</td></tr>
</table>
<!-- END CONTENT -->
