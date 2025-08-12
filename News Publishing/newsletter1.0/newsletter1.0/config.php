<?php 
/**
*    
*    @project: newsletter 
*    @file:    config.php
*    @version: 1.0
*    @author:  Konstantin Atanasov
*
*
NO WARRANTY
 BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, 
 THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES 
 PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
 THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. 
 THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, 
 REPAIR OR CORRECTION.
*/


//
// database connection properties
//
$db_host = "localhost";

$db_name = "database name";

$db_user_name = "user name";

$db_user_password = "password";

// open mysql db connection
    $con = @mysql_connect($db_host,$db_user_name,$db_user_password);
    @mysql_select_db($db_name,$con);
    
    

//
//    admin panel properties
//
$admin_user_name = "admin";        // set admin user name

$admin_user_password = "adminpas";     // set admin password


// url

define("ENEWSLETTER_URL","url where newsletter installed"); // url where script is installed

?>
