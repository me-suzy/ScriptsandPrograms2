<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: showCat.php             *
 * ---------                          *
 *                                    *
 **************************************/

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php'); 
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToLibDir').'func_tree.php');
require_once(path_cnfg('pathToLibDir').'func_getResults.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];

$content = array();

$myDB = db_connect();

checkUser('', ''); 

$content[] = 'doShow();';

// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_showCat'));

db_disconnect($myDB);


# --- START FUNCTIONS ---

// ************* START FUNCTION doShow() *************

function doShow()
{
    GLOBAL $myDB, $HTTP_GET_VARS  ;

    $cat_id = $HTTP_GET_VARS["cat_id"];

    if ($cat_id)
    { 
        $query = 'SELECT cat_id,parent_id,cat_name FROM std_categories WHERE cat_id='.$cat_id;

        $result = mysql_query($query,$myDB);
        $num_rows = mysql_num_rows($result);

        #echo '$num_rows = '.$num_rows.'<BR>';

        echo '<table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr><td valign="top" width="100%">
              ';

    
        $path = climb_tree($cat_id, 'showCat');
        #echo '$path = '.$path.'<BR>';
        $path_arr = split("\!\@\#_SPLIT_\!\@\#", $path);
        for ($i=0; $i<count($path_arr); $i++)
        {   echo $path_arr[$i] ; 
            if ($i < count($path_arr)-2)
            {   echo '<FONT CLASS="subCat" COLOR="#FF0000"><B>&gt;&gt;</B></FONT>'; 
            }
        }
     
        echo '<BR><BR>';
        echo '</td></tr></table>';

        $query = 'SELECT cat_name,cat_id FROM std_categories 
                  WHERE parent_id='.$cat_id.' 
                  ORDER BY cat_name ASC' ;

        $result = mysql_query($query,$myDB);

        if (mysql_num_rows($result) > 0 )
        {   display_cats_sub($result, $cat_id);
        }
        else
        {   getResults();
        }


    } // end if($cat_id)

} // end function doShow()

// ************* END FUNCTION doShow() *************


// ************* START FUNCTION display_cats_sub() *************

function display_cats_sub($result, $cat_id)
{
    GLOBAL $myDB;

    $num_cols = cnfg('subCatsCols');
    $col_buffer_size = cnfg('subCatsBufferCols');
    $td_width = cnfg('subCatsTdWidth');
    $table_width = cnfg('subCatsTableWidth');
    $table_pad = cnfg('subCatsTablePad');
    $table_spc = cnfg('subCatsTableSpace');
    $centered_or_not = cnfg('subCatsCenter');

    if ($centered_or_not)
    {   echo '<CENTER>' ; 
    }

    echo '<table cellpadding="'.$table_pad.'" cellspacing="'.$table_spc.'" ';
    echo 'border="0" width="'.$table_width.'">';

    $countCols = 1;
    $num_cols = $num_cols+($num_cols-1);

    for ($i=0; $i<mysql_num_rows($result); $i++  )
    { 
        if ($countCols==1 ) 
        {   echo '<tr>'."\n"; 
        }

        $num_ads = 0;

        if ( $countCols % 2 == 0 )
        {   echo '<td valign=top width="'.cnfg('subCatsBufferCols').'">'."\n" ; 
            echo '<img src="'.cnfg('deDir').'/images/trans.gif" ';
            echo 'border="0" width="50" height="1">'."\n";
            echo '</td>'."\n";

            $i-- ; // no data used from $result array, so decrement the iterator.
        }
        else
        {   $row = mysql_fetch_array($result) ;     
            get_num_ads($row["cat_id"], true, $num_ads); 


            echo '<td valign="top" width="'.$td_width.'">'."\n";
            echo '<a href="'.cnfg('deDir').'showCat.php?cat_id='.
                  $row["cat_id"].'">'."\n" ;
            echo '<FONT CLASS="subCat">';
            echo $row['cat_name'];
            echo '</FONT>';
            echo '</a>&nbsp;';
            echo '<FONT CLASS="subCat">('.$num_ads.')</FONT>'."\n";
            echo '</td>'."\n";
        }

     
        if($countCols==$num_cols || $i==(mysql_num_rows($result)-1) )
        {   $countCols=1; 
            echo '</tr>';
        }  
        else
        {   $countCols++ ; 
        }

    } // end while

    echo '</table>';

    if($centered_or_not)
    {   echo '</CENTER>' ; 
    }

} // end function display_cats_sub()

// ************* END FUNCTION display_cats_sub() *************


?>
