<?php

// bookmarks_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: bookmarks_view.php,v 1.29 2005/06/20 14:19:22 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("bookmarks") < 1) die("You are not allowed to do this!");

$fields = array();
$fields['url']         = array('filter_show' => 1, 'form_name' => 'URL');
$fields['bezeichnung'] = array('filter_show' => 1, 'form_name' => __('Name'));
$fields['bemerkung']   = array('filter_show' => 1, 'form_name' => __('Text'));

include_once("../lib/dbman_lib.inc.php");
$where = main_filter($filter, $rule, $keyword, $filter_ID, 'bookmarks');

$result = db_query("SELECT ID, datum, von, url, bezeichnung, bemerkung, gruppe
                      FROM ".DB_PREFIX."lesezeichen
                     WHERE $sql_user_group
                           $where") or db_die();
$liste = make_list($result);

if (!$sort) $sort = "url";

$filter_rules = array('like'=>__('contains'),
                      'exact'=>__('exact'),
                      'begins'=>__('starts with'),
                      'ends'=>__('ends with'),
                      '>'=>__('>'),
                      '>='=>__('>='),
                      '<'=>__('<'),
                      '<='=>__('<='),
                      'not like'=>__('does not contain'));
// **************
// navigation bar
// **************

//tabs
$tabs   = array();
$tmp    = get_export_link_data('bookmarks', false);
$tabs[] = array('href' => $tmp['href'], 'active' => $tmp['active'], 'id' => 'tab4', 'target' => '_self', 'text' => $tmp['text'], 'position' => 'right');
$output = get_tabs_area($tabs);

// button bar
$buttons = array();
if (check_role("bookmarks") > 1) {
    $buttons[] = array('type' => 'link', 'href' => 'bookmarks.php?mode=forms&amp;sort='.$sort.'&amp;up='.$up.'&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;keyword='.$keyword.'&amp;filter='.$filter.$sid, 'text' => __('New'), 'active' => false);
}
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';


$add_paras = array();
$add_paras['hidden'] = array('up' => $up, 'sort' => $sort, 'mode2' => 'bookmarks');

$output .= get_filter_execute_bar('bookmarks', true, $add_paras);
$output .= get_filter_edit_bar();
$output .= get_status_bar();
$output .= get_top_page_navigation_bar();

// end navigation bar
// ******************

// ***********
// record list
// ***********
if ($up == "1") {
    $direction = "ASC";
    $up2 = 0;
}
else {
    $direction = "DESC";
    $up2 = 1;
}
$output .= "<a name='content'></a><br /><table>\n<thead>\n";
$output .= "<tr>\n";

$e1 = "<th class='column2' width='50%'>\n<b><a href='bookmarks.php?mode2=bookmarks&amp;sort=";
$e2 = "&amp;up=$up2&amp;page=$page&amp;perpage=$perpage&amp;keyword=$keyword&amp;filter=$filter".$sid."'>
<font color='#ffffff'>";
$e3 = "</font></a></b>\n</th>\n";

// sort by title
$output .= $e1."url".$e2."URL".$e3;
// button to modify the entry
$output .= "<th width='5'>&nbsp;</th>\n";
// sort by date of last change
$output .= $e1."bemerkung".$e2.__('Comment').$e3;
$output .= "</tr>\n</thead>\n<tbody>\n";

$result = db_query("SELECT ID, datum, von, url, bezeichnung, bemerkung, gruppe
                      FROM ".DB_PREFIX."lesezeichen
                     WHERE $sql_user_group
                           $where
                  ORDER BY ".qss($sort)." $direction") or db_die();

while ($row = db_fetch_row($result)) {
    if ($b >= $page*$perpage and $b < ($page+1)*$perpage) {
        $row[3] = stripslashes($row[3]);
        $row[4] = stripslashes($row[4]);
        $row[5] = stripslashes($row[5]);
        // user is the owner of this record -> he can modify it
        if ($row[2] == $user_ID) {
            $ref = "bookmarks.php?mode2=bookmarks&amp;ID=$row[0]&amp;mode=forms&amp;aendern=1&amp;page=$page&amp;perpage=$perpage&amp;keyword=$keyword&amp;filter=$filter&amp;sort=$sort&amp;direction=$direction$sid";
        }
        else {
            $ref = "bookmarks.php?mode2=bookmarks&amp;mode=view&amp;sort=$sort&amp;up=$up".$sid;
        }
        $output .= "<tr>\n";
        $output .= "<td><a href='$row[3]' target='_blank'>".html_out($row[4])."</a></td>\n";
        // button to modify the entry
        if ($row[2] == $user_ID) {
            $output .= "<td><a href='bookmarks.php?mode2=bookmarks&amp;ID=$row[0]&amp;mode=forms&amp;aendern=1&amp;page=$page&amp;perpage=$perpage&amp;keyword=$keyword&amp;filter=$filter&amp;sort=$sort&amp;direction=$direction$sid'><img src='$img_path/b.gif' alt='".__('Modify')."' title='".__('Modify')."' width='7' border='0' /></a></td>\n";
        }
        else {
            $output .= "<td>&nbsp;</td>\n";
        }
        $output .= "<td>".html_out($row[5])."&nbsp;</td>\n</tr>\n";
    }
    $b++;
}

$output .= "</tbody>\n</table><br/>\n";
$output .= get_bottom_page_navigation_bar();

echo $output;

//show_export_form("bookmarks");

?>
