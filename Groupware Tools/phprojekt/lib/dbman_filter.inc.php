<?php

// dbman_filter.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: johann $
// $Id: dbman_filter.inc.php,v 1.70.2.2 2005/08/30 16:57:14 johann Exp $

// provides column name as input field
function col_filter($module, $col_name, $link=null, $cw) {
    global $field_name, $field, $perpage, $page, $img_path, $f_sort, $user_ID,$getstring;
    global $user_kurz, $sql_user_group, $ID, $page, $perpage, $mode, $keyword,$user_group;

    $cw = $cw-10;
    if ($link == null) $link = $module;

    // start form
    $str = "
<form action='".$link.".php?".$getstring."' name='".$field_name."' method='post' style='display:inline;'>
    <input type='hidden' name='mode' value='view' />
    <input type='hidden' name='filter_module' value='$module' />

<noscript>
    <a href='$link.php?mode=view&amp;sort_module=$module&amp;direction=ASC&amp;page=$page&amp;perpage=$perpage&amp;sort=$col_name&amp;$getstring'><img src='../img/ASC".((isset($_REQUEST['direction']) and isset($_REQUEST['sort']) and $_REQUEST['direction'] == 'ASC' and $_REQUEST['sort'] == $col_name) ? '_sel' : '').".gif' alt='ascending' /></a>
    <a href='$link.php?mode=view&amp;sort_module=$module&amp;direction=DESC&amp;page=$page&amp;perpage=$perpage&amp;sort=$col_name&amp;$getstring'><img src='../img/DESC".((isset($_REQUEST['direction']) and isset($_REQUEST['sort']) and $_REQUEST['direction'] == 'DESC' and $_REQUEST['sort'] == $col_name) ? '_sel' : '').".gif' alt='descending' /></a>
    <br />
</noscript>
";
    // offer a select Box
    if ($field['form_type'] == 'select_values') {
        $str .= "<input type='hidden' name='rule' value='exact' />\n";
        $str .= enable_vars($field['form_name'])."<br /><select class='filter_fields' name='keyword'";
        if ($field['form_tooltip'] <> '') $str .= " title='".$field['form_tooltip']."'";
        $str .= read_o($read_o)." onchange='document.".$field_name.".submit();'>\n";
        // blank value with name of field
        $str .= "<option value=''>--</option>\n";
        foreach (explode('|',$field['form_select']) as $select_value) {
            // split the entry into key and value
            list($key,$value) = explode('#',$select_value);
            if (!$value) $value = $key;
            $str .= "<option value='".$key."'";
            if ($key == $field['value']) $str .= ' selected="selected"';
            $str .= '>'.enable_vars($value)."</option>\n";
        }
        $str .= "</select>\n";
    }

    // project list
    else if ($field['form_type'] == 'project') {
        $str .= "<input type='hidden' name='rule' value='exact' />\n";
        $str .= enable_vars($field['form_name'])."<br/><select class='filter_fields' name='keyword'";
        if ($field['form_tooltip'] <> '') $str .= " title='".$field['form_tooltip']."'";
        $str .= read_o($read_o)." onchange='document.".$field_name.".submit();'>";
        $str .= "<option value=''>--</option>\n";
        $str .= show_elements_of_tree('projekte',
                                'name',
                                "where (von = '$user_ID' or acc like 'system' or ((acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group))",
                                'acc'," order by name", $keyword,'parent',0);
        $str .= "</select>\n";
    }
    // user value
    else if ($field['form_type'] == 'userID' or $field['form_type'] == 'user_show') {
    	$str .= "<input type='hidden' name='rule' value='exact' />\n";
        $str .= enable_vars($field['form_name'])."<br/><select class='filter_fields' name='keyword'";
        if ($field['form_tooltip'] <> '') $str .= " title='".$field['form_tooltip']."'";
        $str .= read_o($read_o)." onchange='document.".$field_name.".submit();'>";
        $str .= "<option value=''>--</option>\n";
        $str .= show_filter_group_users($user_group,'','');
        $str .= "</select>\n";
    }
    // select Box on all users where the ID has been stored in this field
    else if ( $field['form_type'] == 'select_sql' ) {
        $str .= enable_vars($field['form_name'])."<br/><select class='filter_fields' name='keyword'";
        if ($field['form_tooltip'] <> '') $str .= " title='".$field['form_tooltip']."'";
        $str .= read_o($read_o)." onchange='document.".$field_name.".submit();'>";
        // blank value with name of field
        $str .= "<option value=''>--</option>\n";
        $result = db_query(enable_vars($field['form_select']));
        while ($row = db_fetch_row($result)) {
            $first_element = array_shift($row);
            $str .= "<option value='".$first_element."'";
            if ($first_element == $field['value'])$str .= ' selected="selected"';
            $str .= ">".implode(',',$row)."</option>\n";
        }
        $str .= "</select>\n";
    }
    // otherwise a simple input box
    else {
        // define length of input field
        $field_length = ( enable_vars($field['form_name']) > 10) ? enable_vars($field['form_name']) : '10';
        $str .= "<input type='hidden' name='rule' value='like' />\n";
        $str .= enable_vars($field['form_name'])."<br/><input type='text' class='filter_fields' name='keyword' value=''";
        if ($field['form_type'] == 'contact') $str .= read_o(1). " />\n";
        else $str .= " onfocus=\"this.value=''\" style='width:95%' />\n";
    }
    // close form
    $hidden = array('filter_module'=>$module, 'mode'=>$mode,'filter'=>$field_name, 'ID'=>$ID,
                    'perpage'=>$perpage,'page'=>$page, 'sort'=>$sort, 'direction'=>$direction);
    if (SID) $hidden[session_name()] = session_id();
    $str .= hidden_fields($hidden);
    $str .= "</form>\n";
    // show icons to sort up and down

    return $str;
}


function main_filter($filter, $rule, $keyword, $filter_ID, $module, $firstchar='') {
    global $fields, $flist, $tablename, $flist_store, $filter_module;

    // -1. action: delete all filters
    if (isset($filter_ID) && $filter_ID == '-1') {
        $flist[$module] = array();
        unset($filter_ID);
    }
    // 0. action: take values from storage
    if (!isset($flist[$module]) && $flist_store[$module]) $flist[$module] = $flist_store[$module];
    // 1. action: check whether a filter element should be removed
    if (isset($filter_ID)) unset($flist[$module][$filter_ID]);
    // 2. action: add the current filter to the filter list
    // 2/a special filter for contacts - select all records where the last name begins with this char
    if ($firstchar <> '') {
        $flist[$module][] = array('nachname', 'begins', $firstchar);
    }
    // 2/b look for a 'normal filter
    else if (isset($keyword) && strlen($keyword) != 0) {
        $flist[$module][] = array($filter, $rule, $keyword);
    }
    // 3. action: apply the filter list
    if (isset($flist[$module]) && is_array($flist[$module]) && count($flist[$module]) > 0) {
        // 3.1 apply the filter
        $unique = array();
        foreach ($flist[$module] as $key=>$p_filter) {
            if (in_array(serialize($flist[$module][$key]), $unique)) {
                // remove multiple entries
                unset($flist[$module][$key]);
                continue;
            }
            if ($p_filter[2] != '') {
                // if the field string is 'all', it has to be looped over all applicable fields
                $tmp = '';
                if ($p_filter[0] == 'all') $tmp .= apply_full_filter($p_filter[1], $p_filter[2]);
                else                       $tmp .= apply_filter($p_filter[0], $p_filter[1], $p_filter[2]);
                if (strlen($tmp)) $where .= ' AND ('.$tmp.')';
            }
            $unique[] = serialize($flist[$module][$key]);
        }
    }
    if (!$where) $where = ' AND 1=1 ';

    $_SESSION['flist'] =& $flist;
    // one result of the whole thing: the where clause for the sql query
    return $where;
}


function display_filters($module, $link=null) {
    global $flist, $fields, $action, $perpage, $page_n, $where, $sid, $tablename, $ID, $getstring;

    // avoid double save
    $mode = ($GLOBALS['mode'] != 'data') ? $GLOBALS['mode'] : 'view'; 
    
    $filter_list_text = '';
    if (!$link) $link = $module;
    if (isset($flist[$module]) && is_array($flist[$module]) && count($flist[$module]) > 0) {
        $filter_list_arr = array();
        foreach ($flist[$module] as $key=>$p_filter) {
            // first fetch the name
            foreach ($fields as $field_name=>$field) {
                if ($field_name == $p_filter[0]) $filtername = enable_vars($field['form_name']);
            }
            // click on link removes the filter
            $hreftext = '&nbsp;'.$filtername.'&nbsp;'.$p_filter[1].'&nbsp;'.$p_filter[2]."&nbsp;";
            
            $filter_list_arr[] = " <a href='".$link.".php?mode=$mode&amp;$getstring&amp;ID=$ID&amp;filter_module=$module&amp;action=$action&amp;filter_ID=$key&amp;perpage=$perpage&amp;page=$page_n".$sid.
                                 "' class='filter_active' title='".__('Delete')."'>".$hreftext."</a>\n";
        }
        $filter_list_text = "<b>".__('Filtered').":</b> ".implode('+', $filter_list_arr).
                            "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='".$link.
                            ".php?mode=$mode&amp;ID=$ID&amp;filter_module=$module&amp;$getstring&amp;action=$action&amp;filter_ID=-1&amp;perpage=$perpage&amp;page=$page_n".$sid.
                            "' class='filter_manage' title='".__('Delete all filter')."'>".__('Delete all filter')."</a>\n";
    }

/*
    $filter_list_text .= "<script language='javascript'><!--\n";
    $filter_list_text .= "function manage_filters() {
var dp = window.open('../lib/dbman_filter_pop.php?module=$module','dp','left=100,top=100,width=430,height=180,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1');
dp.focus();
}";
    $filter_list_text .= "\n//-->\n</script>";
    $filter_list_text .= "| <a href='#' onclick='manage_filters()'>".__('Edit filter')."</a> ";
*/
    #$filter_list_text = '<div class="relObjFilter">myfilter</div>';
    #$filter_list_text ='myfiklter';

    return $filter_list_text;
}

/**
* Show the "Edit filters" Link
* This function creates the required JavaScript function and Link
* for every module.
*
* @param  string $filtermodule Name of the filtered module
* @return string
*/
function display_manage_filters($filtermodule, $color='') {
    global $module, $mode, $ID, $flist, $module, $path_pre;

    $ret = '
<script type="text/javascript">
//<![CDATA[
function manage_filters_'.$filtermodule.'() {
    var dp = window.open("'.$path_pre.'lib/dbman_filter_pop.php?module='.$filtermodule.'&opener='.$module.'&mode='.$mode.'&ID='.$ID.'","dp","left=100,top=100,width=600,height=200,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1");
    dp.focus();
}
//]]>
</script>
';
    if (count($flist[$module]) > 0) $ret .= '&nbsp;&nbsp;|&nbsp;&nbsp;';
    $ret .= '<a title="'.__('This link opens a popup window').'" class="filter_manage" href="#" onclick="manage_filters_'.$filtermodule.'()">';
    if ($color <> '') $ret .= "<font color='$color'>".__('Edit filter')."</font>";
    else              $ret .= __('Edit filter');
    $ret .= '</a>';
    return $ret;
}


function apply_filter($field, $rule, $keyword) {
    switch ($rule) {
        case 'begins':
            $w = "$field LIKE '$keyword%'";
            break;
        case 'ends':
            $w = "$field LIKE '%$keyword'";
            break;
        case 'exact':
            $w = "$field = '$keyword'";
            break;
        case '>':
            $w = "$field > '$keyword'";
            break;
        case '>=':
            $w = "$field >= '$keyword'";
            break;
        case '<=': 
            $w = "$field <= '$keyword'";
            break;
        case '<':

            $w = "$field < '$keyword'";
            break;
        case 'not like':
            $w = "$field NOT LIKE '%$keyword%'";
            break;
        // default rule: like
        default:
            $w = "$field LIKE '%$keyword%'";
    }
    return $w;
}


function apply_full_filter($rule, $keyword) {
    global $fields;
    foreach ($fields as $field_name => $field) {
        if ($field['filter_show'] != '') $f_list[] = apply_filter($field_name, $rule, $keyword);
    }
    $w = implode(' OR ', $f_list);
    return $w;
}


// checks whether a filter element in the list should be removed
function filter_mode($filter_ID) {
    if (isset($filter_ID)) {
        // FIXME: why is 'contacts' a special case in this function ?!
/*
        if ($filter_ID == '-1') {
            // delete all filter
            $flist['contacts'] = array();
        }
        else ...
*/
        if (isset($flist['contacts'][$filter_ID])) {
            unset($flist['contacts'][$filter_ID]);
        }
        $_SESSION['flist'] =& $flist;
    }
}


// Filter in navigation bar
function nav_filter($fields) {
    $filter_rules = array( 'like'     => __('contains'),
                           'exact'    => __('exact'),
                           'begins'   => __('starts with'),
                           'ends'     => __('ends with'),
                           '>'        => __('>'),
                           '>='       => __('>='),
                           '&lt;'     => __('<'),
                           '&lt;='    => __('<='),
                           'not like' => __('does not contain')
                         );
    $str .= '<b>'.__('Filter').':</b> ';
    $str .= "<select name='filter'><option value='all'>".__('all fields')."</option>\n";
    $filter_list = array();
    if (is_array($fields)) {
        foreach ($fields as $field_name => $field) {
            if ($field['filter_show'] > 0 or $field['filter_show']=='on') $filter_list[$field_name] = enable_vars($field['form_name']);
        }
    }
    // sort array by name
    natcasesort($filter_list);
    reset($filter_list);
    foreach($filter_list as $filter_field => $filter_formname) {
        $str .= "<option value='".$filter_field ."'";
        if ($filter_field == $filter) $str .= ' selected="selected"';
        $str .= '>'.$filter_formname."</option>\n";
    }
    $str .= "</select>";
    // ... rule ...
    $str .= '<span class="strich">&nbsp;</span>';
    $str .= "&nbsp;<select name='rule'>\n";
    foreach ($filter_rules as $showrule => $ruletext) {
        $str .= "<option value='".$showrule."'>".$ruletext."</option>\n";
    }
    $str .= "</select>\n";
    $str .= "<input type='text' size='15' name='keyword' />\n";
    return $str;
}

// show all users of a group
function show_filter_group_users($user_group) {
    global $user_ID;
        
    // as this function is called VERY often. the output is cached for one result
    // DO not cache this into a session as the user might change his group from one request to another
    static $str = "";
    if (!empty($str)) return $str;

    // group system, fetch ID's from the other users
    if ($user_group) {
    /*
        $query = "SELECT DISTINCT user_ID, ".DB_PREFIX."users.nachname
                             FROM ".DB_PREFIX."grup_user, ".DB_PREFIX."users
                              WHERE ((grup_ID = '$user_group'
                                   AND ".DB_PREFIX."grup_user.user_ID = ".DB_PREFIX."users.ID)
                                   OR (".DB_PREFIX."grup_user.user_ID = ".DB_PREFIX."users.ID))
                                   ORDER BY nachname";
                                   */
        $query = "SELECT DISTINCT user_ID, u.nachname, u.vorname
                             FROM ".DB_PREFIX."grup_user g, ".DB_PREFIX."users u
                              WHERE grup_ID = '$user_group'
                                AND g.user_ID = u.ID
                                   ORDER BY u.nachname";
      $result3 = db_query($query) or db_die();

    }
    // if user is not assigned to a group or group system is not activated
    else {
        $result3 = db_query("SELECT ID, nachname
                               FROM ".DB_PREFIX."users
                               ORDER BY nachname") or db_die();
    }

    // loop over all user ID's of this group, fetch names and display them
    while ($row3 = db_fetch_row($result3)) {
        $str .= '<option value="'.$row3[0].'"';
        $str .= ">$row3[1], $row3[2]</option>\n";
    }

    return $str;
}

?>
