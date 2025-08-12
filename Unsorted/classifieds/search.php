<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */


require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToLibDir').'func_getResults.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$myDB = db_connect();

$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];

$content = array();

checkUser('', ''); 


if ($search)
{   $content[] = 'getResults();';
}
else
{   $content[] = 'search_by_keyword_form();';
}


// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_search'));

db_disconnect($myDB);


# --- START FUNCTIONS ---


// *********** START FUNCTION search_by_keyword_form() *************

function search_by_keyword_form()
{
  
    ?>

    <CENTER>

    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td valign="top">

    <FORM ACTION="<?=cnfg('deDir')?>search.php?doSearch=1" METHOD="POST">
    Enter Keyword:
    </td>
    </tr>
    <tr>
    <td valign="top">
    <INPUT TYPE="TEXT" NAME="search" SIZE="30">
    </td>
    </tr>
    <tr>
    <td valign="top">
    Search In Category:
    </td>
    </tr>
    <tr>
    <td valign="top">
    <SELECT NAME="category">
    <OPTION VALUE="none">All Categories</OPTION> 

    <?php

    get_cat_options();

    ?>

    </SELECT>
    <BR>
    <INPUT TYPE="SUBMIT" NAME="submit" value="Submit">


    </td>
    </tr>
    </table>

    <INPUT TYPE="HIDDEN" NAME="searchType" VALUE="keyword">
    </FORM>

    <CENTER>
    <BR>&nbsp;

    <?php

} // end function search_by_keyword_form()

// *********** END FUNCTION search_by_keyword_form() *************



// *********** START FUNCTION get_cat_options() *************

function get_cat_options()
{
    $query = 'SELECT cat_name, cat_id 
              FROM std_categories 
              WHERE parent_id=0 ORDER BY cat_name ASC ';

    $result = mysql_query($query);

    while ($row = mysql_fetch_array($result) )
    {   echo '<OPTION VALUE="'.$row['cat_id'].'">';
        echo $row['cat_name'] ;
        echo "</OPTION>" ;
    } 

} // end function get_cat_options()

// *********** END FUNCTION get_cat_options() *************


?>
