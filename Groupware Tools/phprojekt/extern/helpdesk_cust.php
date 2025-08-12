<?php

// helpdesk_cust.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: helpdesk_cust.php,v 1.14.2.2 2005/08/20 08:34:22 nina Exp $

// set contstant avoid_auth in order to bypass authentication in lib
define("avoid_auth", "1");
$module = 'helpdesk';
$path_pre = "../";
$include_path = $path_pre."lib/lib.inc.php";
include_once($include_path);

helpdesk_cust_init();

use_mail('1');

// user authentification is disabled so customers can use it as well -> define language
//if ($langua) include($path_pre."lang/$langua.inc.php");

$page_title = __('Help desk');
$output = set_page_header();

$output .= '
<div class="navi">
    <ul>
        <li><div id="logo" title=" PHProjekt '.PHPR_VERSION.' "></div><br class="navbr" /></li>
        <li><a class="navLink" href="./helpdesk_cust.php?step=" title="Anfrage">';

$output .= ($step == '') ? '<div class="navLinkSelected">' : '<div class="navLink">';

$output .= __('request form').'</div></a><br class="navbr" /></li>
        <li><a class="navLink" href="./helpdesk_cust.php?step=c_q" title="Liste">';

$output .= ($step == 'c_q') ? '<div class="navLinkSelected">' : '<div class="navLink">';

$output .= __('pending requests').'</div></a><br class="navbr" /></li>
        <li><a class="navLink" href="./helpdesk_cust.php?step=kb" title="Knowldegebase">';

$output .= ($step == 'kb') ? '<div class="navLinkSelected">' : '<div class="navLink">';

$output .= __('knowledge database').'</div></a><br class="navbr" /></li>
    </ul>
</div>

<div class="outer_content">
    <div class="content">
';

// tabs
$tabs = array();
//$tmp = get_export_link_data('helpdesk', false);
$output .= get_tabs_area($tabs);

// reception
if ($step == 'submit') {
    //$datetime = mktime(date("H")+PHPR_TIMEZONE,date("i"),date("s"),date("m"),date("d"),date("Y"));
    $due = date("Y-m-d", (mktime(date("H")+PHPR_TIMEZONE,date("i"),date("s"),date("m"),date("d"),date("Y"))+(60*60*24*$due_date)));

    // config option rts_cust_acc > 0: customer must be listed in contact manager
    if (PHPR_RTS_CUST_ACC > 0 and PHPR_CONTACTS) {
        // first option: use the last name
        $field = (PHPR_RTS_CUST_ACC == 1) ? 'nachname' : 'email';
        // look whether a record in the contact manager exist.
        $result = db_query("SELECT ID, email
                              FROM ".DB_PREFIX."contacts
                             WHERE ".qss($field)." = '$rts_cust_string'") or db_die();
        $row = db_fetch_row($result);

        // customer is not listed?
        if (!$row[0]) {
            die(__('Sorry, you are not in the list')."!");
        }
        else {
            $cust_ID    = $row[0];
            $cust_email = $row[1];
        }
    }

    // Assign to group or user
    // priority has the user - if there is one given, take him
    $ass_user = '';
    $assigned = slookup('rts_cat', 'users', 'ID', $category);

    if ($assigned <> '') {
        $ass_user = $assigned;
        $acc_r    = 'private';
    }
    else $acc_r = 'group';

    // no special user given? -> look for a group
    $group = slookup('rts_cat', 'gruppe', 'ID', $category);
 	$datetime=$dbTSnull;
    $result = db_query(xss("INSERT INTO ".DB_PREFIX."rts
                                        (   ID,      contact,       email,   submit,     name,   note,   due_date, status, assigned, gruppe, acc_read, acc_write, von,acc)
                                 VALUES ($dbIDnull,'$cust_ID','$cust_email','$datetime','$name','$note','$due','1','$assigned','$group','$acc_r','w','$ass_user','2')")) or db_die();

    // fetch ID from this request
    $requestID = slookup('rts', 'ID', 'submit', $datetime);
 
    // mail the customer that his request is taken into the database
    if ($cust_email) {
        $success = $mail->go($cust_email, "Re: $name - ".__('Your request Nr. is')." $requestID",
                             stripslashes(__('Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.').
                             "\n\n".__('Request').": $name\n$note\n"), PHPR_RTS_MAIL);
    }

    // if the request is assigend to a single user, notify him as well
    if ($ass_user <> '') {
        $success = $mail->go(slookup('users', 'email', 'ID', $ass_user),
                             __('New request')." Nr. $requestID: $name",
                             stripslashes($note), PHPR_RTS_MAIL);
    }
    $output .= "<br />".__('Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.');
    $step = '';
}
// end of reception

// customer query - list of pending queries
if ($step == "c_q") {
    $output .= "
        <div class='inner_content'>
            <table class='ruler' width='90%'>
                <thead>
                    <tr>
                        <th>Nr.</th>
                        <th><b>".__('Customer')."</b></th>
                        <th><b>".__('Title')."</b></th>
                        <th><b>".__('Date')."</b></th>
                        <th>".__('Status')."</th>
                    </tr>
                </thead>
                <tbody>";

    $result = db_query("SELECT ID, contact, email, submit, recorded, name, note, due_date, status,
                               assigned, priority, remark, solution, solved, solve_time, acc, div1, div2, proj
                          FROM ".DB_PREFIX."rts
                         WHERE (status = '1' OR status = '2')
                           AND acc = 2
                      ORDER BY submit") or db_die();
    while ($row = db_fetch_row($result)) {
        if (PHPR_RTS_CUST_ACC and PHPR_CONTACTS) {
            $name = slookup('contacts', 'nachname', 'ID', $$row[1]);
        }
        else {
            $name = $row[2];
        }
        $output .= "
                    <tr>
                        <td>$row[0]&nbsp;</td>
                        <td>$name&nbsp;</td>
                        <td>$row[5]&nbsp;</td>
                        <td>$row[3]&nbsp;</td>
                        <td>$row[8]&nbsp;</td>
                    </tr>";
    }
    $output .= "
                </tbody>
            </table>
        </div>";
}

if ($step == "kb") {
    // The knowledge base!! :-)

    // list of solved requests
    // filter & items per page
    $output .= "
        <div class='filter_execute_bar'>
            <span class='filter_execute_bar'>
                <form style='display:inline;' action='./helpdesk_cust.php' method='post'>
                    <input type='hidden' name='".session_name()."' value='".session_id()."' />
                    <input type='hidden' name='up' value='$up' />
                    <input type='hidden' name='sort' value='$sort' />
                    <input type='hidden' name='step' value='kb' />
                    <b>".__('knowledge database')."</b>&nbsp;&nbsp;
                    ".__('Search').":
                    <input type='text' size='15' name='keyword' value='$keyword' />
                    ".__('at')."
                    <select name='filter'>
                        <option value='all'>".__('all fields')."</option>
                        <option value='name'>".__('Text')."</option>
                        <option value='note'>".__('Remark')."</option>
                        <option value='solution'>".__('Solution')."</option>
                    </select>
                    ".get_go_button()."
                </form>
            </span>
        </div>";

    // keyword
    if ($keyword) {
        if ($filter == "all") {
            $where = "AND (name LIKE '%$keyword%' OR note LIKE '%$keyword%' OR solution LIKE '%$keyword%')";
        }
        else {
            $where = "AND ".qss($filter)." LIKE '%$keyword%'";
        }
    }

    // define 'next' & 'previous' button
    $result = db_query("SELECT ID
                          FROM ".DB_PREFIX."rts
                         WHERE solution <> ''
                           AND acc = '2'
                               $where") or db_die();
    $liste = make_list($result);
    $output .= get_top_page_navigation_bar();
    $output .= '<div class="hline"></div>';

    // sort & direction
    if (!$sort) $sort = "name";
    if ($up == "1") {
        $direction = "ASC";
        $up2 = 0;
    }
    else {
        $direction = "DESC";
        $up2 = 1;
    }

    $output .= "
        <div class='inner_content'>
            <table class='ruler' width='90%'>
                <thead>
                    <tr>
                        <th>
                            <b><a href='./helpdesk_cust.php?step=kb&amp;sort=name&amp;up=$up2&amp;page=$page&amp;perpage=$perpage&amp;keyword=$keyword&amp;filter=$filter'><font color='#ffffff' size='2'>".__('Text')."</font></a></b>
                        </th>
                        <th>
                            <b><a href='./helpdesk_cust.php?step=kb&amp;sort=note&amp;up=$up2&amp;page=$page&amp;perpage=$perpage&amp;keyword=$keyword&amp;filter=$filter'><font color='#ffffff' size='2'>".__('Remark')."</font></a></b>
                        </th>
                        <th>
                            <b><a href='./helpdesk_cust.php?step=kb&amp;sort=solution&amp;up=$up2&amp;page=$page&amp;perpage=$perpage&amp;keyword=$keyword&amp;filter=$filter'><font color='#ffffff' size='2'>".__('Solution')."</font></a></b>
                        </th>
                    </tr>
                </thead>
                <tbody>";

    // query
    $result = db_query("SELECT name, note, solution
                          FROM ".DB_PREFIX."rts
                         WHERE solution <> '' AND acc = '2'
                               $where
                      ORDER BY ".qss($sort)." $direction") or db_die();

    while ($row = db_fetch_row($result)) {
        $int = 0;
        if ($b >= $page*$perpage and $b < ($page+1)*$perpage) {
            $text     = nl2br(html_out($row[0]));
            $remark   = nl2br(html_out($row[1]));
            $solution = nl2br(html_out($row[2]));
            $cnr      = $int;
            $output1  = '';
            tr_tag('');
            $output .= "
                    $output1
                        <td>$text&nbsp;</td>
                        <td>$remark&nbsp;</td>
                        <td>$solution&nbsp;</td>
                    </tr>";
        }
        $int++;
        $b++;
    }
    $output .= "
                </tbody>
            </table>
            <br />";

    // link back
    $output .= get_bottom_page_navigation_bar()."\n</div>\n";
}


if (!$step) {
    $output .= "
        <div class='inner_content'>
            <br />
            <form action='helpdesk_cust.php' method='post'>
                <input type='hidden' name='step' value='submit' />
                <div class='boxHeader'>".__('request form')."</div>
                <div class='boxContent'>
                    <br style='clear:both;' />";

    // authentication mode: customer has to be in contatcts list or any email adress is sufficient
    // in case the customer has to give his last name (PHPR_RTS_CUST_ACC = 1) or his email (PHPR_RTS_CUST_ACC = 2).

    // If it is an internal user, his last name/email will be inserted
    if (PHPR_RTS_CUST_ACC == 1)      $rts_cust_string = $user_name;
    else if (PHPR_RTS_CUST_ACC == 2) $rts_cust_string = $user_email;

    // show field with required authentication
    if (PHPR_RTS_CUST_ACC > 0) {
        $output .= '
                    <label class="formbody" for="rts_cust_string">'.__('Enter your keyword').'</label>
                    <input id="rts_cust_string" name="rts_cust_string" class="options" value="" type="text" />
                    <br style="clear:both;" />';
    }
    // otherwise the email is enough - internal the eamil of the user
    else {
        $output .= '
                    <label class="formbody" for="cust_email">'.__('Enter your email').'</label>
                    <input id="cust_email" name="cust_email" class="options" size="40" value="'.$user_email.'" type="text" />
                    <br style="clear:both;" />';
    }

    // give your request a name
    $output .= "
                    <label class='formbody' for='name'>".__('Give your request a name')."</label>
                    <input type='text' name='name' id='name' class='option' size='80' maxlength='80' />
                    <br style='clear:both;' />

                    <label class='formbody' for='note'>".__('Describe your request').":</label>
                    <textarea name='note' id='note' class='option' cols='65' rows='10'></textarea>
                    <br style='clear:both;' />";

    // select a category - if anyone is given!
    $result = db_query("SELECT ID, name
                          FROM ".DB_PREFIX."rts_cat") or db_die();
    while ($row = db_fetch_row($result)) {
        // only display this select box if a category is assigned
        if ($i2 == 0 and $row[0] > 0) {
            $output .= "
                    <label class='formbody' for='category'>".__('Category').": </label>
                    <select name='category' id='category' class='option'>\n";
        }
        // show the different categories as options
        $output .= "<option value='$row[0]'>$row[1]</option>\n";
        $i2++;
    }
    $output .= "
                    </select>
                    <br style='clear:both;' />\n";

    // is the customer allowed to set a due date?
    if (PHPR_RTS_DUEDATE) {
        $output .= "
                    <label class='formbody' for='due_date'>".__('Due date').": </label>
                    <select name='due_date' id='due_date' class='option'>
                        <option value=''></option>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='4'>4</option>
                        <option value='8'>8</option>
                    </select>
                    ".__('Days')."
                    <br style='clear:both;' />\n";
    }
    // submit button and end of the form
    $output .= get_go_button()."
                    <br style='clear:both;' />
                </div>
            </form>\n";
    // $output .= "<a href='helpdesk_cust.php?step=kb'>".__('Search the knowledge database')."</a>\n";
} // end of the request form

$output .= '
        </div>
    </div>
</div>

</body>
</html>
';

echo $output;


/**
 * initialize the helpdesk_cust stuff and make some security checks
 *
 * @return void
 */
function helpdesk_cust_init() {
    global $step, $category, $rts_cust_string, $name, $cust_email, $note, $output;
    global $user_name, $user_email;

    $output = '';

    $category        = $_REQUEST['category']        = (int) $_REQUEST['category'];
    $step            = $_REQUEST['step']            = xss($_REQUEST['step']);
    $rts_cust_string = $_REQUEST['rts_cust_string'] = xss($_REQUEST['rts_cust_string']);
    $name            = $_REQUEST['name']            = xss($_REQUEST['name']);
    $note            = $_REQUEST['note']            = xss($_REQUEST['note']);
    $cust_email      = $_REQUEST['cust_email']      = xss($_REQUEST['cust_email']);
    $user_name       = $_REQUEST['user_name']       = xss($_REQUEST['user_name']);
    $user_email      = $_REQUEST['user_email']      = xss($_REQUEST['user_email']);
}

?>
