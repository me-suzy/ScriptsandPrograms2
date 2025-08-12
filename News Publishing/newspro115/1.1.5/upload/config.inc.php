<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

// +------------------------------------------------------------------+
// | MySQL Configuration                                              |
// +------------------------------------------------------------------+

/********************************************************
 If you are unsure of the values you need to enter here,
 then contact your webhost to obtain these values.
********************************************************/

$config = array(

	'hostname' => 'localhost', // The hostname or IP address to database server - usually just 'localhost'

	'database' => 'utopianews', // The name of the MySQL database where Utopia News Pro is, or will be, installed

	'user' => 'root', // The username of a MySQL account that has access to the Utopia News Pro database

	'password' => '', // The password of the database account

	'persistent' => '0', // Do you want to use persistent database connections? (0 = NO, 1 = YES)

	'techemail' => 'user@domain.com', // Email address to the technical administrator of the site - database errors will be sent here
);
/*
 * Ensure that there are *NO* spaces/lines before the <?php
 * at the beginning of this file and *NO* spaces/lines after the ?>
 * at the end of this file.
 */
?>