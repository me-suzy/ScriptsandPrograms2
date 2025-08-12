<?php

// dbman_filter_pop.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Johannes Schlueter
// $Id: dbman_filter_pop.php,v 1.20 2005/07/22 18:32:55 paolo Exp $

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

include($path_pre.'lib/dbman_list.inc.php');
if (empty($_REQUEST['module'])) die('Wrong call');

// clean up some vars
$ID      = (int) $_REQUEST['ID'];
$use     = (int) $_REQUEST['use'];
$dele    = (int) $_REQUEST['dele'];
$add     = xss($_REQUEST['add']);
$mode    = xss($_REQUEST['mode']);
$module  = xss($_REQUEST['module']);
$opener  = xss($_REQUEST['opener']);
$caption = xss($_REQUEST['caption']);

if ($module == 'contacts') $actionstring = 'action=contacts';
else                       $actionstring = 'module='.$module;

$caption = 'o_'.$module;
$caption = $$caption;

$js_close = "
<script type='text/javascript'>
<!--
window.opener.location.href = '../$opener/$opener.php?$actionstring&mode=$mode&ID=$ID"."$sid&';
window.close();
//-->
</script>\n";

$js_reload = "
<script type='text/javascript'>
<!--
window.location.href = '../$module/$module.php?$actionstring"."$sid';
//-->
</script>\n";

//echo set_page_header();

if ($nav == $module) {
    $flist[$module] = load_filter($use, $module);
    header('Location: ../'.$module.'/'.$module.'.php?'.$actionstring.'&'.'mode='.$mode.'&'.$add.$sid);
    exit;
}
else {
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>'.__('Filter configuration').'</title>
<style type="text/css" media="screen">@import "../layout/default/default_css.php";</style>
<style type="text/css">
body {
    background-image: none;
}
</style>
<link rel="shortcut icon" href="/'.PHPR_INSTALL_DIR.'favicon.ico" />
</head>
<body>

<div class="topline"></div>
<div class="inner_content">
    <h3>'.__('Filter configuration').__(':').' '.$caption.'</h3>
';

    if ($use) {
        $flist[$module] = load_filter($use, $module);
        echo $js_close;
    } else if ($dele) {
        delete_filter($dele, $module);
        echo $js_close;
    } else if ($aufheben) {
        $flist[$module] = array();
        echo $js_close;
    } else if ($speichern) {
        save_filter($module, $speichern);
        echo $js_close;
    } else {
        $filter = get_filters($module);

        $hiddenfields = "<input type='hidden' name='module' value='$module' />\n".
                        "<input type='hidden' name='opener' value='$opener' />\n".
                        "<input type='hidden' name='mode'   value='$mode' />\n".
                        "<input type='hidden' name='ID'     value='$ID' />\n";
        if (SID) $hiddenfields .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />";

        echo '
    <div style="width:40%;float:left"></div>
    <div style="width:60%;float:right">
        <a href="./dbman_filter_pop.php?aufheben=1&amp;module='.$module.'&amp;opener='.$opener.'&amp;mode='.$mode.'&amp;'.$actionstring.'&amp;ID='.$ID.$sid.'">'.__('Disable set filters').'</a>
    </div>
    <br style="clear:both" /><br style="clear:both" />

    <form action="dbman_filter_pop.php" method="get">
        <div style="width:40%;float:left">'.__('Save currently set filters').'</div>
        <div style="width:60%;float:right">
            <input name="speichern" />
            '.get_buttons(array(array('type' => 'submit', 'active' => false, 'name' => '', 'value' => __('Save')))).'
        </div>
        '.$hiddenfields.'
    </form>
    <br style="clear:both" />

    <form action="dbman_filter_pop.php" method="get">
        <div style="width:40%;float:left">'.__('Load filter').'</div>
        <div style="width:60%;float:right">
        <select name="use">
            <option value=""></option>
';
        foreach ($filter as $id=>$value) {
            echo '<option value="'.$id.'">'.$value."</option>\n";
        }

        echo '
        </select>
        '.get_buttons(array(array('type' => 'submit', 'active' => false, 'name' => '', 'value' => __('use')))).'</div>
        '.$hiddenfields.'
    </form>
    <br style="clear:both" />

    <form action="dbman_filter_pop.php" method="get">
        <div style="width:40%;float:left">'.__('Delete saved filter').'</div>
        <div style="width:60%;float:right">
        <select name="dele">
            <option value=""></option>
';
        foreach ($filter as $id=>$value) {
            echo '<option value="'.$id.'">'.$value."</option>\n";
        }
        echo '
        </select>
        '.get_buttons(array(array('type' => 'submit', 'active' => false, 'name' => '', 'value' => __('Delete'), 'onclick' => 'return window.confirm(\''.__('Are you sure?').'\');'))).'</div>
        '.$hiddenfields.'
    </form>
    <br style="clear:both" />

    <a href="javascript:window.close()">'.__('Close window').'</a>
</div>

</body>
</html>
';
    }

    $_SESSION['flist'] =& $flist;
}

?>
