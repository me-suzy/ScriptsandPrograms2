<?php
include $home."ressourcen/class.systemObject.php";
include $home."ressourcen/class.DatabaseMysql.php";
$db = new DatabaseMysql($config->dbuser[$HTTP_SESSION_VARS['which_db']], $config->dbpass[$HTTP_SESSION_VARS['which_db']], $config->dbserver[$HTTP_SESSION_VARS['which_db']]);
$ok = $db->init();

if (!$ok and $db->error and (strlen($config->dbserver[1]) > 0)) {
	echo $db->getError();
}

?>