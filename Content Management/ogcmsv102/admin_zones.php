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
//check login:----------------------------
require_once 'header_footer.php';
if (!$_SESSION['authorized']) {
   include 'admin.php';
   die;
   }
//---------------------------------------


function ShowZones () {
         //get language variables
         global $lang;
         //function to list awailable zones

         //query--------------------------------
             $query = "SELECT * FROM og_zones";
             $result = mysql_query($query) or die (mysql_error());

             //print-out list of zones
             while ($row = mysql_fetch_array($result)) {
                   print "{$row['zone']} [
                         <a href=\"{$_SERVER['PHP_SELF']}?edit={$row['id']}\">{$lang[zone_edit_link]}</a>
                         ] [
                         <a href=\"{$_SERVER['PHP_SELF']}?delete={$row['id']}\">{$lang[zone_delete_link]}</a>
                         ]<br>";
             // end of list print-out
             }
}

function AddZonesform () {
         //get language variables
         global $lang;
         //form to add new zones

         print "<form action=\"{$_SERVER['PHP_SELF']}\" name=\"addzone\" method=\"post\">
         {$lang[zone_add_form_header]}<br>
         <input type=\"text\" name=\"zone\" size=\"40\" />
         <input type=\"submit\" name=\"add\" value=\"{$lang[zone_add_button]}\" />
         </form>";
}

function addzone ($zone) {
         //get language variables
         global $lang;
         //function to add new zone to database

         //query-----------------------------------
         $query = "INSERT INTO og_zones(zone) VALUES ('$zone')";
         $query_result = mysql_query($query) or die (mysql_error());
         //---------------------------------------

         print "{$lang[zone_add_success]}";
}

function editzoneform ($zone) {
         //get language variables
         global $lang;
         //function to display form to edit zones

         //query----------------------------------------
         $query = "SELECT * FROM og_zones WHERE id = '$zone'";
         $query_result = mysql_query($query) or die (mysql_error());
         //----------------------------------------------

         $row = mysql_fetch_array($query_result);

         //print-out form
         print "<form action=\"{$_SERVER['PHP_SELF']}\" name=\"editform'\" method=\"post\">
               <input type=\"text\" name=\"edit\" size=\"40\" value=\"{$row['zone']}\" />
               <input type=\"hidden\" name=\"zone\" value=\"$zone\"/>
               <input type=\"submit\" name=\"edit_button\" value=\"{$lang[zone_edit_button]}\" />
         </form>";
         //end of form print-out
         }

function edit_zone () {
         //get language variables
         global $lang;
         //function to edit zone info in database i.a.w. edit zones form

         //get varaibles from post
         $value = $_POST['edit'];
         $zone = $_POST['zone'];

         //query--------------------------------------
         $query = "UPDATE og_zones SET zone='$value' WHERE id = '$zone'";
         $query_result = mysql_query($query) or die (mysql_error());
         //-------------------------------------------

         print "{$lang[zone_edit_success]}";
}

function delete_zone ($zone) {
         //get language variables
         global $lang;
         //function to delete zone info from database

         //query to delete affected posts:
         $pquery = "DELETE FROM og_post WHERE zone = '$zone'";
         $pquery_result = mysql_query($pquery) or die (mysql_error());
         //----------------------------------

         //query---------------------------------------
         $query = "DELETE FROM og_zones WHERE id = '$zone'";
         $query_result = mysql_query($query) or die (mysql_error());
         //-------------------------------------------


         print "{$lang[zone_delete_success]}";
}

function warning($zone) {
           //get language variables
           global $lang;
                   //function to get confirmation for deletion of post
           print "<div class=\"warning\">{$lang[zone_warning]}</div>";
           print "<form name=\"delete\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                 <input type=\"hidden\" name=\"zone\" value=\"$zone\"/>
                 <input type=\"submit\" name=\"delete_button\" value=\"{$lang[post_delete_confirm]}\"/>
                 <input type=\"reset\" name=\"reset\" value=\"$lang[post_delete_cancel]\" onClick=\"history.go(-1)\"/>
           </form>";
           }
         
function zones () {
         //get language variables
         global $lang;
         //control structure function

         if ($_POST['add'] == "{$lang[zone_add_button]}") {
            addzone ($_POST['zone']);
            }

         elseif (isset($_GET['edit'])) {
            editzoneform ($_GET['edit']);
            }

         elseif ($_POST['edit_button'] == "{$lang[zone_edit_button]}") {
            edit_zone ();
            }

         elseif (isset($_GET['delete'])) {
            warning ($_GET['delete']);
            }
         elseif ($_POST['delete_button'] == $lang[post_delete_confirm])  {
                delete_zone ($_POST['zone']);
         }
}

//start
show_header ();
print "<div id=\"view_container\">";
zones ();
AddZonesform ();
showzones ();
print "</div>";
show_footer ();

?>