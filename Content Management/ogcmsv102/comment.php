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
//-----------------------------------------------------------------------------
//comment functions
//----------------------------------------------------------------------------

//include required files-----------
require_once 'header_footer.php';
//---------------------------------

function addCommentForm($id) {
         //get language variables
         global $lang;
         //print out form
         print "
         <form action=\"{$_SERVER['PHP_SELF']}\" name=\"comment\" method=\"post\">
         {$lang[comment_name_prompt]} <br>
         <input type=\"text\" name=\"name\" size=\"40\" value=\"{$_POST['name']}\" /><br>
         <br>
         {$lang[comment_mail_prompt]}<br>
         <input type=\"text\" name=\"mail\" size=\"40\" value=\"{$_POST['mail']}\" /><br>
         <br>
         {$lang[comment_prompt]}<br>
         <textarea name=\"comment\" rows=\"5\" cols=\"60\">";

         if (!isset($_POST['comment'])) {
            print "{$lang[comment_textfield_fill]}";
         }
         else {
              print $_POST['comment'];
         }

         print "</textarea>
         <br><br>
         <input type=\"hidden\" name=\"time\" value=\"";
         print strtotime("now");
         print "\" />
         <input type=\"hidden\" name=\"id\" value=\"$id\" />
         <input type=\"submit\" name=\"add\" value=\"{$lang[comment_add_button]}\" />
         </form>";
}

//function to check mail adress (does not work yet)
function validateEmail ($mail) {
         return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-
         !#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%\'*+\\./0-9=?A-Z^_`a-
         z{|}~]+$', $mail));
}

function CheckComment($fieldname, $field, $mail, $max, $min) {
          //function to check lenght of input
         //check that something is entered, and that it is not unreasonably long:
         if (strlen($field) < $min) {
                 $rtstring = $fieldname . " " . $lang[comment_field_too_short] . " " . $min . " " . $lang[comment_characters];
                 print $rtstring;
                 return false;
         }
         if (strlen($fieldname) > 255) {
            $rtstring = $fieldname . " " . $lang[comment_field_too_long] ." " . $max . " " . $lang[comment_characters];
            print $rtstring;
            return false;
         }

         //----------------------------------
         //this part of function does not wotk yet
         //----------------------------------
         //if it is the mail field, check that it is a mail adress:
         /*if ($mail) {
            if (!validateEmail($field)) {
               print "Not a valid e-mail adress";
               return false;
            }
         } */
         return true;
}

//function to find and insert links:
function InsertLinks ( $Text ){
         //  First match things beginning with http:// (or other protocols)
         $NotAnchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
         $Protocol = '(http|ftp|https):\/\/';
         $Domain = '[\w]+(.[\w]+)';
         $Subdir = '([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?';
         $Expr = '/' . $NotAnchor . $Protocol . $Domain . $Subdir . '/i';

         $Result = preg_replace( $Expr, "<a href=\"$0\" title=\"$0\" target=\"_blank\">$0</a>", $Text );

         //  Now match things beginning with www.
         $NotAnchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
         $NotHTTP = '(?<!:\/\/)';
         $Domain = 'www(.[\w]+)';
         $Subdir = '([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?';
         $Expr = '/' . $NotAnchor . $NotHTTP . $Domain . $Subdir . '/i';

         return preg_replace( $Expr, "<a href=\"http://$0\" title=\"http://$0\" target=\"_blank\">$0</a>", $Result );
}

function processComment($field, $main) {
         //remove tags:
         $field = strip_tags($field);
         //if this is the main comment field:
         if ($main) {
            //find links, and encode them:
            InsertLinks($field);
            //replace linebreak /n with <br>
            $field = nl2br($field);
         }
         //add slashes to conserv the correct string
         $field = AddSlashes($field);
         return $field;
         }

function insertComment($name, $mail, $comment, $time, $id) {
         //function insert fields into DB

         //query-------------------------------
         $query = "INSERT INTO og_comments (id, name, mail, time, comment)
         VALUES('$id', '$name', '$mail', '$time', '$comment')";
         $query_result = mysql_query($query) or DIE (mysql_error());
         //-----------------------------------
         }

function editCommentsForm($cid) {
         //get language variables
         global $lang;
         //function to display form to edit comment

         //query-------------------------------------------
         $query = "SELECT * from og_comments WHERE cid = '$cid' LIMIT 1";
         $query_result = mysql_query($query) or DIE (mysql_error());
         //-----------------------------------------------
         $row = mysql_fetch_array($query_result);

         //print out form,:
         print "
         <form action=\"{$_SERVER['PHP_SELF']}\" name=\"editcomment\" method=\"post\">
         {$lang[comment_name_prompt]}<br>
         <input type=\"text\" name=\"name\" size=\"40\" value=\"{$row['name']}\" /><br>
         <br>
         {$lang[comment_mail_prompt]}<br>
         <input type=\"text\" name=\"mail\" size=\"40\" value=\"{$row['mail']}\" /><br>
         <br>
         {$lang[comment_prompt]}<br>
         <textarea name=\"comment\" rows=\"5\" cols=\"60\">{$row['comment']}</textarea>
         <br><br>
         <input type=\"hidden\" name=\"id\" value=\"{$row['id']}\" />
         <input type=\"hidden\" name=\"cid\" value=\"$cid\" />
         <input type=\"submit\" name=\"edit\" value=\"{$lang[comment_edit_button]}\" />
         </form>";
}

function editComment($cid, $name, $mail, $comment) {
         //function to edit comments information in db

         //query:--------------------------------------
         $query = "UPDATE og_comments SET name = '$name', mail = '$mail', comment = '$comment'
                WHERE cid = '$cid'";
         $query_result = mysql_query($query) or DIE (mysql_error());
         //---------------------------------------------
         }

function deleteCommentConfirm($cid) {
         //get language variables
         global $lang;
         //button form, to confirm delete:
         print "
         <form action=\"{$_SERVER['PHP_SELF']}\" name=\"confirm\" method=\"post\">
         <input type=\"hidden\" name=\"cid\" value=\"$cid\" />
         <input type=\"submit\" name=\"really\" value=\"{$lang[comment_delete_confirm_button]}\" />
         </form>";
}

function deleteComment($cid) {
         //get language variables
         global $lang;
         //query to delete comment:

         //query-----------------------------------------
         $query = "DELETE FROM og_comments WHERE cid = '$cid'";
         $query_result = mysql_query($query) or DIE (mysql_error());
         //----------------------------------------------

         print "{$lang[comment_delete_success]}";

         //button form to continue
         print "<form action=\"post_list.php\" name=\"goon\" method=\"post\">
         <input type=\"submit\" name=\"go\" value=\"{$lang[comment_continue_button]}\"/>
         </form>";
}
         

//----------------------------------------------------------------------------
//control structure, what to do when....
//---------------------------------------------------------------------------
//show header and navbar
show_header ();
//show the add form if id is provided:
if (isset($_GET['add'])) {
   addCommentForm($_GET['add']);
}

//if the add comments form has been posted do this:
//check the fields for bad stuff, and length:
elseif ($_POST['add'] == "{$lang[comment_add_button]}") {
   if (!CheckComment("{$lang[comment_name]}", $_POST['name'], false, 50, 2)) {
      addCommentForm($_POST['id']);
   }
   elseif (!CheckComment("{$lang[comment_mail]}", $_POST['mail'], true, 50, 2)) {
          addCommentForm($_POST['id']);
   }
   elseif (!CheckComment("{$lang[comment]}", $_POST['comment'], false, 255, 3)) {
          addCommentForm($_POST['id']);
   }
   else {
        //remove tags, encode links, and insert <br> tags in place og \n chars:
        $name = processComment($_POST['name'], false);
        $mail = processComment($_POST['mail'], false);
        $comment = processComment($_POST['comment'], true);
        //insert it into the DB:
        insertComment($name, $mail, $comment, $_POST['time'], $_POST['id']);
        print "{$lang[comment_add_success]}
        <a href=\"post_view.php?id={$_POST['id']}\">{$lang[comment_click_here]}</a>
         {$lang[comment_go_back]}";
   }
}
//if edit is selected:
elseif (isset($_GET['edit'])) {
   editCommentsForm($_GET['edit']);
   }
//if edit form has been posted:
elseif ($_POST['edit'] == "{$lang[comment_edit_button]}") {
   //remove tags, encode links, and insert <br> tags in place og \n chars:
   $name = processComment($_POST['name'], false);
   $mail = processComment($_POST['mail'], false);
   $comment = processComment($_POST['comment'], true);
   //insert it into the DB:
   editComment($_POST['cid'], $name, $mail, $comment);
   print "{$lang[comment_edit_success]}
        <a href=\"post_view.php?id={$_POST['id']}\">{$lang[comment_click_here]}</a>
         {$lang[comment_go_back]}";
}
//if delete is selected
elseif (isset($_GET['delete'])) {
   deleteCommentConfirm($_GET['delete']);
   }
//if delete is confirmed:
elseif ($_POST['really'] == "{$lang[comment_delete_confirm_button]}") {
   deleteComment($_POST['cid']);
}
else {
print "{$lang[comment_error]}";
}
show_footer();
?>