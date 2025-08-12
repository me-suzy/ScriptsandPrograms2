<?
// Only the login, password, and database variables need to be changed by replacing the capitolized words with the correct values.

//Login Name for MySQL.
$login = "LOGIN";

//Password for MySQL.
$pass = "PASSWORD";

//MySQL Database name.

$db = "DATABASE";

/*
***********************************
 Nothing bewlow this notice
 needs to be changed.
***********************************
*/

$host = "localhost"; //Host of MySQL - leave on localhost if you don't know

// Database Table Names - almost never need to be changed.

$conf['tbl']['grades'] = 'ds_grades';
$conf['tbl']['projecttable'] = 'ds_projecttable';
$conf['tbl']['studentwork'] = 'ds_studentwork';
$conf['tbl']['teachers'] = 'ds_teachers';

$conf['tbl']['homework'] = 'ds_homework';
$conf['tbl']['projecthomework'] = 'ds_projecthomework';

$conf['tbl']['announcements'] = 'ds_announcements';

//takes out slashes if if the magic quotes are on.
function deslash($string)
{
   if (get_magic_quotes_gpc())
       $string = stripslashes($string);
   return $string;
}

//adds slashes if the magic quotes is off.
function addslash($string)
{
   if (!get_magic_quotes_gpc())
       $string = addslashes($string);
   return $string;
}


error_reporting(E_ALL ^ E_NOTICE); // Disable reports of notices
?>
