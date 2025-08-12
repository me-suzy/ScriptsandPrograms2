<?php

// filemanager_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: filemanager_forms.php,v 1.40.2.1 2005/09/12 11:28:39 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use filemanager.php!");

// check role
if (check_role("filemanager") < 1) die("You are not allowed to do this!");

include_once($lib_path."/access_form.inc.php");
if (eregi("xxx", $parent)) $parent = substr($parent, 14);

if ($justform == 2) $onload = array('window.opener.location.reload();', 'window.close();');
else if ($justform > 0) $justform++;

echo "
<script type='text/javascript'>
<!--
function showhide(elem,showme) {
    df = document.getElementById(elem);
    if (df) {
        if (showme) {
            fs = df.style;
            fs.display = 'block';
        } else {
            fs = df.style;
            fs.display = 'none';
        }
    }
    return false;
}
function sh_fields(slct) {
    sl = document.getElementById(slct);
    switch(sl.value) {
        case 'f':
            showhide('up1',true);
            showhide('up2',true);
            showhide('link1',false);
            break;
        case 'l':
            showhide('up1',false);
            showhide('up2',false);
            showhide('link1',true);
            break;
        default:
            showhide('up1',false);
            showhide('up2',false);
            showhide('link1',false);
    }
}
//-->
</script>
";

// check permission and fetch values for viewing or modifying a record
if ($ID > 0) {
    // check permission
    $result = db_query("select ID, von, acc_write, acc, parent, typ, lock_user, filename, tempname,						  version
                          from ".DB_PREFIX."dateien
                         where ID = '$ID' and
                               (acc like 'system' or ((von = '$user_ID' or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group))") or db_die();
    $row = db_fetch_row($result);
    if (!$row[0]) die("You are not privileged to do this!");
    if ($row[1] <> $user_ID and $row[2] <> 'w') $read_o = 1;
    else $read_o = 0;
    $typ    = $row[5];
    $parent = $row[4];
    $vers = $row[9];
}

if ($ID) $head = slookup('dateien', 'filename', 'ID', $ID);
else     $head = __('New file');
if (!$head) $head = __('New file');

// tabs
$tabs = array();

// form start
$hidden = array('mode' => 'data', 'page' => $page, 'ID'=>$ID, 'name'=>$user_name, session_name()=> session_id(), 'action'=>xss($action));
$form_fields = array();
$buttons = array();
if (SID) $hidden[session_name()] = session_id();
if ($justform > 0) $hidden['justform'] = '1';
$buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'enctype'=>"multipart/form-data", 'onsubmit' => 'return chkForm(\'frm\',\'typ\',\''.__('Please choose an element').'!\');');
$output = get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
if (!$read_o) {
    if (!$ID) {
        $buttons[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Accept'), 'active' => false);
        $buttons[] = array('type' => 'hidden', 'name' => 'anlegen', 'value' => 'neu_anlegen');
    } // modify and delete
    else {
        $buttons[] = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Accept'), 'active' => false);
        // change values
        $buttons[] = array('type' => 'hidden', 'name' => 'aendern', 'value' => 'aendern');
        // check whether there is no subproject beyond this one. if no -> allow to delete
        $result2 = db_query("select ID
                               from ".DB_PREFIX."projekte
                              where parent = '$ID'") or db_die();
        $row2 = db_fetch_row($result2);
        if ($row2[0] == '' && $row[1] == $user_ID) {
            $buttons[] = array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
        }
    }
} // end buttons chief only

// print & lock/unlock
if ($ID > 0) {
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);

    if (!$read_o) {
        if ($row[6]) {
            $buttons[] = array('type' => 'link', 'href' => 'filemanager.php?mode=data&amp;action=lockfile&amp;unlock=true&amp;lock=false&amp;ID='.$ID, 'text' => __('Unlock file'), 'active' => false);
        }
        else {
            $buttons[] = array('type' => 'link', 'href' => 'filemanager.php?mode=data&amp;action=lockfile&amp;unlock=false&amp;lock=true&amp;ID='.$ID, 'text' => __('Lock file'), 'active' => false);
        }
    }
    if ($row[6] == 0 || ($row[6] > 0 && $row[6] == $user_ID)) {
        $buttons[] = array('type' => 'link', 'href' => 'filemanager_down.php?mode=down&amp;mode2=attachment&amp;ID='.$ID, 'text' => __('Download').": ".__('Attachment'), 'active' => false);
        $buttons[] = array('type' => 'link', 'href' => 'filemanager_down.php?mode=down&amp;mode2=inline&amp;ID='.$ID, 'text' => __('Download').": ".__('Inline'), 'active' => false);
    }
    /* disable print buttons in 5.0
    $output.= "<input type='button' onclick='window.open(\"../misc/print.php?ID=$row[0]&module=proj\",\"_blank\")' value='".__('print')."' class='button' /></a>\n";
    */
}

// cancel
if ($justform > 0) {
    $buttons[] = array('type' => 'button', 'name' => 'close', 'value' => __('Close window'), 'active' => false, 'onclick' => 'window.close();');
}
else {
    $buttons[] = array('type' => 'link', 'href' => 'filemanager.php?type='.xss($type).'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('back'), 'active' => false);
}
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

/*******************************
*       basic fields
*******************************/

/********************************
// choose (upload, mkdir, link)
********************************/
$elem_types = array('f'=>__('Upload'),'d'=>__('Directory'),'l'=>__('Link'));

// selected filetype (file, directory, link)
if (!$ID && !$typ) $typ = 'f';

// update -> show selected type and draw hidden field
if ($ID) {
    $tmp = '';
    foreach ($elem_types as $elem_type => $elem_name) {
        if (ereg($elem_type, $typ)) {
            $tmp .= $elem_name;
            $form_fields[] = array('type' => 'hidden', 'name' => 'typ', 'value' => $elem_type);
            break;
        }
    }
    if (strlen($tmp)) $form_fields[] = array('type' => 'parsed_html', 'html' => $tmp);
    unset($tmp);
}
// insert -> show all possible types as links
else if ($justform == 0) {
    $tmp = '';
    foreach ($elem_types as $elem_type => $elem_name) {
        if (ereg($elem_type, $typ)) {
            $tmp .= $elem_name;
            $form_fields[] = array('type' => 'hidden', 'name' => 'typ', 'value' => $elem_type);
            break;
        }
    }
    $typ_link = "<a href='./filemanager.php?mode=forms&amp;new_note=1&amp;sort=$sort&amp;up=$up&amp;page=$page&amp;perpage=$perpage&amp;keyword=$keyword&amp;filter=$filter&amp;typ=";
    $tmp .= "&nbsp;&nbsp;".$typ_link."f".$sid."'>".__('Upload')."</a>";
    $tmp .= "&nbsp;|&nbsp;".$typ_link."d".$sid."'>".__('Create directory')."</a>";
    $tmp .= "&nbsp;|&nbsp;".$typ_link."l".$sid."'>".__('Link')."</a><br/>";
    $form_fields[] = array('type' => 'parsed_html', 'html' => $tmp);
    unset($tmp);
}

$tmp = '<div id="up1"';
if (in_array($typ,  array('d', 'l'))) $tmp .= ' style="display:none;"';
$tmp .= '>';

$form_fields[] = array('type' => 'parsed_html', 'html' => $tmp);
if (!((isset($_REQUEST['ID'])and ($row[6] > '0') and ($row[6]!=$user_ID )))) {
    $form_fields[] = array('type' => 'file', 'name' => 'userfile', 'label' => __('File').__(':'), 'value' => '');
    $form_fields[] = array('type' => 'parsed_html', 'html' => '<label class="formbody">&nbsp;</label><span class="options">('.__('Max. file size').': '.ini_get('upload_max_filesize').'Byte)</span>');
}

$form_fields[] = array('type' => 'parsed_html', 'html' => '</div>');

$tmp = '<div id="link1"';
if (in_array($typ,  array('f', 'd'))) $tmp .= ' style="display:none;"';
$tmp .= '>';

$form_fields[] = array('type' => 'parsed_html', 'html' => $tmp);
$form_fields[] = array('type' => 'text', 'name' => 'filepath', 'label' => __('name and network path').__(':'), 'value' => $row[8]);
$form_fields[] = array('type' => 'parsed_html', 'html' => '</div>');
$form_fields[] = array('type' => 'parsed_html', 'html' => '<script  type="text/javascript">sh_fields("typ");</script>');
$form_fields[] = array('type' => 'parsed_html', 'html' => build_form($fields));
$basic_fields = get_form_content($form_fields);

/*******************************
*    categorization fields
*******************************/
$form_fields = array();
$tmp = '
<label for="parent" class="form">'.__('Parent object').__(':').' </label>
<select class="form" id="parent" name="parent"'.read_o($read_o).'>
<option value="0"></option>
';

// define access rule
$access = " and (von = '$user_ID' or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group";
$where = "where (typ='d' or typ = 'fv') ".$access;
$tmp .= show_elements_of_tree("dateien","filename",$where,"acc"," order by typ,filename",$parent,"parent",$ID);

$tmp .= '</select>';
$form_fields[] = array('type' => 'parsed_html', 'html' => $tmp);

$tmp = '<div id="up2"';
if (in_array($typ, array('l', 'd'))) $tmp .= ' style="display:none;"';
$tmp .= '>';

$form_fields[] = array('type' => 'parsed_html', 'html' => $tmp);
$form_fields[] = array('type' => 'text', 'name' => 'new_sub_dir', 'label' => __('Create directory').__(':'), 'value' => '');
$form_fields[] = array('type' => 'password', 'name' => 'cryptstring', 'label' => __('Crypt upload file with password').__(':'), 'value' => '');
$form_fields[] = array('type' => 'password', 'name' => 'cryptstring2', 'label' => __('Repeat').__(':'), 'value' => '');

if (ereg('v', $typ)) {
    $form_fields[] = array('type' => 'checkbox', 'name' => 'versioning', 'label' => __('Version management').__(':'), 'value' => '1', 'checked' => ereg('v', $typ));
}
else {
    $form_fields[] = array('type' => 'checkbox', 'name' => 'versioning', 'label' => __('Version management').__(':'), 'value' => '1');
}
if ($vers > 0) { 
	$vers++;
	$form_fields[] = array('type' => 'parsed_html', 'html' => $vers.'. '.__('Version'));
}

$form_fields[] = array('type' => 'parsed_html', 'html' => '<script type="text/javascript">sh_fields("typ");</script>');
$form_fields[] = array('type' => 'parsed_html', 'html' => '</div>');
$categorization_fields = get_form_content($form_fields);

/*******************************
*    assignment fields
*******************************/
$form_fields = array();
include_once("../lib/access_form.inc.php");
// acc_read, exclude the user itself, acc_write, no parent possible, write access=yes
$form_fields[] = array('type' => 'parsed_html', 'html' => access_form2($row[3], 1, $row[2], 0, 1));
$assignment_fields = get_form_content($form_fields);

$output .= '
    <br />
    <div class="inner_content">
        <a name="content"></a>
        <a name="oben" id="oben"></a>
        <div class="boxHeaderLeft">'.__('Basis data').'</div>
        <div class="boxHeaderRight"><a class="formBoxHeader" href="#unten">'.__('Links').'</a></div>
        <div class="boxContent">'.$basic_fields.'</div></div>
        <br style="clear:both" /><br />

        <a name="unten" id="unten"></a>
        <div class="boxHeaderLeft">'.__('Categorization').'</div>
        <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
        <div class="boxContent">'.$categorization_fields.'</div>
        <br style="clear:both" /><br />

        <div class="boxHeaderLeft">'.__('Assignment').'</div>
        <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
        <div class="boxContent">'.$assignment_fields.'</div>
        <br style="clear:both" /><br />
    </div>
    <br style="clear:both" /><br />
</form>
';

echo $output;

?>
