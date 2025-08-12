<?php

// contact_import_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: contacts_import_forms.php,v 1.22 2005/07/05 11:27:01 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

  // import routines are available for the following adress books:
  $abooks = array('vcard'=>'vcard','oe'=>'Outlook Express','outlook'=>'Outlook','kde3'=>'KDE 3 Adressbook','other'=>'Other List');

    //tabs
    $tabs = array();
    if($import_contacts <> '' and $import_contacts != "import_contacts"){
        // form start
        $hidden = array('import' => 1, 'ID' => $ID, 'action' => $action);
        foreach($view_param as $key => $value){
            $hidden[$key] = $value;
        }
        if(SID) $hidden[session_name()] = session_id();
        $buttons = array();
        $buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'enctype' => 'multipart/form-data', 'name' => 'frm', 'onsubmit' => 'return chkForm(\'frm\',\'userfile\',\''.__('Please specify a description!').'!\');');
        $outputimf=get_buttons($buttons);
    }
    $buttons = array();
    $outputimf .= get_tabs_area($tabs);
    if(!($import_contacts <> '' and $import_contacts != "import_contacts")){
        // form start
    $hidden = array('mode' => 'import_forms', 'ID' => $ID, 'action' => $action);
    foreach($view_param as $key => $value){
        $hidden[$key] = $value;
    }
    if(SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);

    //$but[] = array('type' => 'form_start', 'hidden' => $hidden);
    //$outputimf .= get_buttons($but);


    // button bar

    $buttons[] = array('type' => 'text', 'text' => __('Import'));

    $select_field = '
    &nbsp;
    <select name="import_contacts" onchange="this.form.submit();">
    <option value="import_contacts">'.__('Please select!').'</option>
    ';
    foreach($abooks as $abook1 => $abook2) {
        $select_field .= '<option value="'.$abook1.'"';
        if ($import_contacts == $abook1){
            $select_field .= 'selected="selected"';
        }
        $select_field .= '>'.$abook2.'</option>';
    }
    $select_field .= '
    </select></span>
    <noscript>
    '.get_go_button().'</noscript><span class="nav_area">';
    $buttons[] = array('type' => 'text', 'text' => $select_field);
    $buttons[] = array('type' => 'form_end');
    }
    else $buttons[] = array('type' => 'submit', 'name' => 'create', 'value' => __('Create'), 'active' => false);
        $buttons[] = array('type' => 'link', 'href' => 'contacts.php?perpage='.$perpage.'&amp;page='.$page_p.'&amp;up='.$up.'&amp;sort='.$sort.'&amp;keyword='.$keyword.'&amp;action=contacts&amp;filter='.$filter.$sid, 'text' => __('back'), 'active' => false);
    // form end

    $outputimf .= get_buttons_area($buttons);
    $outputimf .= '<div class="hline"></div>';
    $outputimf .= '
    <br/>
    <div class="inner_content">
    <a name="content"></a>
    ';


    if($import_contacts <> '' and $import_contacts != "import_contacts"){
        $outputimf .='
            <div class="boxHeader"></div>
            <div class="boxContent">
        ';


    $x="<br /><br /><label class='options' for='kategorie'>".__('Category').": </label><input type='text' name='kategorie' id='kategorie' size='20' maxlength='30' /><br />";
    switch($import_contacts){
      case "vcard":
        $outputimf.= "<br /><label for='userfile' class='options'>".__('Please select a vcard (*.vcf)').":</label> ";
        break;
      case "oe":
        $outputimf.= __('Howto: Open your outlook express address book and select file/export/other book<br />Then give the file a name, select all fields in the next dialog and finish')."<br /><hr noshade='noshade' size='1' />\n";
       $outputimf.="<br /><label for='userfile' class='options'>".__('Please choose an export file (*.csv)').": </label>\n";
        break;
      case "outlook":
        $outputimf.= __('Open outlook at file/export/export in file,<br />choose comma separated values (Win), then select contacts in the next form,<br />give the export file a name and finish.')."<br /><hr noshade='noshade' size='1' />\n";
        $outputimf.= "<br /><label for='userfile' class='options'>".__('Please choose an export file (*.csv)').":</label> \n";
        break;
      case "kde3":
      $outputimf.= "<br /><label for='userfile' class='options'>".__('Please select a file (*.csv)').":</label> ";
        break;
      case "other":
        // explanation of the procedure
        $outputimf.= __('Please export your address book into a comma separated value file (.csv), and either<br />1) apply an import pattern OR<br />2) modify the columns of the table with a spread sheet to this format<br />(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):')."<br />";
        // list here all active fields
        foreach($fields as $field_name => $field) { $outputimf.= enable_vars($field['form_name']).', '; }
        $outputimf.= "<br /><img src='$img_path/s.gif' width='300' height='1' vspace='3' alt='separation line' /><br />\n";
        $outputimf.="<br /><label for='userfile' class='options'>".__('Please select a file (*.csv)').":</label>\n";
        $x="";
        //
        break;
    }
    $outputimf.= "<input type='hidden' name='mode' value='data' />\n";
   $outputimf.= "<input type='hidden' name='import_contacts' value='$import_contacts' />\n";
    // file upload element
    $outputimf.="<input type='file' name='userfile' id='userfile' size='20' />\n";
    // some remarks - depending on the import source
   $outputimf.=$x;
    // select field delimiter - only in case of 'other format'
    if ($import_contacts == 'other') $outputimf.= "<br /><br />
    <label class='options' for='csv_field_delimiter'>".__('Field separator').":</label>
    <select class='options' name='csv_field_delimiter' id='csv_field_delimiter'><option value='0'>,
    </option><option value='1'>;</option></select><br />\n";

    // apply import patterns if contacts_profiles are enabled and cas 'other list'
    if (PHPR_CONTACTS_PROFILES and $import_contacts == 'other') {
      $outputimf.="<label class='options' for='apply_pattern'>".__('Apply import pattern')."</label>
      <select class='options' name='apply_pattern' id='apply_pattern'><option value=''></option>\n";
      $result = db_query("select ID, name
                            from ".DB_PREFIX."contacts_import_patterns
                           where von = '$user_ID'
                        order by name") or db_die();
      while ($row = db_fetch_row($result)) {
        $outputimf.="<option value='$row[0]'>$row[1]</option>\n";
      }
      $outputimf.= "</select>\n";
    }


        $outputimf .='
            </div>
            <br style="clear:both"/><br/>
            <div class="boxHeader">'.__('Assignment') .'</div>
            <div class="boxContent">
        ';
    $outputimf.= '<div class="formbody">';
    // access mode
    include_once($lib_path.'/access_form.inc.php');
    $outputimf.= "<br />".access_form2(0, 0, 0, 0, 1)."\n";
    // end of form and submit icon
      $outputimf.= "</div>\n";
        $outputimf .='
            </div>
            <br style="clear:both"/><br/>
            <div class="boxHeader">'.__('Doublets') .'</div>
            <div class="boxContent">
        ';
    $outputimf.= '<div class="formbody"><fieldset>';

    // check for double entries
    $outputimf.=  "<label class='options' for='doublet_check'>".__('Check for duplicates during import')."</label>
    <input type='checkbox' name='doublet_check' id='doublet_check' /><br />";
     $outputimf.=  "<label class='options' for='doublet_fields'>".__('Fields to match')."</label>
     <select class='options' name='doublet_fields[]' id='doublet_fields' multiple='multiple' size='5'>\n";
    foreach ($doublet_fields_all as $db_name => $field_name) {  $outputimf.=  "<option value='$db_name'>$field_name</option>"; }
    $outputimf.=  "</select><br />";
    $outputimf.=  "<label class='options' for='doublet_action'>".__('Action for duplicates')."</label><select class='options' name='doublet_action' id='doublet_action'>\n";
    $outputimf.=  "<option value='discard'>".__('Discard duplicates')."</option> <option value='dispose_child'>".__('Dispose as child');
    $outputimf.= "</option><option value='replace'>__('Use doublet')</option> </select>";
    $outputimf.= "</fieldset></div>\n";
        $outputimf .='</div></div></form>
            <br style="clear:both"/><br/>

        ';
    }

    //$outputimf .= '</form>';

  // additional mask: administrate import patterns
  if ($import_contacts == 'other') {
    $outputimf .= '
    <div class="boxHeader">'.__('Import pattern').'</div>
    <div class="boxContent">
    ';
    $outputimf.= "<fieldset>";
    $outputimf.= "<form action='contacts.php' method='post' enctype='multipart/form-data' name='frm_pattern'>\n";
    $outputimf.= "<input type='hidden' name='mode' value='import_patterns' />\n";
    // upload example file
     $outputimf.="<label class='options' for='userfile2'>".__('For modification or creation<br />upload an example csv file').":</label>
     <input type='file' name='userfile' id='userfile2' size='12' /><br /><br />\n";
   $outputimf.="<label class='options' for='csv_field_delimiter'>".__('Field separator').":</label>
   <select name='csv_field_delimiter'><option value='0'>,</option><option value='1'>;</option></select>\n";
   $outputimf.="<br /><br /><div align='center'>";
   $outputimf .= get_buttons(array(array('type' => 'submit', 'name' => 'neu', 'value' => __('Create'), 'active' => false)));
   $outputimf.="&nbsp;&nbsp;".__('or')."&nbsp;&nbsp;  \n";
   $outputimf.= "<select name='ID'><option value=''></option>";
    $result = db_query("select ID, name
                      from ".DB_PREFIX."contacts_import_patterns
                     where von ='$user_ID'") or db_die();
    while ($row = db_fetch_row($result)) { $outputimf.= "<option value='$row[0]'>$row[1]</option>\n";}
     $outputimf.= "</select>&nbsp;&nbsp;";
    if(SID)  $outputimf.="<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
   $outputimf .= get_buttons(array(array('type' => 'submit', 'name' => 'aendern', 'value' => __('Modify'), 'active' => false)));
   $outputimf .= get_buttons(array(array('type' => 'submit', 'name' => 'loeschen', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');')));
    $outputimf.= "</div></form></fieldset>\n"; ;

    $outputimf .= '
    </div>
    <br style="clear:both"/><br/>
    ';

  }

echo $outputimf;
?>