<?php

// contacts_selector.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: contacts_selector.php,v 1.13 2005/07/05 11:27:01 nina Exp $

$path_pre = "../";
$include_path = $path_pre."lib/lib.inc.php";
include_once $include_path;
// fetch elements of the form from the db
require_once($path_pre.'lib/dbman_lib.inc.php');
$fields = build_array('contacts', '', 'forms');

if ($goback) {

  // store data
  if ($ID > 0) {
    $result = db_query(xss("update ".DB_PREFIX."contacts_projekt_rel
                           set contact = '$contact',
                               remark = '$remark'
                         where ID = '$ID'")) or db_die();
  }
  else {
    $result = db_query(xss("insert into ".DB_PREFIX."contacts_projekt_rel
                               (   ID,      contact,    projekt,    remark)
                        values ($dbIDnull, '$contact', '$projekt', '$remark')")) or db_die();
  }

  $onload[] = 'window.opener.location.reload();';
  $onload[] = 'window.close();';
}
echo set_page_header();

// check whether a filter element should be removed
if (isset($filter_ID)) {
 unset($flist['contacts_selector'][$filter_ID]);
 $_SESSION['flist'] =& $flist;
}

echo "<h4>Projektteilnehmer editieren</h4>\n";

echo "<br /><form action='contacts_selector.php' method='post'>\n";
echo "<input type='hidde'n name='ID' value='$ID' />\n";
echo nav_filter($fields);
echo "<input type='image' src='$img_path/los.gif' border='0' id='tr' /></form>\n";
  // call the main filter routine
  $where = main_filter($filter,$rule,$keyword,$filter_ID,'contacts_selector',$firstchar);
  // links for next and previous page
  $result = db_query("select count(ID)
                        from ".DB_PREFIX."contacts
                       where (acc_read like 'system' or ((von = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))
                             $where") or db_die();

$row = db_fetch_row($result);
  // if no result is found, unset the last filter since apparently it doesn't make any sense to keep it
  if (!$row[0] and $flist['contacts_selector'] ) {
    array_splice($flist['contacts_selector'],-1);
    echo __('there were no hits found.').".<br />\n";
  }
  // show filter list and option to store selection
  if ($flist['contacts_selector']) { echo display_filters('contacts_selector'); }
  echo display_manage_filters('contacts_selector');

if (PHPR_FILTER_MAXHITS < $row[0]) {
  echo __('Please set (other) filters - too many hits!')." ($row[0] > ".PHPR_FILTER_MAXHITS." max.)<br /><br />\n";
  $where = "and ID = '$ID'";
}
else { $where .= "or ID = '$ID'"; }

echo "<br /><img src='$img_path/s.gif' width='300' height='1' /><br />\n";

echo "<br /><form name='frm' method='post' action='contacts_selector.php'>\n";
echo "<table cellSpacing='0' cellPadding='0' border='0'><tr>\n";
echo "<input type=hidden name=ID value='".$ID."' />\n";
echo "<input type=hidden name=projekt value='".$projekt."' />\n";
echo "<tr><td>".__('Contact').":</td><td><select name='contact'>\n";
echo show_elements_of_tree("contacts",
                      "nachname,vorname,firma",
                      "where (acc_read like 'system' or ((von = $user_ID or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))
                       $where",
                      "acc_read"," order by nachname",$ID,"parent",'');
echo "</select>\n<br /><br /></td></tr>\n";

echo "<input type='hidden' name='goback' value='1' />\n";
// function
echo "<tr><td>".__('Remark')."</td><td><textarea cols=40 rows=8 name=function>".html_out(slookup('contacts_projekt_rel','remark','ID',$ID))."</textarea></td></tr>\n";
echo "<tr><td>&nbsp;</td><td><br /><input name='submit' type='image' src='$img_path/los.gif' /></td></tr>\n";
echo "</table></form>";
echo "</body></html>";

?>
