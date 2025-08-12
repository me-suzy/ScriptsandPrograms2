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
<?php echo $Lang_install->header_process_installation ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<tr valign=top>
<td bgcolor=#f3f3f3 width=330 valign=top>
<b><?php echo $Lang_install->msg_check_htaccess ?></b>
<?php
if ($check["htaccess"]==0) {
    echo "<br>$Lang_install->notes_htaccess";
}
?>
</td>
<td bgcolor=#f3f3f3 valign=top align=center>
<?php
if ($check["htaccess"]==0) {
    echo "<font color=#c80000><b>$Lang_install->str_error</b></font>";
} else {
    echo "<font color=#00c800><b>$Lang_install->str_ok</b></font>";
}
?>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3 width=330 valign=top>
<b><?php echo $Lang_install->msg_check_cms_config ?></b>
<?php
if ($check["cms_config"]==0) {
    echo "<br>$Lang_install->notes_cms_config";
}
?>
</td>
<td bgcolor=#f3f3f3 valign=top align=center>
<?php
if ($check["cms_config"]==0) {
    echo "<font color=#c80000><b>$Lang_install->str_error</b></font>";
} else {
    echo "<font color=#00c800><b>$Lang_install->str_ok</b></font>";
}
?>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3 width=330 valign=top>
<b><?php echo $Lang_install->msg_check_cms_images ?></b>
<?php
if ($check["cms_images"]==0) {
    echo "<br>$Lang_install->notes_cms_images";
}
?>
</td>
<td bgcolor=#f3f3f3 valign=top align=center>
<?php
if ($check["cms_images"]==0) {
    echo "<font color=#c80000><b>$Lang_install->str_error</b></font>";
} else {
    echo "<font color=#00c800><b>$Lang_install->str_ok</b></font>";
}
?>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3 width=330 valign=top>
<b><?php echo $Lang_install->msg_check_cms_files ?></b>
<?php
if ($check["cms_files"]==0) {
    echo "<br>$Lang_install->notes_cms_files";
}
?>
</td>
<td bgcolor=#f3f3f3 valign=top align=center>
<?php
if ($check["cms_files"]==0) {
    echo "<font color=#c80000><b>$Lang_install->str_error</b></font>";
} else {
    echo "<font color=#00c800><b>$Lang_install->str_ok</b></font>";
}
?>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3 width=330 valign=top>
<b><?php echo $Lang_install->msg_check_cms_pages ?></b>
<?php
if ($check["cms_pages"]==0) {
    echo "<br>$Lang_install->notes_cms_pages";
}
?>
</td>
<td bgcolor=#f3f3f3 valign=top align=center>
<?php
if ($check["cms_pages"]==0) {
    echo "<font color=#c80000><b>$Lang_install->str_error</b></font>";
} else {
    echo "<font color=#00c800><b>$Lang_install->str_ok</b></font>";
}
?>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3 width=330 valign=top>
<b><?php echo $Lang_install->msg_check_db ?></b>
<?php
if ($check["db"]==0) {
    echo "<br>$Lang_install->notes_db";
}
?>
</td>
<td bgcolor=#f3f3f3 valign=top align=center>
<?php
if ($check["db"]==0) {
    echo "<font color=#c80000><b>$Lang_install->str_error</b></font>";
} else {
    echo "<font color=#00c800><b>$Lang_install->str_ok</b></font>";
}
?>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3 colspan=2>&nbsp;</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3 colspan=2 align=center>
<?php
if ($error==0) {
?>
<input type=button onClick="location = '/cms-admin';" value="<?php echo $Lang_install->button_login ?>"  style="width:100%; border-width:1; border-color:#363636">
<?php
} else {
?>
<input type=button onClick="history.go(-1);" value="<?php echo $Lang_install->button_back ?>" style="width:100%; border-width:1; border-color:#363636">
<?php
}
?>
</td>
</tr>
</table>
</td></tr>
</table>
</td></tr>
</table>
<p>
<?php echo $Lang_install->notes_options_form ?>
</p>
<!-- END CONTENT -->
