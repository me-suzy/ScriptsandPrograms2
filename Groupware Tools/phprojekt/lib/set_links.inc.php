<?php

// $Id: set_links.inc.php,v 1.14 2005/06/28 14:58:04 alexander Exp $

// include lib to fetch the sessiond data and to perform check
$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;
include_once $path_pre.'lib/dbman_lib.inc.php';

function set_links($ID, $module) {

    $str .= set_page_header();
    $str .= datepicker();

    // button bar
    $buttons = array();
    $buttons[] = array('type' => 'text', 'text' => '<b>'.__('Links').'</b>');
    $str .= get_buttons_area($buttons);
    $str .= '<div class="hline"></div>';

    $arr_ID = explode(',', $ID);
    $html .= "<br/><form action='set_links.inc.php' method=post name=links><table cellpadding=3 rules=none cellspacing=0 border=1>\n";
    // header
    $html .= "<tr><td width='160'>&nbsp;&nbsp;<b>".__('Name')."</b></td><td width='280'><b>".__('Remark')."</b></td><td width='150'><b>".__('From date')."</b></td><td width='120'><b>".__('Priority')."</b></td></tr>\n";
    $html .= "<tr><td colspan='4' height='20'></td></tr>";


    foreach ($arr_ID as $ID) {
        if ($ID > 0) {
            // fetch name of record in database
            switch($module) {
                case 'contacts':
                    $name = slookup('contacts', 'vorname,nachname', 'ID', $ID);
                    break;
                case 'projects':
                    $name = slookup('projekte', 'name', 'ID', $ID);
                    break;
                case 'notes':
                    $name = slookup('notes', 'name', 'ID', $ID);
                    break;
                case 'helpdesk':
                    $name = slookup('rts', 'name', 'ID', $ID);
                    break;
                case 'filemanager':
                    $name = slookup('dateien', 'filename', 'ID', $ID);
                    break;
                case 'mail':
                    $name = slookup('mail_client', 'subject', 'ID', $ID);
                    break;
                case 'todo':
                    $name = slookup('todo', 'remark', 'ID', $ID);
                    break;
            }
            if($name == ''){
                $name = '['.__('No Value').']';
            }
            $html .= "<input type=hidden name=action value='store'>\n";
            $html .= "<input type=hidden name=module value='".$module."'>\n";
            $html .= "<input type=hidden name=record_ID[] value='".$ID."'>\n";
            $html .= "<tr><td>&nbsp;&nbsp;".$name."</td>\n";
            // store the name of the module as well inorder to avoid lookups in the list view
            $html .= "<input type=hidden name='name[".$ID."]' value='".$name."' size=30>\n";
            $html .= "<td><input type=text name='title[".$ID."]' size=30></td>";
            $html .= "<td><input type=text name='date[".$ID."]' value='".date("Y-m-d")."' size=10>\n";
            $html .= "<a href='javascript://' title='".__('This link opens a popup window')."' onclick='callPick(document.links.elements[\"date[".$ID."]\"])'><img src='../img/cal.gif' border=0></a></td>\n";
            $html .= "<td><select name='priority[".$ID."]'>";
            for ($i=1; $i<=10; $i++) {
                $html .= "<option value='".$i."'>".$i."</option>";
            }
            $html .= "</select></td></tr>";
            $html .= "<tr><td colspan='4' height='10'></td></tr>";
        }
    }
    $html .= "<tr><td colspan='4' height='10' style='text-align:right'>".get_go_button()."&nbsp;</td></tr>";
    $html .= "</table>\n";
    $html .= "</form>\n";


$str .= '
<br/>
<div class="inner_content">
    <a name="oben" id="oben"></a>
    <div class="boxHeaderLeft"></div>
    <div class="boxHeaderRight"></div>
    <div class="boxContent">'.$html.'</div></div>
    <br style="clear:both"/><br/>
</div>
<br style="clear:both"/><br/>
';

return $str;
    $arr_ID = explode(',', $ID);
    $str .= "<form action='set_links.inc.php' method=post name=links><table cellpadding=3 rules=none cellspacing=0 border=1>\n";
    // header
    $str .= "<tr><td>".__('Name')."</td><td>".__('Remark')."</td><td>".__('From date')."</td><td>".__('Priority')."</td></tr>\n";

    foreach ($arr_ID as $ID) {
        if ($ID > 0) {
            // fetch name of record in database
            switch($module) {
                case 'contacts':
                    $name = slookup('contacts', 'vorname,nachname', 'ID', $ID);
                    break;
                case 'projects':
                    $name = slookup('projekte', 'name', 'ID', $ID);
                    break;
                case 'notes':
                    $name = slookup('notes', 'name', 'ID', $ID);
                    break;
            }
            $str .= "<input type=hidden name=action value='store'>\n";
            $str .= "<input type=hidden name=module value='".$module."'>\n";
            $str .= "<input type=hidden name=record_ID[] value='".$ID."'>\n";
            $str .= "<tr><td>".$name."</td>\n";
            // store the name of the module as well inorder to avoid lookups in the list view
            $str .= "<input type=hidden name='name[".$ID."]' value='".$name."' size=30>\n";
            $str .= "<td><input type=text name='remark[".$ID."]' size=30></td><td><input type=text name='date[".$ID."]' value='".date("Y-m-d")."' size=10>\n";
            $str .= "<a href='javascript://' onclick='callPick(document.links.date".$ID.")'><img src='../img/cal.gif' border=0></a></td>\n";
            $str .= "<td><select name='priority[".$ID."]'>";
            for ($i=1; $i<=10; $i++) {
                $str .= "<option value='".$i."'>".$i;
            }
            $str .= "</td></tr>";
        }
    }
    $str .= "</table>\n";
    $str .= "<input type=submit value=".__('go')."></forms>\n";
    return $str;
}


/**
* sets the reminder flag to all entries given by post
* @author Albrecht Günther / Alex Haslberger
* @return void
*/
function store_links() {
    global $dbIDnull, $dbTSnull, $user_group, $user_ID, $onload;
    foreach ($_POST['record_ID'] as $ID) {
        #set_links_flag($ID, $_POST['module'], $_POST['date'][$ID], $_POST['priority'][$ID], $_POST['remark'][$ID], 'private', $user_group, 0, 0);
        set_links_flag($ID, $_POST['module'], $_POST['date'][$ID], $_POST['priority'][$ID], $_POST['title'][$ID], $_POST['name'][$ID], 'private', $user_group, 0, 0);
    }
    $onload[] = 'window.close();';
    $str = set_page_header();
    return $str;
}

/**
* sets the links flag to a specific entry
* @author Alex Haslberger
* @param int    $ID id of the entry
* @param string $name link name
* @param string $module module to which the entry belongs
* @param string $reminder_datum date to be remembered
* @param int    $wichtung prioroty of the entry
* @param string $remark users remarks to this entry
* @param string $acc read access flag to this entry
* @param int    $group which group this entry belongs to
* @param int    $parent
* @param int    $archiv flag if entry is in archiv or not
* @return void
*/
#function set_links_flag($ID, $module, $reminder_datum, $wichtung, $remark, $acc, $gruppe, $parent, $archiv) {
#function set_links_flag($ID, $name, $module, $reminder_datum, $wichtung, $remark, $acc, $gruppe, $parent, $archiv) {
function set_links_flag($ID, $module, $reminder_datum, $wichtung, $title, $name, $acc, $gruppe, $parent, $archiv) {
    global $tablename, $user_ID, $dbIDnull, $dbTSnull;

    if($module == 'helpdesk') $module = 'rts';

    // for database security purposes
    $reminder_datum = addslashes($reminder_datum);
    $title          = addslashes($title);
    $name          = addslashes($name);
    $acc            = addslashes($acc);
    $wichtung       = (int) $wichtung;
    $gruppe         = (int) $gruppe;
    $parent         = (int) $parent;
    $archiv         = (int) $archiv;

    // check if ID has already an entry
    $result = db_query("SELECT t_ID
                          FROM ".DB_PREFIX."db_records
                         WHERE t_record = '$ID'
                           AND t_author = '$user_ID'
                           AND t_module = '$module'");
    $row = db_fetch_row($result);
    // insert / update entry
    if ($row[0] > 0) {
        $result = db_query(xss("UPDATE ".DB_PREFIX."db_records
                               SET t_datum = '$dbTSnull',
                                   t_reminder_datum = '$reminder_datum',
                                   t_wichtung = '$wichtung',
                                   t_name = '$name',
                                   t_remark = '$title',
                                   t_acc = '$acc',
                                   t_gruppe = '$gruppe',
                                   t_parent = '$parent',
                                   t_reminder = 1
                             WHERE t_ID = '$row[0]'")) or db_die();
    }
    else {
        $result = db_query(xss("INSERT INTO ".DB_PREFIX."db_records
                                        ( t_ID,    t_author,             t_module,             t_record,  t_datum, t_reminder_datum, t_wichtung, t_remark, t_acc, t_gruppe, t_parent, t_archiv, t_reminder, t_name)
                                 VALUES ($dbIDnull,'$user_ID', '$module',  '$ID', '$dbTSnull', '$reminder_datum', '$wichtung', '$title', '$acc', '$gruppe', '$parent', '$archiv', 1, '$name')")) or db_die();
    }
}

if ($action == 'store') echo store_links();
else                    echo set_links($ID_s,$module);

?>

</body>
</html>
