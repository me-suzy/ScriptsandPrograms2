<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : index.php                                   |
// |   begin                : 22 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 24/08/2004 14:08                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//


define('IN_PHPWC', true);
// include required files
require_once('includes/common.php');

if (USER_LEVEL != 5) {

	die('You do not have access rights. Please contact your adminstrator');
}

// set need templates files
$template -> set_file(array('head'   => 'head.tpl',
							'body'   => 'usermanagement.tpl',
							'footer' => 'footer.tpl'
							)
					);
$template -> set_block("body", "usersrow", "usersrows");

$link = @mysql_connect(PHPWC_DB_HOST, PHPWC_DB_USER, PHPWC_DB_PASS) or 
	die('Could not connect to database !');


if ($_GET['edit'] == 'delete') {

	$sql_detele    = 'DELETE FROM `phpwc_users` WHERE `id`=\'' . $_GET['user'] . '\' LIMIT 1';
	$result_delete = mysql_query($sql_detele);
}

if ($_GET['edit'] == 'changepass') {

	$sql_changepass = 'UPDATE `phpwc_users` SET `password` = \'' . $_GET['newvalue'] . '\' WHERE `id` = \'' . $_GET['user'] . '\' LIMIT 1 ';
	$result_changepass = mysql_query($sql_changepass);
}

if ($_GET['edit'] == 'changeaccess') {

	$sql_changepass = 'UPDATE `phpwc_users` SET `level` = \'' . $_GET['newvalue'] . '\' WHERE `id` = \'' . $_GET['user'] . '\' LIMIT 1';
	$result_changepass = mysql_query($sql_changepass);
}
if ($_GET['edit'] == 'add') {

	$sql_add  = ' INSERT INTO `phpwc_users` ( `id` , `username` , `password` , `level` ) ';
	$sql_add .= ' VALUES ( \'\', \'' . $_GET['user'] . '\', \'' . $_GET['pass'] . '\', \'' . $_GET['level'] . '\' ); ';
	$result_add = mysql_query($sql_add);
}

$query_users = 'SELECT * FROM `phpwc_users`';
$result_users = @mysql_query($query_users);

while ($user_data = @mysql_fetch_row($result_users)) {

	$template -> set_var(array('ID'       => $user_data[0],
							   'USERNAME' => $user_data[1],
							   'PASSWORD' => $user_data[2],
							   'LEVEL'    => $user_data[3],
							   'DELTE'    => 'Delete',
							  )
						);
	$template -> parse("usersrows", "usersrow", true);
}

@mysql_close($link);

$template -> parse("out", 'head', true);
$template -> parse("out", 'body', true);
$template -> parse("out", 'footer', true);
$template -> p("out");

?>