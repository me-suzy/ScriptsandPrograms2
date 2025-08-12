<?php

/* D.E. Classifieds v1.04
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: details.php             *
 * ---------                          *
 *                                    *
 **************************************/

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToLibDir').'func_tree.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');
#require_once 'class_gbl.php';

$myDB = db_connect();
 
$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];

$content = array();

checkUser('', ''); 


$content[] = 'doDetails();' ;


// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_details'));

db_disconnect($myDB);


# --- START FUNCTIONS ---



// *********** START FUNCTION doDetails() ************

/*********************
NOTES ON FUNCTION doDetails() 
------------------------------
This function calls function doTime()
**********************/

function doDetails()
{
    GLOBAL $myDB, $HTTP_GET_VARS ;
  
    $cat_id = $HTTP_GET_VARS['cat_id'];
    $item_id = $HTTP_GET_VARS['item_id'];
    $doSearch = $HTTP_GET_VARS['doSearch'];

    if ( isset($cat_id) )
    {   $query = 'SELECT cat_id,parent_id,cat_name FROM std_categories WHERE cat_id='.$cat_id;

        $result = mysql_query($query,$myDB);
        $num_rows = mysql_num_rows($result);

        if ( !isset($doSearch) || !$doSearch )
        {   $path = climb_tree($cat_id, 'details');
            $path_arr = split("\!\@\#_SPLIT_\!\@\#", $path);
            for( $i=0; $i<count($path_arr); $i++)
            {   echo $path_arr[$i] ; 
                if ($i < count($path_arr)-2)
                { echo '<FONT CLASS="subCat" COLOR="#FF0000"><B>&gt;&gt;</B></FONT>'; 
                }
            }
            echo '<BR><BR>';
        } // end if( !isset($doSearch) || !$doSearch )

        echo '<a href="javascript:onClick=history.go(-1);"><FONT CLASS="mainCat">&lt;&lt;back</font></a>';


        echo '<BR>&nbsp;';

        $query = 'SELECT * FROM std_items
                  WHERE cat_id='.$cat_id.' 
                  AND item_id='.$item_id ;

        $query2 = 'SELECT blob_id FROM std_blob_images 
                   WHERE std_blob_images.item_id='.$item_id.' 
                   ORDER BY blob_id ASC';
 
        $result = mysql_query($query, $myDB);

        $result2 = mysql_query($query2, $myDB);


        while ( $row = mysql_fetch_array($result) )
        {   ?>

            <CENTER>
            <table cellpadding="0" cellspacing="0" border="0" width="95%">
            <tr>
            <td valign="top" bgcolor="#CCCC88">
            <CENTER>
            <FONT FACE="Arial">
            &nbsp;<?=stripslashes($row['title'])?>
            </FONT>
            </CENTER>
            <!-- <BR>&nbsp; -->
            </td>
            </tr>
            <tr>
            <td valign="top">
            <FONT CLASS="mainCat">
            Created: 
            <?=date("m/d/Y \a\\t h:i:sa", $row['date_time'])?>
            <BR> 
        
            <?php
            doTime($row['date_time']);
            ?>

       
            </FONT>
            </td>
            </tr>
            <tr>
            <td valign="top">
        
            <?php

            $query3 = 'SELECT std_users.user_name, std_users.user_id,
                       std_items.email, std_items.email_visible
                       FROM std_users, std_items 
                       WHERE std_items.item_id='.$item_id.' 
                       AND std_items.user_id=std_users.user_id';
          
            $result3 = mysql_query($query3, $myDB);

            if (mysql_num_rows($result3) == 1)
            {   $row3 = mysql_fetch_array($result3);
                echo '<font CLASS="mainCat">Ad  posted by: '.$row3['user_name'].'</font>';
                echo '</td></tr>';
            }

            if ($row3['email_visible'] == 'show')
            {   echo '<tr><td valign="top">';
                echo '<font CLASS="mainCat">Email: '.$row3['email'].'</font>';
                echo '</td></tr>';
            }

            #emailOwnerLink($cat_id, $item_id, $row3['user_id']); 
        
            ?>

            <tr>
            <td>
            &nbsp;
            </td>
            </tr>

            <tr>
            <td valign="top">
             <table cellpadding="0" cellspacing="0" border="0" width="90%">
             <tr><td valign="top" width="90%">
             <FONT FACE="Arial">
             <?=stripslashes($row['the_desc'])?>
             </FONT>
             </td></tr></table>
            </td>
            </tr>
      
            <?php 

            if ($row['image_exists'] && $row['image_exists'] == 'true')
            {   while ($row2 = mysql_fetch_array($result2) )
                {   ?>
                    <tr>
                    <td valign="top">
                    <CENTER>
                    <img src="get_blob.php?the_id=<?=$row2['blob_id']?>">
                    </CENTER>
                    <BR>
                    </td>
                    </tr>

                    <?php
                }

            }
            else
            {   echo '&nbsp';
            }

            echo '
            </table>
            </CENTER>
                ';
      
        } // end while

    } // end if(isset($cat_id)

} // end function doDetails()

// ************* END FUNCTION doDetails() ************


// ************* START FUNCTION doTime() *************

/*********************
NOTES ON FUNCTION doTime() 
------------------------------
This function is called from function doDetails()
**********************/

function doTime($theTime)
{
    $endTime =  $theTime+(cnfg('expireAdsDays')*86400);

    if ($endTime > time())
    {
        $difference = $endTime-time();
        $days_difference = intval($difference / 86400);
        $difference = $difference - ($days_difference * 86400);
                        
        $hours_difference = intval($difference / 3600);
        if (strlen($hours_difference) == 1){
            $hours_difference = "0".$hours_difference;
        }
                        
        $difference = $difference - ($hours_difference * 3600);
        $minutes_difference = intval($difference / 60);
        if (strlen($minutes_difference) == 1){
            $minutes_difference = "0".$minutes_difference;
        }
                        
        $difference = $difference - ($minutes_difference * 60);
        $seconds_difference = $difference;
        if (strlen($seconds_difference) == 1){
            $seconds_difference = "0".$seconds_difference;
        }

        echo '
            Expires:
            '.$days_difference.'<FONT CLASS="subCat"  COLOR="#FF0000"><B>days</B></FONT>
            '.$hours_difference.'<FONT CLASS="subCat" COLOR="#FF0000"><B>Hrs</B></FONT> 
            '.$minutes_difference.'<FONT CLASS="subCat"  COLOR="#FF0000"><B>min</B></FONT>
            '.$seconds_difference.'<FONT CLASS="subCat" COLOR="#FF0000"><B>sec</B></FONT>
            <BR>
              ';

    } // end if($endTime>time()){

} // end function doTime()

// ************* END FUNCTION doTime() *************

?>
