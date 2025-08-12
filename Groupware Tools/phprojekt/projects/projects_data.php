<?php

// projects_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: projects_data.php,v 1.27.2.3 2005/09/12 13:58:27 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("projects") < 2) die("You are not allowed to do this!");

include_once("$lib_path/permission.inc.php");
$include_path3 = $path_pre."lib/access.inc.php";
include_once $include_path3;
include_once("err_pro.php");
$acc = assign_acc($acc, 'projekte');
if ($acc_write <> '') $acc_write = 'w';
if ($parent == '')    $parent = 0;

switch (true) {

    case ($delete_file <> '') :
        delete_attached_file($file_field_name, $ID, 'projects');
        break;

    // **************
    // delete project
    case ($delete_b <> ''):
        manage_delete_records($ID, $module);
        break;

    case ($delete_c):
        if(isset($_REQUEST['ID_s'])){
            $tmp = explode(',', $_REQUEST['ID_s']);
            foreach($tmp as $tmp_id){
                $tmp_id = (int) $tmp_id;
                if(check_children($tmp_id, $module)){
                    message_stack_in(__('project could not be deleted, because it has sub-projects'),"projects","error");
                }
                elseif($tmp_id > 0){
                    manage_delete_records($tmp_id, $module);
                }                
            }
        }
        elseif(isset($_REQUEST['ID'])){
            $tmp_id = (int) $_REQUEST['ID'];
            if(check_children($tmp_id, $module)){
                message_stack_in(__('project could not be deleted, because it has sub-projects'),"projects","error");
            }
            elseif($tmp_id > 0){
                manage_delete_records($tmp_id, $module);
            }
        }
        unset($tmp, $tmp_id);
        break;

    // delete a file attached to a record
    case ($delete_file):
        delete_attached_file($file_field_name, $ID, 'projects');
        break;

    // *************
    // update status
    case ($modify_status_b and $ID > 0):
        if ($status <> '') {
        // check permission if you don't have chef level
        if(slookup('projekte','chef','ID',$ID) <> $user_ID) $error = 1;
        // if the status is not between 0 and 100% or not a number -> forget it
        if (!is_numeric($status) or $status < 0 or $status > 100) {
            message_stack_in(__('please check the status!'),"projects","error");
            $error = 1;
        }
        if (!$error) {
            $result = db_query("update ".DB_PREFIX."projekte
                                                set statuseintrag = '".date("Y-m-d")."',
                                                status = '".(int) $status."'
                                            where ID = '$ID'") or db_die();
        }
       }
        break;

    // *************
    // update record
    case ($modify_b and $ID > 0):

        // **********
        // set status
        if ($status <> '') {
         // check permission if you don't have chef level
        if(slookup('projekte','chef','ID',$ID) <> $user_ID) $error = 1;
        // if the status is not between 0 and 100% or not a number -> forget it
        if (!is_numeric($status) or $status < 0 or $status > 100) {
            message_stack_in(__('please check the status!'),"projects","error");
            $error = 1;
        }
        if (!$error) {
            $result = db_query("update ".DB_PREFIX."projekte
                                                set statuseintrag = '".date("Y-m-d")."',
                                                status = '".(int) $status."'
                                            where ID = '$ID'") or db_die();
            
        }
        $error='';
        }

        check_anlegen();
        // check whether the subproject has changed the parent and resides in a new branch -
        // in this case the next/previous entry and the dependency will be deleted
        if ($parent <> slookup('projekte', 'parent', 'ID', $ID)) {
        unset($depend_mode); unset($depend_proj); unset($next_mode); unset($next_proj);
        // but also: scan for projects which have this project as next or dependency and delete this relations
        $result4 = db_query("update ".DB_PREFIX."projekte
                                set depend_proj='',
                                    depend_mode=''
                                where depend_proj = '$ID'") or db_die();
        $result4 = db_query("update ".DB_PREFIX."projekte
                                set next_proj='',
                                    next_mode=''
                                where next_proj = '$ID'") or db_die();
        }

        if(!$error){
            //keep history
            if (PHPR_HISTORY_LOG) {
                sqlstrings_create();
                history_keep('projekte','acc,acc_write,'.$sql_fieldstring,$ID);
            }

            $accessstring = "acc = '$access',";
            $sql_string = sqlstrings_modify();

            // next check: there is a bug if the user has chosen 'next_mode' (means: thre is a project before or after the current one
            // but hasn't chosen a record for it! workaround: set next_mode inactive in this record
            if ($next_mode > 0 and !$next_proj) unset($next_mode);
            $result = db_query(xss("update ".DB_PREFIX."projekte
                                set $sql_string
                                    personen='".serialize($personen)."',
                                    gruppe='$user_group',
                                    parent='$parent',
                                    depend_mode = '$depend_mode',
                                    depend_proj = '$depend_proj',
                                    next_mode = '$next_mode',
                                    next_proj = '$next_proj',
                                    probability = '$probability',
                                    acc='$acc',
                                    acc_write = '$acc_write'
                                where ID = '$ID'")) or db_die();
            message_stack_in("$project_name: ".__('The project has been modified'),"projects","notice");
        }
        break;

    // leave here on tree open/close mode
    case (isset($_GET['element_mode'])):
        break;

    // *************
    // insert record
    default:
        check_anlegen();
        sqlstrings_create();
        $result = db_query(xss("insert into ".DB_PREFIX."projekte
                                        (ID,         von,   personen,             gruppe,  parent,   probability,   acc,   acc_write,".$sql_fieldstring.")
                                 values ($dbIDnull,'$user_ID','".serialize($personen)."','$user_group','$parent','$probability','$acc','$acc_write',    ".$sql_valuestring.")")) or db_die();
        // message: project inserted
        message_stack_in("$project_name: ".__('The project is now in the list'),"projects","notice");
        break;
}


function check_anlegen() {
    global $ende, $anfang, $sid, $ID, $wichtung, $project_name, $action, $error, $cat;
    global $depend_mode, $depend_proj, $project_name,$project_name, $chef, $note;
    global $contact, $stundensatz, $budget,  $parent, $img_path, $probability;

    // check if end time is bigger than start time
    if ($ende < $anfang) die(__('The duration of the project is incorrect.')."!<br /><a href='projects.php?mode=forms&ID=$ID&name=$name&anfang=$anfang&ende=$ende&wichtung=$wichtung&chef=$chef&parent=$parent&note=$note&contact=$contact&stundensatz=$stundensatz&budget=$budget$sid'>".__('back')."</a> ");
    // if given, check whether budget and hourly rates are integer
    if ($budget <> '' and !is_numeric($budget)) die(__('Calculated budget').": ".__('Please check your date and time format! ')."!<br /><a href='projects.php?mode=forms&action=$action&ID=$ID&project_name=$project_name&anfang=$anfang&ende=$ende&wichtung=$wichtung&chef=$chef&parent=$parent&note=$note&contact=$contact&stundensatz=$stundensatz&budget=$budget$sid'>".__('back')."</a> ");
    if ($stundensatz <> '' and !is_numeric($stundensatz)) die(__('Calculated budget').": ".__('Please check your date and time format! ')."!<br /><a href='projects.php?mode=forms&action=$action&ID=$ID&project_name=$project_name&anfang=$anfang&ende=$ende&wichtung=$wichtung&chef=$chef&parent=$parent&note=$note&contact=$contact&stundensatz=$stundensatz&budget=$budget$sid'>".__('back')."</a> ");

    if ($parent > 0) {
/*
        // the project is subproject? check whether the start and end time is within the limits of the parent project
        $result2 = db_query("select anfang, ende
                               from ".DB_PREFIX."projekte
                              where ID = '$parent'") or db_die();
        $row2 = db_fetch_row($result2);
        // timespan exceeds timespan of parent -> die ...
        if ($anfang < $row2[0] or $ende > $row2[1]) {
        die("$proj_text31<br /><a href='projects.php?mode=forms&action=$action&ID=$ID&project_name=$project_name&anfang=$anfang&ende=$ende&wichtung=$wichtung&chef=$chef&parent=$parent&note=$note&contact=$contact&stundensatz=$stundensatz&budget=$budget&ziel=$ziel&note=$note$sid'>$back</a>\n");
*/
        oerror($parent);
    }
    if ($ID>0) {
        $resun = db_query("SELECT ID
                             FROM ".DB_PREFIX."projekte
                            WHERE parent = '$ID'");
        $uproj[] = $arr_empt;
        while($rowun = db_fetch_row($resun)){
            if (!empty($rowun[0])) $uproj[] = $rowun[0];
        }
        if ($uproj) uerror($uproj);
    }

    // check dependencies
    if ($depend_mode > 1) $error = check_dependencies($ID, $depend_mode, $depend_proj, $cat, $project_name, $anfang, $ende);

    // probability check: in case the status is set to 'offered', the author can enter a percentage for the probability of a project
    // once the status turns into a higher level (e.g. ordered, at work etc.), the probability of course has to change to 'positive' = 100%
    if ($cat > 1) $probability = 100;
}


function check_dependencies($ID, $depend_mode, $depend_proj, $cat, $project_name, $anfang, $ende) {
    global $dependencies, $categories;

    // fetch start and end date of the target project
    $result = db_query("select anfang, ende, kategorie, name
                          from ".DB_PREFIX."projekte
                         where ID = '$depend_proj'") or db_die();
    $row = db_fetch_row($result);

    switch ($depend_mode) {

        // repeat the categiories here for those who don't want to have a look into the other skript:
        // 1=offered, 2=ordered, 3=at work, 4=ended, 5=stopped, 6=reopened 7 = waiting, 10=container, 11=ext. project
        // start means 'at work' or higher, but not 'waiting'
        // end means 'ended' or 'stopped'

        // 2 = this project cannot start before the end of project B
        case "2":
            // check logical
            if (($cat > "2" and $cat <> 7) and ($row[2] <> "4" and $row[2] <> "5")) {
                message_stack_in(__('Warning, violation of dependency').": $project_name \"$dependencies[$depend_mode] $row[3]\"<br />($row[3] = ".$categories[$row[2]].")","projects","error");
                $error = 1;
            }
            // check timeframe
            if ($anfang < $row[1]) {
                message_stack_in(__('Warning, violation of dependency').": ".__('Begin')."($project_name) < ".__('End')."($row[3])","projects","error");
            }
            break;

        // 3 = this project cannot start before start of project B
        case "3":
            if (($cat > "2" and $cat <> 7) and ($row[2] <= "2" or $row[2] == 7)) {
                message_stack_in(__('Warning, violation of dependency').": $project_name \"$dependencies[$depend_mode] $row[3]\"<br />($row[3] = ".$categories[$row[2]].")","projects","error");
                $error = 1;
            }
            // check timeframe
            if ($anfang < $row[0]) {
                message_stack_in(__('Warning, violation of dependency').": ".__('Begin')."($project_name) < ".__('Begin')."($row[3])","projects","error");
            }
            break;

        // 4 = this project cannot end before start of project B
        case "4":
            if (($cat == "4" or $cat == "5") and ($row[2] <= "2" or $row[2] == 7)) {
                message_stack_in(__('Warning, violation of dependency').": $project_name \"$dependencies[$depend_mode] $row[3]\"<br />($row[3] = ".$categories[$row[2]].")","projects","error");
                $error = 1;
            }
            // check timeframe
            if ($ende < $row[0]) {
                message_stack_in(__('Warning, violation of dependency').": ".__('End')."($project_name) < ".__('Begin')."($row[3])","projects","error");
            }
            break;

        // 5 = this project cannot end before end of project B
        case "5":
            if (($cat == "4" or $cat == "5")  and ($row[2] <> "4" and $row[2] <> "5")) {
                message_stack_in(__('Warning, violation of dependency').": $project_name \"$dependencies[$depend_mode] $row[3]\"<br />($row[3] = ".$categories[$row[2]].")","projects","error");
                $error = 1;
            }
            // check timeframe
            if ($ende < $row[1]) {
                message_stack_in(__('Warning, violation of dependency').": ".__('End')."($project_name) < ".__('End')."($row[3])","projects","error");
            }
            break;
    }
    return $error;
}


function delete_record($ID) {
    global $fields, $user_ID;

    // only if an ID is given of course ...
    if (!$ID) die(__('Please choose a project')."!<br /><a href='projects.php?".SID."'>".__('back')."</a>");

    // check whether there are subprojects below this record ..
    $result = db_query("SELECT ID
                          FROM ".DB_PREFIX."projekte
                         WHERE parent = '$ID'") or db_die();
    $row = db_fetch_row($result);
    
    if ($row[0] > 0) {
        message_stack_in(__('Please delete all subelements first')."!","projects","error");
        return;
    }

    // delete project and show message
    $tmp = slookup('projekte','name','ID',$ID);

    // delete corresponding entry from db_record
    $result = db_query("delete from ".DB_PREFIX."db_records
                                  where t_record = '$ID' and t_module = 'projects'") or db_die();
    $result = db_query("delete from ".DB_PREFIX."projekte
                                  where ID = '$ID'") or db_die();
    message_stack_in($tmp." - ".__('The project is deleted'),"projects","notice");
    unset($tmp);
    // free events from project link

    if (PHPR_CALENDAR) {
        $result = db_query("update ".DB_PREFIX."termine
                                   set projekt = ''
                                 where projekt = '$ID'") or db_die();
        message_stack_in(__('All links in events to this project are deleted'),"projects","notice");
    }
    // free files from project link
    if (PHPR_FILEMANAGER) {
        $result = db_query("update ".DB_PREFIX."dateien
                                   set div2 = ''
                                 where div2 = '$ID'") or db_die();
    }
    // free notes from project link
    if (PHPR_NOTES) {
        $result = db_query("update ".DB_PREFIX."notes
                                   set projekt = ''
                                 where projekt = '$ID'") or db_die();
    }
    // free timesheet from assignements from the timecard
    if (PHPR_PROJECTS > 1) {
        $result = db_query("delete from ".DB_PREFIX."timeproj
                                      where projekt = '$ID'") or db_die();
    }
    // free relations to this project
    $result = db_query("update ".DB_PREFIX."projekte
                               set next_mode = '', next_proj = ''
                             where next_proj = '$ID'") or db_die();
    $result = db_query("update ".DB_PREFIX."projekte
                               set depend_mode = '', depend_proj = ''
                             where depend_proj = '$ID'") or db_die();
    echo "<img src='$img_path/s.gif' width='300px' height='1' vspace='2' border='0' />";

    // finally delete history
    if (PHPR_HISTORY_LOG) history_delete('projekte',$ID);
}

// *******
// actions
// *******

if (!$justform) {
  include_once("./projects_view.php");
}
else {
    echo '<script type="text/javascript">self.opener.location.reload();self.close()</script>';
}

?>
