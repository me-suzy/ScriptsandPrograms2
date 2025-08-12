<?php

// contacts_profiles_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: contacts_profiles_forms.php,v 1.24 2005/07/22 13:56:38 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");


// if 'new record' is chosen, free a possible ID
if (isset($neu) && $neu) $ID = '0';
else $neu = false;

$output = "
<form style='display:inline' action='".$_SERVER['PHP_SELF']."' method='post' />
    <input type='hidden' name='mode' value='profiles_data' />
    <input type='hidden' name='action' value='$action' />\n";

if (SID) $output .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
$output .= show_profiles_box();
if ($neu or $aendern) $output .= edit_profile();

$output .= '</form>';

echo $output;


function show_profiles_box() {
    global $user_ID, $ID, $action, $img_path;

    $output = '
    <br style="clearall" />

    <div style="margin:0px;margin-left:1em;padding:0px;background-color:rgb(239,239,239);">
        <div class="box_header">
            <div class="box_header_left">'.__('Profiles').'</div>
            <div class="box_header_right"></div>
        </div>
        <div class="formbody">
            <!-- form action="settings.php" -->
            <fieldset>
                <legend></legend>
                '.message_stack_out_all().
                __('<h2>Profiles</h2>In this section you can create, modify or delete profiles:').'
                <br /><br />
                <select name="ID">
                    <option value=""></option>
';

    // profiles for contacts
    if ($action == "contacts") {
        $result = db_query("select ID, name
                              from ".DB_PREFIX."contacts_profiles
                             where von ='$user_ID'
                          order by name") or db_die();
    }
// TODO: this should be removed from here. profiles are administrated in the settings.
    // profiles for users
    else {
        $result = db_query("select ID, bezeichnung
                              from ".DB_PREFIX."profile
                             where von ='$user_ID'
                          order by bezeichnung") or db_die();
        $output .= "<option value='proxy'";
        if ($ID == "proxy") $output .= " selected='selected'";
        $output .= ">".__('Write access for calendar')."</option>\n";
        $output .= "<option value=''>--------------------</option>\n";
    }
// END TODO

    while ($row = db_fetch_row($result)) {
        $output.= "<option value='$row[0]'";
        if ($row[0] == $ID) $output.= " selected='selected'";
        $output .= ">".html_out($row[1])."</option>\n";
    }

    $output .= "
                </select>
                <br /><br />
            </fieldset>

            <input class='submit' type='submit' name='aendern' value='".__('Modify')."' />&nbsp;
            <input class='submit' type='submit' name='loeschen' value='".__('Delete')."' onclick=\"return confirm('".__('Are you sure?')."');\" />
            <input class='submit' type='submit' name='neu' value='".__('New profile')."' />
            <br /><br />
            <!-- </form> -->
        </div>
    </div>

    <br />\n";

    return $output;
}


function edit_profile() {
    global $ID, $user_ID, $lib_path, $sql_user_group, $action, $user_group;

    // check permission
    if ($ID and $ID != "proxy") {
        include_once($lib_path."/permission.inc.php");
        if ($action == "contacts") check_permission("contacts_profiles", "von", $ID);
        else                       check_permission("profile", "von", $ID);
    }

    // modify? -> fetch properties from this record and the selected contacts
    if ($ID) {
        if ($action == "contacts") {
            $result = db_query("select name, remark
                                  from ".DB_PREFIX."contacts_profiles
                                 where ID = '$ID'") or db_die();
            $row    = db_fetch_row($result);
            $title  = html_out($row[0]);
            $remark = html_out($row[1]);
            // fetch all selected contacts and store them in an array
            $result = db_query("select contact_ID
                                  from ".DB_PREFIX."contacts_prof_rel
                                 where contacts_profiles_ID = '$ID'") or db_die();
            while ($row = db_fetch_row($result)) {
                $selected[] = $row[0];
            }
        }
// TODO: this should be removed from here. proxies and profiles are administrated in the settings.
        else {
            if ($ID == "proxy") {
                $result = db_query("select proxy
                                      from ".DB_PREFIX."users
                                     where ID = '$user_ID'") or db_die();
                $row = db_fetch_row($result);
                $title = __('Write access for calendar');
                $selected = unserialize($row[0]);
            }
            else {
                $result = db_query("select bezeichnung, personen
                                      from ".DB_PREFIX."profile
                                     where ID = '$ID'") or db_die();
                $row = db_fetch_row($result);
                $title = $row[0];
                $selected = unserialize($row[1]);
            }
        }
// END TODO
    }

    $out .= '
    <div style="margin:0px;margin-left:1em;padding:0px;background-color:rgb(239,239,239);">
        <div class="box_header">';

    if ($ID == "proxy") {
        $out .= '
            <div class="box_header_left">'.__('Write access for other users to your calendar').'</div>
            <div class="box_header_right"></div>
        </div>
        <div class="formbody">

        <!--<form action="settings.php" method="post">-->

        <input type="hidden" name="ID" value='.$ID.' />
        <input type="hidden" name="name" value="'.$title.'" />
        <fieldset>
            <legend></legend>'.
            __('User with chief status still have write access');
    }
    else {
        $out .= '
            <div class="box_header_left">'.__('New profile').'</div>
            <div class="box_header_right"></div>
        </div>
        <div class="formbody">

        <!--<form action="settings.php" method="post">-->

        <input type="hidden" name="ID" value='.$ID.' />
        <fieldset>
            <legend></legend>
            <label class="settings" for="name">'.__('Description').': </label>
            <input class="settings_options" type=text size=30 name="name" value="'.$title.'" />
            <br />';
    }

    // remark - only in profiles for contacts
    if ($action == "contacts") {
        $out .= "
            <br />
            <label class='settings' for='remark'>".__('Remark').": </label>
            <textarea class='settings_options' name=remark rows=6 cols=50>$remark</textarea>
            <br />\n";
    }

    $out .= "
        </fieldset>
        <br class='clear'/><br /><br />
        <input class='submit' type=submit name=db_neu value='".__('Create')."' />
        <input class='submit' type=submit name=db_aendern value='".__('Modify')."' />\n";

    $out .= button_back();

    // begin table with all contacts
    $out .= "<br class='clear'/><br />\n";

    // fetch all contacts
    if ($action == "contacts") {
        $result = db_query("select ID, nachname, vorname
                              from ".DB_PREFIX."contacts
                             where (acc_read like 'system'
                                    or ((von = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%')
                                        and $sql_user_group))
                               and $sql_user_group
                          order by nachname") or db_die();
        $newrow = 5;
    }
    // ... or users in your group
    else if ($ID == "proxy") {
        if ($user_group) {
            $result = db_query("select grup_ID
                                  from ".DB_PREFIX."grup_user
                                 where user_ID = '$user_ID'") or db_die();
            $rows = array();
            while ($row = db_fetch_row($result)) {
                $rows[] = $row[0];
            }
            if (count($rows) > 0) {
                $groups = " and grup_ID in ('".implode("','", $rows)."')";
            } else {
                $groups = '';
            }
            $result = db_query("select distinct ".DB_PREFIX."users.ID, nachname, vorname
                                  from ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                                 where ".DB_PREFIX."users.ID = user_ID
                                       $groups
                              order by nachname, vorname") or db_die();
        }
        else $result = db_query("select ID, nachname, vorname
                                   from ".DB_PREFIX."users
                               order by nachname, vorname");
        $newrow = 5;
    }
    else {
        if ($user_group) {
            $result = db_query("select kurz, nachname, vorname, acc
                                  from ".DB_PREFIX."users, ".DB_PREFIX."grup_user
                                 where ".DB_PREFIX."users.ID = user_ID
                                   and grup_ID = '$user_group'
                              order by nachname, vorname") or db_die();
        }
        else {
            $result = db_query("select kurz, nachname, vorname, acc
                                  from ".DB_PREFIX."users
                              order by nachname, vorname");
        }
        $newrow = 1;
    }

    //$out .= '</form></div></div>';return $out;
    $out .= '<!--</form>--></div>'; #return $out;

    $out .= "<table>\n<tr>\n";
    while ($row = db_fetch_row($result)) {
        $out .= "<td>&nbsp;&nbsp;<input type='checkbox' name='s[]' value='$row[0]'";
        if ($ID and $selected[0]) {
            if (in_array($row[0], $selected)) $out .= " checked='checked'";
        }
        $out.=" /></td>\n<td>$row[1], $row[2]&nbsp;</td>\n";
        // add property of calendar for users
        if ($action == "users" and $ID !='proxy') {
            if (eregi("y",$row[3])) $out .= "<td>".__('schedule readable to others')."</td>\n";
            if (eregi("n",$row[3])) $out .= "<td>".__('schedule invisible to others')."</td>\n";
            if (eregi("v",$row[3])) $out .= "<td>".__('schedule visible but not readable')."</td>\n";
        }
        // begin new row after each fifth record
        if ($i == $newrow) {
            $out .= "</tr>\n<tr>\n";
            $i = 0;
        }
        else $i++;
    }
    //$out.= "</tr></table>\n<br /></fieldset></div>\n";
    $out .= "</tr></table>\n<br /></div><br />\n";

    return $out;
}

?>
