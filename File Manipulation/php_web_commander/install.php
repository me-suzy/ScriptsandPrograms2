<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : index.php                                   |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 20/08/2004 17:48                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//

define('IN_PHPWC', true);
define('IS_INSTALL', false);

require_once('includes/classes/template.class.php');
$template = new template('templates');
$template -> set_unknowns();


$template -> set_file('install', 'install.tpl');

$template -> set_block("install", "install_form", "form");
$template -> set_block("install", "install_ok", "ok");

if ((int)$_POST['install']) {

	if ($_POST['path_to_script'] == '') {
		$template -> set_var('BGCOLOR_PATH_TO_SCRIPT' ,'#EFCA59');
		$show_install_form = true;
	}

	mysql_connect($_POST['DB_host'], $_POST['DB_user'], $_POST['DB_pass'])
		or ($connection_error = true);
	mysql_select_db($_POST['DB_name'])
		or ($databse_select_error = true);

	if ($connection_error || $databse_select_error) {
	
		$template -> set_var('BGCOLOR_DB_NAME', '#EFCA59');
		$template -> set_var('BGCOLOR_DB_USERNAME', '#EFCA59');
		$template -> set_var('BGCOLOR_DB_PASSWORD', '#EFCA59');
		$show_install_form = true;
	}
	if ($_POST['temp_dir'] == '' || !is_dir($_POST['temp_dir'])) {
	
		$template -> set_var('BGCOLOR_TEMP_DIR', '#EFCA59');
		$show_install_form = true;
	}
	if ($_POST['admin_username'] == '') {
	
		$template -> set_var('BGCOLOR_ADMIN_USERNAME', '#EFCA59');
		$show_install_form = true;
	}
	if ($_POST['admin_password'] == '') {
	
		$template -> set_var('BGCOLOR_ADMIN_PASSWORD', '#EFCA59');
		$show_install_form = true;
	}

} else {

	$show_install_form = true;
}

if ($show_install_form) {

	
	$cwd = getcwd();
	$cwd = str_replace("\\", "/", $cwd);
	$cwd = str_replace("//", "/", $cwd);

	$template -> set_var(array('PATH_TO_SCRIPT' => $cwd . '/',
							   'START_DIR'      => $DOCUMENT_ROOT,
							   'DB_SERVER'      => 'localhost',
							   'DB_NAME'        => 'phpwc',
							   'DB_USER'        => 'root',
							   'DB_PASS'        => '',
							   'TEMP_DIR'       => $cwd . '/tmp/',
							   'ADMIN_USER'     => 'admin',
							   'ADMIN_PASS'     => 'admin'
							  )
						);
	$template -> parse("form", "install_form");

} else {

	$sql_query_drop_table  = 'DROP TABLE IF EXISTS `phpwc_users`; ';
	$sql_query_build_table = ' CREATE TABLE `phpwc_users` ( `id` int( 11 ) NOT NULL AUTO_INCREMENT ,'
						   . ' `username` varchar( 32 ) NOT NULL default \'\','
						   . ' `password` varchar( 32 ) NOT NULL default \'\','
						   . ' `level` enum( \'1\', \'2\', \'3\', \'4\', \'5\' ) NOT NULL default \'1\','
						   . ' PRIMARY KEY ( `id` ) ,'
						   . ' UNIQUE KEY `username` ( `username` ) ) TYPE = MYISAM AUTO_INCREMENT =1;';
    $sql_query_add_admin   = ' INSERT INTO `phpwc_users` ( `id` , `username` , `password` , `level` ) '
        				   . ' VALUES ( 1, \'' . $_POST['admin_username'] . '\', \'' . md5($_POST['admin_password']) . '\', \'5\' );';


	$sql_result_drop_table = mysql_query($sql_query_drop_table);
	$tpl_drop_table = $sql_result_drop_table ? '<font color="#009933"> OK </font>' : '<font color="#FF0000"> ERROR ! </font>';
	$template -> set_var('DROP_TBL', $tpl_drop_table);

	$sql_result_build_table = mysql_query($sql_query_build_table);
	$tpl_build_table = $sql_result_build_table ? '<font color="#009933"> OK </font>' : '<font color="#FF0000"> ERROR ! </font>';
	$template -> set_var('BUILD_TBL', $tpl_build_table);

	$sql_result_add_admin = mysql_query($sql_query_add_admin);
	$tpl_add_admin = $sql_result_add_admin ? '<font color="#009933"> OK </font>' : '<font color="#FF0000"> ERROR ! </font>';
	$template -> set_var('ADD_ADMIN', $tpl_add_admin);

	$config_is_writable = is_writable('includes/config.php');
	if ($config_is_writable) {

		$handle = fopen('includes/config.php', 'w');
		
		$to_write .= "<?php\n";
		$to_write .= "//\n";
		$to_write .= "// +----------------------------------------------------------------------+\n";
		$to_write .= "// | Web Manager                                                          |\n";
		$to_write .= "// +----------------------------------------------------------------------+\n";
		$to_write .= "// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |\n";
		$to_write .= "// +----------------------------------------------------------------------+\n";
		$to_write .= "// +----------------------------------------------------------------------+\n";
		$to_write .= "// |   filename             : common.php                                  |\n";
		$to_write .= "// |   begin                : 20 07 2004                                  |\n";
		$to_write .= "// |   copyright            : (C) 2004 Dragos Protung                     |\n";
		$to_write .= "// |   email                : dragos@protung.ro                           |\n";
		$to_write .= "// |   last edit            : 23/08/2004 14:17                            |\n";
		$to_write .= "// |                                                                      |\n";
		$to_write .= "// |                                                                      |\n";
		$to_write .= "// +----------------------------------------------------------------------+\n";
		$to_write .= "// | Author: Protung Dragos <dragos@protung.ro>                           |\n";
		$to_write .= "// +----------------------------------------------------------------------+\n";
		$to_write .= "//\n";
		$to_write .= "\n\n";
		$to_write .= "// This file was autogenerated by PHP Web Commander. Please do not edit it.\n";
		$to_write .= "\n\n";
		$to_write .= "if ( !defined('IN_PHPWC') ) {  // Do not delete\n";
		$to_write .= "	die('Hacking attempt');\n";
		$to_write .= "}\n";
		$to_write .= "define('PHPWC_IS_INSTALLED', true);";
		$to_write .= "\n\n";
		$to_write .= "define('PHPWC_NEW_VERSION_ALERT', " . $_POST['get_notify'] . ");\n";
		$to_write .= "define('PHPWC_DIR', '" . $_POST['path_to_script'] . "');\n";
		$to_write .= "define('PHPWC_DB_HOST', '" . $_POST['DB_host'] . "'); // host for the database\n";
		$to_write .= "define('PHPWC_DB_NAME', '" . $_POST['DB_name'] . "'); // database name\n";
		$to_write .= "define('PHPWC_DB_USER', '" . $_POST['DB_user'] . "'); // database useer\n";
		$to_write .= "define('PHPWC_DB_PASS', '" . $_POST['DB_pass'] . "'); // database password\n";
		$to_write .= "define('PHPWC_TMP_DIR', '" . $_POST['temp_dir'] . "'); // yet not in use\n";
		$to_write .= "\n\n";
		$to_write .= "?>";
		
		fwrite($handle, $to_write);
		fclose($handle);
		
		$write_to_config = '<font color="#009933">OK </font>';
		$template -> set_var('WRITE_CONFIG', $write_to_config);
		
	} else {

		$write_to_config = '<br /><font color="#FF0000">File <b>includes/config</b> is not writable ! Please CHMOD it to 0777 !</font>';
		$template -> set_var('WRITE_CONFIG', $write_to_config);
	}
	
	if ($sql_result_build_table && $sql_result_add_admin && $config_is_writable) {

		$template -> set_var('INSTALL_OK', '<font color="#009933">Install succefull !! <br />Click <a class="ok" href="index.php">here</a> to run PHP Web Commander</font>');
	} else {

		$template -> set_var('INSTALL_OK', '<font color="#FF0000">There was an error while installing PHP Web Commander !!<br />Click <a class="error" href="javascript:history.go(-1)">here</a> to corect the install data</font>');
	}
	$template -> parse("form", "install_ok", true);
}

$template -> parse("out", 'install', true);
$template -> p("out");


?>