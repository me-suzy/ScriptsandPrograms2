<?php

// show_related.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: show_related.inc.php,v 1.34.2.3 2005/09/14 11:04:10 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) {
    die('Please use index.php!');
}

echo '
<script type="text/javascript">
<!--
function auf(url, name, det) {
    var ex = window.open(url, name, det);
    if (ex.opener == null) {
        ex.opener = window;
    }
}
//-->
</script>
';

// adjust position of the new window:
$position = "'left=5,top=5,height=540,width=760,scrollbars=1,resizable=1'";

// show realted objects from module designer
function show_related($moduleb, $linkb, $query='', $referer='') {
    global $mode,  $ID, $user_ID, $user_kurz, $sql_user_group, $fields, $fieldlist,$module,$link;
    global $filter_module, $filter, $rule, $keyword, $filter_ID, $flist, $projekt_ID, $contact_ID;
    global $nrel_get, $nrel_sess, $sort_module;
    // FIXME the following sets the global $module! All modules set $module back after this call
    $module=$moduleb;
    $link=$linkb;
    $position = "'left=5,top=5,height=540,width=760,scrollbars=1,resizable=1'";
    $outputrel = '';
    $contextmenu=0;
    switch ($module) {
        case 'dateien':
            $caption = __('Files');
            $news = __('New files');
            $table='dateien';
            break;
        case 'todo':
            $caption = __('Todo');
            $news = __('New todo');
            $table='todo';
            break;
        case 'notes':
            $caption = __('Notes');
            $news = __('New notes');
            $table='notes';
            break;
        case 'projects':
            $caption = __('Projects');
            $news = __('New project');
            $table='projekte';
            break;
        default:
            // to be completed .....
            $caption = $module.': $caption not defined in '.__FILE__.' in line '.__LINE__;
            $news = $module.': $news not defined in '.__FILE__.' in line '.__LINE__;
            break;
    }

    // disable lookups on disabled modules
    if ($table == 'projekte'    && !PHPR_PROJECTS)    return "";
    if ($table == 'timecard'    && !PHPR_TIMECARD)    return "";
    if ($table == 'contacts'    && !PHPR_CONTACTS)    return "";
    if ($table == 'notes'       && !PHPR_NOTES)       return "";
    if ($table == 'todo'        && !PHPR_TODO)        return "";
    if (strstr($table, 'mail_') && !PHPR_QUICKMAIL)   return "";
    if ($table == 'dateien'     && !PHPR_FILEMANAGER) return "";
    if ($table == 'forum'       && !PHPR_FORUM)       return "";
    if ($table == 'rts'         && !PHPR_RTS)         return "";

    $module1 = $module;
    if ($module == 'dateien') $module1 = 'filemanager';

    if (isset($nrel_get[$module])) {
        $perpage = $nrel_get[$module];
        $nrel_sess[$module] = $perpage;
        $_SESSION['nrel_sess'] =& $nrel_sess;
    }
    else if (isset($nrel_sess[$module])) {
        $perpage = $nrel_sess[$module];
    }
    else {
        $perpage = 5;
    }
    if ($query) $query = ' and '.$query;
    $nrel_sess = show_nrel("$link.php?mode=$mode&ID=$ID", $module);
    $fields = build_array($module, null, 'view');
    if ($filter_module == $module) {
        $where = main_filter($filter, $rule, $keyword, $filter_ID, $module, '');
    }
    else {
        $where = main_filter('', '', '', '', $module, '');
    }


    $has_to_be_sorted = ($sort_module == $module);
    $nwhere = " WHERE (acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))
                    $query
                    $where
                    ".sql_filter_flags($module, array('archive', 'read'), false);           
    $res=db_query("SELECT COUNT(*) FROM ".qss(DB_PREFIX.$table).
    sql_filter_flags($module, array('archive', 'read')) .
    $nwhere)or db_die();
    $rowcount= db_fetch_row($res);
    $relcount= $rowcount[0];


    $fieldlist = array();

    $outputrel = '
    <div class="relObjHead">';
    //<div class="relObjLeftAlign">
    $outputrel.='
    <div class="relObjHeadFirstCol"><b>'.$caption.'</b>&nbsp;&nbsp;';
    $outputrel.='<input title="'.__('This button opens a popup window').'" name="tcstart" value="'.$news.'" class="button2b" onclick ="window.open(\'../'.$module1.'/'.$module1.'.php?mode=forms&projekt_ID='.$projekt_ID.'&contact_ID='.$contact_ID.'&justform=1'.$sid.'\', \'related_object\', \'width=760px,height=540px,scrollbars=yes\');" type="button" />';

    $outputrel.='</div>';
    $outputrel.='<div class="relObjHeadNextCol">'.__('Count').__(':').' '.$relcount.'</div>';
    $outputrel.='
    <div class="relObjHeadNextCol">'.__('View').__(':').'</div>
    <div class="relObjHeadNextCol">'.$nrel_sess['out'].'</div>
    ';
    $outputrel .= '<div class="relObjRightAlign"><a name="unten" id="unten"></a>
                        <a class="pa" href="#oben">'.__('Basis data').'</a></div></div>
                        <div class="hline"></div>';
    //$outputrel .= '<br style="clear:both"/>';
    if($flist[$module]||$relcount>0){
        $outputrel .= get_filter_edit_bar(true,$link);
        $outputrel .= get_status_bar();
    }
    if($relcount>0){
        $outputrel .= build_table(array('ID', 'von', 'acc', 'parent'), $module, $nwhere, 0, $perpage, $link, 700, true);
    }
    return $outputrel;
}


// show notes related to a record
function show_related_notes($where, $referer) {
    global $module;
    $out = show_related('notes', $module, $where, $referer);
    return $out;
}


// show files related to a record
function show_related_files($where, $referer) {
    global $module;
    $out = show_related('dateien', $module, $where, $referer);
    return $out;
}


// show todos related to a record
function show_related_todo($where, $referer) {
    global $module;
    $out = show_related('todo', $module, $where, $referer);
    return $out;
}


// show events related to a record
function show_related_events($where, $referer) {
    return "";
    global $user_ID, $sid, $img_path, $flist, $module;
    global $projekt_ID, $contact_ID;

    $res = db_query("SELECT COUNT(*)
                           FROM ".DB_PREFIX."termine
                          WHERE ".xss($where)."
                            AND (von = '$user_ID' OR an = '$user_ID')
                       ORDER BY datum DESC") or db_die();
    $rowcount= db_fetch_row($res);
    $relcount= $rowcount[0];
    $nrel_sess = show_nrel("$referer", 'calendar');

    $outputrel = '
<div class="relObjHead">
<div class="relObjHeadFirstCol"><b>'.__('Calendar').'</b>&nbsp;&nbsp;
<input title="'.__('This button opens a popup window').'" name="tcstart" value="'.__('New event').'" class="button2b" onclick ="window.open(\'../calendar/calendar.php?mode=forms&projekt_ID='.$projekt_ID.'&contact_ID='.$contact_ID.'&justform=1&'.SID.'\', \'related_object\', \'width=760px,height=540px,scrollbars=yes\');" type="button" />
</div>
<div class="relObjHeadNextCol">'.__('Count').__(':').' '.$relcount.'</div>
    <div class="relObjHeadNextCol">'.__('View').__(':').'</div>
    <div class="relObjHeadNextCol">'.$nrel_sess['out'].'</div>
<div class="relObjRightAlign"><a name="unten" id="unten"></a>
                        <a class="pa" href="#oben">'.__('Basis data').'</a></div></div>
                        <div class="hline"></div>

';

    if($flist[$module]||$relcount>0){
        $outputrel .= get_filter_edit_bar(true,$module);
        $outputrel .= get_status_bar();
    }

    if($relcount>0){


        // if there isn't any filter defined, you get future events.
        if (!isset($flist[$module][0]) and !isset($flist_store[$module][0]) and !isset($filter) and !$filter_ID) {
            $filter  = 'datum';
            $rule    = '>=';
            $keyword = sprintf("%04d-%02d-%02d", $year, $month, $day);
            $f_sort['calendar']['sort']      = 'datum,anfang';
            $f_sort['calendar']['direction'] = 'ASC';
        }
        $where = main_filter($filter, $rule, $keyword, $filter_ID, 'calendar');
        $query = "SELECT ID
            FROM ".DB_PREFIX."termine
                 ".sql_filter_flags($module, array('archive', 'read'))."
           WHERE (von='$user_ID' OR an='$user_ID')
                 $where ".sql_filter_flags($module, array('archive', 'read'), false);
        $result = db_query($query) or db_die();
        $liste  = make_list($result);
        // distinction
        if ($view == 4 && $act_for > 0) {
            // 1. case: act as proxy
            $where_an = "an='$act_for'";
        }
        else {
            // 2. case: my own calendar (default)
            $where_an = "an='$user_ID'";
        }

        $where = " WHERE $where_an $where ".sort_string();

        if ($act_for > 0) $getstring = 'act_for='.$act_for;

        global $fields, $fieldlist;
        $fields = array();
        $fieldlist = array();
        $outputrel .= build_table(array('ID', 'von', 'event'), 'calendar', $where, 0, 10, $module, 700, true);


    }

    return $outputrel;

}


// show projects related to a record 8at the moment only contacts
function show_related_projects($query, $referer) {
    global $module;
    $out = show_related('projects', $module, $query, $referer);
    return $out;
}


function show_nrel($referer, $module) {
    global $nrel_sess, $nrel_get, $sid, $outputrel;

    // set default
    if (!isset($nrel_get[$module]) and !isset($nrel_sess[$module])) {
        $nrel_sess[$module] = $nrel_get[$module] = '5';
    }
    // store into session
    if ($nrel_get[$module] <> '') {
        $nrel_sess[$module] = $nrel_get[$module];
        $_SESSION['nrel_sess'] =& $nrel_sess;
    }
    $values = array('0', '5', '20', '100');
    foreach ($values as $value) {
        ($value == $nrel_sess[$module]) ? $style = "class='count_related'" : $style = '';
        $out .= "<a href=\"".$referer."&nrel_get[".$module."]=".$value.$sid."\" ".$style.'>'.$value."</a> | ";
    }
    $nrel_sess['out'] = $out;
    return $nrel_sess;
}

?>
