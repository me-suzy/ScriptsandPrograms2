<?php

// dbman_list.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: dbman_list.inc.php,v 1.90.2.4 2005/09/09 08:54:46 nina Exp $


// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use index.php!');

diropen_mode($element_mode, $element_ID);


function build_table($addfields, $module, $where, $page=0, $perpage=30, $link=null, $origin='', $is_related_obj=false) {
    global $field_name, $field, $fields, $flist, $tablename, $nr_record, $children, $row;
    global $path_pre, $tdw, $tdw_store, $f_sort, $path_pre,$output1, $menu2, $contextmenu;
    global $listentries_single, $listentries_selected, $fieldlist, $addcols;
    global $build_table_records;

    $output1 = '';

    if (!$tdw and $tdw_store) $tdw = $tdw_store;
        // initialize contextmenus
    if ($contextmenu > 0) {
        $is_addon = $_SESSION['common']['module'] == 'addons';
        $output1.=  $menu2->menu_columnheader($module, $link, $is_related_obj, $is_addon);
        $output1.=  $menu2->menu_table($module, $listentries_single, $listentries_selected);
        unset($is_addon);
    }

    // fetch additional colums
    $addcols = get_additional_columns($module, $fieldlist, $fields, '0', $records[$ID]);

    $output1 .= "
<script type='text/javascript'>
//<![CDATA[
function resizeImage(dx,mode) {
    var obj = eval(\"document.img\"+recID);
    if (mode =='relative') {
        if (dx>0) obj.width = column.offsetWidth + dx;
        else obj.width = obj.width + dx;
    }
    else {
        obj.width = dx;
    }
    document.onmouseup = nop;
}
function showsize() {
";
    if (is_array($fields)) {
        foreach($fields as $field_name => $field) {
            $output1 .= "document.tdwfrm.ii".$field_name.".value=document.images['img".$field_name."'].width\n";
        }
    }
    $output1 .= "
    return true;
}//]]>
</script>";

    // table header
    $output1.= "<table id=\"$module\" summary=\"$table for $module\"><thead><tr>\n";
    // fetch the relevant fields to display in list view
    if (is_array($fields)) {
        foreach ($fields as $field_name => $field) {
            if ($field['list_pos'] > 0) {
                $fieldlist[] = $field_name;
            }
        }
    }

    // additional custom column as first cell?
    if (is_array($addcols) and $addcols[0] <> '') {
        $output1 .= "<th scope='col' class='column2' style='width:5%'>&nbsp;</th>";
    }
    if (is_array($fields)) {
        foreach ($fields as $field_name => $field) {
            if ($field['list_pos'] > 0) {
                // define width of table cell
                $cw = floor(97/(count($fieldlist)+1));
                if (!$tdw[$module][$field_name]) $tdw[$module][$field_name] = $cw;
                // um valides xhtml zu erhalten
                if ($contextmenu > 0)$output1.= "<th id=\"".$field_name."_".$module."\" class=\"column2\" scope=\"col\" style='width:".$cw."%' oncontextmenu=\"startMenu('".$menu2->menucolID."','".$field_name."',this)\">";
                else $output1.= "<th id=\"".$field_name."_".$module."\" class=\"column2\" scope=\"col\" style='width:".$cw."%' >";
                //$output1.= col_filter($module,$field_name, $link,100);
                $output1.= col_filter($module,$field_name, $link, $cw);
                $output1.="<img src='".$path_pre."img/t.gif' name=\"img".$field_name."\" height='1' width='".$tdw[$module][$field_name]."' alt='alter direction' title='alter direction' />";
                $output1.= "</th>\n";
            }
        }
    }
    // additional custom column as first cell?
    if (is_array($addcols) and $addcols[1] <> '') { $output1 .= '<th>&nbsp:</th>'; }

    $output1.="</tr></thead>\n";
    $table = isset($tablename[$module]) ? $tablename[$module] : $module;
    // start rows
    // store the result in two arrays: one contains all records, the other one just the root records (just in case no filter is active)
   $result = db_query("SELECT ".implode(',',array_merge($addfields,$fieldlist))."
                          FROM ".qss(DB_PREFIX.$table).
                       sql_filter_flags($module, array('archive', 'read')) .
                       $where) or db_die();
    while ($row2 = db_fetch_row($result)) {
        $row[$row2[0]] = $row2;
        // build array of children
        if ($row2[3] > 0) { $children[$row2[3]][] = $row2[0]; }


        #FIXIT -> needed???
        #$flist[$module]= $arr_empt;


        // depending on tree view or flat view (due to filter setting) add the record to the list
        if ((!$flist[$module] <> '' and !$row2[3])) {$mainrecords[] = $row2[0];}
        else { $foundrecords[] = $row2[0]; }
    }

    // end of transfer from db, begin output
    (count($row) < ($page+1)*$perpage) ? $maxnr = count($row) : $maxnr = ($page+1)*$perpage;
    $nr_record = $page*$perpage;
    $entries = 0;

    while ($nr_record < $maxnr) {
        if (!$flist[$module] and $mainrecords) {
            if ($mainrecords[$nr_record] > 0) {
                $entries++;
                list_records($mainrecords[$nr_record], $module, $fieldlist, $fields, $addfields, $page, $perpage, $is_related_obj);
            }
        }
        // ... otherwise over the found records according to the filter
        else {
            if ($foundrecords[$nr_record] > 0) {
                $entries++;
                list_records($foundrecords[$nr_record], $module, $fieldlist, $fields, $addfields, $page, $perpage, $is_related_obj);
            }
        }
        $nr_record++;
    }

    if ($entries==0) {
        $output1 .= "<tbody><tr><td></td></tr></tbody>\n";
    }
    $build_table_records = $entries;
    $output1 .= "</table><br />\n";
    return $output1;
}


function list_records($ID, $module, $fieldlist, $fields, $addfields, $page, $perpage, $is_related_obj=false) {
    global $level, $diropen, $tree_mode, $img_path, $firstchar, $import, $filter, $sid, $path_pre;
    global $nr_record, $output1, $flist, $getstring, $nr, $children, $row, $user_ID, $menu2, $int, $addcols;

    if($module == 'dateien') $module = 'filemanager';
    $row[$ID] = explode('mySecretPhprojekt5Boundary', html_out(implode('mySecretPhprojekt5Boundary', $row[$ID])));
    // fill main array with values
    for ($i=0; $i < count($fieldlist); $i++) {
        $records[$ID][$fieldlist[$i]] = $row[$ID][$i+count($addfields)];
    }
    $bg_class = get_background_class($module, $fieldlist, $fields, $ID, $records[$ID]);
    // fetch additional colums
    $addcols = get_additional_columns($module, $fieldlist, $fields, $ID, $records[$ID]);

    // create different links for modules and addons
    $link = "";
    if ($_SESSION['common']['module'] == 'addons') { $link = "./addon.php"; }
    else { $link = "../$module/$module.php"; }

    if($is_related_obj){
        $ref = "javascript:void(0);'\" title='".__('This link opens a popup window')."' onclick='window.open(\"$link?justform=1&amp;$getstring&amp;mode=forms&amp;ID=$ID&amp;page=$page&amp;perpage=$perpage$sid\", \"related_object\", \"width=760px,height=540px,scrollbars=yes\");";
    }
    else{
        $ref = "$link?$getstring&amp;mode=forms&amp;ID=$ID&amp;page=$page&amp;perpage=$perpage".$sid;
    }
    tr_tag($ref,"",$ID,$records[$ID][$fieldlist[0]],'',$module,$bg_class); // draw <tr>
    // fetch additional columns which will be drwawn first - and as last cells
    if (isset($addcols[0])) $output1 .= $addcols[0];

    // begin row, first cell
    $output1.= "<td class='column2'";
    $in=10;
       for ($i2=1; $i2 <= $level; $i2++){
        if($module=='filemanager1')$in+=16;
        else $in+=10;
       }
      if(!$children[$ID]){
        if($module=='filemanager1')$in+=18;
        else $in+=12;
      }
    $output1.= " style='padding-left:".$in."px'>";
    // indent until level in tree

    // buttons

    $output1 .= buttons($ID, $module);

    // if this value is blank, add a '[blank]'
    $records[$ID][$fieldlist[0]] ? $first_value = stripslashes($records[$ID][$fieldlist[0]]) : $first_value = '['.__('No Value').']';
    $output1.= "<a href='".$ref."'>".$first_value."</a></td>\n";

    for ($i=1; $i<count($fieldlist); $i++) {
        // mail link
        if ($fields[$fieldlist[$i]]['form_type'] == 'email') {
            $output1.= "<td class='column2'>".showmail_link($records[$ID][$fieldlist[$i]])."</td>\n";
        }
        // url link
        else if ($fields[$fieldlist[$i]]['form_type'] == 'url') {
            $url = $records[$ID][$fieldlist[$i]];
            if ($url <> '' and !ereg("^http",$url) and !ereg("^ftp://",$url)) { $url = "http://".$url; }
            $output1.= "<td class='column2'><a href='".$url."' target='_blank'>".$records[$ID][$fieldlist[$i]]."</a></td>\n";
        }
        // select
        else if ($fields[$fieldlist[$i]]['form_type'] == 'select_values') {
            // fetch values from database
            $values1 = explode('|',$fields[$fieldlist[$i]]['form_select']);
            $values3 = array();
            // if value and text are different, split them again
            foreach($values1 as $value1) {
                if (eregi('#',$value1)) { $values2 = explode('#',$value1); }
                else { $values2[0] = $value1; $values2[1] = $value1; }
                $values3[$values2[1]]=$values2[0];
            }
            $output1.= "<td class='column2'>".enable_vars(array_search($records[$ID][$fieldlist[$i]],$values3))."</td>\n";
        }
        // select_sql
        else if ($fields[$fieldlist[$i]]['form_type'] == 'select_sql') {
            $result2 = db_query(enable_vars($fields[$fieldlist[$i]]['form_select']));
            while ($row4 = db_fetch_row($result2)) {
                $first_element = array_shift($row4);
                if ($first_element == $records[$ID][$fieldlist[$i]]) { $output1 .= "<td class='column2'>".implode(',',$row4)."</td>\n"; }
            }
        }
        // uploaded file
        else if ( $fields[$fieldlist[$i]]['form_type'] == 'upload' ) {
            list($filename,$tempname) = explode('|',$records[$ID][$fieldlist[$i]]);
            $output1.= "<td class='column2'><a href='".$path_pre.PHPR_DOC_PATH."/".$tempname.$sid."' target='_blank'>".$filename."</a></td>\n";
        }
        // link to contact record
        else if ( eregi('contact',$fields[$fieldlist[$i]]['form_type'])) {
            $output1.= "<td class='column2'>".slookup('contacts','nachname,vorname,firma','ID',$records[$ID][$fieldlist[$i]])."&nbsp;</td>\n";
        }
        // link to project record
        else if ( $fields[$fieldlist[$i]]['form_type'] == 'project' ) {
            $output1.= "<td class='column2'>".slookup('projekte','name','ID',$records[$ID][$fieldlist[$i]])."&nbsp;</td>\n";
        }
        // user access - treat it like a normal field
        else if ( eregi('userID_access',$fields[$fieldlist[$i]]['form_type'])) {
            $output1.= "<td class='column2'>".$records[$ID][$fieldlist[$i]]."&nbsp;</td>\n";
        }
        // several user short names, serialized
        else if ( $fields[$fieldlist[$i]]['form_type'] == 'user_ser' ) {
            $pers_all = '';
            $pers =unserialize($records[$ID][$fieldlist[$i]]);
            foreach ($pers as $pers2) {
                $pers_all .= slookup('users','nachname','kurz',$pers2).',';
            }
            $output1.= "<td class='column2'>".$pers_all."&nbsp;</td>\n";
        }
        // link to user
        else if( eregi('user|author',$fields[$fieldlist[$i]]['form_type'])) {
            $output1.= "<td class='column2'>".slookup('users','nachname,vorname','ID',$records[$ID][$fieldlist[$i]])."&nbsp;</td>\n";
        }
        // convert timestamps
        else if( eregi('timestamp',$fields[$fieldlist[$i]]['form_type']) ) {
            $output1.= "<td class='column2'>".show_iso_date1($records[$ID][$fieldlist[$i]])."&nbsp;</td>\n";
        }
        // display string
        else if( $fields[$fieldlist[$i]]['form_type'] == 'display_string' ) {
            $output1.= "<td class='column2'>".show_string_list($fields[$fieldlist[$i]]['form_select'],$fields,$row[$ID],count($addfields))."</td>\n";
        }
        // display Byte
        else if( $fields[$fieldlist[$i]]['form_type'] == 'display_byte' ) {
            $output1.= "<td class='column2'>";
            $total_size=$records[$ID][$fieldlist[$i]];
            if ($total_size > 1000000) {
                $fsize1 = $total_size/1000000;
                $fsize = floor($fsize1).".".substr($total_size,1,2)." M";
            }
            elseif ($total_size > 1000) {
                $fsize1 =$total_size/1000;
                $fsize = floor($fsize1).".".substr($total_size,1,2)." k";
            }
            $output1.="$fsize</td>\n";
        }
        // default action: simply return the value of this field
        else {
            $value = strip_tags(preg_replace('/\&lt;.+\&gt;/U', ' ', $records[$ID][$fieldlist[$i]]));
            $output1.= "<td class='column2'>".substr(nl2br($value),0,200)."&nbsp;</td>\n";
        }
    }

    if (isset($addcols[1])) $output1 .= $addcols[1];

    $output1 .= "</tr>\n";

    // display children
    if (($diropen[$module][$ID] or $tree_mode=='open') and !empty($children[$ID])) {
        foreach ($children[$ID] as $child) {
            // $nr_record++;
            $level++;
            list_records($child, $module, $fieldlist, $fields, $addfields, $page, $perpage);
            $level--;
        }
    }
}


function show_string_list($content, $fields, $content_fields, $field_offset=0) {
    // paste the values of the fields into the array
    foreach($fields as $field_name => $field) {
        $content = ereg_replace($field_name, $content_fields[$field_offset], $content);
        $field_offset++;
    }
    return preg_replace_callback("#\[(.*)\]#siU", 'f2_list', $content);
}

function f2_list($f) {
    eval('$y = '.$f[1].';');
    return $y;
}
// end list functions
// ******************


// *************
// start functions for archive flag and read flag
function read_mode($module) {
    // no value in session?
    if (!isset($_SESSION['show_read_elements']["$module"])) {
        // check for settings
        if (isset($GLOBALS['show_read_elements_settings']["$module"])) {
            $_SESSION['show_read_elements']["$module"] = $GLOBALS['show_read_elements_settings']["$module"];
        }
        // if we cannot find a value in the session nor in the settings, assume that the user is used to see all records
        else {
            $_SESSION['show_read_elements']["$module"] = 0;
        }
    }
    // now check whether the user has toggled the flag for show/hide
    if ($_REQUEST['toggle_read_flag'] == 1) {
        if ($_SESSION['show_read_elements']["$module"] == 0) $_SESSION['show_read_elements']["$module"] = 1;
        else $_SESSION['show_read_elements']["$module"] = 0;
      }
}


function archive_mode($module) {
    // no value in session?
    if (!isset($_SESSION['show_archive_elements']["$module"])) {
        // check for settings
        if (isset($GLOBALS['show_archive_elements_settings']["$module"])) {
            $_SESSION['show_archive_elements']["$module"] = $GLOBALS['show_archive_elements_settings']["$module"];
        }
        // if we cannot find a value in the session nor in the settings, assume that the user is used to see all records
        else {
            $_SESSION['show_archive_elements']["$module"] = 0;
        }
    }

    // now check whether the user has toggled the flag for show/hide
    if ($_REQUEST['toggle_archive_flag'] == 1) {
        if ($_SESSION['show_archive_elements']["$module"] == 0) $_SESSION['show_archive_elements']["$module"] = 1;
        else $_SESSION['show_archive_elements']["$module"] = 0;
    }
}


function html_editor_mode($module) {
    // no value in session?
    if (!isset($_SESSION['show_html_editor']["$module"])) {
        // check for settings
        if (isset($GLOBALS['show_html_editor_settings']["$module"])) {
            $_SESSION['show_html_editor']["$module"] = $GLOBALS['show_html_editor_settings']["$module"];
        }
        // if we cannot find a value in the session nor in the settings, assume that the user is used to see the html editor
        else {
            $_SESSION['show_html_editor']["$module"] = 0;
        }
    }
    // now check whether the user has toggled the flag for show/hide
    if ($_REQUEST['toggle_html_editor_flag'] == 1) {
        if ($_SESSION['show_html_editor']["$module"] == 0) $_SESSION['show_html_editor']["$module"] = 1;
        else $_SESSION['show_html_editor']["$module"] = 0;
    }
}


/**
* return additional sql string to filter archived and/or read elements
* @author Alex Haslberger
* @param string $module module to which the entry belongs
* @param arr    $flags flags to be checked
* @return string $str additional query string
*/
function sql_filter_flags($module, $flags, $before_where=true) {
    if ($module == 'linksinks') {
        return ''; // module reminder should not LEFT JOIN to itself
    }
    global $user_ID;
    // return empty string if no restriction to archived or read elements is made
    if ($_SESSION['show_archive_elements']["$module"] == 0 and $_SESSION['show_read_elements']["$module"] == 0) {
        $str = '';
    }
    else {
        $str = '';
        // perform the left join
        if ($before_where) {
            $str .= ' AS T1 LEFT JOIN  '.DB_PREFIX.'db_records AS T2 ON (T1.ID = T2.t_record AND T2.t_module= "'.$module.'" AND T2.t_author = '.$user_ID.') ';
        }
        // perform the where clause
        else {
            $tmp = array();
            // filter archived elements. if value is 1, show them, if value is 0, dont show them
            if (($_SESSION['show_archive_elements']["$module"] == 1)) {
                $tmp[] = '  (T2.t_archiv  = 0 or T2.t_archiv IS NULL) ';
            }
            // filter read elements - if value is 1, show them, if value is 0, don't show them
            if ($_SESSION['show_read_elements'][$module] == 1) {
                $tmp[] = '  (T2.t_touched  = 0 or T2.t_touched IS NULL) ';
            }
            // implode separate statements
            if ($tmp != array()) $str .= ' AND ('.implode(' AND ', $tmp).')';
        }
    }
    return $str;
}


function sort_mode($default_column) {
    global $module, $f_sort, $f_sort_store;
    if ($_GET['sort']) {
        $f_sort[$module]['sort'] = $_GET['sort'];
        $f_sort[$module]['direction'] = $_GET['direction'];
    }
    else if ($_SESSION['f_sort'][$module]['sort'] <> '') {
        $f_sort[$module]['sort'] = $_SESSION['f_sort'][$module]['sort'];
        $f_sort[$module]['direction'] = $_SESSION['f_sort'][$module]['direction'];
    }
    else if ($f_sort_store[$module]['sort'] <> '') {
        $f_sort[$module]['sort'] = $f_sort_store[$module]['sort'];
        $f_sort[$module]['direction'] = $f_sort_store[$module]['direction'];
    }
    else if (!$f_sort[$module]['sort']) {
        $f_sort[$module]['sort'] = $default_column;
        $f_sort[$module]['direction'] = 'ASC';
    }
    $_SESSION['f_sort'] =& $f_sort;
}


// provides sql string to order result sets
function sort_string($sort_module=null, $has_to_be_sorted = false) {
    global $direction_rel, $sort_col;
    if($has_to_be_sorted == true && isset($sort_col) && isset($direction_rel) && $sort_col != '' && $direction_rel != ''){
        return 'ORDER BY '.$sort_col.' '.$direction_rel;
    }
    global $f_sort, $module;
    if (!$sort_module) $sort_module = $module;
    if ($f_sort[$sort_module]['sort'] <> '') {
        return 'ORDER BY '.$f_sort[$sort_module]['sort'].' '.$f_sort[$sort_module]['direction'];
    }
}


// stores the column width of a module
function store_column_width($module) {
    global $fields, $tdw;
    foreach($fields as $field_name => $field) {
        $tdw[$module][$field_name] = $_POST["ii$field_name"];
    }
    $_SESSION['tdw'] =& $tdw;
}


function diropen_mode($element_mode, $ID) {
    global $element_module, $diropen, $diropen_store;

    // take the stored set
    if (!$diropen and $diropen_store) {
        $diropen = $diropen_store;
    }
    // open and close main contact
    if ($element_mode == 'open') {
        $diropen[$element_module][$ID] = 1;
    }
    else if ($element_mode == 'close') {
        $diropen[$element_module][$ID] = 0;
    }
    $_SESSION['diropen'] =& $diropen;
}
// end archive and read flag
// *************************


// ********
// list nav
function show_page_nav($sql, $module, $page=0, $perpage=30) {
    global $n_results, $tablename;

    $width = 70;
    $str = "<table><tr>";
    $result = db_query("SELECT COUNT(ID)
                          FROM ".qss(DB_PREFIX.$tablename[$module])."
                               $sql") or db_die();
    $row = db_fetch_row($result);
    $n_results = count($row);
    // display amount of found elements
    $str .= "<td>".$row[0].' '.__('records')." </td>";
    // first page
    $str .= "<td width=".$width.">";
    if ($page > 1) $str .= "<a href='$module.php?mode=view&amp;action=$action&amp;firstchar=$firstchar&amp;perpage=$perpage&amp;page=0&amp;direction=$direction&amp;sort=$sort&amp;import=$import".$sid."'>".__('first page')." </a>&nbsp;  \n";
    // previous page
    $str .= "</td><td width=".$width.">";
    if ($page) $str .= "<a href='$module.php?mode=view&amp;action=$action&amp;firstchar=$firstchar&amp;perpage=$perpage&amp;page=".($page-1)."&amp;direction=$direction&amp;sort=$sort&amp;import=$import".$sid."'>".__('previous page')." </a>&nbsp;  \n";
    // next page
    $str .= "</td><td width=".$width.">";
    if ($row[0] > ($page+1)*$perpage) $str .= "<a href='$module.php?mode=view&amp;action=$action&amp;firstchar=$firstchar&amp;perpage=".$perpage."&amp;page=".($page+1)."&amp;direction=$direction&amp;sort=$sort&amp;import=$import".$sid."'>".__('next page')." </a>&nbsp;  \n";
    // last page
    $str .= "</td><td width=".$width.">";
    if ($row[0] > ($page+2)*$perpage) $str .= "<a href='$module.php?mode=view&amp;action=$action&amp;firstchar=$firstchar&amp;perpage=".$page."&amp;page=".(floor($row[0]/$perpage))."&amp;direction=$direction&amp;sort=$sort&amp;import=$import".$sid."'>".$last." </a>&nbsp;  \n";
    $str .= "</tr></table>\n";
    return $str;
}
// end list nav
// *********


function buttons($element_ID, $element_module) {
    global $diropen, $sid, $filter, $keyword, $page, $perpage, $img_path, $tree_mode;
    global $getstring, $tablename, $children, $module, $mode, $ID;

    $buttons = 0;
    // if the radio button 'open' was selected: set all main projects to open:
    if ($tree_mode == 'open')  $diropen[$element_module][$element_ID] = 1;
    if ($tree_mode == 'close') $diropen[$element_module][$element_ID] = 0;
    if ($children[$element_ID]) {
        // show button 'open'
        if (!$diropen[$element_module][$element_ID]) { $str = "<a name='A".$row[0]."' href='".$module.".php?mode=view&amp;element_mode=open&amp;element_ID=$element_ID&amp;element_module=$element_module&amp;page=$page&amp;perpage=$perpage&amp;ID=$ID".$sid."#A$row[0]'><img src='$img_path/close.gif' alt='close Element' title='close Element' border='0' />&nbsp;</a>"; }
        // show button 'close'
        else { $str = "<a name='A".$row[0]."' href='".$module.".php?mode=view&amp;element_mode=close&amp;element_ID=$element_ID&amp;element_module=$element_module&amp;page=$page&amp;perpage=$perpage&amp;ID=$ID".$sid."#A$row[0]'><img src='$img_path/open.gif' alt='open Element' title='open Element' border='0' /></a>&nbsp;"; }
    }
    //else $str = '&nbsp;&nbsp;&nbsp;&nbsp;';
    return $str;
}


function save_filter($module, $name, $ID=NULL, $dat=NULL) {
    global $dbIDnull, $user_ID;

    if (isset($dat)) $filter = serialize($dat);
    else $filter = serialize($_SESSION['flist'][$module]);

    if ($ID) {
        $result = db_query(xss("UPDATE ".DB_PREFIX."filter
                               SET filter='$filter'
                             WHERE ID = '$ID'
                               AND von = '$user_ID'
                               AND module = '$module'")) or db_die();
    }
    else {
        $result = db_query(xss("INSERT INTO ".DB_PREFIX."filter
                                        ( ID,  von, module, name, filter )
                                 VALUES ($dbIDnull, '$user_ID', '$module', '$name', '$filter')")) or db_die();
    }
}


function load_filter($ID, $module) {
    global $user_ID;

    $result = db_query("SELECT filter
                          FROM ".DB_PREFIX."filter
                         WHERE ID = '$ID'
                           AND von = '$user_ID'
                           AND module = '$module'") or db_die();
    $row = db_fetch_row($result);
    return unserialize($row[0]);
}


function get_filters($module) {
    global $user_ID;

    $result = db_query("SELECT ID, name
                          FROM ".DB_PREFIX."filter
                         WHERE module = '$module'
                           AND von ='$user_ID'") or db_die();

    $retval = array();
    while ($row = db_fetch_row($result)) {
        $retval[$row[0]] = $row[1];
    }
    return $retval;
}


function delete_filter($ID, $module) {
    global $user_ID;
    $result = db_query("DELETE FROM ".DB_PREFIX."filter
                              WHERE ID = '$ID'
                                AND von = '$user_ID'") or db_die();
}


/**
* Set background-color of a listitem
* This function can be used to highlight important entries for a module
*
* @param  $module    string Modulename
* @param  $fieldlist array A numeric array with the activated fields
* @param  $fields    array Holds the definition of the fields
* @param  $ID        int ID
* @param  $data      array This is the data for the current dataset (row) with fieldname as array key
* @return string     Either a css class or an empty string
*/
// FIXME: what are the parms $fieldlist, $fields and $ID good for?!
function get_background_class($module, $fieldlist, $fields, $ID, $data) {
    switch ($module) {
        case 'todo':
            if (strlen($data['deadline']) && (strtotime($data['deadline']) < time()) && $data['status'] < 5) {
                return 'todo_deadline_exceeded';
            }
            break;
        case 'calendar':
            if ($data['status'] == '1') return 'calendar_event_canceled';
            switch ($data['partstat']) {
                // yet not decided
                case '1':
                    return 'calendar_event_open';
                    break;
                // accepted
                case '2':
                    return 'calendar_event_accept';
                    break;
                // rejected
                case '3':
                    return 'calendar_event_reject';
                    break;
            }
            break;
    }
    return '';
}


/**
* Create first/last column defined by module
* The returned value needs to include the <td>-Tags
* @param $module string Modulename
* @param $fieldlist array A numeric array with the activated fields
* @param $fields array Holds the definition of the fields
* @param $ID int ID
* @param $data array This is the data for the current dataset (row) with fieldname as array key
* @return array(0 => fisrt column, 1 => last column)
*/
function get_additional_columns($elementmodule, $fieldlist, $fields, $ID, $data) {
    global $user_ID,$tree_mode,$children,$diropen, $module;

    switch ($elementmodule) {
        case 'todo_alt':
            $result = db_query("SELECT progress, status
                                  FROM todo
                                 WHERE ID='$ID'");
            list($progress, $status) = db_fetch_row($result);
            if ($data['von'] == $user_ID and ($status > 1 and $status < 5)) {
                $lastcol = "
        <form action='todo.php' method='post'>
            <td>
                <input type='hidden' name='mode'     value='data' />
                <input type='hidden' name='cstatus'  value='".$GLOBALS['cstatus']."' />
                <input type='hidden' name='category' value='".$GLOBALS['category']."' />
                <input type='hidden' name='ID'       value='$ID' />
                <input type='hidden' name='step'     value='update_progress' />
            </td>
            <td align='right'>
                <input type='text' size='3' name='progress' value='$progress' onblur='this.form.submit();' />%
            </td>
        </form>
";
            }
            else {
                $lastcol = "<td align='right'>$progress%</td>\n";
            }
            return array(1 => $lastcol);
            break;
        case 'filemanager':
        case 'dateien':
            //$firstcol = "<td class='column2' style='width:20px !important;'>&nbsp;$mime_img</td>\n";
            //return array(0 => $firstcol);

            $url = "../filemanager/filemanager_down.php?mode=down&amp;mode2=attachment&amp;ID=$ID";
            $file_parts = explode('.', slookup('dateien', 'userfile', 'ID', $ID));
            $file_lock  = slookup('dateien', 'lock_user', 'ID', $ID);
            $file_type  = slookup('dateien', 'typ', 'ID', $ID);
            $file_mime  = $file_parts[count($file_parts)-1];
            if ($file_type == 'd') {
                if ($tree_mode == 'open')  $diropen[$elementmodule][$ID] = 1;
                if ($tree_mode == 'close') $diropen[$elementmodule][$ID] = 0;
                if ($children[$ID]) {
                    // show button 'open'
                    if (!$diropen[$elementmodule][$ID]) { $mime_img= "<a name='A".$row[0]."' href='".$module.".php?mode=view&amp;element_mode=open&amp;element_ID=$ID&amp;element_module=$elementmodule&amp;page=$page&amp;perpage=$perpage&amp;ID=$ID".$sid."#A$row[0]'><img src='../filemanager/images/folder_yellow.png' alt='close Element' title='close Element' border='0' />&nbsp;</a>"; }
                    // show button 'close'
                    else { $mime_img = "<a name='A".$row[0]."' href='".$module.".php?mode=view&amp;element_mode=close&amp;element_ID=$ID&amp;element_module=$elementmodule&amp;page=$page&amp;perpage=$perpage&amp;ID=$ID".$sid."#A$row[0]'><img src='../filemanager/images/folder_yellow_open.png' alt='open Element' title='open Element' border='0' /></a>&nbsp;"; }
                }
                else { $mime_img = "<img src='../filemanager/images/folder_yellow.png' alt='open Element' title='open Element' border='0' />&nbsp;"; }
            }
            else if (($file_lock > '0') and ($file_lock != $user_ID )) {
                $mime_img ="<a href='".$url."'><img src='../filemanager/images/encrypted.png' alt='encrypted' title='encrypted' border='0' /></a>";
            }

            else switch(TRUE) {
                //case ($file_mime==''):
                //    $mime_img ='';
                //    break;
                case ($file_mime=='jpg' or $file_mime=='jpeg' or $file_mime=='png' or $file_mime=='gif'):
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/image.png' alt='image' title='image' border='0' /></a>";
                    break;
                case ($file_mime=='sxw' or $file_mime=='sxi' or $file_mime=='sxc' or $file_mime=='rtf'):
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/minidoc.png' alt='doc' title='doc' border='0' /></a>";
                    break;
                case ($file_mime=='doc'):
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/ico_doc.gif' alt='doc' title='doc' border='0' /></a>";
                    break;
                case ($file_mime=='txt'):
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/ico_txt.gif' alt='txt' title='txt' border='0' /></a>";
                    break;
                case ($file_mime=='xls'):
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/ico_xls.gif' alt='xls' title='xls' border='0' /></a>";
                    break;
                case ($file_mime=='pdf'):
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/ico_pdf.gif' alt='pdf' title='pdf' border='0' /></a>";
                    break;
                case ($file_mime=='ppt'):
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/ico_ppt.gif' alt='ppt' title='ppt' border='0' /></a>";
                    break;
                #case ($file_mime=='html'):
                    #$mime_img ="<a href='".$url."'><img src='../filemanager/images/ico_html.gif' alt='html' title='html' border='0' /></a>";
                    #break;
                #case ($file_mime=='mp3'):
                #    $mime_img ="<a href='".$url."'><img src='../filemanager/images/mp3.png' alt='mp3' title='mp3' border='0' /></a>";
                #    break;
                case ($file_mime=='zip' or $file_mime=='tar' or $file_mime=='rar'):
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/ico_zip.gif' alt='zip' title='zip' border='0' /></a>";
                    break;
                default:
                    $mime_img ="<a href='".$url."'><img src='../filemanager/images/ico_unknown.gif' alt='unknown' title='unknown' border='0' /></a>";
                    break;
            }
            $firstcol = "<td class='column2' style='width:20px !important;'>&nbsp;$mime_img</td>\n";
            return array(0 => $firstcol);
            break;
        case 'links':
            $url_parts = explode(',', slookup('db_records', 't_module,t_record', 't_ID', $ID));
            if($url_parts[0]=="rts")$url_parts[0]="helpdesk";
            $url = PHPR_HOST_PATH.PHPR_INSTALL_DIR.$url_parts[0].'/'.$url_parts[0].'.php?mode=forms&amp;ID='.$url_parts[1];
            $firstcol = "<td width='20px'><a href='".$url."'><img src='../img/goto.png' alt='goto' title='goto' border='0' /></a>&nbsp;"
            /*
            .slookup('db_records', 't_name', 't_ID', $ID) */.
            "</td>\n";
            return array(0 => $firstcol);
            break;
    }
}

?>
