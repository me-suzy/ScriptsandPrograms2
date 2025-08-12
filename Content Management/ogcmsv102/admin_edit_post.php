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
//check login:-----------------------------
require_once 'header_footer.php';
if (!$_SESSION['authorized']) {
   include 'admin.php';
   die;
   }
//----------------------------------------

//include required functions-------------
require_once 'post_functions.php';
//---------------------------------------

show_header();
//display box-----------------------
print "<div id=\"view_container\">";
//-----------------------------------

//start control structure
//if no id is passed to this page, show error message
if (!isset($_GET['id']) and !isset($_POST['id'])) {
   Print "{$lang[edit_error]}";
   }

//continue with variables
else {
     //show form to edit post, if the from has not
     //allready been posted
     if (!$_POST['submit'] == "{$lang[post_edit_button]}") {
        edit_post_form($_GET['id']);
        }
     //if the edit form has been posted, edit post in db
     //and then show edit form again
     elseif ($_POST['submit'] == "{$lang[post_edit_button]}") {
            edit_post($_POST['id']);
            edit_post_form($_POST['id']);
            }
     //if something goes wrong display error message
     else {
          Print "{$lang[edit_error]}";
          }
}

//close box----
print "</div>";
//-------------
show_footer();
?>


