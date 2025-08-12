<?php
/*-----------------------------------------------------------------------------+
|                            OG CMS v1.02                         |
+------------------------------------------------------------------------------+
| This file is component of OG CMS :: web management system                    |
|                                                                              |
|                    Please, send any comments, suggestions and bug reports to |
|                                                      olegu@soemme.no         |
| Original Author: Vidar Løvbrekke Sømme                                       |
| Author email   : olegu@soemme.no                                             |
| Project website: http://www.soemme.no/                                       |
| Licence Type   : FREE                                                        |
+------------------------------------------------------------------------------+ */
//check login:--------------------------
require_once 'header_footer.php';
if (!$_SESSION['authorized']) {
   include 'admin.php';
   die;
   }
//--------------------------------------

//include required functions------------
require_once 'post_functions.php';
//--------------------------------------

//start---------------------------------
show_header();

//display box------------------------
print "<div id=\"view_container\">";
//-----------------------------------

//control structure
//if the deletion have been confirmed, perform deletion
if ($_POST['delete_button'] == "{$lang[post_delete_confirm]}") {
   delete_post($_POST['id']);
   }

//ask for confirmation
elseif (isset($_GET['id'])) {
     Print "<div class=\"warning\">{$lang[delete_prompt]}</div>";
     delete_confirm($_GET['id']);
     }

//if this page get called without the proper variables set
//display errormessage
else {
print "<div class=\"warning\">{$lang[delete_error]}</div>";
}

//close box-----
print "</div>";
//--------------

show_footer();
?>

