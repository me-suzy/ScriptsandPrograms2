<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: vars_gbl.php            *
 * ---------                          *
 *                                    *
 **************************************/

if (strtolower(substr(PHP_OS, 0, 3)) == 'win' )
{   $newLine = "\r\n" ; 
}
else
{   $newLine = "\n";
}


$gbl = array( 
   'loggedIn'         => false,
   'logInFailed'      => false,
   'user_name'        => '',
   'password'         => '',
   'errors'           => '',
   'try_register'     => false,
   'register_message' => '',
   'errorMessage'     => '',
   'return_val'       => true, 
   'newLine'          => $newLine
    ) ;

?>