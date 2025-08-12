<?php

// search_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: search_forms.php,v 1.20 2005/06/20 10:51:00 nina Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) die('Please use index.php!');


// check whether variable $hits exists
if (!PHPR_MAXHITS) $maxhits = 50;
else               $maxhits = PHPR_MAXHITS;

$geshits = 0;
if (($gebiet == 'termine' or $gebiet == 'all') and PHPR_CALENDAR and check_role('calendar') > 0) {
    $hits = 0;
    $fields = array('event', 'remark');
    $where = build_where($fields);
    $result = db_query("SELECT COUNT(ID)
                          FROM ".DB_PREFIX."termine
                        $where
                           AND an = '$user_ID'") or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {

        $result = db_query("SELECT ID, event, datum
                              FROM ".DB_PREFIX."termine
                            $where
                               AND an = '$user_ID'") or db_die();
        $tmp = "<table class='opt'>";
        $tmp .= "<thead> <tr> <th class='column-1' scope='col'>".__('Date').":</th>
        <th scope='col'>".__('Text').":</th></tr></thead><tbody>\n";
        while ($row = db_fetch_row($result)) {
            if ($hits <= $maxhits) {
                $ref = '../calendar/calendar.php?mode=forms&amp;ID='.$row[0].$sid;
                tr_tag($ref,"parent.");
                $tmp .= "<tr><td scope='row' class='column-1'>$row[2]</td>\n";
                $tmp .= "<td><a href='$ref' target='_top'>".html_out($row[1])."</a></td></tr>\n";
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if ($hits > $maxhits) $tmp .= "$maxhits ".__('of')." $hits ".__('hits were shown for').":\n";
        else $tmp .= "$hits ".__('hits were shown for')."\n";
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Calendar').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);
    }
    else {
        $ou .= get_no_hits_found_message('Calendar');
    }
}


// Forum
if (($gebiet == 'forum' or $gebiet == 'all') and PHPR_FORUM and check_role('forum') > 0) {
    $hits = 0;
    $fields = array('titel', 'remark');
    $where = build_where($fields);
    $query = "SELECT ID, antwort, von, titel, remark, kat, datum, gruppe, lastchange, notify
                FROM ".DB_PREFIX."forum
              $where
                 AND $sql_user_group";

    $result  = db_query($query) or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {

        $result = db_query($query)or db_die();
        $tmp = "<table class='opt'><thead><tr>";
        $tmp .= "<th class='column-1' scope='col'>".__('Title').":</th><th>".__('Text').":</th><th>".__('Date').":</th></tr></thead></body>";
        while ($row = db_fetch_row($result)) {
            if ($hits <= $maxhits) {
                $ref = '../index.php?module=forum&amp;mode=forms&amp;ID='.$row[0].'&amp;action=writetext'.$sid;
                tr_tag($ref, 'parent.');
                $datum = substr($row[6],6,2).'-'.substr($row[6],4,2).'-'.substr($row[6],0,4).' '.substr($row[6],8,2).":".substr($row[6],10,2);
                $remark = nl2br(html_out(substr($row[4],0,300)));
                if (strlen($remark) > 300) $remark = $remark.'...';
                $tmp .= "<tr><td scope='row' class='column-1'><a href='$ref' target='_top'>$row[3]</a></td>\n";
                $tmp .= "<td>$remark</td><td>$datum</td></tr>";
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if ($hits == 0) { $tmp .= "$searchterm: ".__('there were no hits found.')."!"; }
        else if ($hits > $maxhits) { $tmp .= "$maxhits ".__('of')." $hits ".__('hits were shown for').":\n"; }
        else { $tmp .= "$hits ".__('hits were shown for').":\n"; }
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Forum').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);
    }
    else {
        $ou .= get_no_hits_found_message('Forum');
    }
}


// file module
if (($gebiet == 'files' or $gebiet == 'all') and PHPR_FILEMANAGER and check_role('filemanager') > 0) {
    // set the total size to zero because we want to build the list entirely new.
    $total_size = 0;

    $hits = 0;
    $fields = array('filename', 'remark', 'kat');
    $where = build_where($fields);
    $query = "SELECT ID, von, filename, remark, kat, acc, datum, filesize, gruppe,
                     tempname, typ, div1, div2, pw, acc_write, version, lock_user, contact
                FROM ".DB_PREFIX."dateien
              $where
                 AND (von = '$user_ID' OR acc LIKE 'system' OR ((acc = 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))
            ORDER BY filename";
    $result  = db_query($query) or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {

        $result  = db_query($query) or db_die();
        $tmp = "<table class='opt'><thead><tr>\n";
        $tmp .= "<th class='column-1' scope='col'>".__('Name')."</th>\n";
        $tmp .= "<th>".__('Date')."</th>\n";
        $tmp .= "<th>Byte</th>\n";
        $tmp .= "<th>".__('Category')."</th>\n";   // category
        $tmp .= "<th>".__('Comment')."</th></tr></thead><tbody>\n"; // remark
        $parent = "parent.";
        $total_size=0;
        while ($row = db_fetch_row($result)) {
            if ($hits <= $maxhits) {
                $total_size= $total_size+$row[7];
                $ref = "../index.php?module=filemanager&amp;mode=forms&amp;ID=$row[0]&amp;action=writetext$sid";
                tr_tag($ref, 'parent.');
                $datum = substr($row[6],6,2)."-".substr($row[6],4,2)."-".substr($row[6],0,4)." ".substr($row[6],8,2).":".substr($row[6],10,2);
                $tmp .= "<tr><td scope='row' class='column-1'><a href='$ref' target='_top'>$row[2]</a></td>\n";
                $tmp .= "<td>$datum</td><td>$row[7]</td><td>$row[4]</td><td>$row[3]</td></tr>";
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if (!$hits) $hits = '0';
        if (!$total_size) $total_size = '0';
        $tmp .= " &nbsp;<i>".__('Sum').": $hits ".__('objects').", $total_size Byte</i>\n";
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Files').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);
    }
    else {
        $ou .= get_no_hits_found_message('Files');
    }
}


// *******************
// result for contacts
if (($gebiet == 'contacts' or $gebiet == 'all') and PHPR_CONTACTS and check_role('contacts') > 0) {
    $hits = 0;
    $fields = array("vorname","nachname","firma","gruppe","email","strasse","land","state","tel1","tel2","fax","bemerkung","div1","div2");
    $where = build_where($fields);
    $where2 = "(acc_read like 'system' or ((von = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))";
    $query="select ID,vorname,nachname,gruppe,firma,email,tel1,tel2,fax,strasse,stadt,plz,land,kategorie,bemerkung
                          from ".DB_PREFIX."contacts
                               $where and
                               $where2";
    $result  = db_query($query) or db_die();
    $row= db_fetch_row($result);
    if ($row[0] > 0) {

        $result  = db_query($query) or db_die();
        $tmp = "<table class='opt'><thead><tr>\n";
        $tmp .= "<th class='column-1' scope='col'>".__('Family Name').":</th><th>".__('First Name').":</th>";
        $tmp .= "<th>".__('Company')."</th><th>".__('Street')."</th>";
        $tmp .= "<th>".__('City')."</th><th>".__('Country')."</th></tr></thead><tbody>\n";
        while ($row = db_fetch_row($result)) {
            if ($hits <= $maxhits) {
                $ref = "../index.php?module=contacts&amp;action=contacts&amp;ID=$row[0]&amp;mode=forms&amp;modify=1$sid";
                tr_tag($ref,"parent.");
                $tmp .= "<tr><td scope='row' class='column-1'>&nbsp;<a href='$ref' target=_top>$row[2]</a></td>\n";
                $tmp .= "<td>&nbsp;$row[1]</td>\n";
                $tmp .= "<td>&nbsp;$row[4]</td><td>&nbsp;$row[9]</td><td>&nbsp;$row[11] $row[10]</td><td>&nbsp;$row[12]</td></tr>\n";
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if ($hits == 0) { $tmp .= "$searchterm: ".__('there were no hits found.')."!"; }
        else if ($hits > $maxhits) { $tmp .= "$maxhits ".__('of')." $hits ".__('hits were shown for').":\n"; }
        else { $tmp .= "$hits ".__('hits were shown for').":\n"; }
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Contacts').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);
    }
    else {
        $ou .= get_no_hits_found_message('Contacts');
    }
}


// *****************
// results for notes
if (($gebiet == 'notes' or $gebiet == 'all') and PHPR_NOTES and check_role('notes') > 0) {
    $hits = 0;
    $fields = array("name","remark");
    $where = build_where($fields);
    $where2 = "(acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))";
    $query = "SELECT ID, name, remark
                FROM ".DB_PREFIX."notes
              $where
                 AND $where2";
    $result = db_query($query) or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {

        $result  = db_query($query) or db_die();
        $tmp = "<table class='opt'><thead><tr>";
        $tmp .= "<th class='column-1' scope='col'>".__('Title')."</th><th>".__('Text')."</th></tr></thead><tbody>\n";
        while ($row = db_fetch_row($result)) {
            if ($hits <= $maxhits) {
                $ref = '../index.php?module=notes&amp;mode=forms&amp;ID='.$row[0].$sid;
                tr_tag($ref, 'parent.');
                $remark = nl2br(html_out(substr($row[2], 0, 300)));
                if (strlen($remark) > 300) $remark = $remark.'...';
                $tmp .= "<tr><td scope='row' class='column-1'><a href='$ref' target='_top'>$row[1]</a></td>\n";
                $tmp .= "<td>$remark</td></tr>\n";  // text
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if ($hits == 0) { $tmp .= "$searchterm: ".__('there were no hits found.')."!"; }
        elseif ($hits > $maxhits) { $tmp .= "$maxhits ".__('of')." $hits ".__('hits were shown for').":\n"; }
        else { $tmp .= "$hits ".__('hits were shown for').":\n"; }
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Notes').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);
    }
    else {
        $ou .= get_no_hits_found_message('Notes');
    }
}


// ****************
// results for todo
if (($gebiet == 'todo' or $gebiet == 'all') and PHPR_TODO and check_role('todo') > 0) {
    $hits = 0;
    $fields = array('note', 'remark');
    $where = build_where($fields);
    $where2 = "(acc LIKE 'system' OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))";
    $query = "SELECT ID, remark, note
                FROM ".DB_PREFIX."todo
              $where
                 AND $where2";
    $result = db_query($query) or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {

        $result = db_query($query) or db_die();
        $tmp = "<table class='opt'><thead><tr>";
        $tmp .= "<th class='column-1' scope='col'>".__('Title')."</th><th>".__('Text')."</th></tr></thead><tbody>\n";
        while ($row = db_fetch_row($result)) {
            if ($hits <= $maxhits) {
                $ref = "../index.php?module=todo&amp;mode=forms&amp;ID=$row[0]$sid";
                tr_tag($ref, 'parent.');
                $tmp .= "<tr><td scope='row' class='column-1'><a href='$ref' target='_top'>$row[1]</a></td>\n";
                $tmp .= "<td>".nl2br(html_out(substr($row[2],0,300)))."</td></tr>\n";  // text
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if ($hits == 0) { $tmp .= "$searchterm: ".__('there were no hits found.').'!'; }
        elseif ($hits > $maxhits) { $tmp .= "$maxhits ".__('of')." $hits ".__('hits were shown for').":\n"; }
        else { $tmp .= "$hits ".__('hits were shown for').":\n"; }
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Todo').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);
    }
    else {
        $ou .= get_no_hits_found_message('Todo');
    }
}


// results for mails
if (($gebiet == 'mails' or $gebiet == 'all') and PHPR_QUICKMAIL == 2 and check_role('mail') > 0) {
    $hits = 0;
    $fields = array('subject', 'body', 'sender', 'recipient', 'remark', 'header');
    $where = build_where($fields);
    $query= "SELECT ID, subject, body, sender
                              FROM ".DB_PREFIX."mail_client
                            $where
                               AND von = '$user_ID'";
    $result = db_query($query) or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {

        $result  = db_query($query) or db_die();
         $tmp = "<table class='opt'><thead><tr>";
        // subject, body, sender, recipient, remark
        $tmp .= "<th class='column-1' scope='col'>".__('Subject')."</th><th>".__('Text')."</th>\n";
        $tmp .= "<th>".__('Receiver')."</th></tr></thead><tbody>\n";
        while ($row = db_fetch_row($result)) {
            if ($hits < $maxhits) {
                $ref = "../index.php?module=mail&amp;mode=forms&amp;ID=$row[0]$sid";
                tr_tag($ref,"parent.");
                // subject with link
                $tmp .= "<tr><td scope='row' class='column-1'><a href='$ref' target='_top'>$row[1]&nbsp;</a></td>\n";
                $remark = nl2br(html_out(substr($row[2],0,300)));
                if(strlen($remark) > 300) { $remark = $remark."..."; }
                $tmp .= "<td>$row[3]&nbsp;</td><td>$remark&nbsp;</td></tr>\n";
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if ($hits == 0) { $tmp .= "$searchterm: ".__('there were no hits found.')."!"; }
        elseif ($hits > $maxhits) { $tmp .= "$maxhits ".__('of')." $hits ".__('hits were shown for').":\n"; }
        else { $tmp .= "$hits ".__('hits were shown for').":\n"; }
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Mail').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);
    }
    else {
        $ou .= get_no_hits_found_message('Mail');
    }
}


// results for helpdesk
if (($gebiet == 'helpdesk' or $gebiet == 'all') and PHPR_RTS and check_role('helpdesk') > 0) {
    $hits = 0;
    $fields = array('contact', 'email', 'name', 'note', 'remark', 'solution');
    $where = build_where($fields);
    if (!ereg("c", $user_access)) {
        $where .= " AND (assigned = '$user_kurz'";
        $result = db_query("SELECT kurz
                              FROM ".DB_PREFIX."gruppen
                             WHERE ID = '$user_group'") or db_die();
        $row = db_fetch_row($result);
        $where .= " OR assigned = '$row[0]')";
    }
    $query = "SELECT ID, name, note
                FROM ".DB_PREFIX."rts
              $where ";
    $result = db_query($query) or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {

        // additional limitation for normal users: only those requests which are assigned to you or your group
        // group system? fetch short name of the group
        $result = db_query($query) or db_die();
        $tmp = "<table class='opt'><thead><tr>";
        // sender, title
        $tmp .= "<th class='column-1' scope='col'>".__('Text')."</th><th>".__('Remark')."</th></tr></thead><tbody>\n";
        while ($row = db_fetch_row($result)) {
            if ($hits < $maxhits) {
                $ref = "../index.php?module=helpdesk&amp;mode=forms&amp;ID=$row[0]$sid";
                tr_tag($ref,"parent.");
                // subject with link
                $tmp .= "<tr><td scope='row' class='column-1'><a href='$ref' target='_top'>$row[1]&nbsp;</a></td>\n";
                $remark = nl2br(html_out(substr($row[2],0,300)));
                if(strlen($remark) > 300) { $remark = $remark."..."; }
                $tmp .= "<td>$remark </td></tr>\n";
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if ($hits == 0) { $tmp .= "$searchterm: ".__('there were no hits found.')."!"; }
        elseif ($hits > $maxhits) { $tmp .= "$maxhits ".__('of')." $hits ".__('hits were shown for').":\n"; }
        else { $tmp .= "$hits ".__('hits were shown for').":\n"; }
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Helpdesk').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);
    }
    else {
        $ou .= get_no_hits_found_message('Helpdesk');
    }
}


// results for bookmarks
if (($gebiet == 'bookmarks' or $gebiet == 'all') and PHPR_BOOKMARKS and check_role('bookmarks') > 0) {
    $hits = 0;
    $fields = array('url', 'bezeichnung', 'bemerkung');
    $where = build_where($fields);
    $query = "SELECT ID, url, bezeichnung, bemerkung
                FROM ".DB_PREFIX."lesezeichen
              $where
                 AND (von = '$user_ID' OR gruppe = '$user_group')";
    $result = db_query($query) or db_die();
    $row = db_fetch_row($result);
    if ($row[0] > 0) {

        $result  = db_query($query) or db_die();
        $tmp = "<table class='opt'><thead><tr>";
        $tmp .= "<th class='column-1' scope='col'>".__('Description')."</th><th>".__('Comment')."</th></tr></thead><tbody>\n";
        while ($row = db_fetch_row($result)) {
            if ($hits <= $maxhits) {
                $ref = "../index.php?module=bookmarks&amp;mode=forms&amp;aendern=1&amp;ID=$row[0]$sid";
                tr_tag($ref,"parent.");
                $tmp .= "<tr><td scope='row' class='column-1'><a href='$row[1]' target=_blank>$row[2]</a></td>\n";
                $tmp .= "<td>".nl2br(html_out(substr($row[3],0,300)))."</td></tr>\n";  // text
            }
            $hits++;
        }
        $tmp .= "</tbody></table>\n";
        if ($hits == 0) { $tmp .= "$searchterm: ".__('there were no hits found.')."!"; }
        elseif ($hits > $maxhits) { $tmp .= "$maxhits ".__('of')." $hits ".__('hits were shown for').":\n"; }
        else { $tmp .= "$hits ".__('hits were shown for').":\n"; }
        $geshits = $geshits + $hits;

        $ou .= '
        <div class="boxHeader">'.__('Bookmarks').'</div>
        <div class="boxContent">'.$tmp.'</div>
        <br style="clear:both"/><br/>
        ';
        unset($tmp);

    }
    else {
        $ou .= get_no_hits_found_message('Bookmarks');
    }
}

// *********


function build_where($fields) {
    global $searchterm;

    $where = 'WHERE';
    // split the string into keywords
    if (ereg(" AND ", $searchterm)) $words = explode(' AND ', $searchterm);
    else $words[0] = $searchterm;
    $where .= ' (';
    foreach ($words as $keyword) {
        if ($flag1) $where .= ') AND (';
        $flag2 = 0;
        foreach ($fields as $field) {
            if ($flag2) $where .= ' OR ';
            $where .= $field." LIKE '%".$keyword."%'";
            $flag2 = 1;
        }
        $flag1 = 1;
    }
    $where .= ')';
    return $where;
}

?>
