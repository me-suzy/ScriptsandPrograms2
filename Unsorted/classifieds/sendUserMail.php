<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */


/**************************************
 * File Name: sendUserMail.php        *
 * ---------                          *
 *                                    *
 **************************************/


// ************* START FUNCTION send_user_mail *************

function sendUserMail()
{
    $headers .= "From: <".cnfg('replyEmail').">\r\n";
    $headers .= "Reply-To: <".cnfg('replyEmail').">\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    

    if ($result) 
    {   if( mail($to, $subject, $message, $headers) )
        {   return true;
        }
    }

} // end function send_user_mail

// ************* END FUNCTION send_user_mail *************


?>