<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */


/*************************************
 * File Name: func_getResults.php    *
 *                                   *
 *************************************/



// ************* START function get_searchable_cats() *************

function get_searchable_cats($cat_id, &$cats_arr)
{
    GLOBAL $myDB; 
  
    $query = "SELECT cat_id, cat_name FROM std_categories WHERE 
              top_parent_id=$cat_id" ;
    $result = mysql_query($query);

    while ($row = mysql_fetch_array($result) )
    {   $query2 = 'SELECT * FROM std_categories 
                   WHERE parent_id='.$row["cat_id"];
        $result2 = mysql_query($query2);
        if (mysql_num_rows($result2) == 0 )
        {   $cats_arr[] = $row["cat_id"];
        }
    }

} // end function get_searchable_cats()

// ************* END function get_searchable_cats() *************


// ************* START function which_search ************* 

function which_search()
{
    GLOBAL $HTTP_POST_VARS;

    echo 'in which_search !!!!!<BR>';
  
    #exit;

    $expTime = time()-(cnfg('expireAdsDays')*86400);

    if  ($HTTP_POST_VARS['searchType']=='keyword' )
    {   $query = '
            SELECT 
            std_items.item_id as item_id,
            std_items.cat_id as cat_id,
            std_items.title as title,
            std_items.the_desc as the_desc,
            std_items.image_exists as image_exists,
            std_categories.cat_id as cat_id,
            std_categories.cat_name as cat_name
            FROM std_items,std_categories WHERE 
            (std_items.the_desc LIKE "%'.$search.'%" OR 
            std_items.title LIKE "%'.$search.'%")
            AND std_items.date_time>'.$expTime.'
            AND std_items.cat_id=std_categories.cat_id';
       
        if (count($cats_arr) > 0 )
        {   $query .= ' AND  (';
            for ($i=0; $i<count($cats_arr); $i++)
            {   $query .= 'std_items.cat_id='.$cats_arr[$i] ;
                if($i != count($cats_arr)-1 )
                {   $query .= ' OR ' ; 
                }
            }

            $query .= ')';
        }
        
    }
    elseif($HTTP_POST_VARS['searchType']=='id' )
    {   $search_id = trim($HTTP_POST_VARS['search_id']);

        if(preg_match("/^[0-9]+$/", $search_id) )
        {   echo 'preg_match successful!!!<BR>' ; 
        }
        else
        {   echo 'preg_match failure!!!<BR>' ; 
            return false;
        }
   
        $query = '      
            SELECT 
            std_items.item_id as item_id,
            std_items.cat_id as cat_id,
            std_items.title as title,
            std_items.the_desc as the_desc,
            std_items.image_exists as image_exists,
            std_categories.cat_id as cat_id,
            std_categories.cat_name as cat_name
            FROM std_items,std_categories WHERE 
            std_items.date_time>'.$expTime.' 
            AND std_items.item_id='.$search_id.' 
            AND std_items.cat_id=std_categories.cat_id' ; 
    }

    return $query;

} // end function which_search

// ************* END function which_search ************* 


// ************* START FUNCTION getResults() *************

function getResults()
{
    GLOBAL $myDB, $HTTP_GET_VARS, $HTTP_POST_VARS  ;

  
    if (isset($HTTP_POST_VARS['search']) )
    {   $search = $HTTP_POST_VARS['search'] ;
    }
    elseif (isset($HTTP_GET_VARS['search']) )
    {   $search = $HTTP_GET_VARS['search'] ; ; 
    }

    $category   = $HTTP_POST_VARS['category'];
    $searchType = $HTTP_POST_VARS['searchType'];
  
    $doSearch  = $HTTP_GET_VARS['doSearch'];
    $cat_id    = $HTTP_GET_VARS['cat_id'];
    $offset    = $HTTP_GET_VARS['offset'];

    $bgcolor = cnfg('viewAdsRowColor1');
    $topRow = true;
    $searchOrShowCat = '';

    if (!$offset) 
    {   $offset = 0 ; 
    }


    if  ($doSearch) 
    {   $cats_arr = array();
        if (isset($category) && $category != 'none')
        {   /* get_searchable_cats takes a reference 
            * to $cats_arr and loads it up.
            */
            get_searchable_cats($category, $cats_arr) ; 
        }

        echo '$doSearch is true!!!<BR>';

        $query = which_search();

        echo '$query = '.$query.'<BR>';
        #exit;

        $searchOrShowCat = 'search.php';
    }
    else
    {   $query = '
            SELECT 
            std_items.item_id as item_id,
            std_items.cat_id as cat_id,
            std_items.title as title,
            std_items.the_desc as the_desc,
            std_items.image_exists as image_exists,
            std_categories.cat_id as cat_id,
            std_categories.cat_name as cat_name
            FROM std_items,std_categories 
            WHERE std_items.date_time>'.$expTime.' 
            AND std_items.cat_id='.$cat_id.'
            AND std_items.cat_id=std_categories.cat_id';

        $searchOrShowCat = 'showCat.php';
        $doSearch = 0;
    }


    $query .= ' LIMIT '.$offset.','."21" ;

    echo '$query = '.$query.'<BR>';

    if ($offset != 0)
    {   $offset += 20; 
    }


    $result = mysql_query($query,$myDB);
    if (!$result)
    {   echo mysql_error(); 
    }

    $num_rows = mysql_num_rows($result);


    echo "<CENTER>\n";

    if ($num_rows >0)
    { 
        ?>

        <table cellpadding="0" cellspacing="0" border="0" width="<?php echo  cnfg('rowsOfAdsTableWidth') ?>">
        <tr>
        <td width="10%" align="left">

        <?php
     
        if  ( ($offset && $offset != 0) && ($offset != 10) )
        {
            echo '
              <a href="'.cnfg('deDir').$searchOrShowCat.
              '?doSearch='.$doSearch.'&search='.urlencode($search).'&offset=';
            echo $offset-40;
            echo "&cat_id=$cat_id";
            echo '"> Previous&nbsp;20</a>
                 </td>';
            echo "\n";
        } 
        else
        {   echo '&nbsp;
                  </td>';
            echo "\n";
        }

        ?>

        <td width="80%">
        <CENTER>
        <FONT CLASS="subCat">

        <?php

        if ($searchType != 'id')
        {   echo 'Results ';
            if ($offset==0)
            {   echo ($offse+1).'-'.($offset+20);
            }
            else
            {   echo (($offset-20)+1).'-'.$offset;
            }
        }

        ?>

        &nbsp;&nbsp;
        </FONT></CENTER>
        </td>

        <?php

        if ($num_rows==21)
        {   echo '
             <td align="right" width="10%">
             <a href="'.cnfg('deDir').$searchOrShowCat.'?doSearch='.$doSearch.'&search='
             .urlencode($search).'&offset=';

            if ($offset==0)
            {   echo $offset+20; 
            }
            else
            {   echo $offset; 
            } 

            echo "&cat_id=$cat_id";
            echo '"> Next&nbsp;20</a>
                 </td>
                    '; 
        }
        else
        {   ?>
            <td valign="right" width="10%"> 
            &nbsp;
            </td>
            <?php
        }
  
        ?>
        </tr>
        </table>
     
        <table cellpadding="0" cellspacing="2" border="0" width="<?php echo  cnfg('rowsOfAdsTableWidth') ?>">
        <tr>
        <td bgcolor="<?php echo $bgcolor ?>">
        <FONT COLOR="#000000" FACE="Arial">
        <CENTER>
        <B>Image</B>
        </CENTER>
        </FONT>
        </td>
        <td bgcolor="<?php echo $bgcolor ?>">
        <FONT COLOR="#000000" FACE="Arial">
        <CENTER>
        <B>Title</B>
        </CENTER>
        </FONT>
        </td>
        <td bgcolor="<?php echo $bgcolor ?>">
        <FONT COLOR="#000000" FACE="Arial">
        <CENTER>
        <B>Description</B>
        </CENTER>
        </FONT>
        </td>
        <td bgcolor="<?php echo $bgcolor ?>">
        <FONT COLOR="#000000" FACE="Arial">
        <CENTER>
        <B>Category</B>
        </CENTER>
        </FONT>
        </td>
        <td bgcolor="<?php echo $bgcolor ?>">
        &nbsp;
        </td>
        </tr>

        <?php 

        if $bgcolor == cnfg('viewAdsRowColor1') )
        {   $bgcolor=cnfg('viewAdsRowColor2') ; 
        }
        else
        {   $bgcolor = cnfg('viewAdsRowColor1') ; 
        }

        $count_to;
        if ($num_rows>20)
        {   $count_to = 20; 
        }
        else
        {   $count_to = $num_rows; 
        }

        for ($i=0; $i<$count_to; $i++)
        {  $row = mysql_fetch_array($result) ;

            ?>
            <tr>
            <td bgcolor="<?php echo $bgcolor?>">

            <?php
            if ($row['image_exists'] && $row['image_exists'] == 'true')
            {   ?>

                <CENTER>
                <img src="<?php echo cnfg('deDir') ?>images/pic_yes.gif" border="0" width="31" height="26">
                </CENTER>

                <?php
            }
            elseif (!$row['image_exists'] || $row['image_exists'] != 'true')
            {   ?>
           
                <CENTER>
                <img src="<?php echo cnfg('deDir') ?>images/pic_no.gif" border="0" width="40" height="33">
                </CENTER>  

                <?php
            }


            ?>

            </td>
            <td bgcolor="<?php echo $bgcolor ?>">

            <?php

            if (strlen(stripslashes(strip_tags($row['title']))) > 20 )
            {   echo substr(stripslashes(strip_tags($row['title'])), 0,20).'....'; 
            }
            else
            {   echo stripslashes(strip_tags($row['title'])); 
            }

            ?>

            </td>
            <td bgcolor="<?php echo $bgcolor ?>">
         
            <?php

            $row['the_desc'] = stripslashes(strip_tags($row['the_desc']));

            if (strlen(stripslashes(strip_tags($row['the_desc']))) > 20 )
            {   echo substr(stripslashes(strip_tags($row['the_desc'])), 0,20).'.....'; 
            }
            else
            {   echo stripslashes(strip_tags($row['the_desc'])); 
            }
        
            echo '
               </td>
               <td bgcolor="'.$bgcolor.'">'
               .$row['cat_name'].
               '</td>
               <td bgcolor="'.$bgcolor.'"><a href="details.php?';
            
            if ($doSearch)
            {   echo 'doSearch=true';
            }


            if  ($row['cat_id'])
            {   echo '&cat_id='.$row['cat_id']; 
            }
            else
            {   echo '&cat_id='.$cat_id; 
            }


            echo '&item_id='.$row['item_id'].'&offset=';

            if (isset($offset) && $offset>0)
            {   echo $offset-20; 
            }
            else
            {   echo $offset;
            }


            echo '">';

            ?>

            <FONT FACE="Arial" STYLE="font-size:13pt; font-color:#ff0000;">
            Details
            </FONT>
            </a>
            </td>
            </tr>
         
            <?php 

            if ($bgcolor == cnfg('viewAdsRowColor1') )
            {   $bgcolor=cnfg('viewAdsRowColor2'); 
            }
            else
            {   $bgcolor = cnfg('viewAdsRowColor1') ; 
            }

        } // end while

        ?>

     
        </td>
        </tr>
        </table>


        <table cellpadding="0" cellspacing="0" border="0" width="80%">
        <tr>
        <td align="left" width="20%">  
     
        <?php

        if ( ($offset && $offset != 0) && ($offset != 10) )
        {   echo '
              <a href="'.cnfg('deDir').$searchOrShowCat.
              '?doSearch='.$doSearch.'&search='.urlencode($search).'&offset=';
            echo $offset-40;
            echo "&cat_id=$cat_id";
            echo '"> Previous&nbsp;20</a>
                 </td>';
            echo "\n";
        } 
        else
        {   echo '&nbsp;';
        }

        ?>
     
        </td>

        <td valign="top" width="60%">
        &nbsp;
        </td>
      
        <?php 

        if ($num_rows==21)
        {   echo '
              <td align="right" width="20%">
              <a href="'.cnfg('deDir').$searchOrShowCat.'?doSearch='.$doSearch.'&search='
              .urlencode($search).'&offset=';

            if ($offset==0)
            {   echo $offset+20; 
            }
            else
            {   echo $offset; 
            } 

            echo "&cat_id=$cat_id";
            echo '"> Next 20</a>'; 
        }

        ?>
        </td>
        </tr>
        </table>

    <?php
    } // end if($num_rows >0){
    else
    {   if ($doSearch)
        {   echo '
              <table><tr><td valign="top">
              <BR>
              Sorry, no matches.<BR>
              <a href="'.cnfg('deDir').'search.php">
              Search again.
              </a>
              </td></tr></table>';
        }
        else
        {   echo '
              <table><tr><td valign="top">
              <BR>
              There are no items in this category.<BR>
              <a href="javascript: onClick=history.go(-1);">
              << Go Back
              </a>
              </td></tr></table>';
        }
    }

    echo "</CENTER>\n";

} // end function getResults()


// ************** END FUNCTION getResults() *************

?>