<?php

// datasource_contacts.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Martin Brotzeller, $Author: fgraf $
// $Id: datasource_contacts.php,v 1.10.2.1 2005/08/22 07:32:14 fgraf Exp $

/** datasource_contacts.php
*
* Datenquelle für Anzeige von Kontakten
*
*
* @copyright (c) 2004 Mayflower GmbH
* @author Martin Brotzeller
* @package PHProjekt
*/
// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Not to be called directly!');


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
function contactsfetch_fields($options) {

    // default values if options fields are not set
    $maxdisp = (isset($options['limit'])) ? $options['limit'] : PHPR_FILTER_MAXHITS;
    if (!isset($options['table'])) $options['table'] = 'contacts';
    if (is_array($options['table'])) $options['table'] = implode(", ".DB_PREFIX, $options['table']);
    $where = implode(" AND ",$options['where']);
    if ($where == "") $where = "1";
    if (!isset($options['order'])) $options['order'] = "nachname";

    $order = "ORDER BY ".$options['order']." ".$options['direction'];
    if (!isset($options['ID'])) $options['ID'] = "ID";
    if (!isset($options['display'])) $options['display'] = array("vorname", "nachname");
    if (!isset($options['dstring'])) {
        $options['dstring'] = "%s";
        for ($i=1; $i<count($options['display']); $i++) $options['dstring'] .= " %s";
    }

    // count hits and compare with limit
    // Count(Distinct ..) is not yet implemented in SQLITE :-(
    $query = "SELECT DISTINCT ".$options['ID']."
                FROM ".DB_PREFIX.$options['table']."
               WHERE $where";
    $result = db_query($query) or db_die();
    $count = 0;
    while ($row = db_fetch_row($result)) { $count++; }
    if ($maxdisp>0 && $count>$maxdisp) return $count;
    unset($count);

    // collect data and return search hits
    $fields = $options['ID'].",".implode(",", $options['display']);
    $query = "SELECT DISTINCT $fields
                FROM ".DB_PREFIX.$options['table']."
               WHERE $where
              $order";
    $result = db_query($query) or db_die();

    while ($row = db_fetch_row($result)) {
        $ary = array($options['dstring']);
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
function contactsdisplay_filters1($options, $object, $name, $getprm=array()) {
    global $path_pre, $filters, $contactsextras, $bgcolor2;

    $_SESSION['filters'] =& $filters;
    $sarr =& $filters[$name];

    $fform = "
    <input type='hidden' name='sthis' value='$object' />
    <input type='hidden' name='filterform' value='done' />
    <table cellspacing='5' border='0' cellpadding='5'>
        <tr>";
    if (!empty($options['filter'])) {
        $fform .= "
            <td bgcolor='$bgcolor2'>
                <table cellpadding='5' cellspacing='5'>
                    <tr>";
    }
    if (!empty($options['filter']['text']) || !empty($options['filter']['alternative'])) {
        $fform .= "
                        <td colspan='3'><b><u>Filter setzen</u></b></td>
                    </tr>
                    <tr>";
    }
    if (!empty($options['filter']['text'])) {
        $fform .= "
                        <td>
                            <select name='textfilter' tabindex='10'>\n";
        foreach ($options['filter']['text'] as $key => $value) {
            $fform .= "<option value='$key'>$value</option>\n";
        }
        $fform .= "                            </select>
                        </td>
                        <td>
                            <select name='textfiltermode' tabindex='11'>
                                <option>begins with</option>
                                <option>contains</option>
                                <option>ends width</option>
                                <option>is equal</option>
                            </select>
                        </td>
                        <td><input type='text' name='textfilterstring' size='20' maxlength='20' tabindex='12' /></td>\n";
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
    if (!empty($options['filter'])) {
        $fform .= "
                    </tr>
                </table>
            </td>";
    } else {
        $fform .= "                        <td></td>\n";
    }

    $fform .= "
                <td rowspan='2' style='vertical-align:bottom;'>
                    <input type='image' src='$path_pre"."img/los.gif' name='filterset' value='set' tabindex='19' alt='' />
                </td>
            </tr>
            <tr>
                <td bgcolor='$bgcolor2'>
                    <table cellpadding='5' cellspacing='5'>";
    if(!empty($contactsextras)){
        $fform .= "
                        <tr>
                            <td colspan='3'><b><u>Schnelles Hinzufügen</u></b></td>
                        </tr>";
    }
    foreach ($contactsextras as $val) {
        $fform .= $val['getform']();
    }
    $fform .= "
                    </table>
                </td>
            </tr>\n";

    if (is_array($sarr) && count($sarr) > 0) {
        $fform .= "<tr><td bgcolor='$bgcolor2'>";
        $ti = 20;
        foreach ($sarr as $k=>$v) {
            $ti++;
            $fform .= "<input type=submit name='filterdel' value=\"$k\" tabindex='$ti' />".
                      str_replace("%", "", $v)."&nbsp;&nbsp;|&nbsp;&nbsp;";
        }
        $fform .= "</td>
            <td></td>
        </tr>\n";
    }
    $fform .= "    </table>\n";

    return $fform;
}


/** parsen des angezeigten Filters und ablegen der Filter in der Session
*
* @param  $object    - Selektor-Objekt in das die filter als Where-Bedingung abgelegt wird
* @access
*/
function contactsparse_filters(&$object) {
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
function contactsextra_profiles() {
    global $user_ID;

    $ret = "
    <tr>
        <td>Profil hinzufügen:</td>
        <td align='right'><input type=text name='contactsextra_profileglob' /></td>
        <td align='right'>
            <select name='contactsextra_profile' onchange='selectme();submit();' style='width:200px;'>
                <option selected='selected'></option>\n";

    $query = "SELECT ID, name
                FROM ".DB_PREFIX."contacts_profiles
               WHERE von='$user_ID'";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res)){
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
function contactseval_extra_profiles($options) {
    global $contactsextra_profile, $contactsextra_profileglob, $user_ID, $selektor_answer;

    $addprof = array();
    if (isset($contactsextra_profile) && $contactsextra_profile !=0 ) {
        $query = "SELECT r.contact_ID, c.vorname, c.nachname, p.name
                    FROM ".DB_PREFIX."contacts_profiles AS p,
                         ".DB_PREFIX."contacts_prof_rel AS r,
                         ".DB_PREFIX."contacts AS c
                   WHERE p.von='$user_ID'
                     AND p.ID='$contactsextra_profile'
                     AND p.ID=r.contacts_profiles_ID
                     AND c.ID=r.contact_ID";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $addprof[$row[0]] = 'on';
            $reply[] = $row[1]." ".$row[2];
            $proname = $row[3];
        }

        //$selektor_answer = "Profil '$proname' (".implode(", ", $reply).") hinzugefügt";
        $selektor_answer = "Profil '$proname' hinzugefügt";
    }
    if (isset($contactsextra_profileglob) && $contactsextra_profileglob != "") {
        $query = "SELECT p.ID, p.name, r.ID, c.vorname, c.nachname
                    FROM ".DB_PREFIX."contacts_profiles AS p,
                         ".DB_PREFIX."contacts_prof_rel AS r,
                         ".DB_PREFIX."contacts AS c
                   WHERE p.name LIKE '".str_replace("%","\%",quote_runtime($contactsextra_profileglob))."%'
                     AND p.von='$user_ID'
                     AND c.ID=r.contact_ID
                     AND r.contacts_profiles_ID=p.ID";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
            $profiles[$row[0]] = $row[1];
            $addprof[$row[2]]  = 'on';
            $reply[] = $row[3]." ".$row[4];
        }
        $subjcnt = (count($profiles) > 1) ? "e" : "";
        //$selektor_answer = "Profil$subjcnt '".implode("' '", $profiles)."' (".implode(", ", $reply).") hinzugefügt";
        $selektor_answer = "Profil$subjcnt '".implode("' '", $profiles)."' hinzugefügt";

    }
    return $addprof;
}

/** zeigt Eingabe und Selectbox für Schnelle Namenssuche
 *
 * @return string
 * @access public
 */
function contactsextra_names(){
    $ret = "
    <tr>
        <td>Kontakt hinzufügen:</td>
        <td>Vorname:</td>
        <td>Nachname:</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align='right'><input type='text' name='contactsextra_namevor' /></td>
        <td><input type='text' name='contactsextra_namenach' /></td>
        <td>&nbsp;</td>
    </tr>\n";

    return $ret;
}

/** Wertet die Selectbox extra_name aus
 *
 * @param $options array der selektorklasse, benötigt wird 'save'
 * @access public
 */
function contactseval_extra_names($options) {
    global $contactsextra_namevor, $contactsextra_namenach, $selektor_answer;
    global $user_ID, $user_kurz, $sql_user_group;

    $addproj = array();
    if ( (isset($contactsextra_namevor) || isset($contactsextra_namenach)) &&
         $contactsextra_namevor.$contactsextra_namenach != "" ) {
        $query = "SELECT ID, vorname, nachname
                    FROM ".DB_PREFIX."contacts
                   WHERE vorname  LIKE '".str_replace("%", "\%", quote_runtime($contactsextra_namevor))."%'
                     AND nachname LIKE '".str_replace("%", "\%", quote_runtime($contactsextra_namenach))."%'
                     AND (acc_read LIKE 'system' OR ((von = '$user_ID' OR acc_read LIKE 'group' OR acc_read LIKE '%\"$user_kurz\"%') AND $sql_user_group))";
        $res = db_query($query) or db_die();
        while ($row = db_fetch_row($res)) {
               $addproj[$row[0]] = 'on';
               $ansnam[] = $row[1]." ".$row[2];
        }
        //$selektor_answer = $query."<br />";
        $selektor_answer .= count($ansnam)." Kontakte hinzugefügt";
    }

    return $addproj;
}

$contactsextras = array(
    array('getform'  => 'contactsextra_names',
          'evalform' => 'contactseval_extra_names',
          'formname' => array('contactsextra_namevor', 'contactsextra_namenach')),
    array('getform'  => 'contactsextra_profiles',
          'evalform' => 'contactseval_extra_profiles',
          'formname' => array('contactsextra_profile', 'contactsextra_profileglob'))
);

?>
