<?php

// contact_import_patterns.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: contacts_import_patterns.php,v 1.15.2.1 2005/08/02 11:58:38 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("contacts") < 1) die("You are not allowed to do this!");


// array for field_delimiters
$delimiters = array(0=>',',1=>';');

if ($make <> '') {
  // flag for forms
  $import_contacts = 'other';
  // insert new record
  if ($make == "neu") {
    $result = db_query(xss("insert into ".DB_PREFIX."contacts_import_patterns
                               (   ID,      name,   von,      pattern)
                        values ($dbIDnull,'$name','$user_ID','".serialize($pattern_field)."')")) or db_die();
  }
  // update existing record
  else {
    $result = db_query(xss("update ".DB_PREFIX."contacts_import_patterns
                           set name = '$name',
                               pattern = '".serialize($pattern_field)."'
                         where ID='$ID'")) or db_die();
  }
  $import="yes";
  include_once('./contacts_import_forms.php');
}
elseif ($loeschen) {
  $result = db_query("delete from ".DB_PREFIX."contacts_import_patterns
                       where ID = '$ID'") or db_die();
  include_once('./contacts_import_forms.php');
}

// form
if (!$make) {
//tabs
    $tabs = array();
    $outputex .= get_tabs_area($tabs);
    // button bar
    $buttons = array();
    $buttons[] = array('type' => 'link', 'href' => 'contacts.php?mode=import_forms&amp;import_contacts=other&amp;page=$page&amp;perpage=$perpage&amp;up=$up&amp;sort=$sort.$sid', 'text' => __('back'), 'active' => false);


   $hidden = array('mode' => 'import_patterns', 'ID' => $ID, 'action' => $action);
   if(SID) $hidden[session_name()] = session_id();
   if ($aendern <> '')  $hidden['make'] = 'aendern';
   else  $hidden['make'] = 'neu';
      $buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'enctype' => 'multipart/form-data', 'name' => 'frm', 'onsubmit' => 'return chkForm(\'frm\',\'name\',\''.__('Please fill in the following field').': name '.__('Name').'\');');
   $buttons[] = array('type' => 'submit', 'name' => 'create', 'value' => __('Create'), 'active' => false);
   $outputex .= get_buttons_area($buttons);
  $outputex.="<form action='contacts.php' method='post' name=frm onSubmit=\"return chkForm('frm','name','".__('Please fill in the following field').": name ".__('Name')."')\">\n";
  if ($aendern <> '') {
    // fetch values
    $result = db_query("select pattern, von, name
                          from ".DB_PREFIX."contacts_import_patterns
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // check permission - the hacker doesn't deserve any message!
    if ($row[1] <> $user_ID) exit;
    // convert serialized pattern to array
    $pattern = unserialize($row[0]);
  }

  // title
  $outputex.='<br><div class="inner_content"><a name="content"></a><div class="boxHeader">'.__('Import pattern')."</div><div class='boxContent'\n";
  // name
  $outputex.="<br /><label for='name' class='options'>".__('Name').":</label> <input type='text' name='name' value='$row[2]' size='30' maxlength='40' /><br style='clear:both;'/><br />\n";
  // list headers
    $fp = fopen($userfile, "r");
  if (!$fp) {
    $outputex.="<br /><br /><b>Please upload a file!<br /><a href='contacts.php?mode=forms&amp;import=other".$sid."'>".__('back')."</a></b><br /><br />\n";
    exit;
  }
  $outputex.="<table class='ruler'><thead>\n";
  $outputex.="<tr><th>Position (Value of imported list)</th>\n<th>Reference to contacts table</th></tr></thead><tbody>\n";
  // fetch array of available fields
  include_once('../lib/dbman_lib.inc.php');


  $a = fgetcsv($fp, 4000, "$delimiters[$csv_field_delimiter]");
  for ($i=0; $i<count($a);$i++) {
    $outputex.="<tr><td>".__('Position')." <b>$i</b> (".$a[$i].")</td>\n";
    $outputex.="<td><select name='pattern_field[]'><option value='void'>".__('Skip field')."</option>\n";
    // display all available fields
    foreach ($fields as $field_name => $field) {
      $outputex.="<option value='$field_name'";
      if ($field_name == $pattern[$i][0]) $outputex.=" selected='selected'";
      $outputex.=">".enable_vars($field['form_name'])."</option>\n";
    }
    $outputex.="</select></td></tr>\n";
  }

  $butsub[]=array('type' => 'submit', 'name' => 'create', 'value' => __('Create'), 'active' => false);
  $outputex.='</tbody></table>'.get_buttons($butsub);
  $outputex.="</div></div></form>\n";
  echo $outputex;
}

?>
