<?php

// contacts_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, Norbert Ku:ck
// $Id: contacts_forms.php,v 1.44.2.4 2005/09/12 11:21:43 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use index.php!');

include_once($lib_path.'/access_form.inc.php');

// **********************
// form for users/members
if ($action == 'members') {

    // show data details of members, edit own data
    $result = db_query("SELECT ID, anrede, vorname, nachname, kurz, firma, email, tel1, tel2,
                               mobil, fax, strasse, stadt, plz, land, ldap_name
                          FROM ".DB_PREFIX."users
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ((PHPR_LDAP != 0) && ($ldap_conf[$row[15]]["ldap_sync"] == "2")) {
        get_ldap_usr_data($row[0]);
    }

    $row = explode("?", html_out(implode('?', $row)));
    if(($user_ID <> $row[0]) or ((PHPR_LDAP != 0) && ($ldap_conf[$row[15]]['ldap_sync'] == '2'))) {
        read_o($read_o);
    }


    /******************************
    *           tabs
    ******************************/
    $tabs = array();
    $buttons = array();
     // form start
    $hidden = array('mode' => 'view', 'ID' => $ID, 'action', 'members');
    if(SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
    $output.=get_buttons($buttons);
    $output .= get_tabs_area($tabs);

    /******************************
    *         buttons
    ******************************/
    $buttons = array();
    $buttons[] = array('type' => 'text', 'text' => $row[3].', '.$row[2]);
    $buttons[] = array('type' => 'separator');
    // modify
    if ($user_ID == $row[0]){
        $buttons[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
        $buttons[] = array('type' => 'submit', 'name' => 'members', 'value' => __('Accept'), 'active' => false);
    }
    // cancel
    $buttons[] = array('type' => 'link', 'href' => 'contacts.php?action=contacts&amp;type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page.'&amp;action=members', 'text' => __('Cancel'), 'active' => false);
    $output .= get_buttons_area($buttons);


    /*******************************
    *       basic fields
    *******************************/
    $form_fields = array();
    $form_fields[] = array('type' => 'text', 'name' => 'anrede', 'label' => __('Salutation').__(':'), 'value' => $row[1], 'readonly' => true);
    $form_fields[] = array('type' => 'text', 'name' => 'vorname', 'label' => __('First Name').__(':'), 'value' => $row[2], 'readonly' => true);
    $form_fields[] = array('type' => 'text', 'name' => 'firma', 'label' => __('Company').__(':'), 'value' => $row[5], 'readonly' => ($read_o != 0));
    $form_fields[] = array('type' => 'text', 'name' => 'email', 'label' => __('Email').__(':'), 'value' => $row[6], 'readonly' => ($read_o != 0));
    $form_fields[] = array('type' => 'text', 'name' => 'fax', 'label' => __('Fax').__(':'), 'value' => $row[10], 'readonly' => ($read_o != 0));
    $form_fields[] = array('type' => 'text', 'name' => 'plz', 'label' => __('Zip code').__(':'), 'value' => $row[13], 'readonly' => ($read_o != 0));
    $form_fields[] = array('type' => 'text', 'name' => 'land', 'label' => __('Country').__(':'), 'value' => $row[14], 'readonly' => ($read_o != 0));
    $basic_fields_left = get_form_content($form_fields);

    $form_fields = array();
    $form_fields[] = array('type' => 'text', 'name' => 'nachname', 'label' => __('Family Name').__(':'), 'value' => $row[3], 'readonly' => true);
    $form_fields[] = array('type' => 'text', 'name' => 'kurz', 'label' => __('Short Form').__(':'), 'value' => $row[4], 'readonly' => true);
    $form_fields[] = array('type' => 'text', 'name' => 'tel1', 'label' => __('Phone').'1'.__(':'), 'value' => $row[7], 'readonly' => ($read_o != 0));
    $form_fields[] = array('type' => 'text', 'name' => 'tel2', 'label' => __('Phone').'2'.__(':'), 'value' => $row[8], 'readonly' => ($read_o != 0));
    $form_fields[] = array('type' => 'text', 'name' => 'mobil', 'label' => __('Mobile Phone').__(':'), 'value' => $row[9], 'readonly' => ($read_o != 0));
    $form_fields[] = array('type' => 'text', 'name' => 'strasse', 'label' => __('Street').__(':'), 'value' => $row[11], 'readonly' => ($read_o != 0));
    $form_fields[] = array('type' => 'text', 'name' => 'stadt', 'label' => __('City').__(':'), 'value' => $row[12], 'readonly' => ($read_o != 0));
    $basic_fields_right = get_form_content($form_fields);

    $output .= '
    <br/>
    <div class="inner_content">
        <a name="content"></a>
        <a name="oben" id="oben"></a>
        <div class="boxHeaderLeft">'.__('Basis data').'</div>
        <div class="boxHeaderRight"><a class="formBoxHeader" href="#unten">'.__('Links').'</a></div>
        <div class="boxContentLeft">'.$basic_fields_left.'</div>
        <div class="boxContentRight">'.$basic_fields_right.'</div>
        <br style="clear:both"/><br/>
    </div>
    <br style="clear:both"/><br/>
    </form>
    ';
}

// ***********
// case import
elseif ($import){
    include_once('./contacts_import_forms.php'); }

// ******************
// create/edit contact
// ******************

else {

  // check permission and fetch values for viewing or modifying a record
  if ($ID > 0) {
    // check permission
    $result = db_query("select ID, von, acc_write
                          from ".DB_PREFIX."contacts
                         where ID = '$ID' and
                               (acc_read like 'system' or ((von = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))") or db_die();
    $row = db_fetch_row($result);
    if (!$row[0]) { die("You are not privileged to do this!"); }
    if ($row[1] <> $user_ID and $row[2] <> 'w') { $read_o = 1; }
    else $read_o = 0;
    // fetch values from db
    $result = db_query("select anrede,vorname,nachname,firma,email,email2,url,tel1,tel2,mobil,fax,strasse,
                               stadt,plz,land,state,div1,div2,kategorie,parent,von,acc_write,bemerkung,acc_read
                          from ".DB_PREFIX."contacts
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    $row = explode("?",html_out(implode("?",$row)));

    touch_record('contacts', $ID);
  }
  // **********
  // start form
  if($ID)$head=slookup('projekte','name','ID',$ID);
  else $head=__('New contact');
  if(!$head) $head=__('New contact');
  if ($approve_contacts)$head=__('Import');


    /******************************
    *           tabs
    ******************************/
    $tabs = array();
   // $tabs[] = array('href' => $_SERVER['PHP_SELF'], 'active' => false, 'id' => 'tab2', 'target' => '_self', 'text' => __('Export'), 'position' => 'right');
    $buttons = array();
     // form start
    $hidden = array('mode'=>'data','input'=>1);
    if (SID) $hidden[session_name()] = session_id();
    $buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'enctype' => 'multipart/form-data', 'name' => 'frm');

    $output.=get_buttons($buttons);
    $output .= get_tabs_area($tabs);

    /******************************
    *         buttons
    ******************************/
    $buttons = array();

    if (!$read_o and check_role("contacts") > 1 and $approve_contacts){
        $hidden = array('mode'=>'data','input'=>1);
        if (SID) $hidden[session_name()] = session_id();
       // $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
        // text
        $buttons[] = array('type' => 'text', 'text' => '<b>&nbsp;&nbsp;&nbsp;'.__('Import list').' </b>');
        $buttons2[] = array('type' => 'text', 'text' => '<b>&nbsp;&nbsp;&nbsp;'.__('Import list').' </b>');
        // approve contacts
        $buttons[] = array('type' => 'submit', 'name' => 'imp_approve', 'value' => __('approve'), 'active' => false);
        $buttons2[] = array('type' => 'submit', 'name' => 'imp_approve', 'value' => __('approve'), 'active' => false);
        $buttons[] = array('type' => 'submit', 'name' => 'imp_undo', 'value' => __('undo'), 'active' => false);
        $buttons2[] = array('type' => 'submit', 'name' => 'imp_undo', 'value' => __('undo'), 'active' => false);
        // form end
        $buttons[] = array('type' => 'form_end');
        $buttons2[] = array('type' => 'form_end');
    }

    if (!$read_o and check_role("contacts") > 1){
        if (!$approve_contacts) {
            if (!$ID) {
                $buttons[]  = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Accept'), 'active' => false);
                $buttons2[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Accept'), 'active' => false);
                $buttons[]  = array('type' => 'hidden', 'name' => 'anlegen', 'value' => 'neu_anlegen');
                $buttons2[] = array('type' => 'hidden', 'name' => 'anlegen', 'value' => 'neu_anlegen');
            } // modify and delete
            else if ($ID > 0 ) {
                $buttons[]  = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);
                $buttons2[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);
                if ($row[20] == $user_ID or $row[21] == 'w') {
                    $buttons[]  = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Accept'), 'active' => false);
                    $buttons2[] = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Accept'), 'active' => false);
                    // change values
                    $buttons[]  = array('type' => 'hidden', 'name' => 'aendern', 'value' => 'aendern');
                    $buttons2[] = array('type' => 'hidden', 'name' => 'aendern', 'value' => 'aendern');
                    // check whether there is no subproject beyond this one.
                    // if no parent exists AND if we are the owner of the contact -> allow to delete
                    $result2 = db_query("select ID
                                       from ".DB_PREFIX."projekte
                                      where parent = '$ID'") or db_die();
                    $row2 = db_fetch_row($result2);
                    if ($row2[0] == '' && $row[20] == $user_ID) {
                        $buttons[]  = array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
                        $buttons2[] = array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
                    }
                }
            }
        }
    } // end buttons chief only
    // cancel
    if ($justform > 0) {
        $buttons[]  = array('type' => 'button', 'name' => 'close', 'value' => __('Close window'), 'active' => false, 'onclick' => 'window.close();');
        $buttons2[] = array('type' => 'button', 'name' => 'close', 'value' => __('Close window'), 'active' => false, 'onclick' => 'window.close();');
    }
    else {
        $buttons[]  = array('type' => 'link', 'href' => 'contacts.php?action=contacts&amp;type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('Cancel'), 'active' => false);
        $buttons2[] = array('type' => 'link', 'href' => 'contacts.php?action=contacts&amp;type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('Cancel'), 'active' => false);
    }
    // export vcard
    if (!$import_contacts and $row[2]) {
        $buttons[] = array('type' => 'link', 'href' => 'vcard_ex.php?contact_ID='.$ID.'&name='.urlencode($row[2]).'&'.SID, 'text' => __('create vcard'), 'active' => false);
    }
    $output .= get_buttons_area($buttons);


    /*******************************
    *       basic fields
    *******************************/
    $form_fields   = array();
    $form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
    $form_fields[] = array('type' => 'hidden', 'name' => 'action', 'value' => $action);
    $form_fields[] = array('type' => 'hidden', 'name' => 'ID', 'value' => $ID);
    if (SID) $form_fields[] = array('type' => 'hidden', 'name' => session_name(), 'value' => session_id());
    foreach ($view_param as $key => $value) {
        $form_fields[] = array('type' => 'hidden', 'name' => $key, 'value' => $value);
    }
    $form_fields[] = array('type' => 'parsed_html', 'html' => build_form($fields));
    $basic_fields  = get_form_content($form_fields);

    /******************************
    *       categorization
    ******************************/
    $form_fields = array();
    $cat = "<label for='parent' class='center2'>".__('Parent object').":</label>\n";
    $cat .= select_contacts($row[19], 'parent',$ID,'','parent');
    if (PHPR_CONTACTS_PROFILES) {
        // fetch all profiles where the contact is member
        $result3 = db_query("select ".DB_PREFIX."contacts_profiles.ID, name
                               from ".DB_PREFIX."contacts_profiles, ".DB_PREFIX."contacts_prof_rel
                              where contacts_profiles_ID = ".DB_PREFIX."contacts_profiles.ID and
                                    contact_ID = '$ID'") or db_die();
        while ($row3 = db_fetch_row($result3)) { $profile_member[] = $row3[0]; }
        $cat .=  "<br /> <br/><label for='profile_lists' class='center2'>".__('Profiles')."</label>\n";
        $cat .=  "<select name='profile_lists[]' id='profile_lists' multiple='multiple' size='6' ".read_o($read_o).">
        <option value=''></option>\n";
        // show all profiles
        $result3 = db_query("select ID, name
                               from ".DB_PREFIX."contacts_profiles
                              where von = '$user_ID'
                           order by name") or db_die();
        while ($row3 = db_fetch_row($result3)) {
          $cat .= "<option value=$row3[0]";
          // compare the array of profiles where the contact is listed with the current profile - if yes, mark it as selected
          // the first condition is there to avoid that a warning will appear if the array is empty - IMO a silly warning because it wouldn't harm anything ...
          if ($profile_member[0] > 0) { if (in_array($row3[0], $profile_member)) {$cat .=" selected"; }}
          $cat .= "> $row3[1]</option>\n";
        }
        $cat .="</select>\n";
    }
    $form_fields[] = array('type' => 'parsed_html', 'html' => $cat);
    $categorization_fields = get_form_content($form_fields);

    /******************************
    *         assignment
    ******************************/
    include_once("../lib/access_form.inc.php");
    $form_fields = array();
    $form_fields[] = array('type' => 'parsed_html', 'html' => access_form2($row[23], 1, $row[21], 0, 1)); // acc_read, exclude the user itself, acc_write, no parent possible, write access=yes
    $assignment_fields = get_form_content($form_fields);

    $output .= '
    <br/>
    <div class="inner_content">
        <a name="content"></a>
        <a name="oben" id="oben"></a>
        <div class="boxHeaderLeft">'.__('Basis data').'</div>
        <div class="boxHeaderRight"><a class="formBoxHeader" href="#unten">'.__('Links').'</a></div>
        <div class="boxContent">'.$basic_fields.'</div></div>
        <br style="clear:both"/><br/>

        <div class="boxHeaderLeft">'.__('Categorization').'</div>
        <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
        <div class="boxContent">'.$categorization_fields.'</div>
        <br style="clear:both"/><br/>

        <a name="unten" id="unten"></a>
        <div class="boxHeaderLeft">'.__('Assignment').'</div>
        <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
        <div class="boxContent">'.$assignment_fields.'</div>
        <br style="clear:both"/><br/>
    ';
    $output .= get_buttons_area($buttons2);
    $output .='</div></form><div class="hline"></div>';


    if (!$read_o ){
        /******************************
        *    show related objects
        ******************************/
        if ($ID > 0) {
            $output.= "<br />\n";
            $referer = "contacts.php?mode=forms&amp;ID=$ID";
            $contact_ID = $ID;
            // include the lib
            include_once("$lib_path/show_related.inc.php");
            if (PHPR_PROJECTS and check_role("projects") > 0) {
                $contact_ID = $ID;
                $query = "contact = '$ID'";
                $output.= show_related_projects($query, $referer);
                $output.= "<br />\n";
            }
            // show related todos
            if (PHPR_TODO and check_role("todo") > 0) {
                $module='contacts';
                $query = "contact = '$ID'";
                $output.=show_related_todo($query,$referer);
                $output.= "<br />\n";
            }
            // related notes, show only for existing projects
            if (PHPR_NOTES and check_role("notes") > 0) {
                $module='contacts';
                $query = "contact = '$ID'";
                $output.=show_related_notes($query,$referer);
                $output.= "<br />\n";
            }
            // show related files
            if (PHPR_FILEMANAGER and check_role("filemanager") > 0) {
                $module='contacts';
				$query = "contact='$ID'";
                $output.= show_related_files($query,$referer);
                $output.= "<br />\n";
            }
            // show related events
            if (PHPR_CALENDAR and check_role("calendar") > 0) {
                $module='contacts';
                $query = "contact = '$ID'";
                $output.= show_related_events($query,$referer);
                $output.= "<br />\n";
            }
            $module='contacts';
            // show history
            if (PHPR_HISTORY_LOG) $output .= history_show('contacts', $ID);
        }
    }
}

echo $output;

?>
