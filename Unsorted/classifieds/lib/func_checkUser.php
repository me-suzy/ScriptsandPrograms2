<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: func_checkUser.php      *
 * ---------                          *
 *                                    *
 **************************************/

// *************** START FUNCTION checkUser ***************

function checkUser($which, $msg, $func_arg=array())
{
    GLOBAL $gbl, $myDB, $cookie  ;

    if ($cookie)
    {   $uid = $cookie["user"];
        $cid = $cookie["id"];
  

        $query = "SELECT user_id,user_name FROM std_users WHERE user_name='$uid' AND cookie_id='$cid'" ;
        $result = mysql_query($query, $myDB);
        $row = mysql_fetch_array($result);
        $gbl["user_id"] = $row['user_id'];
        $gbl["user_name"] = $row['user_name'];
        $num_rows = mysql_num_rows($result);

        if($num_rows == 1)
        {
            if($which=='editForm()')             { editForm(); }
            elseif($which=='edit()')             { edit(); }
            elseif($which=='confirm_delete()')   { confirm_delete(); }
            elseif($which=='do_delete()')        { do_delete(); }
            elseif($which=='showItems()')        { showItems(); }
            elseif($which=='doFirst()')          { doFirst(); } 
            elseif($which=='doCategory()')       { doCategory(); }
            elseif($which=='show_cats()')        { show_cats(); }
            elseif($which=='ask_to_add($cat_id)' ) 
            {   ask_to_add($func_arg["cat_id"]); 
            }
            elseif($which=='add_item_form($cat_id)' ) 
            {   add_item_form($func_arg["cat_id"]); 
            }
            elseif($which=='get_tree(select_to_add)' ) 
            {   get_tree('select_to_add'); 
            }
            elseif($which=='add_item()')        
            {   $gbl["loggedIn"] = true;
                $trueOrFalse=add_item($gbl["user_id"], $func_arg["cat_id"]);
                return $trueOrFalse;
            }
            elseif($which=='sendUserMailForm($cat_id, $item_id, $owner_id)')
            {   sendUserMailForm($func_arg["cat_id"], $func_arg["item_id"], $func_arg["owner_id"]);
	        }
            elseif($which=='sendUserMail($cat_id, $item_id, $owner_id)')
            {   sendUserMail($func_arg["cat_id"], $func_arg["item_id"], $func_arg["owner_id"]);
	        }
    
            $gbl["loggedIn"] = true;   

            return true;

        }

    } // end if($cookie)
    else
    {   if ($msg)
        {   echo $msg; 
            return false;
        }
    }

} // end function checkUser()

// *************** END FUNCTION checkUser ***************

?>