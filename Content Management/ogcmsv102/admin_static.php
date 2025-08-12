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
//check if you are logged in-----
require_once 'header_footer.php';
if (!$_SESSION['authorized']) {
   include 'admin.php';
   die;
   }
//-------------------------------


function popup () {
         //java script for popup window
             /*******************************
             the popupWins array stores an object reference for
             each separate window that is called, based upon
             the name attribute that is supplied as an argument
             *******************************/
             print "<script language=\"JavaScript\">
                   var dialogArguments;
                       function getValueImage (field) {
                                dialogArguments = field;
                                window.open('upload_image.php', 'popup',
                                'width=250,height=100,scrollbars=1'); 
                                }
                       function getValueFile (field) {
                                dialogArguments = field;
                                window.open('upload_file.php', 'popup',
                                'width=250,height=100,scrollbars=1'); 
                                }
                       function getValueStatic (field) {
                                dialogArguments = field;
                                window.open('upload_static.php', 'popup',
                                'width=250,height=100,scrollbars=1'); 
                                }
                   </script>";

}


function Showstatics() {
         //get language variables
         global $lang;
         //function for listing the current static pages

         //query----------------------------------------
             $query = "SELECT * FROM og_static";
             $result = mysql_query($query);
         //---------------------------------------------

         //if there are no static pages, print message
                if (!$result) {
                print $lang[static_missing];
                }
         //if there are any static pages, list them
             else {

             //print-out-----------------------------------
             while ($row = mysql_fetch_array($result)) {
                   print "{$row['name']} [
                         <a href=\"{$_SERVER['PHP_SELF']}?edit={$row['id']}\">{$lang[static_edit_link]}</a>
                         ] [
                         <a href=\"{$_SERVER['PHP_SELF']}?delete={$row['id']}\">{$lang[static_delete_link]}</a>
                         ]<br>";
                   }
             //----------------------------------------------

             }
}

function AddStaticform () {
         //get language variables
         global $lang;
         //function to display form to add static page:

         //load popup java script
         popup();

         //print out form:
         print "<form action=\"{$_SERVER['PHP_SELF']}\" name=\"addzone\" method=\"post\">
         {$lang[static_form_header]}<br>

         {$lang[static_name_prompt]}
         <input type=\"text\" name=\"name\" size=\"40\" /><br>

         {$lang[static_filenanme_prompt]}
         <input type=\"text\" name=\"file_name\" size=\"40\" />
         <INPUT TYPE=\"button\" VALUE=\"{$lang[static_upload_button]}\"
                  ONCLICK=\"getValueStatic(this.form.file_name)\"><br>
         <input type=\"submit\" name=\"add\" value=\"{$lang[static_add_button]}\" />
         </form>";
         //end of form print out.
}

function addstatic ($name, $file_name) {
         //get language variables
         global $lang;
         //function to add static page to database

         //query-------------------------------------
         $query = "INSERT INTO og_static(name, file_name) VALUES ('$name', '$file_name')";
         $query_result = mysql_query($query) or die (mysql_error());
         //-------------------------------------------

         print "{$lang[static_add_success]}";
}

function editstaticform ($id) {
         //get language variables
         global $lang;
         //function to print form to edit static page information

         //query--------------------------------------------------
         $query = "SELECT * FROM og_static WHERE id = '$id'";
         $query_result = mysql_query($query) or die (mysql_error());
         //--------------------------------------------------------

         $row = mysql_fetch_array($query_result);

         //print-out form
         print "<form action=\"{$_SERVER['PHP_SELF']}\" name=\"editform'\" method=\"post\">
               {$lang[static_name_prompt]}
               <input type=\"text\" name=\"name\" size=\"40\" value=\"{$row['name']}\" />
               <br>
               {$lang[static_filenanme_prompt]}
               <input type=\"text\" name=\"file_name\" size=\"40\" value=\"{$row['file_name']}\" />
               <input type=\"hidden\" name=\"id\" value=\"$id\"/>
               <input type=\"submit\" name=\"edit_button\" value=\"{$lang[static_edit_button]}\" />
               </form>";
         //end of form print-out
         }

function edit_static () {
         //get language variables
         global $lang;
         //function to change the info about static pages in the database
         //gets values from the edit static form

         //get values from $_post superglobal array
         $name = $_POST['name'];
         $file_name = $_POST['file_name'];
         $id = $_POST['id'];

         //query-----------------------------------
         $query = "UPDATE og_static SET name='$name', file_name='$file_name' WHERE id = '$id'";
         $query_result = mysql_query($query) or die (mysql_error());
         //---------------------------------------

         print "{$lang[static_edit_success]}";
}

function delete_static ($id) {
         //get language variables
         global $lang;
         //function to delete information about a static page from the database

         //query-----------------------------------------
         $query = "DELETE FROM og_static WHERE id = '$id'";
         $query_result = mysql_query($query) or die (mysql_error());
         //------------------------------------------------

         print "{$lang[static_delete_sucess]}";
}

function warning($id) {
           //get language variables
           global $lang;
                   //function to get confirmation for deletion of post
           print "<div class=\"warning\">{$lang[static_warning]}</div>";
           print "<form name=\"delete\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                 <input type=\"hidden\" name=\"id\" value=\"$id\"/>
                 <input type=\"submit\" name=\"delete_button\" value=\"{$lang[post_delete_confirm]}\"/>
                 <input type=\"reset\" name=\"reset\" value=\"$lang[post_delete_cancel]\" onClick=\"history.go(-1)\"/>
           </form>";
           }
         
function statics () {
         //get language variables
         global $lang;
         //control structure function


         if ($_POST['add'] == "{$lang[static_add_button]}") {
            addstatic ($_POST['name'],$_POST['file_name']);
            }

         elseif (isset($_GET['edit'])) {
            editstaticform ($_GET['edit']);
            }

         elseif ($_POST['edit_button'] == "{$lang[static_edit_button]}") {
            edit_static ();
            }

         elseif (isset($_GET['delete'])) {
            warning ($_GET['delete']);
            }
         elseif ($_POST['delete_button'] == $lang[post_delete_confirm]) {
                delete_static ($_POST['id']);
         }
}

//start
show_header ();
print "<div id=\"view_container\">";
statics ();
Addstaticform ();
showstatics ();
print "</div>";
show_footer ();
?>