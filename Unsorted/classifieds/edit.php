<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: edit.php                *
 * ---------                          *
 *                                    *
 **************************************/

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToLibDir').'func_validateForm.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_add_edit.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$myDB = db_connect();

$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];

$content = array();

if ($submit && $submit=='edit')
{   $content[] = "checkUser('edit()', 'You have to log in.');" ;
}
elseif ($action=='confirm_delete' )
{   #echo 'confirm_delete() is being called??';
    $content[] = "checkUser('confirm_delete()', 'You have to log in.');" ;
}
elseif ($action == 'delete')
{   $content[] = "checkUser('do_delete()', 'You have to log in.') ;" ;
}
elseif ($item_id && $cat_id )
{   $content[] = "checkUser('editForm()', 'You have to log in.');" ;
}
else 
{   $content[] = "checkUser('showItems()', 'You have to log in.');" ;
}


// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_edit'));


db_disconnect($myDB);


# --- START FUNCTIONS ---


// ************* START FUNCTION confirm_delete *************

function confirm_delete()
{
    GLOBAL $item_id;
  
    echo '<FORM ACTION="'.cnfg('deDir').'edit.php" METHOD="POST">
          Are you sure you want to delete this item? 
          &nbsp;<input type="submit" name="submit" value="yes">
          <BR>
          <a href="javascript:history.go(-1);"><< Go back</a>
          <!--
          <input type="submit" name="submit" value="no">
          -->
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="item_id" value="'.$item_id.'">
          </form>
           ';

} // end function delete()

// ************* END FUNCTION confirm_delete *************

// ************* START FUNCTION do_delete *************

function do_delete()
{
    GLOBAL $myDB, $submit, $item_id;


    $query = "SELECT cat_id FROM std_items 
              WHERE item_id=$item_id";
    $result = mysql_query($query, $myDB);

    $query2 = "DELETE FROM std_items 
               WHERE item_id=".$item_id  ;
    $result2 = mysql_query($query2, $myDB);

    $query3 = "DELETE FROM std_blob_images
               WHERE item_id=".$item_id ;
    $result3 = mysql_query($query3, $myDB);


    if ($result2 && $result3)
    {   echo "Your item has been successfully deleted.<BR>" ;
        echo '<a href="'.cnfg('deDir').'edit.php">Go back to edit</a><BR>';

     
        $row = mysql_fetch_array($result);
        $query4 = 'UPDATE std_categories SET num_items=(num_items-1) 
                   WHERE cat_id='.$row["cat_id"] ;

        $result4 = mysql_query($query4);
    }
    else
    {   echo "Deletion of you item failed<BR>" ;
    }
  
} // end function delete()

// ************* END FUNCTION do_delete *************



// ************* START FUNCTION edit() *************

function edit()
{
    GLOBAL $myDB, $gbl, $HTTP_POST_VARS, $HTTP_POST_FILES;

    $postVars = $HTTP_POST_VARS;
    $postFiles = $HTTP_POST_FILES;

    $trueOrFalse=true;

    $postVars['description'] = 
         preg_replace("/\n/", "<BR>", $postVars['description']);

    if ($postVars['visible_email']=='on')
    {   $postVars['visible_email'] = 'show'; 
    }
    else
    {   $postVars['visible_email'] = 'hide'; 
    }

    if  ( validateForm() )
    { 
        $query1 = 'UPDATE std_items SET title=\''.addslashes($postVars['title']).'\', 
                  the_desc=\''.addslashes($postVars['description']).'\', 
                  email=\''.$postVars['email'].'\',
                  email_visible=\''.$postVars['visible_email'].'\'
                  WHERE item_id='.$postVars['item_id'] ;


        if (!$result1 = mysql_query($query1, $myDB) )
        {   $trueOrFalse = false ; 
            $gbl["errorMessage"] .= 'query1 - '.mysql_error().'<BR>';
        }

        /******
        while(list($key, $val) = each($postVars) )
        { echo '$key = '.$key.'<BR>';
          echo '$val = '.$val.'<BR>';
          echo '-------------<BR>';
          
        }
        reset($postVars);
        *******/

        $timestamp_query = 'SELECT date_time 
                            FROM std_items 
                            WHERE item_id='.$postVars['item_id'] ;
        $timestamp_result = mysql_query($timestamp_query);
        if (!$timestamp_result)
        {   die("Couldn't get timestamp for ad.<BR>");
        }
        else
        {   $timestamp = mysql_result($timestamp_result, 0) ; 
            #echo '$timestamp = '.$timestamp.'<BR>';
        }

      
        while (list($key, $val) = each($postFiles) )
        { 
            $set_image_exists=Null;

            $data_file = $postFiles[$key]['tmp_name'];
            $name      = $postFiles[$key]['name'];
            $size      = $postFiles[$key]['size'];
            $type      = $postFiles[$key]['type'];

            $fd_id = ''.$key.'_id' ;

            /*******
            echo '$fd_id = '.$fd_id.'<BR>';
            echo '$postVars[$fd_id] = '.$postVars[$fd_id].'<BR>';
            echo '$postFiles[$key][\'tmp_name\'] = '.$postFiles[$key]['tmp_name'].'<BR>';
            ********/

            $fd_delete = ''.$key.'_delete';

            if (isset($postVars[$fd_delete]) )
            {   #echo '$postVars[$fd_delete] = '.$postVars[$fd_delete].'<BR>';
                $query2 = 'DELETE FROM std_blob_images 
                           WHERE blob_id='.$postVars[$fd_delete] ;

                #echo '$query2 = '.$query2.'<BR>';

                if (!$result2 = mysql_query($query2, $myDB) )
                {   $trueOrFalse = false ; 
                    $gbl["errorMessage"] .= 'query2 - '.mysql_error().'<BR>';
                }

                $set_image_exists='delete';
            }
            elseif (isset($data_file) && $data_file!='none' && $data_file != '')
            {
                $blob_data = 
                    addslashes(fread(fopen($data_file, "r"), filesize($data_file)));
            
                if (isset($postVars[$fd_id]) )
                {   $query3 = 
                        "UPDATE std_blob_images
                        SET bin_data=\"".$blob_data."\",
                        filename='".$name."', 
                        filesize='".$size."', 
                        filetype='".$type."', 
                        timestamp=$timestamp 
                        WHERE blob_id=".$postVars[$fd_id] ;

                    #echo '$query3 = '.$query3.'<BR>';

                    if (!$result3 = mysql_query($query3, $myDB) )
                    {   $trueOrFalse = false ; 
                        $gbl["errorMessage"] .= 'query3 - '.mysql_error().'<BR>';
                    }
                }
                else
                {   $query4 = 
                        'INSERT INTO 
                        std_blob_images(item_id,bin_data,filename,filesize,filetype,timestamp)   
                        VALUES('.$postVars['item_id'].',"'.$blob_data.'",\''.$name.'\',\''.$size.'\',\''.$type."', $timestamp)" ; 

                    #echo '$query4 = '.$query4.'<BR>'; 

                    if (!$result4 = mysql_query($query4, $myDB) )
                    {   $trueOrFalse = false ; 
                        $gbl["errorMessage"] .= 'query4 - '.mysql_error().'<BR>';
                    }

                    $set_image_exists='insert';

                }

            }
         
            if (isset($set_image_exists) )
            {   update_image_exists($postVars['item_id'], $set_image_exists);
            }

            $i++;

            unset($data_file);
            unset($size);
            unset($type);
            unset($name);
            unset($fd_delete);
            unset($fd_id);

        } // end while

        reset($postFiles);

    } // if( validateForm() )


    if ($trueOrFalse)
    {   echo 'The item has been updated successfully.<BR>'; 
        echo 'When you view the ad again you may have to hit your browser\'s reload button to make sure that it loads the updated version of the page.<BR>';
        echo '<a href="'.cnfg('deDir').'details.php?cat_id='.$postVars['cat_id'].'&item_id='.$postVars['item_id'].'">Look at updated ad</a>';
    }
    else
    {   echo 'Update failed for the following reasons:<BR><BR>';
        echo $gbl["errorMessage"];
        echo '<a href="javascript: onClick=history.go(-1)">Click here to try again.</a><BR>';
    }

} // end function edit()

// ************* END FUNCTION edit() *************



function update_image_exists($item_id, $which)
{
    GLOBAL $myDB;

    if ($which=='insert')
    {   $query1 = 'UPDATE std_items SET image_exists=\'true\' 
                   WHERE item_id='.$item_id ;

        $result1 = mysql_query($query1, $myDB);
    }
    elseif($which=='delete')
    {   $query2 = 'SELECT item_id FROM std_blob_images 
                   WHERE item_id='.$item_id ;
        $result2 = mysql_query($query2, $myDB);

        if (mysql_num_rows($result2)==0 )
        {   $query3 = 'UPDATE std_items SET image_exists=\'false\' 
                       WHERE item_id='.$item_id ;
            $result3 = mysql_query($query3, $myDB);
        }
    }
}

// ************* START FUNCTION showItems() *************

function showItems()
{
    GLOBAL $myDB, $gbl ;


    $expTime = time()-(cnfg('expireAdsDays')*86400);

    $query = 'SELECT 
        std_items.item_id as item_id,
        std_items.cat_id as cat_id,
        std_items.title as title,
        std_items.the_desc as the_desc,
        std_categories.cat_name as cat_name
        FROM std_items,std_categories WHERE
        std_items.cat_id=std_categories.cat_id
        AND std_items.user_id='.$gbl["user_id"].'
        AND std_items.date_time>'.$expTime ;


    $result = mysql_query($query, $myDB);
    $num_rows = mysql_num_rows($result);

    if ($num_rows > 0)
    {   $bgcolor = cnfg('editAdsRowColor1');

        ?>

        <CENTER>
        Here are the ads that you currently have running.
        <BR>
        <BR>

        <table cellpadding=0 cellspacing=0 border=1 width="<?php echo cnfg('rowsOfEditAdsTableWidth') ?>">
        <tr>
        <td bgcolor="<?php echo $bgcolor ?>">
        <FONT COLOR="#000000" FACE="Arial">
        <CENTER><B>Title</B></CENTER>
        </FONT>
        </td>
        <td bgcolor="<?php echo $bgcolor ?>">
        <FONT COLOR="#000000" FACE="Arial">
        <CENTER><B>Description</B></CENTER>
        </FONT>
        </td>
        <td bgcolor="<?php echo $bgcolor ?>">
        <FONT COLOR="#000000" FACE="Arial">
        <CENTER><B>Category</B></CENTER>
        </FONT>
        </td>
        <td bgcolor="<?php echo $bgcolor ?>">
        &nbsp;
        </td>
        <td bgcolor="<?php echo $bgcolor ?>">
        &nbsp;
        </td>
        </tr>

        <?php 
 
        if ($bgcolor == cnfg('editAdsRowColor1') )
        {   $bgcolor = cnfg('editAdsRowColor2'); 
        }
        else
        {   $bgcolor = cnfg('editAdsRowColor1') ; 
        }
 
        while ( $row = mysql_fetch_array($result) )
        {   echo '
              <tr>
              <td valign="middle" bgcolor="'.$bgcolor.'">'
             .substr(stripslashes(strip_tags($row['title'])), 0,20);

            if (strlen(stripslashes(strip_tags($row['title']))) > 20)
            {   echo '....' ;
            }
        
            echo '&nbsp;
                 </td>
                 <td valign="middle" bgcolor="'.$bgcolor.'">'
                .substr(stripslashes(strip_tags($row['the_desc'])), 0,20);
            if (strlen(stripslashes(strip_tags($row['the_desc']))) > 20)
            {   echo '....' ;
            }
            
            echo '&nbsp;
                 </td>
                 <td valign="middle" bgcolor="'.$bgcolor.'">'
                 .$row['cat_name'].
                 '</td>';
            
            ?>
            <td valign="middle" bgcolor="<?php echo $bgcolor ?>">
            <NOBR>&nbsp;
            <a href="<?php echo cnfg('deDir')?>edit.php?item_id=<?php echo $row['item_id']?>&cat_id=<?php echo $row['cat_id']?>">
             <FONT COLOR="#0000FF" FACE="Arial">
             Edit
             </FONT>
             </a>
             &nbsp;</NOBR>
             </td>
             <td valign="middle" bgcolor="<?php echo $bgcolor?>">
             <NOBR>&nbsp;
             <a href="<?php echo cnfg('deDir')?>edit.php?item_id=<?php echo $row['item_id']?>&action=confirm_delete">
             <FONT COLOR="#0000FF" FACE="Arial">
             Delete
             </FONT>
             </a>
             &nbsp;</NOBR>
             </td>
            </tr>
        
            <?php   

            if ($bgcolor == cnfg('editAdsRowColor1') )
            {   $bgcolor=cnfg('editAdsRowColor2'); 
            }
            else
            {   $bgcolor = cnfg('editAdsRowColor1') ; 
            }

        } // end while

        echo '</table>
             </CENTER>';

    } // end if($num_rows > 0)
    else
    {   echo "You currently don't have any ads in the system.";
    }

} // end function showItems()

// ************* END FUNCTION showItems() *************

// ************* START FUNCTION editForm() *************

function editForm()
{
    GLOBAL $myDB, $gbl, $item_id, $cat_id, $gbl_quick_tips_description, $gbl_quick_tips_image_long;


    $query = 'SELECT title,the_desc,email,email_visible,image_exists
              FROM std_items 
              WHERE item_id='.$item_id.' 
              AND cat_id='.$cat_id.' 
              AND user_id='.$gbl["user_id"] ;

    $result = mysql_query($query, $myDB);

    $query2 = 'SELECT blob_id,filename FROM std_blob_images 
               WHERE std_blob_images.item_id='.$item_id ;

    $result2 = mysql_query($query2, $myDB);


    if ( mysql_num_rows($result) == 1 )
    {
        echo $gbl_quick_tips_description.$gbl_quick_tips_image_long;

        echo '
          <FORM ACTION="'.cnfg('deDir').'edit.php?edit=1" METHOD="POST" enctype="multipart/form-data">
          <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            ';

        $row = mysql_fetch_array($result);
        $description = $row['the_desc'] ;
        $description = preg_replace("/<br>/i", "\n", $description);

        $title = preg_replace("/\"/", "&quot;", $row['title']);

        echo '
          <td valign="top" align="right">
          Title: 
          </td>
          <td valign="top">
          <INPUT TYPE="TEXT" NAME="title" size=20 value="'.stripslashes($title).'">
          </td>
          </tr>
          <tr>
          <td valign="top" align="right">
          Description: 
           </td>
          <td valign="top">
          <TEXTAREA NAME="description" ROWS="15" COLS="45">'
          .stripslashes($description).
          '</TEXTAREA>
          </td>
          </tr>
          <tr>
          <td valign="top" align="right">Email:</td>
          <td valign="top">
          <INPUT TYPE="TEXT" NAME="email" value="'
           .$row['email'].
          '">
          </td>
          </tr>
          <tr>
          <td valign="top">&nbsp;</td>
          <td valign="top">
          Email is visible:<INPUT TYPE="CHECKBOX" NAME="visible_email" ';
          if ($row['email_visible']=='show')
          { echo 'CHECKED'; 
          }
        
          echo '
            >
            <BR>&nbsp;
            </td>
            </tr>

            <script LANGUAGE="JavaScript">
            <!-- Begin

            function open_win(id) {
                var the_location = "view_image.php?the_id="+id;
                window.open(the_location,"", "height=450, width=500, left=0, top=0, scrollbars");
            }

            // End -->
            </script>
                ';


            ##if($row['image_exists'] && $row['image_exists'] == 'true'){
            #echo 'It exists!!<BR>';

            $file_count = 1;
            #$row2_arr = array();
            if  (mysql_num_rows($result2) > 0 )
            {   while($row2 = mysql_fetch_array($result2) )
                {   echo '<tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">
                    <FONT CLASS="small">'.$row2["filename"].'
                    </FONT>
                    <BR>
                    <input type="file" name="fd'.$file_count.'">
                    <a href="javascript:onClick=open_win(';
                    echo $row2["blob_id"];
                    echo ');">View Image</a>
                    <FONT CLASS="small">Delete</FONT>
                    <input type="CHECKBOX" name="fd'.$file_count.'_delete" value="'.$row2["blob_id"].'">';
                    echo "\n";
                    echo '<input type="hidden" name="fd'.$file_count.'_id" value="'.$row2["blob_id"].'">';

                    /*<a href="javascript: 
                    onClick=window.open(\'get_blob.php?the_id='.
                    $row2["blob_id"].'\', "", \'height=500,width=500,top=0,left=0\');">
                    View Image</a>';*/
                    echo '</td>
                         </tr>';

                    ++$file_count;
            
                }

            }


            for ($file_count; $file_count<=2; $file_count++)
            {   echo '<tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">
                    <input type="file" name="fd'.$file_count.'">
                    </td>
                    </tr>';
            }
         
            unset($file_count);
            
            #}
            #else{
            #     echo 'it dont exist???<BR>';
            #}

            echo'
              <tr>
              <td valign="top">&nbsp;</td>
              <td valign="top">
              <input type="submit" name="submit" value="edit">
              </td>
              </tr>
              </table>
              <input type="hidden" name="item_id" value="'.$item_id.'">
              <input type="hidden" name="cat_id" value="'.$cat_id.'">
               ';
  
        echo '</FORM>';

    } // end if

    /***
    echo '
      <FORM ACTION="http://localhost/declass/win/edit.php" METHOD="POST">
      <INPUT TYPE="TEXT">
      <BR>
      <INPUT TYPE="TEXT">
      <BR>
      <INPUT TYPE=SUBMIT VALUE="Submit">
      </FORM>
       ';
    ***/

} // end function editForm()

// ************* END FUNCTION editForm() *************

?>
