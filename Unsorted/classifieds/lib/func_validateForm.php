<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

// *************** START FUNCTION validateForm() ***************

function validateForm()
{
    GLOBAL $gbl, $title, $description, $email, $form_data1, $form_data1_name, $form_data2, $form_data2_name ;

    #echo 'In validateForm() !!!<BR>';
    $trueOrFalse = true;
    $varsArr = array($title, $description, $email);
    $wrongArr = array('Title', 'Description', 'Email', 'Image');

    #echo 'In validateForm()<BR>';

    for($i=0; $i<count($wrongArr); $i++) 
    {
        if ( $i==0 || $i==1 )
        {   $theReg = "/[\w]/"; 
            if ( !preg_match($theReg, $varsArr[$i]) )
            {   $gbl["errorMessage"] .= "$wrongArr[$i] field is empty.<BR>";
                $trueOrFalse = false;
            }
        }
        elseif ($i==2)
        {   $theReg = "/([\w\-\.])+@([\w\-\.])+\.([a-zA-Z])+/i"; 
            if ( !preg_match($theReg, $varsArr[$i]) )
            {   $gbl["errorMessage"] .= 'Invalid email address.<BR>';
                $trueOrFalse = false;
            }
        }
        elseif ( $i==3 )
        {   if  ( !check_file_size() )
            {   $trueOrFalse = false;
            }
        }

    } // end for
  
    return $trueOrFalse;

} // end function validateForm()

// *************** END FUNCTION validateForm() ***************

// **************** START FUNCTION check_file_size ************

function check_file_size()
{
    GLOBAL $gbl, $form_data1, $form_data1_name, $form_data2, $form_data2_name ;

    #echo 'In check_file_size() !!!!<BR>';

    $fd1_size=0;
    $fd2_size=0;
    $fd1_too_big = false;
    $fd2_too_big = false;

    $trueOrFalse = true;

    if ( $form_data1 && $form_data1 != "none" )
    {   $fd1_size = filesize($form_data1);
        if ($fd1_size > 100000 )
        {   $fd1_too_big = true;
        }
    } 


    if ( $form_data2 && $form_data2 != "none" )
    {   $fd2_size = filesize($form_data2);
        if ($fd2_size > 100000 )
        {   $fd2_too_big = true;
        }
    }

     
    if ( $fd1_too_big || $fd2_too_big)
    {   $gbl["errorMessage"] .= 'The following images are too big:<BR>';
        if ($fd1_too_big)
        {   $gbl["errorMessage"] .= $form_data1_name;
        }

        if ($fd2_too_big)
        {   if($fd1_too_big)
            {  $errorMessage .= ', '; 
            }

            $gbl["errorMessage"] .= $form_data2_name;
        }

        $gbl["errorMessage"] .= '<BR>';
        $trueOrFalse = false;
    }

    return $trueOrFalse;

} // end function check_file_size()

// **************** END FUNCTION check_file_size ************

?>