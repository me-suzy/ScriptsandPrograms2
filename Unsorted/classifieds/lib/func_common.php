<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: func_common.php         *
 * ---------                          *
 *                                    *
 **************************************/

require_once(path_cnfg('pathToLibDir').'func_tree.php') ;

// ************* START FUNCTION db_connect *************

function db_connect()
{
    $myDB = mysql_connect(cnfg('dbHost'), cnfg('dbUser'), cnfg('dbPass') );

    if ($myDB && mysql_select_db(cnfg('dbName') ) )
    {   return $myDB;
    }
    else
    {   echo mysql_error();
        return false;
    }
}

// ************* END FUNCTION db_connect *************


// ************* START FUNCTION db_disconnect *************

function db_disconnect(&$db_link)
{
    if ($db_link)
    {   mysql_close($db_link) ;
    }
}

// ************* END FUNCTION db_disconnect *************


// ************* START FUNCTION content *************

function content(&$content)
{
    GLOBAL $gbl;

    for ($i=0; $i<count($content); $i++)
    {   eval($content[$i]);
    }

} // end function content

// ************* END FUNCTION content *************



// ************* START FUNCTION logIn() *************

function logIn($user_name, $password)
{
    GLOBAL $myDB ;

    srand((double)microtime()*1000000);

    $the_rand = rand(1, 10000);
    $the_rand2 = rand(1, 10000);
    $the_rand3 = rand(1, 10000);
    $the_rand = ''.$the_rand.''.$the_rand2.''.$the_rand3;

    $query = "UPDATE std_users SET cookie_id='$the_rand' 
              WHERE user_name='$user_name' AND password='$password' " ; 

    $result = mysql_query($query,$myDB) ;

    if ( mysql_affected_rows($myDB)==1 )
    {   setcookie('log_in_cookie[user]', $user_name); 
        setcookie('log_in_cookie[id]', $the_rand);

        return true;
    } 
    else
    {   return false;
    } // end if( mysql_affected_rows($myDB)==1 )..else

} // end function logIn()

// ************* END FUNCTION logIn() *************


// *************** START FUNCTION top_nav ***************

function top_nav()
{
    ?>

    <TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="100%" background="<?php echo cnfg('deDir') ?>images/bg_topNav.gif" >
    <TR>
    <td VALIGN="TOP" WIDTH="40%">&nbsp;</td>
    <td valign="top" width="60%"  >
    <FONT FACE="Arial">
    <a href="<?php echo cnfg('deDir') ?>index.php">Home</a>
    <FONT COLOR="#000000" SIZE="1">
    <B>|</B>
    </FONT>
    <a href="<?php echo cnfg('deDir') ?>register.php">Register</a>
    <FONT COLOR="#000000" SIZE="1">
    <B>|</B>
    </FONT>
    <a href="<?php echo cnfg('deDir') ?>select_to_add.php">List an item</a>
    <FONT COLOR="#000000" SIZE="1">
    <B>|</B>
    </FONT>
    <a href="<?php echo cnfg('deDir') ?>edit.php">Edit item</a>
    <FONT COLOR="#000000" SIZE="1">
    <B>|</B>
    </FONT> 
    <a href="<?php echo cnfg('deDir') ?>search.php">Search</a>  
    </FONT>
    </TD>
    </TR>
    </TABLE>

    <?php 
   
} // end function top_nav()

// *************** END FUNCTION top_nav ***************

// ************* START FUNCTION display_cats_main() *************

function display_cats_main()
{
    GLOBAL $myDB ;

    $num_cols = cnfg('mainCatsCols');
    $col_buffer_size = cnfg('mainCatsBufferCols');
    $td_width = cnfg('mainCatsTdWidth');
    $table_width = cnfg('mainCatsTableWidth');
    $table_pad = cnfg('mainCatsTablePad');
    $table_spc = cnfg('mainCatsTableSpace');
    $centered_or_not = cnfg('mainCatsCenter');


    $query = 
        'SELECT cat_name,cat_id FROM std_categories 
         WHERE parent_id=0 
         ORDER BY cat_name ASC' ;
  
    $result = mysql_query($query, $myDB);


    if ($centered_or_not)
    {   echo '<CENTER>' ; 
    }

    echo '<table width="'.$table_width.'" cellpadding="'.$table_pad.'" cellspacing="'.$table_spc.'">';

    $countCols = 1;
    $num_cols = $num_cols+($num_cols-1);


    for ($i=0; $i<mysql_num_rows($result); $i++  )
    { 
        if  ($countCols==1 ) 
        {   echo '<tr>'."\n"; 
        }

        if ( $countCols % 2 == 0 )
        {   echo '<td valign=top width="'.cnfg('mainCatsBufferCols').'">'."\n" ; 
            echo '<img src="'.cnfg('deDir').'images/trans.gif" ';
            echo 'border="0" width="50" height="1">'."\n";
            echo '</td>'."\n";

            $i-- ; // no data used from $result array, so decrement the iterator.
        }
        else
        {   $num_ads = 0;

            $row = mysql_fetch_array($result) ;     
		    #echo '$row[cat_id] = '.$row['cat_id'].'<BR>';
	
            get_num_ads($row["cat_id"], true, $num_ads); 
          
            echo '<td valign="top" width="'.$td_width.'">'."\n";
            echo '<FONT CLASS="subCat"><a href="'.cnfg('deDir').'showCat.php?cat_id='.$row['cat_id'].'">'.$row['cat_name'];
            echo '</a></FONT>&nbsp;';
            echo '<FONT CLASS="subCat">('.$num_ads.')</FONT>'."\n";
            echo '</td>'."\n";
        }


        if ($countCols==$num_cols || $i==mysql_num_rows($result)-1 )
        {   $countCols=1; 
            echo '</tr>';
        }  
        else
        {   $countCols++ ; 
        }

    } // end while

    echo '</table>';

    if ($centered_or_not)
    {   echo '</CENTER>' ; 
    }

    #echo 'this is bottom of display_cats_main()!!<BR>';

} // end function display_cats_main()

// ************* END FUNCTION display_cats_main() *************


// *************** START FUNCTION leftNav ***************

function leftNav()
{
    GLOBAL $gbl, $myDB ;

    echo '
      <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
      <td valign="top" bgcolor="#000099" width="100%">
      <CENTER>
      <font class="mainCat" color="#ffffff" face="Arial">
      &nbsp;Categories&nbsp;
      </font>
      </CENTER>
      </td></tr>
      </table>

      <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
      <td valign="top" width="5">
      &nbsp;
      </td>
      <td valign="top">

      <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr><td valign="top">
       ';


    $query = "SELECT cat_name,cat_id FROM std_categories WHERE parent_id=0 ORDER BY cat_name ASC" ;

    $result = mysql_query($query, $myDB);

    if (!$result)
    {   echo 'query - '.mysql_error().'<BR>' ; 
    }

    while ( $row = mysql_fetch_array($result) )
    {
        #$saveRow[] = $row['cat_id'];
        #$saveRow[] = $row['cat_name'];
  
        $num_ads = 0;
        get_num_ads($row["cat_id"], true, $num_ads);

        echo '<tr><td valign="top" width="100%">
              <a href="'.cnfg('deDir').'showCat.php?cat_id='.
              $row['cat_id'].'">'.
              $row['cat_name'].
              '&nbsp;<FONT CLASS="user">('.$num_ads.')</FONT>'.
              '</a></td></tr>';
    }

    # echo 'mysql_error() = '.mysql_error().'<BR>';

    echo '
      </td></tr>
      </table>

      </td></tr></table>

    '; 

} // end function leftNav()

// *************** END FUNCTION leftNav ***************

// *************** START FUNCTION main_css ***************

function main_css()
{
    ?>

    <STYLE TYPE="text/css">
    <!--

    A:link{color:#ff0000; font-size:10pt; }
    A:visited{color:#ff0000; font-size:10pt; }
    A:hover{color:#0000ff; font-size:10pt; }

    A:link.catLink{color:#ff0000; }
    A:visited.catLink{color:#ff0000; }
    A:hover.catLink{color:#0000ff; }

    A:link.catLinkRed{color:#ff0000; }
    A:visited.catLinkRed{color:#ff0000; }
    A:hover.catLinkRed{color:#ff0000; }

    A:link.catLinkBlue{color:#0000ff; }
    A:visited.catLinkBlue{color:#0000ff; }
    A:hover.catLinkBlue{color:#0000ff;  }

    font.errorLittle{font-size:11pt; color:#ff0000; font-family:Arial;}
    font.errorBig{font-size:13pt; color:#ff0000; font-weight:bold; font-family:Arial;}
    font.user{font-size:11pt; font-family:Arial;}
    font.subCat{font-size:13pt; font-family:Arial;}
    font.mainCat{font-size:15pt; font-family:Arial;}
    font.small{font-size:11pt; font-family:Arial;}
    font.logInFormFont{font-size:11pt; font-family:Arial; }
    font.logInFormCaptionFont{font-size:13pt; font-family:Arial; color:<?=cnfg('logInFormCaptionFontColor')?>; }
    font.logInStatusMsg{font-size:11pt; font-family:Arial; }
    font.logInStatusLogOut{font-size:11pt; font-family:Arial; }

    // -->
    </STYLE>
  
  <?php

} // end function main_css()

// *************** END FUNCTION main_css ***************



// ************* START FUNCTION log_in_form_and_status *************

function log_in_form_and_status()
{
    GLOBAL $gbl ;

    if ($gbl["loggedIn"] == true)
    {   log_in_status(); 
    }
    else
    {   log_in_form();
    }

} // end function rightColumn()

// ************* END FUNCTION log_in_form_and_status *************


// *************** START FUNCTION log_in_form ***************

function log_in_form()
{
    $captionFntClr = cnfg('logInFormCaptionFontColor') ;
    $caption       = cnfg('logInFormCaption');
    $captionBg     = cnfg('logInFormCaptionBgColor');
    $formWidth     = cnfg('logInFormWidth');
    $formBgClr     = cnfg('logInFormBgColor');

    ?>

    <table CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="<?php echo $formWidth; ?>" >
    <tr>
    <td valign="top"  width="100%" BGCOLOR="<?php echo $captionBg; ?>">
    <CENTER>
    <FONT CLASS="logInFormCaptionFont">
    <?php echo $caption; ?>
    </FONT>
    </CENTER>
    </td></tr>
    <tr><td valign="top">

    <FORM ACTION="<?php echo cnfg('deDir') ?>log_in.php" METHOD="POST">
    <table CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="<?php echo $formWidth; ?>" BGCOLOR="<?php echo $formBgClr; ?>">
    <tr>
    <td valign="top" align="right">
    <FONT CLASS="logInFormFont">
    User:
    </FONT>
    </td>
    <td valign="top">
    <INPUT TYPE="TEXT" SIZE=10 NAME="user_name">
    </td>
    </tr>
    <tr>
    <td valign="top" align="right">
    <FONT CLASS="logInFormFont">
    Password:
    </FONT>
    </td>
    <td valign="top">
    <INPUT TYPE="PASSWORD" SIZE=10 NAME="password">
    <INPUT TYPE="SUBMIT"  VALUE="Submit">
    <INPUT TYPE="hidden" NAME="submit" value="validate">
    </td></tr></table>
    </FORM>

    </td>
    </tr>
    </table>

    <?php

} // end function log_in_form()

// *************** END FUNCTION log_in_form ***************


function logged_in_false_msg()
{
    ?>
    You are not logged in.
    <?php
}

// *************** START FUNCTION log_in_status ***************

function log_in_status()
{
    GLOBAL $gbl ;

    $statusWidth = cnfg('logInStatusWidth') ;
    $statusBgColor = cnfg('logInStatusBgColor');

    /*
    = cnfg('logInFormCaptionBgColor');
    = cnfg('logInFormWidth');
    = cnfg('logInFormBgColor');
    */
 
    ?>

    <table CELLSPACING="0" CELLPADDING="0" BORDER="0" WIDTH="<?php echo $statusWidth; ?>" BGCOLOR="<?php echo $statusBgColor; ?>">
    <tr>
    <td VALIGN="TOP" WIDTH="100%">

    <?php

    if (!$gbl['loggedIn'])
    {   logged_in_false_msg() ;
    }
    else
    { 
        ?>

        <FONT CLASS="logInStatusMsg" COLOR="#000000">
        You are logged in as <?php echo $gbl["user_name"]; ?>
        <CENTER>
        <a href="<?php echo cnfg('deDir') ?>log_out.php">
        <FONT CLASS="logInStatusLogOut" COLOR="#0000FF">
        Log out
        </FONT>
        </a>
        </CENTER>
        </FONT>

    <?php
    } // end else
    ?>

    </td>
    </tr>
    </table>

    <?php

} // end function log_in_status()

// *************** END FUNCTION log_in_status ***************


// *************** START FUNCTION main_header ***************

function main_header()
{
    ?>

    <!-- DO NOT EDIT ABOVE HERE -->

    <TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="100%" BGCOLOR="#000000">
    <TR>
    <TD VALIGN="TOP" WIDTH="35%" BGCOLOR="#FFFFFF">
    <IMG SRC="<?php echo cnfg('deDir') ?>images/ban_dewebware.gif" WIDTH="250" HEIGHT="60" BORDER="0" ALT="&nbsp;">
    </TD>
    <TD VALIGN="TOP" BGCOLOR="#FFFFFF" WIDTH="100%">
    &nbsp;
    </TD>
    <TD VALIGN="TOP" WIDTH="65%">
    <IMG SRC="<?php echo cnfg('deDir') ?>images/ban_declass2.gif" WIDTH="468" HEIGHT="60" BORDER="0" ALT="d.e. classifieds">
    </TD>
    </TR>
    </TABLE>
  
    <!-- DO NOT EDIT BELOW HERE -->

    <?php

} // end function main_header() 

// *************** END FUNCTION main_header ***************


// *************** START FUNCTION main_footer ***************

function main_footer()
{
    ?>

    <!-- DO NOT EDIT ABOVE HERE -->

    <TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="100%" >
    <TR>
    <TD VALIGN="TOP">
    <CENTER>
    <a href="http://www.dewebware.com" target="_blank">
    <FONT SIZE="4" COLOR="#FF0000" FACE="Arial">
    <img src="<?php echo cnfg("deDir") ?>images/powered_declass2.gif" border="0">
    </FONT>
    </a>
    </CENTER>
    </TD>
    </TR>
    </TABLE>

    <!-- DO NOT EDIT BELOW HERE -->

    <?php

} // end function main_footer()

// *************** END FUNCTION main_footer ***************


?>