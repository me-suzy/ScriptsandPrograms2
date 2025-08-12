<?php

// contacts.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, Norbert Ku:ck
// $Id: contacts.php,v 1.25 2005/06/29 15:38:47 alexander Exp $

$module = 'contacts';
$contextmenu = 1;
$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

contacts_init();

$_SESSION['common']['module'] = 'contacts';

if (!isset($action)) $action = (isset($cont_action)) ? $cont_action : 'contacts';
$action = xss($action);


if (PHPR_LDAP) {
    $include_path = $path_pre.'lib/ldap.php';
    include_once($include_path);
}

// fields for possible doublet scan:
$doublet_fields_all = array( 'vorname'  => __('First Name'),
                             'nachname' => __('Family Name'),
                             'firma'    => __('Company'),
                             'email'    => 'Email',
                             'strasse'  => __('Street'),
                             'plz'      => __('Zip code'),
                             'stadt'    => __('City'),
                             'land'     => __('Country') );

echo set_page_header();

if (!$mode) $mode = 'view';
else        $mode = xss($mode);

$ID = (int) $ID;
$justform = (int) $justform;

// fetch elements of the form from the db
require_once($path_pre.'lib/dbman_lib.inc.php');
$fields = build_array('contacts', $ID, $mode);
if ($justform != 1) {
    include_once($path_pre.'lib/navigation.inc.php');
    echo '<div class="outer_content">';
    echo '<div class="content">';
}
else echo '<div class="justformcontent">';
include_once('contacts_'.$mode.'.php');
if ($justform != 1) echo '</div>';
echo '</div>';

echo "\n</body>\n</html>\n";


/**
 * initialize the contacts stuff and make some security checks
 *
 * @return void
 */
function contacts_init() {
    global $ID, $mode, $mode2, $output;

    $output = '';

    $ID = $_REQUEST['ID'] = (int) $_REQUEST['ID'];

    if (!isset($_REQUEST['mode']) || !in_array($_REQUEST['mode'], array('view', 'forms', 'data', 'import_data', 'import_forms', 'import_patterns', 'profiles_data', 'profiles_forms', 'selector'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];

}

?>
