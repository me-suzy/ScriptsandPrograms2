<?php

// mail.php - PHProjekt Version 5.0
// copyright  ©  2000-2004 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $auth$
// $Id: mail.php,v 1.18.2.1 2005/09/06 08:42:08 fgraf Exp $

$module = 'mail';
$contextmenu = 1;

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

mail_init();

$_SESSION['common']['module'] = 'mail';

require_once($path_pre.'lib/dbman_lib.inc.php');


// no full mail client installed? -> only send mail possible
if (PHPR_QUICKMAIL == 1 and !$action) $mode = 'send_form';

if ($mode=='send_form') $js_inc[] = "src='../lib/fckeditor.js'>";
echo set_page_header();

require_once($path_pre.'lib/dbman_lib.inc.php');
// include the navigation
include_once($path_pre.'lib/navigation.inc.php');

// now the actual content
echo '
<div class="outer_content">
    <div class="content">
';

if ($mode=='view' or $mode=='forms') $fields = build_array('mail', $ID, $mode);
else if ($mode=='data')              $fields = build_array('mail', $ID, 'forms');
//print_r($field);
include_once('./mail_'.$mode.'.php');

echo '
    </div>
</div>

</body>
</html>
';


/**
 * initialize the mail stuff and make some security checks
 *
 * @return void
 */
function mail_init() {
    global $ID, $mode, $action, $output;

    $output = '';

    $ID     = $_REQUEST['ID']     = (int) $_REQUEST['ID'];
    $action = $_REQUEST['action'] = xss($_REQUEST['action']);

    if (!isset($_REQUEST['mode']) ||
        !in_array($_REQUEST['mode'], array('view', 'forms', 'data', 'accounts', 'down', 'fetch', 'forms',
                                           'list', 'options', 'rules', 'send', 'send_form', 'sender'))) {
        $_REQUEST['mode'] = 'view';
    }
    $mode = $_REQUEST['mode'];
}

?>
