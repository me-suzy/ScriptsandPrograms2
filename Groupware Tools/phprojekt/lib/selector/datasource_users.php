<?php

// datasource_users.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Martin Brotzeller, $Author: nina $
// $Id: datasource_users.php,v 1.23.2.4 2005/09/02 09:19:24 nina Exp $

/** datasource_users.php
*
* Datenquelle für Anzeige von Benutzern
*
*
* @copyright (c) 2004 Mayflower GmbH
* @author Martin Brotzeller
* @package PHProjekt
*/
// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Not to be called directly!');


// since lib.inc.php is already included, lib_path contains the correct value
include_once($GLOBALS['lib_path']."/selector/selector.inc.php");

/** fetch_fields() - Funktion zum Anfragen der Datenquelle
*
* @param options Array mit optionen für die Datenquelle
*                'table'     - Tabelle die angefragt wird
*                'where'     - Array mit Zusatzbedingungen
*                'order'     - Komma-separierte Liste von Spalten nach denen sortiert werden soll
*                'direction' - Sortier-Richtung
*                'ID'        - Name der ID-Spalte
*                'display'   - Array der anzuzeigenden Felder
*                'dstring'   - Format-String für angezeigte Felder
*                'filter'    - Array mit Daten für die Anzeige und das Setzen der Filter
*                'limit'     - Limit für Anzeige
* @access public
*/
function usersfetch_fields($options) {

    // default values if options fields are not set
    $maxdisp = (isset($options['limit'])) ? $options['limit'] : PHPR_FILTER_MAXHITS;
    if (!isset($options['table'])) $options['table'] = 'users';
    if (is_array($options['table'])) $options['table'] = implode(", ".DB_PREFIX, $options['table']);
    $where = implode(" AND ", $options['where']);
    if ($where == "") $where = "1=1";
    if (!isset($options['order'])) $options['order'] = "nachname";

    $order = "ORDER BY ".$options['order']." ".$options['direction'];
    if (!isset($options['ID'])) $options['ID'] = "loginname";
    if (!isset($options['display'])) $options['display'] = array("vorname","nachname");
    if (!isset($options['dstring'])) {
        $options['dstring'] = "%s";
        for ($i=1; $i<count($options['display']);$i++) $options['dstring'] .= " %s";
    }

    // count hits and compare with limit
    // The following part is disabled because Count(Distinct ..) is not yet
    // implemented in SQLITE. For the sake of compatibiility we skip the COUNT()
    /*
    $query = "SELECT DISTINCT ".$options['ID']."
                FROM ".DB_PREFIX.$options['table']."
               WHERE $where";
    $result = db_query($query) or db_die();
    $count = 0;
    while ($row = db_fetch_row($result)) { $count++; }
    if ($maxdisp>0 && $count>$maxdisp) return $count;
    unset($count);
	*/
    // collect data and return search hits
    $fields = $options['ID'].",".implode(",", $options['display']);
	/*
    $query = "SELECT DISTINCT $fields
                FROM ".DB_PREFIX.$options['table']."
               WHERE $where
                     $order";
    */
    // optimized query for big numbers of users and groups
     $query = "SELECT $fields
                 FROM ".DB_PREFIX."grup_user as g
            LEFT JOIN ".DB_PREFIX."users as u 
                   ON g.user_ID=u.ID
                WHERE $where $order ";
    $result = db_query($query) or db_die();
    $hits = array();
    while ($row=db_fetch_row($result)) {
        // return if there are too many hits
        if ($maxdisp>0 && count($hits)>$maxdisp) { return count($hits); }
        
        $ary  = array($options['dstring']);
        for ($i=1; $i<=count($options['display']); $i++) {
            $ary[] = $row[$i];
        }
        $hits[$row[0]] = call_user_func_array('sprintf', $ary);
    }
    return $hits;
}


/** Anzeige der Filter
*
* @param $options    - optionen wie bei fetch_fields
* @param $object     - Das Selektor-Objekt und die Ausgewählten Kontakte, serialisiert
* @param $name       - Name des Aktuellen Filterobjekts
* @access public
*/
function usersdisplay_filters1($options, $object, $name, $getprm=array()) {
    global $path_pre, $filters, $usersextras, $sid;

    $_SESSION['filters'] =& $filters;
    $sarr =& $filters[$name];

    $fform = "
    <input type='hidden' name='sthis' value='$object' />
    <input type='hidden' name='filterform' value='done' />
    <br />
    <table border='0' class='selector_head'>
        <tr>";

    if (isset($options['title'])) {
        $fform .= "
            <td class='selector_head'>
                <span class='selector_title'>".str_replace("<br />", " ", $options['title'])."</span>
            </td>
        </tr>
        <tr>";
    }

    if (!empty($options['filter']['text']) || !empty($options['filter']['alternative'])) {
        $fform .= "
            <td class='selector_head'>
                <table border='0' class='selector_filter'>
                    <tr>
                        <td colspan='3' class='selector_filter'>
                            <b><u>".__("set filter")."</u></b>
                        </td>
                    </tr>
                    <tr>";
    }

    if (!empty($options['filter']['text'])) {
        $fform .= "
                        <td class='selector_filter'>
                            <select class='selector_filter' name='textfilter' tabindex='10'>\n";
        foreach ($options['filter']['text'] as $key => $value) {
            $fform .= "<option value='$key'>$value</option>\n";
        }
        $fform .= "                            </select>
                        </td>
                        <td class='selector_filter'>
                            <select name='textfiltermode' tabindex='11'>
                                <option value='begins with'>".__('starts with')."</option>
                                <option value='contains' selected='selected'>".__('contains')."</option>
                                <option value='ends with'>".__('ends with')."</option>
                                <option value='is equal'>".__('exact')."</option>
                            </select>
                        </td>
                        <td class='selector_filter'>
                            <input type='text' name='textfilterstring' size='40' maxlength='20' tabindex='12' style='width:200px;' />
                        </td>\n";
    }

    if (!empty($options['filter']['alternative'])) {
        foreach ($options['filter']['alternative'] as $key => $value) {
            $fform .= "                        <td>&nbsp;|&nbsp;</td>\n";
            $fform .= "                        <td>".$value['name'].": ";
            foreach ($value['option'] as $k => $v) {
                $fform .= "<input type='radio' name='altfilter$key' value='$k' />$v ";
            }
            $fform .= "</td>\n";
        }
    }

    if (!empty($options['filter']['text']) || !empty($options['filter']['alternative'])) {
        $fform .= "                    </tr>
                </table>
            </td>\n";
    } else {
        $fform .= "                        <td></td>\n";
    }

    $fform .= "                        <td rowspan='2' class='selector_head_submit_cell'>";
    //$fform .= "<input type='image' src='$path_pre"."img/los.gif' name='filterset' value='set' tabindex='19' /></td>\n";
    //$fform .= "<input name='filterset' type='submit' class='submit' value='".__('go')."' /></td>\n";
    $fform .= get_go_button_with_name('filterset')."</td>";
    $fform .= "
                    </tr>
                    <tr>
                        <td class='selector_head'>
                            <table border='0' class='selector_filter'>";
    if (!empty($usersextras)) {
        $fform .= "
                    <tr>
                        <td class='selector_filter' colspan='3'>
                            <b><u>".__("quickadd")."</u></b>
                        </td>
                    </tr>\n";
    }

    foreach ($usersextras as $key=>$val) {
        //error_log("display filters extras $key w/ options ".var_export($options['extra'],true));
        //error_log($val['getform']);
        $fform .= $val['getform']($options['extra'][$key]);
    }

    $fform .= "
                </table>
            </td>
        </tr>
    </table>\n";

    if (is_array($sarr) && count($sarr) > 0) {
        $hrefprm = '';
        if (count($getprm) > 0) {
            foreach ($getprm as $k=>$v) {
                $hrefprm .= "&amp;$k=".xss($v);
            }
        }
        $filter_list_arr = array();
        foreach ($sarr as $k=>$v) {
            $filter_list_arr[] = " <a href='".$_SERVER['PHP_SELF']."?filterdel=$k".$hrefprm.$sid.
                                 "' class='filter_active' title='".__('Delete')."'>&nbsp;".str_replace("%", "", $v)."&nbsp;</a>\n";
        }
        // link to delete all filter
        $fform .= "<b>".__('Filtered').":</b> ".implode('+', $filter_list_arr).
                  "&nbsp;&nbsp;|&nbsp;&nbsp;<a href='".$_SERVER['PHP_SELF']."?filterdel=-1".$hrefprm.$sid.
                  "' class='filter_manage' title='".__('Delete all filter')."'>".__('Delete all filter')."</a>\n";
        $fform .= "<br /><br />\n";
    }

    return $fform;
}


/** parsen des angezeigten Filters und ablegen der Filter in der Session
*
* @param  $object    - Selektor-Objekt in das die filter als Where-Bedingung abgelegt wird
* @access
*/
function usersparse_filters(&$object) {
    global $textfilter, $textfilterstring, $textfiltermode, $filters, $filterform;

    if ($filterform == "done") {
        $_SESSION['filters'] =& $filters;

        $sarr =& $filters[$object->name];
        if (isset($textfilterstring) && $textfilterstring != "") {
            $c = "$textfilter ";
            switch ($textfiltermode) {
                case 'contains':
                    $c .= "like '%$textfilterstring%'";
                    break;
                case 'begins with':
                    $c .= "like '$textfilterstring%'";
                    break;
                case 'ends with':
                    $c .= "like '%$textfilterstring'";
                    break;
            }
            // avoid duplicate entries
            if (!is_array($sarr) || !in_array($c, $sarr)) {
                $sarr[] = $c;
            }
        }
    }
}


/** zeigt Eingabe und Selectbox für Profile
*
* @return string
* @access public
*/
function usersextra_profiles($extopt) {
    global $user_ID;

    $ret = "
    <tr>
        <td class='selector_filter'>".__('add profile').":</td>
        <td class='selector_filter'><!-- <input type='text' name='usersextra_profileglob' /> --></td>
        <td class='selector_filter'>
            <select class='selector_filter' name='usersextra_profile' onchange='selectme();submit();' style='width:200px;'>
                <option selected='selected'></option>\n";
    $query = "SELECT ID, bezeichnung
                FROM ".DB_PREFIX."profile
               WHERE von = '$user_ID'";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)) {
        $ret .= "                <option value='".$row[0]."'>".$row[1]."</option>\n";
    }
    $ret .= "            </select>
        </td>
    </tr>\n";

    return $ret;
}


/** Wertet die Selectbox extra_profiles aus
*
* @param $options array der selektorklasse, benötigt wird 'save'
* @access public
*/
function userseval_extra_profiles($options) {
    global $usersextra_profile, $usersextra_profileglob, $user_ID, $selektor_answer;

    $addprof = array();
    if(isset($usersextra_profile) && $usersextra_profile!=0){
        $profile=unserialize(slookup('profile','personen','ID',$usersextra_profile));
        foreach($profile as $k => $v){
            $addprof[$v]=$v;
        }
        $selektor_answer = __('Added profile')."<br /><br />\n";
    }
    if(isset($usersextra_profileglob) && $usersextra_profileglob!=""){
        $query = "SELECT p.ID, p.bezeichnung, p.personen
                    FROM ".DB_PREFIX."profile AS p
                   WHERE p.bezeichnung LIKE '".str_replace("%","\%",quote_runtime($usersextra_profileglob))."%'
                     AND p.von = '$user_ID'";
        $res = db_query($query) or db_die();
        while($row=db_fetch_row($res)){
            $profiles[$row[0]]=$row[1];
            foreach(unserialize($row[2]) as $val) $addprof[$val]=$val;
        }
        // $subjcnt=(count($profiles)>1)?"e":"";
        // $selektor_answer="Profil$subjcnt '".implode("' '",$profiles)."' hinzugefügt";
        if(empty($addprof)) $selektor_answer = __('No profile found')."<br /><br />\n"; // "kein Profil gefunden";

    }
    return $addprof;
}


/** zeigt Eingabe und Selectbox für Projekte
*
* @return string
* @access public
*/
function usersextra_projects($extopt) {
    global $user_ID, $user_kurz, $sql_user_group;

    $ret = "
    <tr>
        <td>".__('add project participants').":</td>
        <td align='right'><input type='text' name='usersextra_projectglob' /></td>
        <td align='right'>
            <select name='usersextra_project' onchange='selectme();submit();' style='width:200px;'>
                <option selected='selected'></option>\n";
    $query = "SELECT ID, name
                FROM ".DB_PREFIX."projekte
               WHERE (acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)) {
        $ret .= "                <option value='".$row[0]."'>".$row[1]."</option>\n";
    }
    $ret .= "            </select>
        </td>\n";
    if (!empty($extopt)) {
        $ret .= "        <td><input type='submit' name='usersextra_projectbutton' value='".__('add project participants')."' /></td>\n";
    }
    $ret .= "    </tr>\n";

    return $ret;
}

/** Wertet die Selectbox extra_project aus
*
* @param $options array der selektorklasse, benötigt wird 'save'
* @access public
*/
function userseval_extra_projects($options) {
    global $usersextra_project, $usersextra_projectglob, $usersextra_projectbutton;
    global $selektor_answer, $user_ID, $user_kurz, $sql_user_group;

    $addproj = array();
    if (isset($usersextra_project) && $usersextra_project != 0) {
        $projects = unserialize(slookup('projekte', 'personen', 'ID', $usersextra_project));
        foreach ($projects as $k => $v) {
            $addproj[$v] = $v;
        }
        $selektor_answer = __('Added project participants')."<br /><br />\n"; //"Projektteilnehmer hinzugefügt";
    }
    if (isset($usersextra_projectglob) && $usersextra_projectglob != "") {
        $query = "SELECT ID, name, personen
                    FROM ".DB_PREFIX."projekte
                   WHERE name LIKE '".str_replace("%","\%",addslashes($usersextra_projectglob))."%'
                     AND (acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $projekt[$row[0]] = $row[1];
            foreach (unserialize($row[2]) as $val) $addproj[$val] = $val;
        }
        $selektor_answer = "Projekt$subjcnt '".implode("' '",$projekt)."' (".implode(' ',$addproj).") hinzugefügt";
    }
    if (isset($usersextra_projectbutton) && $usersextra_projectbutton!="") {
        $query = "SELECT ID, name, personen
                    FROM ".DB_PREFIX."projekte
                   WHERE ID='".$options['extra']['projects']."'
                     AND (acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $projekt[$row[0]] = $row[1];
            foreach (unserialize($row[2]) as $val) $addproj[$val] = $val;
        }
        //$selektor_answer = "Projekt$subjcnt '".implode("' '",$projekt)."' hinzugefügt";
    }
    return $addproj;
}


/** zeigt Eingabe und Selectbox für Gruppen
*
* @return string
* @access public
*/
function usersextra_groups() {
    global $user_ID, $user_kurz, $sql_user_group;

    // are there group-restrictions in effect?
    $additional_where = '';
    if (PHPR_ACCESS_GROUPS != 2) {
        $additional_where = selector_get_groupIds();
        if (is_array($additional_where) && count($additional_where) > 0) {
            $additional_where = " WHERE ID IN ('".implode("','", $additional_where)."')";
        } else {
            $additional_where = '';
        }
    }

    $ret = "
    <tr>
        <td class='selector_filter'>".__('add group of participants').":</td>
        <td class='selector_filter'></td>
        <td class='selector_filter'>
            <select class='selector_filter' name='usersextra_group' onchange='selectme();submit();' style='width:200px;'>
                <option selected='selected'></option>\n";
    $query = "SELECT ID, name
                FROM ".DB_PREFIX."gruppen ".$additional_where;
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)) {
        $ret .= "                <option value='".$row[0]."'>".$row[1]."</option>\n";
    }
    $ret .= "            </select>
        </td>
    </tr>\n";

    return $ret;
}

/** Wertet die Selectbox extra_group aus
*
* @param $options array der selektorklasse, benötigt wird 'save'
* @access public
*/
function userseval_extra_groups($options) {
    global $usersextra_group, $selektor_answer;

    $addprof = array();
    if (isset($usersextra_group) && $usersextra_group != 0) {
        // group-restrictions in effect?
        $additional_where = '';
        if (PHPR_ACCESS_GROUPS != 2) {
            $additional_where = selector_get_groupIds();
            if (is_array($additional_where) && count($additional_where) > 0) {
                $additional_where = " AND grup_ID IN ('".implode("','", $additional_where)."')";
            } else {
                $additional_where = '';
            }
        }
        //$query = "SELECT u.ID
        //            FROM ".DB_PREFIX."grup_user as g, ".DB_PREFIX."users as u
        //           WHERE u.ID=g.user_ID
        //             AND g.grup_ID='$usersextra_group'";
        $query = "SELECT u.ID
                    FROM ".DB_PREFIX."grup_user as g, ".DB_PREFIX."users as u
                   WHERE u.ID=g.user_ID
                     AND g.grup_ID='$usersextra_group' ".$additional_where;
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $addproj[$row[0]] = $row[0];
        }
        $selektor_answer = __('Added group of participants')."<br /><br />\n";
    }

    return $addproj;
}

/** zeigt Eingabe und Selectbox für Schnelle Namenssuche
*
* @return string
* @access public
*/
function usersextra_names(){
    $ret  = "
    <tr>
        <td>".__('add user').":</td>
        <td>Vorname:</td>
        <td>Nachname:</td>
    </tr>
    <tr>
        <td> </td>
        <td align='right'><input type='text' name='usersextra_namevor' /></td>
        <td><input type='text' name='usersextra_namenach' /></td>
        <td></td>
    </tr>\n";

    return $ret;
}

/** Wertet die Selectbox extra_name aus
*
* @param $options array der selektorklasse, benötigt wird 'save'
* @access public
*/
function userseval_extra_names($options) {
    global $usersextra_namevor, $usersextra_namenach, $selektor_answer;

    $addproj = array();
    if ((isset($usersextra_namevor) || isset($usersextra_namenach)) &&
         $usersextra_namevor.$usersextra_namenach != "") {
        // group-restrictions in effect?
        $additional_where = '';
        if (PHPR_ACCESS_GROUPS != 2) {
            $additional_where = selector_get_groupIds();
            if (is_array($additional_where) && count($additional_where) > 0) {
                $additional_where = " AND grup_ID IN ('".implode("','", $additional_where)."')";
            } else {
                $additional_where = '';
            }
        }
//        $query="SELECT DISTINCT u.ID, u.vorname, u.nachname from ".DB_PREFIX."users u
//            WHERE vorname  like '".str_replace("%","\%",quote_runtime($usersextra_namevor))."%'
//              AND nachname like '".str_replace("%","\%",quote_runtime($usersextra_namenach))."%'
        $query = "SELECT DISTINCT u.ID, u.vorname, u.nachname
                    FROM ".DB_PREFIX."users u
               LEFT JOIN ".DB_PREFIX."grup_user gu ON u.ID=gu.user_ID
                   WHERE vorname  LIKE '".str_replace("%", "\%", quote_runtime($usersextra_namevor))."%'
                     AND nachname LIKE '".str_replace("%", "\%", quote_runtime($usersextra_namenach))."%'
                 $additional_where ";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $addproj[$row[0]] = $row[0];
            $ansnam[]         = $row[1]." ".$row[2];
        }
        //$selektor_answer = implode(", ",$ansnam)." hinzugefügt";
        //$selektor_answer = count($ansnam)." Namen hinzugefügt";
        $selektor_answer = count($ansnam)." ".__('Added user')."<br /><br />\n";
    }

    return $addproj;
}


$usersextras = array(
    'names'    => array('getform'  => 'usersextra_names',
                        'evalform' => 'userseval_extra_names',
                        'formname' => array('usersextra_namevor', 'usersextra_namenach')),
    'profiles' => array('getform'  => 'usersextra_profiles',
                        'evalform' => 'userseval_extra_profiles',
                        'formname' => array('usersextra_profile', 'usersextra_profileglob')),
    'projects' => array('getform'  => 'usersextra_projects',
                        'evalform' => 'userseval_extra_projects',
                        'formname' => array('usersextra_project', 'usersextra_projectglob', 'usersextra_projectbutton')),
    'groups'   => array('getform'  => 'usersextra_groups',
                        'evalform' => 'userseval_extra_groups',
                        'formname' => array('usersextra_group'))
);


?>
