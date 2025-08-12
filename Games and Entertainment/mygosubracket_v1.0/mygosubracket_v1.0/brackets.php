<?php
/**
* brackets.php
* ------------------------------------------------------------
* @project  MyGosuBracket
* @version  1.0
* @license  GPL
* @author   cagrET (Cezary Tomczak) <cagret@yahoo.com>
* @link     http://cagret.prv.pl
* ------------------------------------------------------------
*/

require 'kernel/config.php';

global $is_admin;
$is_admin = false;

//if login and password correct - add cookie
if (isset($_GET['login'])) {
    if (@$_POST['login'] == $admin['login'] && @$_POST['password'] == $admin['password']) {
        setcookie('admin[login]', $admin['login'], 0);
        setcookie('admin[password]', md5($admin['password']), 0);
        $_COOKIE['admin']['login']    = $admin['login'];
        $_COOKIE['admin']['password'] = md5($admin['password']);
    }
} elseif (isset($_GET['logout'])) {
    setcookie('admin[login]', '', time() - 3600);
    setcookie('admin[password]', '', time() - 3600);
    unset($_COOKIE['admin']);
}

//if cookie login and password ok - is_admin
if (isset($_COOKIE['admin'])) {
    if (@$_COOKIE['admin']['login'] == $admin['login'] 
        && @$_COOKIE['admin']['password'] == md5($admin['password'])) 
    {
        $is_admin = true;
    } else {
        $is_admin = false;
    }
} else {
    $is_admin = false;
}

include 'kernel/class.Brackets.php';
new brackets($db, $dsn['prefix'], $brackets_show, $is_admin);

?>