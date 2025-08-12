<?php

// selector.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Franz Graf, $Author: paolo $
// $Id: selector.inc.php,v 1.13 2005/07/21 17:29:21 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Not to be called directly!');


/**
* Build a selectbox (multiple) filled with users.
* Selected users are shown selected and on top.
* A certain number of other users is shown below.
* This function is only to be used in connection w/ the selector!
* The valus will be the users' shortnames. The displayed text: 'surname, name (group)'
* FIXME: Currently only the first 30 (see $MAX_NUMBER_OF_USERS within the function) users are shown.
*
* @access public
* @param string $name           Name of the selectbox
* @param array  $selected_users array of shortnames or IDs that should be selected
* @param string $html           (optional) String printed into the select-tag (ie: "id='bar' class='foo'")
* @param int    $size           (optional) size of the multiple-select
* @return string complete multiple-select or empty string on errors
*/
function selector_create_select_multiple_users($name, $selected_users=array(), $html="", $size=7) {

    $MAX_NUMBER_OF_USERS = 10;

    // check input
    $size = (int) $size;
    if (empty($name))               return "";
    if (!is_array($selected_users)) return "";
    if (empty($size) or $size <= 0) return "";
    // input is not completely nonsense now

    // create head
    $output = "<select $html multiple='multiple' name='$name' size='$size'>\n";

    // get all selected users if the array is not empty
    if (count($selected_users)) {
        $temp_users = selector_get_users($selected_users);
        foreach ($temp_users as $temp) {
            $output .= " <option value='".$temp[0]."' selected='selected'>".$temp[1]."</option>\n";
        }
        unset($temp_users, $temp);
        $output .= " <option value=''>- - - - - - -</option>\n";
    }


    // get _all_ users and show the first $MAX_NUMBER_OF_USERS
    $temp_users = selector_get_users();
    $remaining_users = $MAX_NUMBER_OF_USERS;
    while ((list(,$temp) = each($temp_users)) and $remaining_users-- > 0) {
        $output .= " <option value='".$temp[0]."'>".$temp[1]."</option>\n";
    }
    // is there more data than currently shown?
    if ($remaining_users<=0 and count($temp_users) > $MAX_NUMBER_OF_USERS) {
        $output .= " <option value=''>. . .</option>\n";
    }
    unset($temp_users, $temp, $remaining_users);

    // add the footer
    $output .= "</select>\n";

    return $output;
}



/**
* Select a set of users for the selector-dropdown/multiple.
* If an non-empty array is referenced, the shortnames in the
* array are selected only. Otherwise, all users are selected.
* A tuple will look like this:
*  array('test1', 'surname, name (group)')
* The result depends on PHPR_ACCESS_GROUPS which may restrict access
* to users of certain groups.
* If the calling user acts as a proxy (global[act_for]) the permissions
* of the act_for-user are taken.
*
* @param  array $ids     optional array of user IDs
* @return array Array of arrays: array(ID, string)
*/
function selector_get_users($ids=array()) {
    $ids              = (array) $ids; // select only these IDs
    $additional_where = array();      // additional where for query
    $userID           = $GLOBALS['user_ID'];
    $return_array     = array();
    $additional_where = array();

    // select only a certain set of users
    if (count($ids) > 0) {
        $additional_where[] = " u.ID IN ('". implode("','", $ids) ."')";
    }

    if (null !== selector_get_groupIds() && count(selector_get_groupIds()) ) {
        $additional_where[] = "gu.grup_ID IN ('".implode("','", selector_get_groupIds())."')";
    }

    // build where
    if (count($additional_where) > 0) {
        $additional_where = " WHERE ".implode(" AND ", $additional_where);
    }
    else {
        $additional_where = "";
    }
    $query = "SELECT DISTINCT u.ID, u.nachname, u.vorname, g.name
                         FROM ".DB_PREFIX."users u
                    LEFT JOIN ".DB_PREFIX."gruppen    g ON u.gruppe = g.ID   /* always show the name of the defaultgroup */
                    LEFT JOIN ".DB_PREFIX."grup_user gu ON u.ID = gu.user_ID /* users of all allowed groups */
                              $additional_where
                     ORDER BY u.nachname, u.vorname, g.name ";
    // echo nl2br($query);
    $result = db_query($query) or db_die();
    while ($row = db_fetch_row($result)) {
        $return_array[] = array($row[0], $row[1].", ".$row[2]." (".$row[3].")");
    }

    return $return_array;
}


/**
* Get the groupIDs the user may access.
* This depends on PHPR_ACCESS_GROUPS.
* Once the data is selected it is 'cached' in a static-variable.
*
* @return array Array with allowed groupIds as values or empty array if all allowed.
*/
function selector_get_groupIds() {
    $userID = $GLOBALS['user_ID'];
    if (isset($GLOBALS['act_for']) && $GLOBALS['act_for']>0) $userID = $GLOBALS['act_for'];
    $userID = (int) $userID;

    // "caching"
    static $allowed_groups = null;
    if ($allowed_groups !== null) return $allowed_groups;
    $allowed_groups = array();

    // fetch all groups of this user
    $query = "SELECT grup_ID
                FROM ".DB_PREFIX."grup_user
               WHERE user_ID = '$userID'";
    $user_groups = array();
    $result = db_query($query) or db_die();
    while ($row=db_fetch_row($result)) {
        $user_groups[] = $row[0];
    }

    // current group only
    if (PHPR_ACCESS_GROUPS == 0) {
        if (in_array($GLOBALS['user_group'],$user_groups)) {
            $allowed_groups = array($GLOBALS['user_group']);
        }
    }
    // all groups the user is member of
    else if (PHPR_ACCESS_GROUPS == 1) {
        $allowed_groups = $user_groups;
    }
    // no restrictions=all groups
    else if (PHPR_ACCESS_GROUPS == 2) {
        // fetch all groups of this user
        $query = "SELECT grup_ID
                    FROM ".DB_PREFIX."grup_user";
        $result = db_query($query) or db_die();
        while ($row=db_fetch_row($result)) {
            $allowed_groups[] = $row[0];
        }
    }

    return $allowed_groups;
}

?>
