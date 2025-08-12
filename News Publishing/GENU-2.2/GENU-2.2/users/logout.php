<?php
// -------------------------------------------------------------
//
// $Id: logout.php,v 1.2 2004/12/31 21:42:00 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if ($_SESSION['user_id'])
{
// 	$_SESSION = array();
	session_destroy();
	success_template($lang['USERS_LOGOUT_SUCCESS']);
	header('Refresh: 3; URL= ./../index.php');
}
else
{
	error_template($lang['USERS_LOGOUT_ERROR']);
}

page_header($lang['USERS_LOGOUT_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'success');
page_footer();

?>