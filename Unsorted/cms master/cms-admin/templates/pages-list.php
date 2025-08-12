<form name=page_form method=POST action=pages.php>
<input type=hidden name=content value="">
<input type=hidden name=id value="">
<input type=hidden name=mode value="">
</form>

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
<?php include("$CFG->dir_admin_templates/pages-menu.php") ?>
<!-- END MENU 2 -->

</td>
</tr>
</table>
</td>
<td width=20 valign=top><img src=images/trans.gif width=20 height=1 border=0></td>
<td width=450 valign=top>
<img src=images/trans.gif width=450 height=10 border=0>

<h2>
<?php echo $Lang_pages->header_page_list ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>

<?php
if ($count_pages==0) {
    echo "<tr>\n";
    echo "<td bgcolor=#f3f3f3 align=center><br>".$Lang_pages->msg_no_pages."<br><br></td>\n";
    echo "</tr>\n";
} else {
?>
<tr>
<td bgcolor=#c80000 width=320><font color=#ffffff><b><?php echo $Lang_pages->table_page_name ?></b></font></td>
<td bgcolor=#c80000 align=center><font color=#ffffff><b><?php echo $Lang_pages->table_page_cmd ?></b></font></td>
</tr>

<?php
    $n=1;
    while($page_tree[$n]) {
	$page = $page_tree[$n]["page"];
	$cmd_edit = "<a href=# onClick=\"edit_page = window.open('templates/pages-edit.php?id=$page[id]', 'page_edit', 'width=730,height=480,toolbar=0'); return false;\" title=\"$Lang_pages->cmd_page_edit '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"><img src=images/cmd_edit.gif width=16 height=16 border=0 alt=\"$Lang_pages->cmd_page_edit '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"></a>";
	$cmd_option = "<a href=pages.php?mode=edit_page_options&id=$page[id] title=\"$Lang_pages->cmd_page_option '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"><img src=images/cmd_option.gif width=16 height=16 border=0 alt=\"$Lang_pages->cmd_page_option '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"></a>";
	$cmd_del = "<a href=# onClick=\"if(confirm('$Lang_pages->msg_confirm_delete_page \'".preg_replace("/\"/", "&#34;", preg_replace("/'/", "\\'", $page["name_menu"]))."\'?')) { location='pages.php?mode=delete_page&id=$page[id]'; } else { return false; }\" title=\"$Lang_pages->cmd_page_del '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"><img src=images/cmd_del.gif width=16 height=16 border=0 alt=\"$Lang_pages->cmd_page_del '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"></a>";
	$cmd_moveup = "<a href=pages.php?mode=move_page_up&id=$page[id] title=\"$Lang_pages->cmd_page_moveup '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"><img src=images/cmd_moveup.gif width=16 height=16 border=0 alt=\"$Lang_pages->cmd_page_moveup '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"></a>";
	$cmd_movedown = "<a href=pages.php?mode=move_page_down&id=$page[id] title=\"$Lang_pages->cmd_page_movedown '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"><img src=images/cmd_movedown.gif width=16 height=16 border=0 alt=\"$Lang_pages->cmd_page_movedown '".preg_replace("/\"/", "&#34;", $page["name_menu"])."'\"></a>";
	$indent = "";
	for($i=1; $i<$page["level"]; $i++) { $indent .= "&nbsp;&nbsp;"; }
?>
    <tr>
    <td bgcolor=#f3f3f3 width=320><?php echo $indent ?><?php echo $page["name_menu"] ?></td>
    <td bgcolor=#f3f3f3 align=center><?php echo $cmd_edit ?> <?php echo $cmd_option ?> <?php echo $cmd_del ?>&nbsp;&nbsp;<?php echo $cmd_moveup ?> <?php echo $cmd_movedown ?></td>
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
