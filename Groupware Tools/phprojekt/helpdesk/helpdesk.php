<?php

// helpdesk.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: helpdesk.php,v 1.26.2.1 2005/08/20 08:34:22 nina Exp $

$module = 'helpdesk';
$contextmenu = 1;

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

helpdesk_init();

$_SESSION['common']['module'] = 'helpdesk';

// check whether there is a general rts mail in the config. if not, assign give the email of the user
if (!PHPR_RTS_MAIL) $rts_mail = $user_email;
else                $rts_mail = PHPR_RTS_MAIL;


// access: = = n/a, 1 = internal, 2 = open. only solved requests with access = 2 are in the knowledge base
$access = array( '0' => __('internal'), '2' => __('open') );

// database cols according to user role
$recipient_column = '9';  // to whom the ticket is assigned to
$author_column    = '21'; // who has checked in this ticket


// helpdesk states
// field status:  0 = optional, 1 = mandatory, 2 = remove from workflow
$helpdesk_states = array(
    array('mandatory' => 0, 'key' => '1', 'label' => __('unconfirmed')),
    array('mandatory' => 0, 'key' => '2', 'label' => __('new')),
    array('mandatory' => 1, 'key' => '3', 'label' => __('assigned')),
    array('mandatory' => 0, 'key' => '4', 'label' => __('reopened')),
    array('mandatory' => 0, 'key' => '5', 'label' => __('solved')), /* this state is a must state -> after reaching this state customers will be notified */
    array('mandatory' => 0, 'key' => '6', 'label' => __('verified')),
    array('mandatory' => 1, 'key' => '7', 'label' => __('closed'))
);


$status_arr = array();
$hd_i = 1;
foreach($helpdesk_states as $hd_state) {
    $status_arr[$hd_i] = array ($hd_state['key'], array($recipient_column, $author_column), $hd_state['mandatory'], $hd_state['label']);
    $hd_i++;
}

require_once($path_pre.'lib/dbman_lib.inc.php');

if (!$mode) $mode = "view";
else        $mode = xss($mode);

$ID = (int) $ID;

$fields = build_array('helpdesk', $ID, $mode);

$output = '';
echo set_page_header();

include_once($path_pre.'lib/navigation.inc.php');
echo '<div class="outer_content">';
echo "<div class='content'>";
include_once('./helpdesk_'.$mode.'.php');
echo '</div>';
echo '</div>';
echo "\n</body>\n</html>\n";

/**
 * initialize the helpdesk stuff and make some security checks
 *
 * @return void
 */
function helpdesk_init() {
    global $ID, $mode, $output;

    $output = '';

    $ID = $_REQUEST['ID'] = (int) $_REQUEST['ID'];

    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];
}

?>
