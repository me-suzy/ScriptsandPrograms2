<script>
function switch_url_field() {
    var is_url_external = document.getElementById("is_url_external");
    var name_url = document.getElementById("name_url");
    var name_url_external = document.getElementById("name_url_external");
    if (is_url_external.value==1) {
	name_url.disabled = true;
	name_url_external.disabled = false;
    } else {
	name_url.disabled = false;
	name_url_external.disabled = true;
    }
}

function verify_form() {
    var error_msg = "";
    if (document.getElementById("name_menu").value=="") {
	error_msg = error_msg + "<?php echo $Lang_pages->error_name_menu_empty ?>" + "\n";
    }
    if (document.getElementById("name_page").value=="") {
	error_msg = error_msg + "<?php echo $Lang_pages->error_name_page_empty ?>" + "\n";
    }
    
<?php
if($frm["id"]!=0) {
?>
    if (document.getElementById("is_url_external").value=="1") {
	if (document.getElementById("name_url_external").value=="") {
	    error_msg = error_msg + "<?php echo $Lang_pages->error_name_url_external_empty ?>" + "\n";
	} 
    } else {
	if (document.getElementById("name_url").value=="") {
	    error_msg = error_msg + "<?php echo $Lang_pages->error_name_url_empty ?>" + "\n";
	} else {
	    if (document.getElementById("name_url").value.match("[^a-z0-9A-Z_-]+")) {
		error_msg = error_msg + "<?php echo $Lang_pages->error_name_url_invalid ?>" + "\n";
	    }
	}
    }
<?php
}
?>
    
//    if (document.getElementById("parent").value=="<?php echo $frm[id]?>") {
//	error_msg = error_msg + "<?php echo $Lang_pages->error_parent_invalid ?>" + "\n";
//    }
    if (document.getElementById("redirect").value=="<?php echo $frm[id]?>" && document.getElementById("redirect").value!="-1") {
	error_msg = error_msg + "<?php echo $Lang_pages->error_redirect_invalid ?>" + "\n";
    }
    
    if (error_msg=="") {
	return true;
    } else {
	alert(error_msg);
	return false;
    }
}
</script>

<?php
$page_tree = $this->get_page_list();
?>

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
<?php echo $frm["header"] ?>
</h2>

<!-- START CONTENT -->
<table width=450 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<form name=page_form action=pages.php method=post onSubmit="return verify_form();">
<input type=hidden name=id value="<?php echo $frm["id"] ?>">
<input type=hidden name=mode value="<?php echo $frm["mode"] ?>">

<?php
if ($frm["mode"]=="insert_new_page") {
?>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_parent ?>:</td>
<td bgcolor=#f3f3f3>
<select id=parent name=parent style="width:100%; border-width:1; border-color:#363636">
<?php
for($n=0; $n<count($page_tree); $n++) {
    $indent = "";
    $page = $page_tree[$n]["page"];
    for($i=1; $i<$page["level"]; $i++) { $indent .= "&nbsp;&nbsp;"; }
    if ($page["id"]==$frm["parent"]) { 
	echo "<option value=".$page["id"]." selected>".$indent.$page["name_menu"]."</option>";
    } else {
	echo "<option value=".$page["id"].">".$indent.$page["name_menu"]."</option>";
    }

}
?>
</select>
</td>
</tr>
<?php
} else {
?>
<input type=hidden name=parent value="<?php echo $frm["parent"] ?>">
<?php
}
?>


<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_is_visible ?>:</td>
<td bgcolor=#f3f3f3>
<select name=is_visible style="width:100%; border-width:1; border-color:#363636">
<?php
if ($frm["is_visible"]==1) {
    echo "<option value=1 selected>".$Lang->str_yes."</option>";
    echo "<option value=0>".$Lang->str_no."</option>";
} else {
    echo "<option value=1>".$Lang->str_yes."</option>";
    echo "<option value=0 selected>".$Lang->str_no."</option>";
}
?>
</select>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_name_menu ?><font color=#c80000>*</font>:</td>
<td bgcolor=#f3f3f3><input type=text id=name_menu name=name_menu value="<?php echo $frm["name_menu"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_name_title ?>:</td>
<td bgcolor=#f3f3f3><input type=text name=name_title value="<?php echo $frm["name_title"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_name_page ?><font color=#c80000>*</font>:</td>
<td bgcolor=#f3f3f3><input type=text id=name_page name=name_page value="<?php echo $frm["name_page"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3>&nbsp;</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_redirect ?>:</td>
<td bgcolor=#f3f3f3>
<select id=redirect name=redirect style="width:100%; border-width:1; border-color:#363636">
<?php
echo "<option value=-1>".$Lang_pages->str_no_redirect."</option>";
for($n=0; $n<count($page_tree); $n++) {
    $indent = "";
    $page = $page_tree[$n]["page"];
    for($i=1; $i<$page["level"]; $i++) { $indent .= "&nbsp;&nbsp;"; }
    if ($page["id"]==$frm["redirect"]) { 
	echo "<option value=".$page["id"]." selected>".$indent.$page["name_menu"]."</option>";
    } else {
	echo "<option value=".$page["id"].">".$indent.$page["name_menu"]."</option>";
    }

}
?>
</select>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3>&nbsp;</td>
</tr>

<?php
if ($frm["id"]!=0) {
?>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_is_url_external ?>:</td>
<td bgcolor=#f3f3f3>
<select id=is_url_external name=is_url_external style="width:100%; border-width:1; border-color:#363636" onChange="switch_url_field(); return true;">
<?php
if ($frm["is_url_external"]==1) {
    echo "<option value=1 selected>".$Lang->str_yes."</option>";
    echo "<option value=0>".$Lang->str_no."</option>";
    $disable_name_url = "disabled";
    $disable_name_url_external = "";
} else {
    echo "<option value=1>".$Lang->str_yes."</option>";
    echo "<option value=0 selected>".$Lang->str_no."</option>";
    $disable_name_url = "";
    $disable_name_url_external = "disabled";
}
?>
</select>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_name_url ?><font color=#c80000>*</font>:</td>
<td bgcolor=#f3f3f3><input <? echo $disable_name_url ?>  type=text id=name_url name=name_url value="<?php echo $frm["name_url"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_name_url_external ?><font color=#c80000>*</font>:</td>
<td bgcolor=#f3f3f3><input <? echo $disable_name_url_external ?>  type=text id=name_url_external name=name_url_external value="<?php echo $frm["name_url_external"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3>&nbsp;</td>
<td bgcolor=#f3f3f3>&nbsp;</td>
</tr>
<?php
} else {
?>
<input type=hidden name=is_url_external value="0">
<input type=hidden name=name_url value="">
<input type=hidden name=name_url_external value="">
<?php
}
?>


<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_website_title ?>:</td>
<td bgcolor=#f3f3f3><input type=text name=website_title value="<?php echo $frm["website_title"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><?php echo $Lang_pages->field_website_keywords ?>:</td>
<td bgcolor=#f3f3f3><input type=text name=website_keywords value="<?php echo $frm["website_keywords"] ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3 valign=top><?php echo $Lang_pages->field_website_description ?>:</td>
<td bgcolor=#f3f3f3>
<textarea name=website_description rows=5 style="width:100%; border-width:1; border-color:#363636">
<?php echo $frm["website_keywords"] ?>
</textarea>
</td>
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
<?php echo $Lang_pages->notes_add_new_page ?>
</p>
<!-- END CONTENT -->
