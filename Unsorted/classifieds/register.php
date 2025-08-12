<?php

/* D.E. Classifieds v1.04  
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: register.php            *
 * ---------                          *
 *                                    *
 **************************************/

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');
 
$myDB = db_connect();
 
$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];

$content = array();

$submit = $HTTP_POST_VARS["submit"];

if ( !checkUser('', '') )
{   if ( $submit && $submit != 'validate' && checkForm() )
    {   $gbl["try_register"] = true;
        if ( prelim_insert() )
        {   $gbl["register_message"] = 'A registration key has been emailed to  the address   that you supplied.<BR>Follow the instructions in the email to verify your registration.<BR>' ; 
        }
        else
        {   $gbl["register_message"] = 'Preliminary registration failed.<BR>'.$gbl["register_message"];
        } 
    }
  
}


if ($gbl["loggedIn"])
{   $content[] = "echo 'You are already logged into the system.';";
}
elseif (!$submit)
{   $content[] = "echo 'Only numbers and letters and the underscore character( _ ) are allowed in user names.<BR>';";
    $content[] = 'doForm();';
}
elseif ($gbl["errors"]!="")
{   $content[] = "echo '<font class=\"errorBig\">Errors:<BR></font>';";
    $content[] = "echo '<font class=\"errorLittle\">';" ;
    $content[] = 'echo $gbl["errors"] ;' ;
    $content[] = 'echo "</font><BR>";' ;
    #echo $content[(count($content)-1)]."<BR>";
    $content[] = 'doForm();';
} 
elseif ($gbl["try_register"])
{   $content[] = 'echo $gbl["register_message"];';
}

// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_register'));

db_disconnect($myDB);


# --- START FUNCTIONS ---


// *********** START FUNCTION prelim_insert() *************

function prelim_insert()
{ 
    GLOBAL $gbl, $myDB, $HTTP_POST_VARS ;

    $new_user_name = $HTTP_POST_VARS["new_user_name"];
    $email = $HTTP_POST_VARS["email"];
    $password1 = $HTTP_POST_VARS["password1"];


    $query = "SELECT user_name FROM std_users 
              WHERE user_name='$new_user_name'";

    $result = mysql_query($query, $myDB);

    if (mysql_num_rows($result) > 0)
    {   $gbl["register_message"] .= 'Sorry that user name is already taken, please try another one.<BR>';
        return false;
    }

    srand((double)microtime()*1000000);

    $the_rand = rand(1, 10000);
    $the_rand2 = rand(1, 10000);
    $the_rand3 = rand(1, 10000);

    $the_rand = ''.$the_rand.''.$the_rand2.''.$the_rand3;

    $query = "INSERT INTO std_temp_users(user_name, password, email, sign_up_date, prelim_rand) VALUES('$new_user_name', '$password1', '$email', ".time().", '$the_rand')";

    $result = mysql_query($query, $myDB);

    if (!$result) 
    {   if ( preg_match("/duplicate entry/i", mysql_error()) )
        {   $gbl["errors"] .= "Please choose another user name.<BR>
                               That one is already taken.";
        }

        return false;
    }

    $to = $email;
    $subject = 'Your Registration';

    $the_link = cnfg('deDir').'verify_registration.php';

    $message = '<html><body>
    Please follow these instructions to verify your registration:
    <BR>
    1. Click the link below.
    <BR>
    2. Enter the username and the confirmation number that are located below this line.
    <BR>
    <BR>
    Note: After verifying your registration you will login using your username and password that you provided. 
    <BR>
    The confirmation number below is only used to confirm your registration.
    <BR>
    <BR>
    Username: '.$new_user_name.'
    <BR>
    Confirmation number:  '.$the_rand.'
    <BR>
    <BR>
     ' ;

    $message .= "<a href=\"$the_link\">$the_link</a>";

    $message .= '</body></html>';


    #$headers = "MIME-Version: 1.0\r\n";
    #$headers .= "Return-Path: <".cnfg('replyEmail').">\r\n";
    #$headers .= "X-Sender: <".cnfg('replyEmail').">\r\n"; 
    #$headers .= "X-Mailer: PHP\r\n"; // mailer
    $headers .= "From: <".cnfg('replyEmail').">".$gbl['newLine'];
    $headers .= "Reply-To: <".cnfg('replyEmail').">".$gbl['newLine'];
    $headers .= "Content-type: text/html; charset=iso-8859-1".$gbl['newLine'];
    

    if ($result) 
    {   if ( mail($to, $subject, $message, $headers) )
        {   return true;
        }
	    else
	    {   echo 'error sending mail<BR>';
		    return false;
	    }
    }
    else 
    {   return false;
    }

} // end function prelim_insert()

// *********** END FUNCTION prelim_insert() *************


// *********** START FUNCTION doForm() *************

function doForm()
{
    GLOBAL $HTTP_POST_VARS ;

    $new_user_name = $HTTP_POST_VARS["new_user_name"];
    $password1 = $HTTP_POST_VARS["password1"];
    $password2 = $HTTP_POST_VARS["password2"];
    $email = $HTTP_POST_VARS["email"];
  
    if (!$submit || $errors!="")
    {
        ?>

        <FORM ACTION="<?php echo cnfg('deDir'); ?>register.php" METHOD="POST">
        <table cellpadding="0" cellspacing="0" border="0">
        <BR>
        <tr><td valign="top" align="right">
        User Name:
        </td>
        <td valign="top" align="right">
        <INPUT TYPE="TEXT" NAME="new_user_name" value="<?php echo $new_user_name; ?>" SIZE="15">
        </td></tr>
        <tr><td valign="top" align="right">
        Password:
        </td>
        <td valign="top" align="right">
        <INPUT TYPE="PASSWORD" NAME="password1" value="<?php echo $password1; ?>" SIZE="15">
        </td></tr>
        <tr><td valign="top" align="right">
        Type password again:
        </td>
        <td valign="top" align="right">
        <INPUT TYPE="PASSWORD" NAME="password2" value="<?php echo $password2; ?>" SIZE="15">
        </td></tr>
        <tr><td valign="top" align="right">
        Email Address:
        </td>
        <td valign="top" align="right">
        <INPUT TYPE="TEXT" NAME="email" value="<?php echo $email; ?>" SIZE="15">
        </td></tr>
        <tr><td valign="top" align="right">
        &nbsp;
        </td>
        <td valign="top" align="right">
        <INPUT TYPE="SUBMIT" NAME="submit" VALUE="Submit">
        </td></tr></table>

        </FORM>

        <?php 

    } // end if

} // end function doForm()

// *********** END FUNCTION doForm() *************

// *********** START FUNCTION checkForm() *************
function checkForm()
{
    GLOBAL $gbl, $HTTP_POST_VARS ;


    $new_user_name = $HTTP_POST_VARS['new_user_name'];
    $password1 = $HTTP_POST_VARS['password1'];
    $password2 = $HTTP_POST_VARS['password2'];
    $email = $HTTP_POST_VARS['email'];
    $submit = $HTTP_POST_VARS['submit'];

    if ($submit)
    {   if ($new_user_name)
        {   if ( preg_match("/[^0-9a-zA-Z_]/", $new_user_name ) )
            {   $gbl['errors'] .= '&#149; Invalid characters<BR>' ;
                $new_user_name='';
            }

            if ( (strlen($new_user_name) < cnfg('userNameMinLength') )
                 || (strlen($new_user_name) > cnfg('userNameMaxLength') ) )
            {   $gbl['errors'] .= '&#149; User names must be less than ';
                $gbl['errors'] .= cnfg('userNameMaxLength').' and greater than ';
                $gbl['errors'] .= cnfg('userNameMinLength').' characters long.<BR>'; 
            }


        }
        else
        {   $gbl['errors'] .= '&#149; You didn\'t supply a user name.<BR>';
        }


        if($password1 && $password2)
        {   if($password1 != $password2)
            {   $gbl['errors'] .= '&#149; Passwords don\'t match<BR>';
                $password1 = ''; 
                $password2 = '';
            }

            if ( (strlen($password1) < cnfg('userPassMinLength') )
                 || (strlen($password1) > cnfg('userPassMaxLength') ) )
            {   $gbl['errors'] .= '&#149; Passwords must be less than ';
                $gbl['errors'] .= cnfg('userPassMaxLength').' and greater than ';
                $gbl['errors'] .= cnfg('userPassMinLength').' characters long.<BR>';
            }
        }
        else
        {   $gbl['errors'] .= '&#149; You must provide a password.<BR>';
            $password1 = ''; 
            $password2 = '';
        }


        if ($email)
        {   if ( !preg_match("/([\w\-\.])+@([\w\-\.])+\.([a-zA-Z])+/i", $email) )
            {   $gbl['errors'] .= '&#149; Invalid email address.<BR>';
                $email = '';
            }
        }
        else
        {   $gbl['errors'] .= '&#149; You must provide an email address.<BR>';
        }
    } // end if($submit) 


    if ($gbl['errors'] != '')
    {   return false; 
    }
    else
    {   return true; 
    }

} // end function checkForm()

// *********** END FUNCTION checkForm() *************

?>
