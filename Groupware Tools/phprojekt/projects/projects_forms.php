<?php

// projects_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: projects_forms.php,v 1.45.2.3 2005/09/12 12:17:36 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("projects") < 1) die("You are not allowed to do this!");
$path_pre = '../';
$include_path = $path_pre.'lib/access_form.inc.php';
include_once $include_path;

if ($justform == 2) {
    $onload[] = 'window.opener.location.reload();';
    $onload[] = 'window.close();';
}
else if ($justform > 0) {
    $justform++;
}

  // update project? -> fetch values form record
if ($action <> "new" and $ID > 0) {
    $result = db_query("SELECT ID, name, anfang, ende, chef, contact, stundensatz, budget, wichtung,
                               ziel, note, depend_mode, depend_proj, next_mode, next_proj, probability,
                               ende_real, kategorie, status, statuseintrag, parent, personen, acc,
                               acc_write, von
                          FROM ".DB_PREFIX."projekte
                         WHERE (acc LIKE 'system'
                                OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%')
                                    AND $sql_user_group))
                           AND ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // check access
    // genreal acces - either the user has direct access to it or the user has chief status
    if (!$row[0] and !eregi('c', $user_access)) die("You are not privileged to do this!");

    if (($row[24] <> $user_ID and $row[23] <> 'w') or check_role("projects") < 2) $read_o = 1;

    // get values
    $project_name   = html_out($row[1]);
    $anfang         = $row[2];
    $ende           = $row[3];
    $chef           = $row[4];
    $contact        = $row[5];
    $stundensatz    = $row[6];
    $budget         = $row[7];
    $wichtung       = $row[8];
    $ziel           = $row[9];
    $note           = $row[10];
    $depend_mode    = $row[11];
    $depend_proj    = $row[12];
    $next_mode      = $row[13];
    $next_proj      = $row[14];
    $probability    = $row[15];
    $ende_real      = $row[16];
    $category       = $row[17];
    $status         = $row[18];
    $statuseintrag  = $row[19];
    $parent         = $row[20];
    $personen       = $row[21];
    $acc            = $row[22];
    $acc_write      = $row[23];
}
// set variables for a new project:
else {
    // new subproject ...
    if ($parent) {
        $row[11] = set_new_subproject($parent);
    }
    // ... or a new project at all
    else {
        set_new_project();
    }
}

if ($ID)    $head = slookup('projekte', 'name', 'ID', $ID);
else        $head = __('New project');

// tabs
$tabs = array();
$buttons = array();
$hidden  = array();
if (SID) $hidden[session_name()] = session_id();

// form start
$buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'name' => 'frm', 'onsubmit' => "return chkForm('frm','name','".__('Please insert a name')."') && chkISODate('frm','anfang','".__('Begin').": ".__('ISO-Format: yyyy-mm-dd')."') && chkISODate('frm','ende','".__('End').": ".__('ISO-Format: yyyy-mm-dd')."') && chkNumbers('frm','budget','".__('Calculated budget has a wrong format')."') && chkNumbers('frm','stundensatz','".__('Hourly rate has a wrong format')."');");
$output = get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
if (!$read_o and check_role("projects") > 1) {
    if (!$ID) {
        // create new project
        $buttons[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Accept'), 'active' => false);
        // hidden
        $buttons[] = array('type' => 'hidden', 'name' => 'anlegen', 'value' => 'neu_anlegen');
    } // modify and delete
    else {
        // modify project
        $buttons[] = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Accept'), 'active' => false);
        // hidden
        $buttons[] = array('type' => 'hidden', 'name' => 'aendern', 'value' => 'aendern');
        // check whether there is no subproject beyond this one.
        // if no and if userid = owner of the project-> allow to delete
        $result2 = db_query("SELECT ID
                               FROM ".DB_PREFIX."projekte
                              WHERE parent = '$ID'") or db_die();
        $row2 = db_fetch_row($result2);
        if ($row2[0] == '' and $row[24] == $user_ID) {
            $buttons[] = array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
        }
    }
}
else if (check_role("projects") > 1 and $user_ID == $chef) {
    // modify status
    $buttons[] = array('type' => 'submit', 'name' => 'modify_status_b', 'value' => __('Modify status'), 'active' => false);
    // hidden
    $buttons[] = array('type' => 'hidden', 'name' => 'modify_status', 'value' => 'modify_status');
}



// new subproject
if (!$read_o and check_role("projects") > 1 and $ID > 0) {
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?parent='.$ID.'&amp;action=new&amp;mode=forms', 'text' => __('New Sub-Project'), 'active' => false);
    //$output.= "<input type='button' onclick='self.location.href=\"projects.php?parent=$ID&amp;action=new&amp;mode=forms\"' value='".__('New Sub-Project')."' class='button' />";
}
// print
if ($ID > 0) {
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);

    // disable print buttons in 5.0
    //$output.= "<input type='button' onclick='window.open(\"../misc/print.php?ID=$row[0]&amp;module=proj\",\"_blank\")' value='".__('print')."' class='button' />\n";
}
// cancel
$buttons[] = array('type' => 'link', 'href' => 'projects.php?type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('Cancel'), 'active' => false);
$output .= get_buttons_area($buttons);

$output .= '
<div class="hline"></div>
<div class="inner_content">
<a name="content"></a>
<br />
';

/*************************************
    Header Box 1 (Basis data)
*************************************/
$box_right_data = array();
$box_right_data['type']         = 'anker';
$box_right_data['anker_target'] = 'unten';
$box_right_data['link_text']    = __('Links');
$output .= get_box_header(__('Basis data'), 'oben', $box_right_data);

$basis_data = "
<div class='formbody'>
    <fieldset style='margin:0;'>
    <legend></legend>
";
// calculate hidden fields
$hidden = array_merge(array('ID'=>$ID, 'type'=>$type, 'mode'=>'data', 'gruppe'=>'user_group', 'justform'=>$justform, 'project_name'=>$project_name), $view_param);
// add hidden fields
$basis_data .= hidden_fields($hidden);
// fields html
$basis_data .= build_form($fields);
//     project to a subproject
$basis_data .= '
    </div></fieldset>
</div>
';

$output .= '
    <div class="boxContent">'.$basis_data.'</div>
    <br style="clear:both" />
';


/*************************************
    Header Box 2 (Categorization)
*************************************/
$box_right_data = array();
$box_right_data['type']         = 'anker';
$box_right_data['anker_target'] = 'oben';
$box_right_data['link_text']    = __('Basis data');
$output .= '<br style="clear:both" />';
$output .= get_box_header(__('Categorization'), 'unten', $box_right_data);

$categorization = '
<div class="formbody">
    <fieldset>
    <legend></legend>
    <br />
    <label for="parent" class="center2">'.__('Sub-Project of').':</label>
    <select class="projectCat" id="parent" name="parent"'.read_o($read_o).'>
        <option value="0"></option>
';

// prepare query for function
$query = "WHERE $sql_user_group";
// call function to show all required elements in a tree structure in the select box
$categorization .= show_elements_of_tree('projekte', 'name',
                                "WHERE (acc LIKE 'system' OR ((von = ".$user_ID." OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))",
                                'acc', " ORDER BY name", $parent, 'parent', $ID);
$categorization .= "</select><br />\n";

$read_o_status = $user_ID == $chef ? 0 : 1;
$categorization .= "<label for='parent' class='center2'>".__('Status')." [%]:</label>\n";
$categorization .= "<input name='status' value='$status' type='text' class='form smallinput' ".read_o($read_o_status, 'readonly')."/>\n";
$categorization .= '<br style="clear:both" /><br />'."\n";


// next record in list
if ($ID > 0) {
    // check where there are any other projects on this level
    $result2 = db_query("SELECT COUNT(ID)
                           FROM ".DB_PREFIX."projekte
                          WHERE parent = '$parent'
                            AND ID <> '$ID'") or db_die();
    $row2 = db_fetch_row($result2);
    // first display the possible modes
    if ($row2[0] > 0) {
        $categorization .= "<label for='next_mode' class='center2'>".__('List').":</label>\n";
        $categorization .= "<select class='projectCat' name=next_mode".read_o($read_o).">\n<option value='0'></option>\n";
        foreach ($next_mode_arr as $next1 => $next2) {
            $categorization .= "<option value='$next1'";
            if ($next1 == $next_mode) $categorization .= ' selected="selected"';
            $categorization .= ">$next2:</option>\n";
        }
        $categorization .= "</select>\n";
        // fetch all of these neighbours and display them
        $categorization .= "<label for='next_mode' class='center2'>".__('Next Project').":</label>\n";
        $categorization .= "<select class='projectCat' name='next_proj'".read_o($read_o)."><option value='0'></option>\n";
        $result2 = db_query("SELECT ID, name
                               FROM ".DB_PREFIX."projekte
                              WHERE parent = '$parent'
                                AND $sql_user_group
                                AND ID <> '$ID'
                           ORDER BY name") or db_die();
        while ($row2 = db_fetch_row($result2)) {
            $categorization .= "<option value='$row2[0]'";
            if ($row2[0] == $next_proj) $categorization .= ' selected="selected"';
            $categorization .= ">$row2[1]</option>\n";
        }
        $categorization .= "</select><br />\n";
        // dependency
        $categorization .= "<label for='depend_mode' class='center2'>".__('Dependency').":</label>\n";
        $categorization .= "<select class='projectCat' name='depend_mode'".read_o($read_o)."><option value='0'>\n";
        foreach ($dependencies as $dep1 => $dep2) {
            $categorization .= "<option value='$dep1'";
            if ($dep1 == $depend_mode) $categorization .= ' selected="selected"';
            $categorization .= ">$dep2:</option>\n";
        }
        $categorization .= "</select>\n";
        // fetch all of these neighbours and display them
        $categorization .= "<label for='depend_mode' class='center2'>".__('Dependend projects').":</label>\n";
        $categorization .= "<select class='projectCat' name='depend_proj'".read_o($read_o)."><option value='0'>\n";
        $result2 = db_query("SELECT ID, name
                               FROM ".DB_PREFIX."projekte
                              WHERE parent = '$parent'
                                AND $sql_user_group
                                AND ID <> '$ID'
                           ORDER BY name") or db_die();
        while ($row2 = db_fetch_row($result2)) {
            $categorization .= "<option value='$row2[0]'";
            if ($row2[0] == $depend_proj) $categorization .= ' selected="selected"';
            $categorization .= ">$row2[1]</option>\n";
        }
        $categorization .= "</select>\n";
        $categorization .= "</td></tr>\n";
    }
    // otherwise set the dependency to 0 to avoid that this project has an 'old' dependency
    else {
        $output.= "<input type='hidden' name='dependency' value='0' />\n";
    }
}
$categorization .= "</fieldset>\n</div>\n";


$output .= '
    <div class="boxContent">'.$categorization.'</div>
    <br style="clear:both"/>
';


/*
// show already booked work time
if (PHPR_PROJECTS > 1) {
    $result2 = db_query("select h,m
                           from ".DB_PREFIX."timeproj
                          where projekt = '$ID'") or db_die();
    while ($row2 = db_fetch_row($result2)) {
        $sum1 = $sum1 + $row2[0]*60+$row2[1];
    }
    $h = floor($sum1/60);
    $m = $sum1 - $h*60;
    $amount = number_format($row[16] * $sum1/60);
    if ($amount > 0) {
        $output.= "<tr><td>$proj_text16: </td><td>".PHPR_CUR_SYMBOL.":$amount - h:$h/m:$m </td></tr>\n";
    }
}
*/

/**************************************************
    Header Box 3 (Assignment of Participants)
**************************************************/
$box_right_data = array();
$box_right_data['type']         = 'anker';
$box_right_data['anker_target'] = 'oben';
$box_right_data['link_text']    = __('Basis data');
$output .= '<br style="clear:both"/>';
$acc_read = slookup('projekte', 'personen', 'ID', $ID);
$assignment = '
    <div class="formbody" style="margin-top:2px;">
    <fieldset style="border:1px solid black;width:400px;padding:10px;">
    <legend>'.__('Participants').'</legend>
    <select size="7" name="personen[]" multiple="multiple"'.read_o($read_o).'>'.show_group_users($user_group, false, $acc_read, true).'</select>
    </fieldset>
    </div>
';

// access
// select participants
$access_form = '<div class="formbody" style="margin-top:2px;">';
// acc_read, exclude the user itself, acc_write, no parent possible, write access=yes
$access_form .= access_form2($row[22], 1, $row[23], 0, 1).'</div>';

$output .= '
    <div class="boxHeaderSmallLeft">'.__('Assignment').'</div>
    <div class="boxHeaderSmallRight">'.__('Participants').'</div>
    <div class="boxContentSmallLeft" style="height:170px">'.$access_form.'</div>
    <div class="boxContentSmallRight" style="height:170px">'.$assignment.'</div>
    <br style="clear:both" /><br />


';

/**************************************************
                        Buttons
**************************************************/


/*
$output .= '
<div class="buttons" style="margin-top:5px;">
<span class="co1">'.$head.'</span>
<span class="col3">
    <form style="display:inline;" action="forum.php" name="forumneu" method="post">
';

if (!$read_o and check_role("projects") > 1){
    if (!$ID) {
        $output .= "<input type='submit' name='create_b' value='".__('Create')."' class='button' />\n";
        $output .= "<input type='hidden' name='anlegen' value='neu_anlegen' />\n";
    } // modify and delete
    else {
        $output .= "<td><input type='submit' name='modify_b' value='".__('Modify')."' class='button' /></td>\n";
        // change values
        $output .= "<input type='hidden' value='aendern' class='button' />\n";
        // check whether there is no subproject beyond this one. if no -> allow to delete
        $result2 = db_query("SELECT ID
                               FROM ".DB_PREFIX."projekte
                              WHERE parent = '$ID'") or db_die();
        $row2 = db_fetch_row($result2);
        if ($row2[0] == '') $output .= "<input type='submit' name='delete_b' value='".__('Delete')."' onclick=\"return confirm('".__('Are you sure?')."');\" class='button' />\n";
    }
} // end buttons chief only
// new subproject
if (!$read_o and check_role("projects") > 1 and $ID > 0) {
    $output .= "<input type='button' onclick='self.location.href=\"projects.php?parent=$ID&amp;action=new&amp;mode=forms\";' value='".__('New Sub-Project')."' class='button' />";
}
// print
if ($ID > 0) {
    // disable print buttons in 5.0
    //$output .= "<input type='button' 'window.open(\"../misc/print.php?ID=$row[0]&amp;module=proj\",\"_blank\")' value='".__('print')."' class='button' />\n";
}
// cancel
$ure = "projects.php?type=$type&amp;sort=$sort&amp;mode=view&amp;up=$up&amp;filter=$filter&amp;keyword=$keyword&amp;perpage=$perpage&amp;page=$page";
$output .= "<input type='button' onclick='self.location.href=\" $ure\";' value='".__('back')."' class='button' /></a>\n";
$output .= '</span></div></form>';
*/

// button bar
$buttons = array();
if (!$read_o and check_role("projects") > 1) {
    if (!$ID) {
        // create new project
        $buttons[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Accept'), 'active' => false);
    } // modify and delete
    else {
        // modify project
        $buttons[] = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Accept'), 'active' => false);
         // check whether there is no subproject beyond this one. if no -> allow to delete
        $result2 = db_query("SELECT ID
                               FROM ".DB_PREFIX."projekte
                              WHERE parent='$ID'") or db_die();
        $row2 = db_fetch_row($result2);
        if ($row2[0] == '') {
            $buttons[] = array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
        }
    }
}
else if (check_role("projects") > 1 and $user_ID == $chef) {
    // modify status
    $buttons[] = array('type' => 'submit', 'name' => 'modify_status_b', 'value' => __('Modify status'), 'active' => false);
}



// new subproject
if (!$read_o and check_role("projects") > 1 and $ID > 0) {
    $buttons[] = array('type' => 'link', 'href' => 'projects.php?parent='.$ID.'&amp;action=new&amp;mode=forms', 'text' => __('New Sub-Project'), 'active' => false);
    //$output.= "<input type='button' onclick='self.location.href=\"projects.php?parent=$ID&amp;action=new&amp;mode=forms\"' value='".__('New Sub-Project')."' class='button' />";
}
// print
if ($ID > 0) {
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);

    /* disable print buttons in 5.0
    $output.= "<input type='button' onclick='window.open(\"../misc/print.php?ID=$row[0]&amp;module=proj\",\"_blank\")' value='".__('print')."' class='button' />\n";
    */
}
// cancel
$buttons[] = array('type' => 'link', 'href' => 'projects.php?type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('Cancel'), 'active' => false);
$output .= get_buttons_area($buttons);
$output .= '</div>
</form>
<div class="hline"></div>
';


/*
// preselect selector data
$participants = unserialize($personen);
$accessors    = unserialize($acc);
settype($participants, "array");
settype($accessors, "array");
$output .= '
<script type="text/javascript">
<!--
participants = new Array("'.implode('","', $participants).'");
preselect_options(participants, "perfound1[]", "personen[]");
accessors = new Array("'.implode('","', $accessors).'");
preselect_options(accessors, "perfound[]", "persons[]");
//-->
</script>
';
*/


/**************************************************
                    related objects
**************************************************/
if ($ID > 0) {
    $output .= "<br />\n";
    $projekt_ID = $ID;
    // include the lib
    include_once($lib_path."/show_related.inc.php");
    $referer = "projects.php?mode=forms&amp;ID=$ID";
    // show related todos
    if (PHPR_TODO and check_role("todo") > 0) {
        $query = "project='$ID'";
        $output .= show_related_todo($query, $referer);
        $output .= "<br />\n";
    }

    // related notes, show only for existing projects
    if (PHPR_NOTES and check_role("notes") > 0) {
        $module = "projects";
        $query = "projekt='$ID'";
        $output .= show_related_notes($query, $referer);
        $output .= "<br />\n";
    }

    // show related files
    if (PHPR_FILEMANAGER and check_role("filemanager") > 0) {
        $module = "projects";
        $query = "div2='$ID'";
        $output .= show_related_files($query, $referer);
        $output .= "<br />\n";
    }

    // show related events
    if (PHPR_CALENDAR and check_role("calendar") > 0) {
        $module = "projects";
        $query = "projekt='$ID'";
        $output .= show_related_events($query, $referer);
        $output .= "<br />\n";
    }
    // show history
    if (PHPR_HISTORY_LOG) $output .= history_show('projekte', $ID);
}
// end show related objects
// ************************

// close big div
//$output .= '</div>';
echo $output;

// end  of big form :-)


// set variables for a new subproject
function set_new_subproject($parent) {
    global $ID, $row, $anfang, $ende;

    $result = db_query("SELECT ID, name, anfang, ende
                          FROM ".DB_PREFIX."projekte
                         WHERE ID='$parent'") or db_die();
    $row = db_fetch_row($result);
    // delete ID, because it's a new project
    $row[0] = $ID = 0;
    $row[1] = '';
    $anfang = $row[2];
    $ende   = $row[3];
    return $parent;
}

// set variables for a new root project
function set_new_project() {
    global $ID, $anfang, $ende, $row;

    $ID      = $row[0] = 0;
    $anfang  = date("Y")."-".date("m")."-".date("d");
    $ende    = date("Y")."-12-31";
    $row[16] = 0;   // stundensatz / hourly rate
    $row[17] = 0;   // budget
}

?>
