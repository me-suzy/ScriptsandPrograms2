<?php

// $Id: selector.php,v 1.8 2005/07/18 13:45:03 paolo Exp $

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

// fetch elements of the form from the db
require_once($path_pre.'lib/dbman_lib.inc.php');
$fields = build_array('contacts', '', 'forms');

$js_inc[] = ">function go(x1,x2){ window.opener.dTarget.value = x1; window.opener.sTarget.value = x2; close();}";
echo set_page_header();

// check whether a filter element should be removed
if (isset($filter_ID)) {
    if ($filter_ID == '-1') $flist['selector'] = array();
    else                    unset($flist['selector'][$filter_ID]);
    $_SESSION['flist'] =& $flist;
}

echo "<h4>".__('Contact selector')."</h4>\n";
echo "<br /><form action='selector.php' method='post'>\n";
echo "<input type='hidden' name='exclude_ID' value='$exclude_ID' />\n";
echo nav_filter($fields);
echo "<input type='image' src='$img_path/los.gif' border='0' id='tr' /></form>\n";
  // call the main filter routine
$where = main_filter($filter, $rule, $keyword, $filter_ID, 'selector', $firstchar);
$result = db_query("SELECT COUNT(ID)
                      FROM ".DB_PREFIX."contacts
                     WHERE (acc_read LIKE 'system'
                            OR ((von = '$user_ID'
                                 OR acc_read LIKE 'group'
                                 OR acc_read LIKE '%\"$user_kurz\"%')
                                AND $sql_user_group))
                            $where") or db_die();

$row = db_fetch_row($result);
// if no result is found, unset the last filter since apparently it doesn't make any sense to keep it
if (!$row[0] and $flist['selector'] ) {
    array_splice($flist['selector'], -1);
    echo __('there were no hits found.').'<br />';
}
// show filter list and option to store selection
if ($flist['selector']) echo display_filters('selector');

echo "<br /><br /><b>".__('show results').":</b><br />\n";

if (PHPR_FILTER_MAXHITS < $row[0]) {
    echo __('Please set (other) filters - too many hits!')." ($row[0] > ".PHPR_FILTER_MAXHITS." max.)";
}
else {
    echo "<br /><br />\n<form name='frm'>
    <table cellspacing='0' cellpadding='0' border='0'><tr>\n";
    echo "<tr><td>".__('Contact').":</td><td><select name='contact'><option value='0'></option>\n";
    echo show_elements_of_tree("contacts",
                            "nachname, vorname, firma",
                            "WHERE (acc_read LIKE 'system' OR ((von = '$user_ID' OR acc_read LIKE 'group' OR acc_read LIKE '%\"$user_kurz\"%') AND $sql_user_group))
                            $where",
                            "acc_read"," ORDER BY nachname",$selected,"parent",$exclude_ID);
    echo "</select></td>\n";
    echo "<td><input name='submit' type='image' src='$img_path/los.gif'
            onclick='go(document.frm.contact.options[document.frm.contact.options.selectedIndex].value,document.frm.contact.options[document.frm.contact.options.selectedIndex].text);' type='button' /></td></tr>\n";
    echo "</table>\n</form>\n";
}

echo "</body>\n</html>\n";

?>
