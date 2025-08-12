<?php
/**
* kernel/config.php
* ------------------------------------------------------------
* @project  MyGosuBracket
* @version  1.0
* @license  GPL
* @author   cagrET (Cezary Tomczak) <cagret@yahoo.com>
* @link     http://cagret.prv.pl
* ------------------------------------------------------------
*/

//database connect informations
//mostly u have to change only: username, password, database
$dsn = array
(
    'host'     => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'mygosubracket',
    'prefix'   => 'mgb_',
    'db_type'  => 'mysql'
);

//admin login and password
$admin['login']    = 'admin';
$admin['password'] = 'a';

//configuration
//how many brackets per page - show
$brackets_show = 50;

//connect to database
include 'kernel/db_mysql.inc';

$db =& new DB_Sql;
$db->connect($dsn['database'], $dsn['host'], $dsn['username'], $dsn['password']);

?>