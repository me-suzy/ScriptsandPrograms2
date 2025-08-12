<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$content = array();

$myDB = db_connect();

$verify = $HTTP_POST_VARS["verify"];
$temp_name = $HTTP_POST_VARS["temp_name"];
$confirmation_num = $HTTP_POST_VARS["confirmation_num"];


if (isset($verify) )
{   if ((isset($temp_name) && $temp_name !='') 
        && (isset($confirmation_num) && $confirmation_num !='') )
    {   if (verify_user() )
        {   if (doInsert() )
            {   delete_temp_user();
                // These two $gbl values are set in doInsert() 
                logIn($gbl["user_name"], $gbl["password"]) ;
                header("Location: ".cnfg('deHome') );
            }
            else
            {   #$content[] = 'echo $gbl["errors"].\'<BR>\' ;' ;
                $content[] = 'verify_form($gbl[\'errors\']);' ;
            }
        }
        else
        {   #$content[] = 'echo "Wrong username/number combination.<BR>";' ;
            $gbl['misc']['verify_msg'] = 'Wrong username/number combination.' ;
            $content[] = 'verify_form($gbl[\'misc\'][\'verify_msg\']);' ;
        }
    }
    else
    {   #$content[] = 'echo "You must enter a username and confirmation number.<BR>";' ;
        $gbl['misc']['verify_msg'] = 'You must enter a username and confirmation number.' ;
        $content[] = 'verify_form($gbl[\'misc\'][\'verify_msg\']);' ;
    }
}
else
{   $content[] = 'verify_form();' ;
}


// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_verify_registration'));


db_disconnect($myDB);



# ------ START FUNCTIONS -------


// ************** START FUNCTION verify_user() *************

function verify_user()
{
    GLOBAL $myDB, $HTTP_POST_VARS;    

    $temp_name = $HTTP_POST_VARS["temp_name"];
    $confirm_num = ''.$HTTP_POST_VARS["confirmation_num"];

    $temp_name = trim($temp_name);
    $confirm_num = trim($confirm_num);

    $query = "SELECT COUNT(*) as num FROM std_temp_users 
              WHERE user_name='$temp_name' 
              AND prelim_rand='$confirm_num' ";

    $result = mysql_query($query);

    if ($result)
    {   $row = mysql_fetch_array($result) ;
        if ($row["num"]==1 )
        {   return true ;
        }
        else
        {   return false;
        }  
    }
    else
    {   return false ; 
    }


} // end verify_user() 

// ************** END FUNCTION verify_user() *************


// ************** START FUNCTION verify_form() *************

function verify_form($dsply_msg='')
{
    echo '<CENTER>'."\n";
    echo '<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0">'."\n";
    echo '<TR>'."\n";
    echo '<TD VALIGN="TOP">'."\n";


    if ($dsply_msg=='')
    {   echo 'Please enter the username and confirmation number that were ';
        echo 'emailed to you.<BR>'."\n";
    }
    else
    {   echo $dsply_msg.'<BR>' ; 
    }

    echo ' <FORM NAME="verify_reg_form" ACTION="verify_registration.php" METHOD="POST">'."\n";
    echo ' <table cellpadding="0" cellspacing="0" border="0">'."\n";
    echo ' <tr>'."\n";
    echo ' <td valign="top" align="right">'."\n";
    echo '  Username:&nbsp;'."\n";
    echo ' </td>'."\n";
    echo ' <td valign="top">'."\n";
    echo '  <INPUT TYPE="TEXT" NAME="temp_name">'."\n";
    echo ' </td>'."\n";
    echo ' </tr>'."\n";
    echo ' <tr>'."\n";
    echo ' <td valign="top" align="right">'."\n";
    echo '  Confirmation number:&nbsp;'."\n";
    echo ' </td>'."\n";
    echo ' <td valign="top">'."\n";
    echo '  <INPUT TYPE="TEXT" NAME="confirmation_num">'."\n";
    echo ' </td>'."\n";
    echo ' </tr>'."\n";
    echo ' <tr>'."\n";
    echo ' <td valign="top">'."\n";
    echo '  &nbsp;'."\n";
    echo ' </td>'."\n";
    echo ' <td valign="top" align="left">'."\n";
    echo '  <INPUT TYPE="SUBMIT" NAME="submit" value="Submit">'."\n";
    echo ' </td>'."\n";
    echo ' </tr>'."\n";
    echo ' </table>'."\n";
    echo ' <INPUT TYPE="HIDDEN" NAME="verify" VALUE="1">'."\n";
    echo '  </FORM>'."\n";

    echo '</TD>'."\n";
    echo '</TR>'."\n";
    echo '</TABLE>'."\n";

    echo '</CENTER>'."\n";

} // end function verify_form()

// ************** END FUNCTION verify_form() *************


 // ************* START FUNCTION delete_temp_user() *************

function delete_temp_user()
{
    GLOBAL $myDB, $HTTP_POST_VARS ;
 
    $temp_name = $HTTP_POST_VARS["temp_name"];
    $confirm_num = $HTTP_POST_VARS["confirmation_num"];

    $temp_name = trim($temp_name);
    $confirm_num = trim($confirm_num);

    $query = "DELETE FROM std_temp_users
              WHERE user_name='$temp_name' 
              AND prelim_rand='$confirm_num'" ;

    $result = mysql_query($query,$myDB);


} // end function delete_temp_user() 

// ************* END FUNCTION delete_temp_user() *************


// *********** START FUNCTION doInsert() *************

function doInsert()
{ 
    GLOBAL $myDB, $gbl, $HTTP_POST_VARS ;

    $temp_name = $HTTP_POST_VARS["temp_name"];
    $confirm_num = ''.$HTTP_POST_VARS["confirmation_num"];;

    $temp_name = trim($temp_name);
    $confirm_num = trim($confirm_num); 


    $query = "SELECT user_name, password, email, sign_up_date 
              FROM std_temp_users 
              WHERE user_name='$temp_name' 
              AND prelim_rand='$confirm_num' ";

    $result = mysql_query($query,$myDB);

    if (!$result)
    {   $gbl["errors"] .=  'query - Failed to get user info.<BR>'; 
    }

 
    $row = mysql_fetch_array($result);
    $user_name = $row["user_name"];
    $gbl["user_name"] = $row["user_name"];

    $password = $row["password"];
    $gbl["password"] = $row["password"];

    $email = $row["email"];
    $sign_up_date = $row["sign_up_date"];

    $query2 = "INSERT INTO std_users(user_name,password,email,sign_up_date) VALUES('$user_name','$password','$email', ".$sign_up_date.')' ;
    $result2 = mysql_query($query2,$myDB);

    if (!$result2)
    {   $gbl["errors"] .= 'query2 - Failed to insert info.<BR>' ; 
    }



    if ( preg_match("/duplicate entry/i", mysql_error()) )
    {   $gbl["errors"] .= "Please choose another user name.<BR>
                           That one is already taken.";


        $query3 = "DELETE FROM std_temp_users
                   WHERE user_name='$temp_name' 
                   AND prelim_rand='$confirm_num' ";

        $result3 = mysql_query($query3,$myDB);

        return false;

    }

    return true;

} // end function doInsert()

// *********** END FUNCTION doInsert() *************

?>
