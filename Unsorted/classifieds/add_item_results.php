<?php

/* D.E. Classifieds v1.04
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: add_item.php            *
 * ---------                          *
 *                                    *
 **************************************/

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToLibDir').'func_validateForm.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');


$myDB = db_connect();

$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];

if (!$added || !isset($added) )
{ 
    $func_arg_arr = array("cat_id"=>$cat_id);
    $return_val = checkUser('add_item()', '', $func_arg_arr);
    if ($return_val)
    {   $return_val=true;   
        $the_location = cnfg('deDir').'add_item_results.php?added=1&val='.$return_val;
        header("Location: $the_location");
    }
    else
    {   $return_val=false; 
    }

}
else
{   checkUser('', '');
}

$content = array();

if ($val)
{
    $content[] = "echo 'Your item has been added successfully.<BR>';";
}
elseif (!$return_val)
{
    $content[] = "echo 'Addition of your item has failed because of the following error(s):<BR><BR>';";
    $content[] = 'echo $gbl["errorMessage"];';
    $content[] = 'echo \'<a href="javascript: onClick=history.go(-1)">Click here to try again.</a><BR>\'; ' ;
}

// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_add_item_results'));

db_disconnect($myDB);

# --- START FUNCTIONS ---

// ************* START FUNCTION add_item() *************

function add_item($user_id, $cat_id)
{ 
    GLOBAL $myDB, $gbl, $title, $description, $email, $visible_email, $category, 
           $main_cat, $sub_cat, $form_data1, $form_data1_name, $form_data1_size, $form_data1_type, $form_data2, $form_data2_name, $form_data2_size, $form_data2_type ;



    if ( validateForm() ) 
    {   $true_or_false = true;

        if ($visible_email=='on') 
        {   $visible_email = 'show'; 
        }
        else 
        {   $visible_email = 'hide'; 
        }


        $description = preg_replace("/\n/", "<BR>", $description);

        $timestamp = time();

        $image_exists="";
        if (  ( $form_data1 && $form_data1 != "none" )  
            || ( $form_data2 && $form_data2 != "none" ) )
        {   $image_exists = 'true'; }
        else
        {   $image_exists = 'false'; 
        }
     

        $title = addslashes($title);
        $description = addslashes($description);

        $query2 = 'INSERT INTO std_items(user_id,cat_id,title,the_desc,email,email_visible,image_exists,date_time) VALUES('.$user_id.','.$cat_id.",'$title','$description','$email','$visible_email','$image_exists', $timestamp)" ;


        $result2 = mysql_query($query2,$myDB);

        $item_id = mysql_insert_id();

        if (!$result2)
        {   $true_or_false = false ; 
            echo 'query2 - '.mysql_error().'<BR>';
        }

    /********
     $query3 = "UPDATE std_categories SET num_items=(num_items+1) 
             WHERE cat_id=$cat_id";
     $result3 = mysql_query($query3) ;
     if(!$result3)
      { $true_or_false = false ; 
        echo 'query3 - '.mysql_error().'<BR>';
      }

    ********/

        if ( ($form_data1 && $form_data1 != "none") 
            || ($form_data2 && $form_data2 != "none") )
        {   for ($i=0; $i<=2; $i++)
            {    $data = 'form_data'.($i+1);
                $name = 'form_data'.($i+1).'_name';
                $size = 'form_data'.($i+1).'_size';
                $type = 'form_data'.($i+1).'_type';
                $$size = ''.$$size;

                if ($$data && $$data != "none")
                {   $form_data = addslashes(fread(fopen($$data, "r"), filesize($$data)));
                    $query4 = 'INSERT INTO std_blob_images(item_id,bin_data,filename,filesize,filetype,timestamp) VALUES('.$item_id.',"'.$form_data.'",\''.$$name."','".$$size."','".$$type."', $timestamp)" ;
                    $result4 = mysql_query($query4, $myDB) ;
                    if (!$result4)
                    {   $true_or_false = false ; 
                        echo 'query4 - '.mysql_error().'<BR>';
                    }
                }

            }
        }

        return $true_or_false;

    } // end if( validateForm() )
    else
    {   return false;
    }

} // end function add_item()

// ************* END FUNCTION add_item() *************


?>

