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
<?php echo $Lang_system->header_system_stat ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<tr>
<td bgcolor=#c80000 colspan=2><font color=#ffffff><b><?php echo $Lang_system->table_system_pages ?></b></font></td>
</tr>
<tr>
<td bgcolor=#f3f3f3 width=370><?php echo $Lang_system->field_count_pages ?></td>
<td bgcolor=#f3f3f3 align=center><?php echo $count_pages ?></td>
</tr>
<tr>
<td bgcolor=#f3f3f3><?php echo $Lang_system->field_count_visible_pages ?></td>
<td bgcolor=#f3f3f3 align=center><?php echo $count_visible_pages ?></td>
</tr>
<tr>
<td bgcolor=#f3f3f3><?php echo $Lang_system->field_count_hidden_pages ?></td>
<td bgcolor=#f3f3f3 align=center><?php echo $count_hidden_pages ?></td>
</tr>

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
<tr>
<td bgcolor=#c80000 colspan=2><font color=#ffffff><b><?php echo $Lang_system->table_system_images ?></b></font></td>
</tr>
<tr>
<td bgcolor=#f3f3f3 width=370><?php echo $Lang_system->field_count_images ?></td>
<td bgcolor=#f3f3f3 align=center><?php echo $count_images ?></td>
</tr>
<tr>
<td bgcolor=#f3f3f3><?php echo $Lang_system->field_images_size ?></td>
<td bgcolor=#f3f3f3 align=center><?php echo $images_size ?></td>
</tr>

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
<tr>
<td bgcolor=#c80000 colspan=2><font color=#ffffff><b><?php echo $Lang_system->table_system_files ?></b></font></td>
</tr>
<tr>
<td bgcolor=#f3f3f3 width=370><?php echo $Lang_system->field_count_files ?></td>
<td bgcolor=#f3f3f3 align=center><?php echo $count_files ?></td>
</tr>
<tr>
<td bgcolor=#f3f3f3><?php echo $Lang_system->field_files_size ?></td>
<td bgcolor=#f3f3f3 align=center><?php echo $files_size ?></td>
</tr>

</table>
</td></tr>
</table>
</td></tr>
</table>
<!-- END CONTENT -->
