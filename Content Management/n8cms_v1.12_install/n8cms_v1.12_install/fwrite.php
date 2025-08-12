<?php
error_reporting(E_ALL &~E_NOTICE &~E_WARNING);

$db_name=$_GET['db_name'];
$db_user=$_GET['db_user'];
$db_pass=$_GET['db_pass'];
$db_host=$_GET['db_host'];
$cms_path=$_GET['cms_path'];
/*
if ( (!$db_name)|| (!$db_user)|| (!$db_pass)|| (!$db_host) ){
	echo" you must fill in the following info:";
	if (!$db_name){echo"please enter a database name<br>";}
	if (!$db_user){echo"enter a your user name<br>";}
	if (!$db_pass){echo"enter your password<br>"; }
	if (!$db_host){echo"enter your host name or path (eg: localhost, 127.0.0.1)<br>";}

}
else{*/
$filename = '_.php';
$somecontent = "<?
define(\"DB_NAME\", \"".$db_name."\");
define(\"DB_USER\", \"".$db_user."\");
define(\"DB_PASS\", \"".$db_pass."\");
define(\"DB_HOST\", \"".$db_host."\");
define(\"cms_path\", \"".$cms_path."\");

error_reporting(E_ALL &~E_NOTICE &~E_WARNING);

mysql_pconnect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
mysql_select_db(DB_NAME);
?>";

// Let's make sure the file exists and is writable first.


if (is_writable($filename)) {
    // In our example we're opening $filename in append mode.
    // The file pointer is at the bottom of the file hence 
    // that's where $somecontent will go when we fwrite() it.
    if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
    }
    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }
    echo "Success, wrote to file ($filename)<br><a href=setup.php>continue</a>";
    fclose($handle);
} else {
    echo "The file $filename is not writable";
}


?> 