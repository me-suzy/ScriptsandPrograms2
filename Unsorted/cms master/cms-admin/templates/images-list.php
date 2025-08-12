<script>
function verify_form() {
    if(document.image_form.image.value=="") {
	alert("<?php echo $Lang_images->error_image_empty ?>");
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
<?php echo $Lang_images->header_images_list ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<form name=image_form onSubmit="return verify_form();" action=images.php method=post enctype="multipart/form-data">
<input type=hidden name=mode value=upload_image>
<tr>
<td bgcolor=#c80000 colspan=2><font color=#ffffff><b><?php echo $Lang_images->table_image_add ?></b></font></td>
</tr>
<tr>
<td bgcolor=#f3f3f3 width=340><input type=file name=image style="width:100%; border-width:1; border-color:#363636"></td>
<td bgcolor=#f3f3f3><input type=submit value="<?php echo $Lang_images->button_upload ?>" style="width:100%; border-width:1; border-color:#363636"></td>
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
if ($count_images==0) {
?>
<tr>
<td bgcolor=#f3f3f3 align=center><br><?php echo $Lang_images->msg_no_images ?><br><br></td>
</tr>
<?php
} else {
?>
<tr>
<td bgcolor=#c80000 width=220><font color=#ffffff><b><?php echo $Lang_images->table_image_name ?></b></font></td>
<td bgcolor=#c80000 width=50 align=center><font color=#ffffff><b><?php echo $Lang_images->table_image_size ?></b></font></td>
<td bgcolor=#c80000 width=70 align=center><font color=#ffffff><b><?php echo $Lang_images->table_image_date ?></b></font></td>
<td bgcolor=#c80000 align=center><font color=#ffffff><b><?php echo $Lang_images->table_image_cmd ?></b></font></td>
</tr>
<?php
    $n = 0;
    while($images[$n]) {
	$image_name = $images[$n]["name"];
	$image_size = $images[$n]["size"];
	if ($image_size[0]<100) { $image_size[0] = 105; } else { $image_size[0] = $image_size[0] + 5; }
	if ($image_size[1]<100) { $image_size[1] = 105; } else { $image_size[1] = $image_size[1] + 5; }
	$cmd_view = "<a href=# onClick=\"window.open('templates/images-view.php?name=$image_name', 'image_view', 'width=$image_size[0],height=$image_size[1],toolbar=0'); return false;\" title=\"$Lang_images->cmd_image_view '$image_name'\"><img src=images/cmd_view.gif width=16 height=16 border=0 alt=\"$Lang_images->cmd_image_view '$image_name'\"></a>";
	$cmd_del = "<a href=# onClick=\"if(confirm('$Lang_images->msg_confirm_delete_image \'$image_name\'?')) { location='images.php?mode=delete_image&name=$image_name'; } else { return false; }\" title=\"$Lang_images->cmd_image_del '$image_name'\"><img src=images/cmd_del.gif width=16 height=16 border=0 alt=\"$Lang_images->cmd_image_del '$image_name'\"></a>";
?>
	<tr>
	<td bgcolor=#f3f3f3><?php echo $images[$n]["name"] ?></td>
	<td bgcolor=#f3f3f3 align=center><?php echo $images[$n]["size"][0] ?>x<?php echo $images[$n]["size"][1] ?></td>
	<td bgcolor=#f3f3f3 align=center><?php echo $images[$n]["date"] ?></td>
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
