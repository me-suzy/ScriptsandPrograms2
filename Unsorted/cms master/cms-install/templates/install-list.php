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
<?php echo $Lang_install->header_install_list ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>

<tr>
<td bgcolor=#c80000 width=350><font color=#ffffff><b><?php echo $Lang_install->table_name ?></b></font></td>
<td bgcolor=#c80000 align=center><font color=#ffffff><b><?php echo $Lang_install->table_cmd ?></b></font></td>
</tr>

<?php
$n = 0;
while($component[$n]) {
    if ($component[$n]["status"]==1) {
	if ($n==0) {
	    $cmd = "<img src=images/cmd_del.gif width=16 height=16 border=0 alt=\"$Lang_install->cmd_cant_delete '".$component[$n]["title"]."'\">";
	} else {
	    $cmd = "<a href=\"".$conponent[$n]["file"]."\" title=\"$Lang_install->cmd_delete '".$component[$n]["title"]."'\"><img src=images/cmd_del.gif width=16 height=16 border=0 alt=\"$Lang_install->cmd_delete '".$component[$n]["title"]."'\"></a>";
	}
    } else {
	$cmd = "<a href=\"".$component[$n]["file"]."\" title=\"$Lang_install->cmd_install '".$component[$n]["title"]."'\"><img src=images/cmd_go.gif width=16 height=16 border=0 alt=\"$Lang_install->cmd_install '".$component[$n]["title"]."'\"></a>";
    }
?>
    <tr>
    <td bgcolor=#f3f3f3 width=320 valign=top><b><?php echo $component[$n]["title"] ?></b><br><?php echo $component[$n]["description"] ?></td>
    <td bgcolor=#f3f3f3 align=center valign=top><?php echo $cmd ?></td>
    </tr>
<?php
    $n++;
}
?>
</table>
</td></tr>
</table>
</td></tr>
</table>
<!-- END CONTENT -->
