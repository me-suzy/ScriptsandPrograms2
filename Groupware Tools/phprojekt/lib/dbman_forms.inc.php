<?php

// dbman_forms.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: dbman_forms.inc.php,v 1.50.2.2 2005/09/14 09:36:15 fgraf Exp $


// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use index.php!');

// output of all elements of a form, uses the function form_element to display the different types of form elements like text, textarea  etc.
function build_form($fields) {
    global $settings,$js_html_textarea,$module,$path_pre;
    // include datepicker
    $output1 = datepicker();
    // determine how many rows should be displayed
    if      ($settings['screen'] > 1250) $rows = 4;
    else if ($settings['screen'] > 1000) $rows = 3;
    else $rows = 2;

    $current_row = 1;
    $count = 0;

    foreach ($fields as $field_name => $field) {
        $count++;
        $ff = '';
        //Wenn bei Links neu anfängt kann es keine Probleme geben
        if ($count%4==1) {
            if (($field['form_colspan'] > 1)||($field['form_rowspan'] > 1)||($field['form_type'] == 'textarea')) {
                $output1 .= '<div class="formBodyRow">';
                $ff = 'small';
                $count = 4;
            }
            else $output1 .= '<div class="formBodyLeft">';
        }
        //Wenn bei Rechts neu anfängt geht das nur wenn coslpan=1
        else if ($count%4==3) {
            if (($field['form_colspan'] > 1)||($field['form_rowspan'] > 1)||($field['form_type'] == 'textarea')) {
                $output1 .= '<div class="formBodyRight"><br /><br class="clear" /></div>';
                $output1 .= '<div class="formBodyRow">';
                $ff = 'small';
                $count++;
            }
            else $output1 .= '<div class="formBodyRight">';
        }
        else if ($count%4==2&&(($field['form_colspan'] > 1)||($field['form_rowspan'] > 1)||($field['form_type'] == 'textarea'))) {
            $output1 .= '<br class="clear" /></div><div class="formBodyRight"><br /><br class="clear" /></div><div class="formBodyRow">';
            $ff = 'small';
            $count = 4;
        }
        else if ($count%4==0&&(($field['form_colspan'] > 1)||($field['form_rowspan'] > 1)||($field['form_type'] == 'textarea'))) {
            $output1 .= "<br class='clear' /></div><div class='formBodyRow'>";
            $ff = 'small';
            $count = 4;
        }
        else $output1 .= '<br class="clear" />';

        $output1 .= form_element($field, $field_name, $ff);
        // raise $current_row if we occur a rowspan flag
        if ($count%4==2 || $count%4==0) {
            $output1 .= '<br class="clear" /></div>';
        }
    }

    if ($count%4==1)      $output1 .= '</div><div class="formBodyRight"><br />&nbsp;';
    else if ($count%4==2) $output1 .= '<div class="formBodyRight">';
    else if ($count%4==3) $output1 .= '';

    // check for textarea with html editor
    if ($_SESSION['show_html_editor'][$module] == 1 and is_array($js_html_textarea)) {
      $output1 .= "<script type=\"text/javascript\">window.onload = function() {\n";
      foreach ($js_html_textarea as $f) {
        $output1 .= "var ".$f."=new FCKeditor('".$f."');\n".$f.".BasePath='".$path_pre."lib/';\n".$f.".Width='450';\n".$f.".Height='150';\n".$f.".ReplaceTextarea();\n";
      }
      $output1 .=  '}</script>';
    }

    return $output1;
}


// foreach defined form element type the html snippet
function form_element($field, $field_name, $ff='') {
    global $ID, $read_o, $text_width, $user_ID, $user_kurz, $sql_user_group, $contact_ID, $js_html_textarea;
    global $projekt_ID, $user_group, $img_path, $path_pre, $file_ID, $filter_maxhits, $selected, $module;

    // if it's a new record, give the default value predefined in the db
    if (!$ID) $field['value'] = enable_vars($field['form_default']);
    $field['value'] = stripslashes($field['value']);
    
    $output1 = '';

    switch ($field['form_type']) {

        case 'timestamp_show':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label><div class='form_div' id='$field_name'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            if ($field['value'] <> '') $output1.= ">".show_iso_date1($field['value']);
            else $output1.= '>-';
            $output1.= "</div>\n";
            break;

        // skip display on creation, onlyshow value at modification
        case 'timestamp_create':
            if ($ID > 0) {
                $output1.= "<label class='form$ff' for='$field_name'>";
                $output1.=enable_vars($field[form_name])."</label><div class='form_div'";
                if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
                $output1.= '>'.show_iso_date1($field['value']);
                $output1.= "</div>\n";
                break;
            }
            break;

        case 'timestamp_modify':
            if ($ID > 0) {
                $output1.= "<span class='form$ff'>";
                $output1.=enable_vars($field[form_name])."</span><div class='form_div'";
                if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
                $output1.= '>'.show_iso_date1($field['value']);
                $output1.= "</div>\n";
            }
            break;

        // display: no chance to edit this, just plain view
        case 'display':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label><div class='form_div' id='$field_name'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= '>'.$field['value'];
            $output1.= "</div>\n";
            break;

        // display: value taken out of a foreign table via sql
            case 'display_sql':
            $result = db_query(enable_vars($field['form_select']));
            if($result)$row = db_fetch_row($result);
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label><div class='form_div'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= '>'.implode(' ',$row);
            $output1.= "</div>\n";
            break;

        // more complex string, even with calculations
        case 'display_string':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label><div class='form_div'>";
            $output1.= show_string_list($field['form_select'],$GLOBALS['fields'],'','');

            $output1.= "</div>\n";
            break;

        // text-create means that the user can only insert a text at the time he craetes the record but cannot modify the value later
        case 'text_create':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            if (!$ID) {
                $output1.= "<input type='text' size='".set_size($field)."' name='". $field_name."' value='".$field['value']."'";
                if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
                $output1.= read_o($read_o)." ".build_regexp($field['regexp'], $field_name)."/>";
            }
            else $output1.="<div class='form_div'> $field[value]</div>";
            $output1.= "\n";
            break;

        // textarea - no comment
        case 'textarea':
            // use html editor?
            if ($_SESSION['show_html_editor'][$module] > 0) {
              $textarea_id = 'id="'.$field_name.'"';
              $js_html_textarea[] = $field_name;
            }
            else {
               $textarea_id = 'id="'.$field_name.'"';
                    }
            $output1.= "<label class='formsmall' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<textarea class='form' ".set_size($field)." name='". $field_name."' ".$textarea_id;
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o, 'readonly').">".$field['value']."</textarea>";
            $output1.= "\n";
            break;

        // single checkbox - another 'no comment'
        case 'checkbox':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<input class='form$ff' style='width:15px;' type='checkbox' name='".$field_name."' value='yes'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            if ($field['value'] <> '') $output1.= " checked";
            $output1.= read_o($read_o)." />\n";
            $output1.= "\n";
            break;

        // range of predefined, fixed values from the field 'form_select'
        case 'select_values':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<select class='form$ff' id='$field_name'  name='". $field_name."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o).'>';
            foreach (explode('|', $field['form_select']) as $select_value) {
                // split the entry into key and value
                list($key,$value) = explode('#',$select_value);
                if (!$value) $value = $key;
                $output1.= "<option value='".$key."'";
                if ($key == $field['value'])$output1.= ' selected="selected"';
                $output1.= '>'.enable_vars($value)."</option>\n";
            }
            $output1.= "</select>\n";
            break;

        // multiple select
        case 'select_multiple':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.= enable_vars($field[form_name])."</label>";
            $output1.= "<select multiple='multiple' class='form$ff' size='".set_size($field)."' name='". $field_name."[]'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o).'>';
            // move previously selected values into array
            $selected = explode('|',$field['value']);
            foreach (explode('|', $field['form_select']) as $select_value) {
                $output1.= "<option value='".$select_value."'";
                if (in_array($select_value,$selected))$output1.= ' selected="selected"';
                $output1.= '>'.$select_value."</option>\n";
            }
            $output1.= "</select>\n";
            break;

        // fetches the result of a sql statement and displays it in a select box
        case 'select_sql':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<select  class='form$ff' name='". $field_name."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o).'>';
            $result = db_query(enable_vars($field['form_select']));
            while ($row = db_fetch_row($result)) {
                $first_element = array_shift($row);
                $str .= "<option value='".$first_element."'";
                if ($first_element == $field['value'])$str .= ' selected="selected"';
                $str .= ">".implode(',',$row)."</option>\n";
            }
            $str .= "</select>\n";
            break;

        // for any kind of phone operation, kind of call system is set in the config.inc.php
        case 'phone':

            $output1.= "<label class='form$ff' for='$field_name'>";
            if ($ID > 0)$output1.= "<a title='".__('This link opens a popup window')."' href=\"javascript:go_phone('".PHPR_CALLTYPE."','".$field['value']."');\">".enable_vars($field[form_name])."</a>";
            else $output1.= enable_vars($field[form_name]);
            $output1.= "</label><input type='text' id='$field_name' class='form$ff' size='".set_size($field)."' name='". $field_name."' value='".$field['value']."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o)." />\n";
            break;

        // combines the option to select an entry from previous records or to insert a new value
        case 'select_category':
            // first the previous values
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<select class='form$ff' id='$field_name' name='". $field_name."' style='margin-left: -5px;";
            if($read_o!=0)$output1.= "background-color:".PHPR_BGCOLOR3."' disabled='disabled";
            if ($field['form_tooltip'] <> '') { $output1.= "' title='".$field['form_tooltip']."\n"; }

           $output1.="'><option value=''></option>";
            // define access rule
            $result = db_query("SELECT DISTINCT ".qss($field_name)."
                                           FROM ".qss(DB_PREFIX.$field['tablename'])."
                                          WHERE ".qss($field_name)." <> ''
                                            AND von = '$user_ID'
                                       ORDER BY ".qss($field_name)) or db_die();
            $cats_all = array();
            while ($row = db_fetch_row($result)) { $cats_all[] = $row[0]; }
            // look whether the displayed value is in the list of previouse values. if not, add it.
            if (!in_array($field['value'],$cats_all)) array_unshift($cats_all,$field['value']);
            foreach($cats_all as $cat_current) {
                $output1.= "<option value='".$cat_current."'";
                if ($cat_current == $field['value']) $output1.= ' selected="selected"';
                $output1.= ">".$cat_current."</option>\n";
            }
            $output1.= "</select> \n";
            $output1.= "<div class='form_div'>".$admin_texttext64."</div> <input class='form$ff'  type='text' size='".set_size($field)."' name='new_category' style='width:80px; \n";
            if($read_o!=0)$output1.= "background-color:".PHPR_BGCOLOR3."' disabled='disabled";
            if ($field['form_tooltip'] <> '') { $output1.= "' title='".$field['form_tooltip']."\n"; }

            $output1.= "' />\n";
            break;

        case 'upload':
            list($filename,$tempname) = explode('|',$field['value']);
            $tooltip = empty($field['form_tooltip']) ? '' : "title='".$field['form_tooltip']."'";
            $output1.= "<label class='form$ff' for='$field_name'>".enable_vars($field[form_name])."</label>";
            $output1.= "<input class='form$ff'
                               type='file' 
                               size='".set_size($field)."' 
                               name='". $field_name."' 
                               $tooltip 
                               ".read_o($read_o)." />";
            
            // link to file and red button to delete this file separately
            if ($filename <> '') {
                $rnd = rnd_string(9);
                $file_ID[$rnd] = $field['value'];
                $output1.= "(<a href='".$path_pre."lib/file_download.php?module=$module&download_attached_file=".$rnd.$sid."' target=_blank>$filename</a> \n";
                if (!$read_o) {
                    $output1.= "<a href='$module.php?mode=data&delete_file=1&ID=$ID&file_field_name=".$field_name."'><img src='".$img_path."/r.gif' height=7 alt='".__('Delete')."' border=0></a>)";
                }
            }
            $output1 .= "\n";
            $_SESSION['file_ID'] =& $file_ID;
            break;

        case 'date':
            //  take predefined values from global space or set to 'today'
            if ($GLOBALS[$field_name] <> '') { $field['value'] = $GLOBALS[$field_name]; }
            //else if (!$field['value']) { $field['value'] = date("Y-m-d"); }
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<input class='form$ff' type='text' style='width:80px;' name='". $field_name."' id='". $field_name."' value='".$field['value']."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $date_pick = ($read_o)?(""):("&nbsp;<a href='javascript://' title='".__('This link opens a popup window')."' onclick='callPick(document.frm.".$field_name.")' ><img style='margin-top:8px'src='".$img_path."/cal.gif' border='0' alt='calendar' /></a>");
            $output1.= read_o($read_o)." />".$date_pick."\n";
            break;

        case 'time':
            // split time into hours and minutes
            $hour   = substr($field['value'], 0, 2);
            $minute = substr($field['value'], 3, 5);
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<select class='form$ff' name='".$field_name."_hour'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o).'>';
            // hours
            for ($i=0; $i<=23; $i++) {
                if ($i<10) $i = '0'.$i;
                $output1.= "<option value='".$i."'";
                if ($i == $hour) $output1 .= ' selected="selected"';
                $output1.= ">$i</option>\n";
            }
            $output1.= '</select> ';
            // minutes
            $output1.= "<select class='form$ff' name='".$field_name."_minute'".read_o($read_o).">";
            for ($i=0; $i<=59; $i++) {
                if ($i<10) $i = '0'.$i;
                $output1.= "<option value='".$i."'";
                if ($i == $minute) $output1 .= ' selected="selected"';
                $output1.= ">$i</option>\n";
            }
            $output1 .= '</select>';
            $output1 .= "\n";
            break;

        // mail address - gives link zu mail reader
        case 'email':
            $output1.= "<label class='form$ff' for='$field_name'>";
            if ($ID > 0)$output1.= "<a href=\"javascript:mailto('email',0,'".(SID? session_id() :"")."',".PHPR_QUICKMAIL.")\">".enable_vars($field[form_name])."</a>";
            else $output1.= enable_vars($field[form_name]);
            $output1.= "</label><input class='form$ff' type='text' id='$field_name' size='".set_size($field)."' name='". $field_name."' value='".$field['value']."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1 .= read_o($read_o)." />\n";
            break;

        // give the mail adress at creation of record
        case 'email_create':
            $output1.= "<label class='form$ff' for='$field_name'>";
            if ($ID > 0) {
                $output1.= "<a href=\"javascript:mailto(0,'".$field['value']."','".(SID? session_id() :"")."',".PHPR_QUICKMAIL.")\">".enable_vars($field[form_name])."</a>";
                $output1.= "</label><div class='form_div'>".$field['value']."</div>";
            }
            else {
                $output1.= enable_vars($field[form_name]);
                $output1.= "</label><input class='form$ff' type='text' size='".set_size($field)."' name='".$field_name."' value='".$field['value']."'";
                if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'".read_o($read_o)." />"; }
            }
            $output1 .= "\n";
            break;

        // url - opens address in a new window
        case 'url':
            $output1.= "<label class='form$ff' for='$field_name'>";
            if ($ID > 0)$output1.= "<a title='".__('This link opens a popup window')."' href=\"javascript:go_web();\">".enable_vars($field[form_name])."</a>";
            else $output1.= enable_vars($field[form_name]);
            $output1.= "</label><input class='form$ff' type='text' size='".set_size($field)."' name='". $field_name."' id='". $field_name."' value='".$field['value']."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o)." />\n";
            break;

        // refers to an entry from the table 'contacts', selectable
        case 'contact':
            if (PHPR_CONTACTS) {
                // transmit the contact ID if the call comes from a foreign module
                if ($contact_ID > 0) $field['value'] = $contact_ID;
                $output1 .= '';
                $output1 .= "<a title='".__('This link opens a popup window')."' href=\"javascript:show();\"><label class='form$ff' for='$field_name'>".enable_vars($field[form_name])."</label></a>\n";
                $output1 .= select_contacts($field['value'], 'contact','','form'.$ff,$field_name);
                $output1 .= "\n";
            }
            break;

        // select a contact during the creation of the record but only display the value later
        case 'contact_create':
            if (PHPR_CONTACTS) {
                // transmit the contact ID if the call comes from a foreign module
                if ($contact_ID > 0) $field['value'] = $contact_ID;
                if ($ID > 0) {
                  $output1 .= "<a title='".__('This link opens a popup window')."' href=\"javascript:show();\"><label class='form$ff' for='$field_name'>".enable_vars($field[form_name])."</label></a>\n";
                $output1 .= select_contacts($field['value'], 'contact','','form'.$ff,$field_name);
                $output1 .= "\n";
                }
                else {
                   $output1 .= "<a title='".__('This link opens a popup window')."' href=\"javascript:show();\"><label class='form$ff' for='$field_name'>".enable_vars($field[form_name])."</label></a>\n";
                $output1 .= select_contacts($field['value'], 'contact','','form'.$ff,$field_name);
                $output1 .= "\n";
                }
            }
            break;

        // refers to an entry from the table 'project'
        case 'project':
            if (PHPR_PROJECTS) {
                // transmit the project ID if the call comes from a foreign module
                if ($projekt_ID > 0) $field['value'] = $projekt_ID;
                // special hack for forms - if the contact ID is given, mark this one as selected
                $projekt_ID > 0 ? $selected = $projekt_ID : $selected = $field['value'];
                $output1.= "<label class='form$ff' for='$field_name'>";
                $output1.= enable_vars($field[form_name])."</label>";
                $output1.= "<select class='form$ff'  id='$field_name' name='". $field_name."'";
                if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
                $output1.= read_o($read_o)."><option value=''></option>";
                $output1.= show_elements_of_tree('projekte',
                                                 'name',
                                                 "where (von = ".$user_ID." or acc like 'system' or ((acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group))",
                                                 'acc'," order by name",$selected,'parent',0);
                $output1.= "</select>\n";
            }
            break;

        // author ID - store the name of the user which has written the record
        case 'authorID':
            if ($ID > 0) {
                $output1.= "<label class='form$ff' for='$field_name'>";
                $output1.=enable_vars($field[form_name])."</label><div class='form_div'";
                if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
                $output1.= '>&nbsp;'.slookup('users','nachname,vorname','ID',$field['value']);
                $output1.= "</div>\n";
            }
            break;

        // handles the access to this field - if the userID equals the content of the given field, the user is able to edit this field, otherwise just display
        case 'userID_access':
            $output1.= "<span class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            // check whether content(field) == userID
            if (slookup($field['tablename'],$field['form_select'],'ID',$ID) == $user_ID) {
                $output1.= "<input type='text' class='form$ff' size='".set_size($field)."' id='$field_name' name='". $field_name."' value='".$field['value']."'";
                if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
                $output1.= read_o($read_o)." ".build_regexp($field['regexp'], $field_name)." />";
            }
            else $output1.= $field['value'];
            $output1.= "\n";
            break;

        // user ID - select an user of this group and store the ID
        case 'userID':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<select class='form$ff' id='$field_name' name='". $field_name."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o).'><option value="0"></option>';
            $result = db_query("select ".DB_PREFIX."users.ID, nachname, vorname
                                  from ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                                 where ".DB_PREFIX."users.ID = user_ID
                                   and grup_ID = '$user_group'
                                   and ".DB_PREFIX."users.status = 0
                                   and ".DB_PREFIX."users.usertype = 0
                              order by nachname");
            while ($row = db_fetch_row($result)) {
                $output1.= "<option value='".$row[0]."'";
                if ($row[0] == $field['value']) $output1.= ' selected="selected"';
                $output1.= ">".$row[1].",".$row[2]."</option>\n";
            }
            $output1.= "</select>\n";
            break;

        // select Box on all users where the ID has been stored in this field
        case 'user_select_distinct':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= "<select class='form$ff' name='". $field_name."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o).'><option value="0"></option>';
            $result = db_query("select ".DB_PREFIX."users.ID, ".DB_PREFIX."users.nachname, ".DB_PREFIX."users.vorname
                                  from ".DB_PREFIX."users, ".qss(DB_PREFIX.$field['tablename'])."
                                 where ".DB_PREFIX."users.ID = ".qss(DB_PREFIX.$field['tablename'].$field_name)."
                                   and ".DB_PREFIX."users.status = 0
                                   and ".DB_PREFIX."users.usertype = 0
                              group by ".DB_PREFIX."users.ID
                              order by nachname");
            while ($row = db_fetch_row($result)) {
                $output1.= "<option value='".$row[0]."'";
                if ($row[0] == $field['value']) $output1.= ' selected="selected"';
                $output1.= ">".$row[1].",".$row[2]."</option>\n";
            }
            $output1.= "</select>\n";
            break;


        // just display the user name
        case 'user_show':
            $output1.= "<span class='form$ff'>";
            $output1.=enable_vars($field[form_name])."</span>";
            $output1.="<div class='form_div'>";
            if ($field['value'] > 0) {
                $output1.= slookup('users','nachname,vorname','ID',$field['value']);
            }
          //  else $output1.= slookup('users','nachname,vorname','ID',$user_ID);
            $output1.="</div>";
            $output1.= "\n";
            break;

        // processes field values and formulas
        case 'formula':
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.="<div class='form_div'>";
            $output1.= build_formula($field['form_select']);
            $output1.= "</div>\n";
            break;

        // field element 'text' is default
        default:
            $output1.= "<label class='form$ff' for='$field_name'>";
            $output1.=enable_vars($field[form_name])."</label>";
            $output1.= " <input class='form$ff' id='$field_name' type='text' size='".set_size($field)."' name='". $field_name."' value='".$field['value']."'";
            if ($field['form_tooltip'] <> '') { $output1.= " title='".$field['form_tooltip']."'"; }
            $output1.= read_o($read_o)." ".build_regexp($field['form_regexp'], $field_name)." />\n";
            $output1.=" \n";
            break;
    }

    return $output1;
}


// next two function below to case 'display_string'
// but provide values from other fields and formulas
function build_formula($content) {
    global $fields;
    foreach ($fields as $field_name => $field) {
        $content = ereg_replace($field_name,$field['value'], $content);
        // special hack on special request: replaces german title 'Herr ' with 'Herrn' :)
        $content = ereg_replace('Herr ','Herrn ', $content);
    }
    return preg_replace_callback("#\[(.*)\]#siU", 'f2', $content);
}

// Security: this does get called only if the dbmanager includes a field with 
// type formula, and the value is taken from the select column. 
// So this can (as of 18.07.05) only be exploited if you are able to enter the 
// module designer, and it's not a security flaw by itself - only as a part 
// of an exploit vector (admin privileges, sql injection)
function f2($f) {
    eval('$y = '.$f[1].';');
    return $y;
}




// return size of input element - especially if we have a rowspan/colspan > 1
function set_size($field) {
    global $text_width;

    if (!PHPR_DEFAULT_SIZE) $default_size1 = 40;
    else $default_size1 = PHPR_DEFAULT_SIZE;

    if ($field['form_colspan'] > 1) {
        $default_size1 = $default_size1*$field['form_colspan']+$text_width/4*($field['form_colspan']-1);
    }

    // for some elements we have to calculate another size number for a nice table layout
    switch ($field['form_type']) {
        case 'upload':
            return ($default_size1 - 22);
            break;
        case 'textarea':
            $hor_size=floor($default_size1*0.7);
            if ($field['form_rowspan'] > 1) { $ver_size = $field['form_rowspan']*2 - 1; }
            else $ver_size = 2;
            return 'rows="'.$ver_size.'" cols="'.$hor_size.'"';
            break;
        case 'select_multiple':
            if ($field['form_rowspan'] > 1)  { $ver_size = $field['form_rowspan']*2 - 1; }
            else $ver_size = 2;
            return $ver_size;
            break;
        case 'select_category':
            return ($default_size1 - 20);
            break;
        default:
            return $default_size1;
            break;
    }
}


// adds the reagexp definition for the javascript check at submission of form
function build_regexp($regexp, $field_name) {
    if ($regexp <> '') {
        return "onBlur=\"reg_exp('frm','".$field_name."','".__('Check the content of the previous field!')."',/".$regexp."/)\"";
    }
}


// define size of element and probably colspan /rowspan
function set_span($field) {
    if ($field['form_rowspan'] > 1) {
        $rows = $field['form_rowspan'];
        $size = " rowspan=$rows";
    }
    if ($field['form_colspan'] > 1) {
        $cols = $field['form_colspan'];
        $size .= " colspan=$cols";
    }
    return $size;
}


// *******
// buttons
function set_buttons($mode) {
    switch ($mode) {
        case 'create':
            return button_create().button_back();
            break;
        case 'modify':
            return button_modify().button_delete().button_back();
            break;
        case 'copy':
            return button_copy().button_back();
            break;
    }
}

function button_create() {
    return get_buttons(array(array('type' => 'submit', 'name' => 'create_b', 'value' => __('Create'), 'active' => false)));
}
function button_modify() {
    return get_buttons(array(array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Modify'), 'active' => false)));
}
function button_delete() {
    global $ID, $module, $tablename, $ID_dbname, $parent_dbname;
    if (!slookup($tablename[$module],get_db_fieldname($module,'ID'),get_db_fieldname($module,'parent'),$ID)) {
        return get_buttons(array(array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false)));
    }
}
function button_copy() {
    return get_buttons(array(array('type' => 'submit', 'name' => 'create_b', 'value' => __('Copy'), 'active' => false)));
}
function button_back() {
    return get_buttons(array(array('type' => 'submit', 'name' => 'cancel_b', 'value' => __('back'), 'active' => false)));
}
// end buttons
// ***********

?>
